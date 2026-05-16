<?php

namespace App\Http\Controllers\UltraSuperAdmin\SuperAdminManagement;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin;
use App\Models\SchoolGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * Super Admin Management Controller
 *
 * Ultra Super Admin has full control over all Super Admin users.
 * Can create, edit, delete, toggle status, and override permissions.
 */
class SuperAdminController extends Controller
{
    /**
     * Display a listing of Super Admins.
     */
    public function index(Request $request)
    {
        $query = SuperAdmin::with('schoolGroup');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('active_status', $request->status === 'active');
        }

        $superAdmins = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('backEnd.ultraSuperAdmin.super_admins.index', compact('superAdmins'));
    }

    /**
     * Show the form for creating a new Super Admin.
     */
    public function create()
    {
        $schoolGroups = SchoolGroup::active()->get();
        return view('backEnd.ultraSuperAdmin.super_admins.create', compact('schoolGroups'));
    }

    /**
     * Store a newly created Super Admin.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:super_admins,username',
            'email' => 'required|email|max:255|unique:super_admins,email',
            'full_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'school_group_id' => 'nullable|exists:school_groups,id',
        ]);

        try {
            $superAdmin = SuperAdmin::create([
                'username' => $request->username,
                'email' => $request->email,
                'full_name' => $request->full_name,
                'phone_number' => $request->phone_number,
                'school_group_id' => $request->school_group_id,
                'password' => Hash::make($request->password),
                'active_status' => true,
                'role' => 'super_admin',
            ]);

            Log::channel('daily')->info('Super Admin created by Ultra Super Admin', [
                'super_admin_id' => $superAdmin->id,
                'created_by' => Auth::guard('ultrasuperadmin')->id(),
            ]);

            return redirect()->route('ultrasuperadmin.super-admins.index')
                ->with('message-success', "Super Admin '{$superAdmin->full_name}' created successfully.");

        } catch (\Exception $e) {
            Log::error('Failed to create Super Admin', ['error' => $e->getMessage()]);
            return back()->withInput()->with('message-danger', 'Failed to create Super Admin.');
        }
    }

    /**
     * Show the form for editing a Super Admin.
     */
    public function edit($id)
    {
        $superAdmin = SuperAdmin::findOrFail($id);
        $schoolGroups = SchoolGroup::active()->get();
        return view('backEnd.ultraSuperAdmin.super_admins.edit', compact('superAdmin', 'schoolGroups'));
    }

    /**
     * Update the specified Super Admin.
     */
    public function update(Request $request, $id)
    {
        $superAdmin = SuperAdmin::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:255|unique:super_admins,username,' . $id,
            'email' => 'required|email|max:255|unique:super_admins,email,' . $id,
            'full_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'school_group_id' => 'nullable|exists:school_groups,id',
        ]);

        try {
            $data = [
                'username' => $request->username,
                'email' => $request->email,
                'full_name' => $request->full_name,
                'phone_number' => $request->phone_number,
                'school_group_id' => $request->school_group_id,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $superAdmin->update($data);

            return redirect()->route('ultrasuperadmin.super-admins.index')
                ->with('message-success', "Super Admin '{$superAdmin->full_name}' updated successfully.");

        } catch (\Exception $e) {
            Log::error('Failed to update Super Admin', ['error' => $e->getMessage()]);
            return back()->withInput()->with('message-danger', 'Failed to update Super Admin.');
        }
    }

    /**
     * Remove the specified Super Admin.
     */
    public function destroy($id)
    {
        $superAdmin = SuperAdmin::findOrFail($id);

        try {
            $name = $superAdmin->full_name;
            $superAdmin->delete();

            Log::channel('daily')->info('Super Admin deleted by Ultra Super Admin', [
                'deleted_super_admin' => $name,
                'deleted_by' => Auth::guard('ultrasuperadmin')->id(),
            ]);

            return redirect()->route('ultrasuperadmin.super-admins.index')
                ->with('message-success', "Super Admin '{$name}' deleted successfully.");

        } catch (\Exception $e) {
            return back()->with('message-danger', 'Failed to delete Super Admin.');
        }
    }

    /**
     * Toggle the active status of a Super Admin.
     */
    public function toggleStatus($id)
    {
        $superAdmin = SuperAdmin::findOrFail($id);
        $superAdmin->update(['active_status' => !$superAdmin->active_status]);

        $status = $superAdmin->active_status ? 'activated' : 'deactivated';
        return back()->with('message-success', "Super Admin '{$superAdmin->full_name}' {$status} successfully.");
    }
}
