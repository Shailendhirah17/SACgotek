<?php

namespace App\Http\Controllers\SuperAdmin\Impersonate;

use App\Http\Controllers\Controller;
use App\Models\SuperAdminAuditLog;
use App\SmSchool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ImpersonateController extends Controller
{
    /**
     * Display the impersonation page with school list.
     */
    public function index()
    {
        $schools = SmSchool::where('active_status', 1)
            ->orderBy('school_name')
            ->get();

        return view('backEnd.superAdmin.impersonate.index', compact('schools'));
    }

    /**
     * Impersonate a school admin — login as the school's admin user.
     */
    public function impersonate(Request $request, $schoolId)
    {
        try {
            $school = SmSchool::findOrFail($schoolId);
            $currentAdmin = Auth::guard('superadmin')->user();

            // Find the school's admin user (role_id = 1)
            $schoolAdmin = \App\User::where('school_id', $schoolId)
                ->where('role_id', 1)
                ->where('active_status', 1)
                ->first();

            // Support discovery by email if ID lookup fails
            if (!$schoolAdmin) {
                $schoolAdmin = \App\User::where('email', $school->email)
                    ->where('school_id', $schoolId)
                    ->first();
            }

            // Broader email search (any school)
            if (!$schoolAdmin) {
                $schoolAdmin = \App\User::where('email', $school->email)->first();
            }

            if (!$schoolAdmin) {
                // Auto-Repair: Create a default admin user if one is missing
                // Use unique username to avoid conflicts
                $username = 'admin_' . $school->id . '_' . time();
                $email = $school->email;
                
                // Check if email already exists in users table
                $existingUser = \App\User::where('email', $email)->first();
                if ($existingUser) {
                    // Repair existing user instead of creating new
                    $schoolAdmin = $existingUser;
                } else {
                    $schoolAdmin = new \App\User();
                    $schoolAdmin->full_name = $school->school_name . ' Admin';
                    $schoolAdmin->email = $email;
                    $schoolAdmin->username = $username;
                    $schoolAdmin->password = \Illuminate\Support\Facades\Hash::make('Eash@2005');
                    $schoolAdmin->role_id = 1;
                    $schoolAdmin->school_id = $schoolId;
                    $schoolAdmin->active_status = 1;
                    $schoolAdmin->is_administrator = 'yes';
                    $schoolAdmin->save();
                }

                // Also ensure General Settings exist
                \App\SmGeneralSettings::updateOrCreate(
                    ['school_id' => $schoolId],
                    [
                        'school_name' => $school->school_name,
                        'site_title' => $school->school_name,
                        'email' => $school->email,
                        'active_status' => 1,
                        'currency' => 'INR',
                        'currency_symbol' => '₹',
                    ]
                );
            }

            // Ensure the found user has the correct role and school link (Repair if broken)
            $schoolAdmin->role_id = 1;
            $schoolAdmin->school_id = $schoolId;
            $schoolAdmin->is_administrator = 'yes';
            $schoolAdmin->active_status = 1;
            $schoolAdmin->save();

            // ============================================
            // DEEP REPAIR: On-the-fly dashboard initialization
            // ============================================
            
            // 1. Ensure Academic Year exists
            $academicYear = \App\SmAcademicYear::where('school_id', $schoolId)->where('active_status', 1)->first();
            if (!$academicYear) {
                $academicYear = new \App\SmAcademicYear();
                $academicYear->year = date('Y');
                $academicYear->title = date('Y') . ' Academic Year';
                $academicYear->starting_date = date('Y') . '-01-01';
                $academicYear->ending_date = date('Y') . '-12-31';
                $academicYear->school_id = $schoolId;
                $academicYear->active_status = 1;
                $academicYear->save();
                
                // Link it in General Settings
                \App\SmGeneralSettings::where('school_id', $schoolId)->update([
                    'session_id' => $academicYear->id,
                    'academic_id' => $academicYear->id
                ]);
            } else {
                // Ensure General Settings has the session_id linked
                $gs = \App\SmGeneralSettings::where('school_id', $schoolId)->first();
                if ($gs && !$gs->session_id) {
                    $gs->session_id = $academicYear->id;
                    $gs->save();
                }
            }

            // 2. Ensure Permissions exist (Repair if none assigned)
            $existingPermissions = \Modules\RolePermission\Entities\InfixPermissionAssign::where('school_id', $schoolId)
                ->where('role_id', 1)
                ->count();
            
            if ($existingPermissions == 0) {
                $templatePermissions = \Modules\RolePermission\Entities\InfixPermissionAssign::where('school_id', 1)
                    ->where('role_id', 1)
                    ->get();
                
                foreach ($templatePermissions as $tp) {
                    $newPermission = new \Modules\RolePermission\Entities\InfixPermissionAssign();
                    $newPermission->module_id = $tp->module_id;
                    $newPermission->role_id = 1;
                    $newPermission->school_id = $schoolId;
                    $newPermission->save();
                }
            }
        
            // Store SuperAdmin info for returning
            Session::put('impersonating', true);
            Session::put('impersonator_id', $currentAdmin->id);
            Session::put('impersonator_name', $currentAdmin->full_name);
            Session::put('original_guard', 'superadmin');

            // Log the impersonation
            SuperAdminAuditLog::log(
                $currentAdmin->id,
                'impersonation_started',
                'School',
                $schoolId,
                "Impersonating school '{$school->school_name}' as admin user #{$schoolAdmin->id} (Deep Repair active)"

            );

            // Login as the school admin via the web guard
            Auth::guard('web')->login($schoolAdmin);

            // Set school context
            Session::put('school_id', $schoolId);

            return redirect()->route('admin-dashboard')
                ->with('message-success', "Now viewing as admin of: {$school->school_name}");

        } catch (\Exception $e) {
            Log::error('Impersonation failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->with('message-danger', 'Impersonation error: ' . $e->getMessage());

        }
    }

    /**
     * Return from impersonation back to SuperAdmin panel.
     */
    public function stopImpersonating()
    {
        try {
            $impersonatorId = Session::get('impersonator_id');

            // Logout from web guard
            Auth::guard('web')->logout();

            // Clear impersonation data
            Session::forget(['impersonating', 'impersonator_id', 'impersonator_name', 'original_guard', 'school_id']);

            // Log the return
            if ($impersonatorId) {
                SuperAdminAuditLog::log(
                    $impersonatorId,
                    'impersonation_ended',
                    'SuperAdmin',
                    $impersonatorId,
                    'Returned from school impersonation'
                );
            }

            return redirect()->route('superadmin.login')
                ->with('message-success', 'Returned to SuperAdmin panel. Please login again.');

        } catch (\Exception $e) {
            Log::error('Stop impersonation failed', ['error' => $e->getMessage()]);
            return redirect()->route('superadmin.login');
        }
    }
}
