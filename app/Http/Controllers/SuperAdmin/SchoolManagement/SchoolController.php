<?php

namespace App\Http\Controllers\SuperAdmin\SchoolManagement;

use App\Http\Controllers\Controller;
use App\Models\SuperAdminAuditLog;
use App\SmSchool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchoolController extends Controller
{
    /**
     * Display a listing of schools with filters.
     */
    public function schoolList(Request $request)
    {
        $query = SmSchool::query()
            ->leftJoin('sm_states', 'sm_schools.state_id', '=', 'sm_states.id')
            ->leftJoin('sm_cities', 'sm_schools.city_id', '=', 'sm_cities.id')
            ->select('sm_schools.*', 'sm_states.name as state_name', 'sm_cities.name as city_name');

        // Hierarchy Scoping: Restrict Super Admins to their assigned school group if present
        if (session()->has('assigned_school_group_id')) {
            $query->where('sm_schools.school_group_id', session('assigned_school_group_id'));
        }

        // Apply filters
        if ($request->filled('status')) {
            $query->where('sm_schools.active_status', $request->status == 'active' ? 1 : 0);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sm_schools.school_name', 'like', "%{$search}%")
                  ->orWhere('sm_schools.email', 'like', "%{$search}%")
                  ->orWhere('sm_schools.phone', 'like', "%{$search}%");
            });
        }

        $schools = $query->orderBy('sm_schools.id', 'desc')->paginate(20);

        return view('backEnd.superAdmin.schools.index', compact('schools'));
    }

    /**
     * Show the details of a school.
     */
    public function show($id)
    {
        $query = SmSchool::where('id', $id);
        if (session()->has('assigned_school_group_id')) {
            $query->where('school_group_id', session('assigned_school_group_id'));
        }
        $school = $query->firstOrFail();

        // Get school-level stats
        $stats = [
            'students' => DB::table('sm_students')->where('school_id', $id)->count(),
            'staff' => DB::table('sm_staffs')->where('school_id', $id)->count(),
            'classes' => DB::table('sm_classes')->where('school_id', $id)->where('active_status', 1)->count(),
            'sections' => DB::table('sm_sections')->where('school_id', $id)->where('active_status', 1)->count(),
        ];

        return view('backEnd.superAdmin.schools.show', compact('school', 'stats'));
    }

    /**
     * Show the form for creating a new school.
     */
    public function create()
    {
        return view('backEnd.superAdmin.schools.create');
    }

    /**
     * Store a newly created school.
     */
    public function store(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:200',
            'email' => 'required|email|max:200',
            'primary_phone' => 'nullable|string|max:20',
            'secondary_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'domain' => 'required|string|max:191|unique:sm_schools,domain',
            'custom_domain' => 'nullable|string|max:191|unique:sm_schools,custom_domain',
            'admin_password' => 'required|string|min:6',

        ]);

        try {
            DB::beginTransaction();

            $currentAdmin = Auth::guard('superadmin')->user();

            $data = [
                'school_name' => $request->school_name,
                'email' => $request->email,
                'phone' => $request->primary_phone,
                'secondary_phone' => $request->secondary_phone,
                'address' => $request->address,
                'domain' => $request->domain,
                'custom_domain' => $request->custom_domain,
                'active_status' => 1,
            ];

            // Hierarchy Assignment: Automatically tag the school to the Super Admin's assigned group
            if ($currentAdmin->school_group_id) {
                $data['school_group_id'] = $currentAdmin->school_group_id;
            }

            $school = \App\SmSchool::create($data);

            // ============================================
            // DEEP FIX: Automated School initialization
            // ============================================

            // 1. Create Default Academic Year (e.g. 2024)
            $academicYear = new \App\SmAcademicYear();
            $academicYear->year = date('Y');
            $academicYear->title = date('Y') . ' Academic Year';
            $academicYear->starting_date = date('Y') . '-01-01';
            $academicYear->ending_date = date('Y') . '-12-31';
            $academicYear->school_id = $school->id;
            $academicYear->active_status = 1;
            $academicYear->save();

            // 2. Create School Admin User (role_id = 1)
            $adminUser = \App\User::create([
                'full_name' => $request->school_name . ' Admin',
                'email' => $request->email,
                'username' => strtolower(str_replace(' ', '', $request->domain)) . '_admin',
                'password' => \Illuminate\Support\Facades\Hash::make($request->admin_password),
                'role_id' => 1,
                'school_id' => $school->id,
                'active_status' => 1,
                'is_administrator' => 'yes',
            ]);

            // 3. Create Default General Settings for the School
            \App\SmGeneralSettings::create([
                'school_name' => $request->school_name,
                'site_title' => $request->school_name,
                'school_id' => $school->id,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'active_status' => 1,
                'currency' => 'INR',
                'currency_symbol' => '₹',
                'system_domain' => $request->domain,
                'session_id' => $academicYear->id,
                'academic_id' => $academicYear->id,
            ]);

            // 4. Clone Permissions from Template (School 1, Role 1)
            $templatePermissions = \Modules\RolePermission\Entities\InfixPermissionAssign::where('school_id', 1)
                ->where('role_id', 1)
                ->get();

            foreach ($templatePermissions as $tp) {
                $newPermission = new \Modules\RolePermission\Entities\InfixPermissionAssign();
                $newPermission->module_id = $tp->module_id;
                $newPermission->role_id = 1;
                $newPermission->school_id = $school->id;
                $newPermission->save();
            }

            SuperAdminAuditLog::log(
                $currentAdmin->id,
                'created',
                'School',
                $school->id,
                "Created school: {$school->school_name} with admin user #{$adminUser->id}",

                null,
                $school->toArray()
            );

            DB::commit();

            return redirect()->route('superadmin.school-list')
                ->with('message-success', "School '{$school->school_name}' created successfully. Admin login: {$request->email}");


        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('School creation failed', ['error' => $e->getMessage()]);

            return back()->withInput()
                ->with('message-danger', 'Failed to create school. Error: ' . $e->getMessage());

        }
    }

    /**
     * Clone an existing school to quickly create a new branch.
     */
    public function clone(Request $request, $id)
    {
        $sourceSchool = SmSchool::findOrFail($id);

        $request->validate([
            'school_name' => 'required|string|max:200',
            'email' => 'required|email|max:200|unique:users,email', // admin email
        ]);

        try {
            $cloningService = new \App\Services\SchoolCloningService();
            $newSchool = $cloningService->cloneSchool($sourceSchool->id, [
                'school_name' => $request->school_name,
                'email' => $request->email,
            ]);

            if ($newSchool) {
                // Provision initial admin user for the cloned school
                $adminUser = new \App\User();
                $adminUser->role_id = 1; // Admin role
                $adminUser->full_name = 'Admin - ' . $newSchool->school_name;
                $adminUser->email = $request->email;
                $adminUser->username = $request->email;
                $adminUser->password = \Illuminate\Support\Facades\Hash::make('123456');
                $adminUser->school_id = $newSchool->id;
                $adminUser->save();

                $currentAdmin = \Illuminate\Support\Facades\Auth::guard('superadmin')->user();
                \App\Models\SuperAdminAuditLog::log(
                    $currentAdmin->id,
                    'cloned',
                    'School',
                    $newSchool->id,
                    "Cloned school: {$newSchool->school_name} from {$sourceSchool->school_name}",
                    null,
                    $newSchool->toArray()
                );

                return redirect()->route('superadmin.school-list')
                    ->with('message-success', "School '{$newSchool->school_name}' cloned successfully!");
            }

            return back()->with('message-danger', 'Failed to clone school structure.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('School cloning failed', ['error' => $e->getMessage()]);
            return back()->with('message-danger', 'Failed to clone school. Error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing a school.
     */
    public function edit($id)
    {
        $query = SmSchool::where('id', $id);
        if (session()->has('assigned_school_group_id')) {
            $query->where('school_group_id', session('assigned_school_group_id'));
        }
        $school = $query->firstOrFail();

        $states = \Illuminate\Support\Facades\DB::table('sm_states')->where('active_status', 1)->orderBy('name', 'asc')->get();
        return view('backEnd.superAdmin.schools.edit', compact('school', 'states'));
    }

    /**
     * Get cities/districts for a specific state via AJAX.
     */
    public function ajaxGetCities(Request $request)
    {
        $cities = \Illuminate\Support\Facades\DB::table('sm_cities')
            ->where('state_id', $request->state_id)
            ->where('active_status', 1)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);
        
        // Clean output buffer to prevent stray whitespace from other files
        if (ob_get_length()) ob_clean();
        
        return response()->json($cities);
    }

    /**
     * Update the specified school.
     */
    public function update(Request $request, $id)
    {
        $query = SmSchool::where('id', $id);
        if (session()->has('assigned_school_group_id')) {
            $query->where('school_group_id', session('assigned_school_group_id'));
        }
        $school = $query->firstOrFail();

        $request->validate([
            'school_name' => 'required|string|max:200',
            'email' => 'required|email|max:200',
            'primary_phone' => 'nullable|string|max:20',
            'secondary_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'domain' => 'nullable|string|max:191|unique:sm_schools,domain,' . $id,
            'custom_domain' => 'nullable|string|max:191|unique:sm_schools,custom_domain,' . $id,
        ]);

        try {
            DB::beginTransaction();

            $currentAdmin = Auth::guard('superadmin')->user();
            $oldValues = $school->toArray();

            $school->update([
                'school_name' => $request->school_name,
                'email' => $request->email,
                'phone' => $request->primary_phone,
                'secondary_phone' => $request->secondary_phone,
                'address' => $request->address,
                'domain' => $request->domain,
                'custom_domain' => $request->custom_domain,
            ]);

            SuperAdminAuditLog::log(
                $currentAdmin->id,
                'updated',
                'School',
                $school->id,
                "Updated school: {$school->school_name}",
                $oldValues,
                $school->toArray()
            );

            DB::commit();

            return redirect()->route('superadmin.school-list')
                ->with('message-success', 'School updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('School update failed', ['error' => $e->getMessage()]);

            return back()->withInput()
                ->with('message-danger', 'Failed to update school.');
        }
    }

    /**
     * Remove the specified school.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $currentAdmin = Auth::guard('superadmin')->user();
            
            $query = SmSchool::where('id', $id);
            if (session()->has('assigned_school_group_id')) {
                $query->where('school_group_id', session('assigned_school_group_id'));
            }
            $school = $query->firstOrFail();

            SuperAdminAuditLog::log(
                $currentAdmin->id,
                'deleted',
                'School',
                $school->id,
                "Deleted school: {$school->school_name}",
                $school->toArray(),
                null
            );

            $school->delete();

            DB::commit();

            return redirect()->route('superadmin.school-list')
                ->with('message-success', 'School deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('School deletion failed', ['error' => $e->getMessage()]);

            return back()->with('message-danger', 'Failed to delete school.');
        }
    }

    /**
     * Toggle school active status.
     */
    public function toggleStatus(Request $request)
    {
        $request->validate(['id' => 'required|integer']);

        try {
            $query = SmSchool::where('id', $request->id);
            if (session()->has('assigned_school_group_id')) {
                $query->where('school_group_id', session('assigned_school_group_id'));
            }
            $school = $query->firstOrFail();
            $currentAdmin = Auth::guard('superadmin')->user();
            $oldStatus = $school->active_status;

            $school->update(['active_status' => !$school->active_status]);

            SuperAdminAuditLog::log(
                $currentAdmin->id,
                'status_changed',
                'School',
                $school->id,
                "Toggled school '{$school->school_name}' status to " . ($school->active_status ? 'active' : 'inactive'),
                ['active_status' => $oldStatus],
                ['active_status' => $school->active_status]
            );

            return back()->with('message-success', 'School status updated.');

        } catch (\Exception $e) {
            Log::error('School status toggle failed', ['error' => $e->getMessage()]);
            return back()->with('message-danger', 'Failed to update school status.');
        }
    }

    /**
     * Perform bulk actions on schools.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|string|in:activate,deactivate,delete',
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        try {
            DB::beginTransaction();

            $currentAdmin = Auth::guard('superadmin')->user();
            $action = $request->action;
            $ids = $request->ids;

            switch ($action) {
                case 'activate':
                    SmSchool::whereIn('id', $ids)->update(['active_status' => 1]);
                    $description = 'Bulk activated ' . count($ids) . ' schools';
                    break;
                case 'deactivate':
                    SmSchool::whereIn('id', $ids)->update(['active_status' => 0]);
                    $description = 'Bulk deactivated ' . count($ids) . ' schools';
                    break;
                case 'delete':
                    SmSchool::whereIn('id', $ids)->delete();
                    $description = 'Bulk deleted ' . count($ids) . ' schools';
                    break;
            }

            SuperAdminAuditLog::log(
                $currentAdmin->id,
                'bulk_action',
                'School',
                null,
                $description,
                ['ids' => $ids, 'action' => $action],
                null
            );

            DB::commit();

            return back()->with('message-success', $description);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('School bulk action failed', ['error' => $e->getMessage()]);
            return back()->with('message-danger', 'Bulk action failed.');
        }
    }
}
