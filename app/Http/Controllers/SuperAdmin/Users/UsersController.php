<?php

namespace App\Http\Controllers\SuperAdmin\Users;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin;
use App\Models\SuperAdminAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    /**
     * Display a listing of SuperAdmin users.
     */
    public function index()
    {
        $query = SuperAdmin::query();

        // Group Isolation: Scoped by the current Super Admin's assigned group
        if (session()->has('assigned_school_group_id')) {
            $query->where('school_group_id', session('assigned_school_group_id'));
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);


        return view('backEnd.superAdmin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new SuperAdmin user.
     */
    public function create()
    {
        return view('backEnd.superAdmin.users.create');
    }

    /**
     * Store a newly created SuperAdmin user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100|unique:super_admins,username',
            'email' => 'required|email|max:200|unique:super_admins,email',
            'password' => 'required|string|min:8|confirmed',
            'full_name' => 'required|string|max:200',
            'phone_number' => 'nullable|string|max:20',
            'active_status' => 'nullable|boolean',
            'role' => 'nullable|string|in:super_admin,admin_manager',
        ]);

        try {
            DB::beginTransaction();

            $currentAdmin = Auth::guard('superadmin')->user();

            $user = SuperAdmin::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'full_name' => $request->full_name,
                'phone_number' => $request->phone_number,
                'active_status' => $request->boolean('active_status', true),
                'role' => $request->input('role', 'admin_manager'),
                'created_by' => $currentAdmin->id,
                'school_group_id' => $currentAdmin->school_group_id, // Auto-assign group

            ]);

            // Audit log
            SuperAdminAuditLog::log(
                $currentAdmin->id,
                'created',
                'SuperAdmin',
                $user->id,
                "Created SuperAdmin user: {$user->username}",
                null,
                $user->only(['username', 'email', 'full_name', 'role', 'active_status'])
            );

            DB::commit();
            Cache::forget('superadmin_users_list');

            return redirect()->route('superadmin.users.index')
                ->with('message-success', 'SuperAdmin user created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('SuperAdmin user creation failed', ['error' => $e->getMessage()]);

            return back()->withInput()
                ->with('message-danger', 'Failed to create user. Please try again.');
        }
    }

    /**
     * Show the form for editing a SuperAdmin user.
     */
    public function edit($id)
    {
        $user = SuperAdmin::findOrFail($id);
        // Safety: Ensure admin only edits users from their own group
        $currentAdmin = Auth::guard('superadmin')->user();
        if ($currentAdmin->school_group_id && $user->school_group_id != $currentAdmin->school_group_id) {
            return redirect()->route('superadmin.users.index')->with('message-danger', 'Unauthorized access.');
        }

        return view('backEnd.superAdmin.users.edit', compact('user'));
    }

    /**
     * Update the specified SuperAdmin user.
     */
    public function update(Request $request, $id)
    {
        $query = SuperAdmin::where('id', $id);
        if (session()->has('assigned_school_group_id')) {
            $query->where('school_group_id', session('assigned_school_group_id'));
        }
        $user = $query->firstOrFail();

        $request->validate([
            'username' => 'required|string|max:100|unique:super_admins,username,' . $id,
            'email' => 'required|email|max:200|unique:super_admins,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'full_name' => 'required|string|max:200',
            'phone_number' => 'nullable|string|max:20',
            'active_status' => 'nullable|boolean',
            'role' => 'nullable|string|in:super_admin,admin_manager',
        ]);

        try {
            DB::beginTransaction();

            $currentAdmin = Auth::guard('superadmin')->user();
            $oldValues = $user->only(['username', 'email', 'full_name', 'phone_number', 'active_status', 'role']);

            $updateData = [
                'username' => $request->username,
                'email' => $request->email,
                'full_name' => $request->full_name,
                'phone_number' => $request->phone_number,
                'active_status' => $request->boolean('active_status', true),
                'role' => $request->input('role', $user->role),
                'updated_by' => $currentAdmin->id,
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            // Audit log
            SuperAdminAuditLog::log(
                $currentAdmin->id,
                'updated',
                'SuperAdmin',
                $user->id,
                "Updated SuperAdmin user: {$user->username}",
                $oldValues,
                $user->only(['username', 'email', 'full_name', 'phone_number', 'active_status', 'role'])
            );

            DB::commit();
            Cache::forget('superadmin_users_list');

            return redirect()->route('superadmin.users.index')
                ->with('message-success', 'SuperAdmin user updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('SuperAdmin user update failed', ['error' => $e->getMessage()]);

            return back()->withInput()
                ->with('message-danger', 'Failed to update user. Please try again.');
        }
    }

    /**
     * Remove the specified SuperAdmin user.
     */
    public function destroy($id)
    {
        $currentAdmin = Auth::guard('superadmin')->user();

        // Prevent self-deletion
        if ($currentAdmin->id == $id) {
            return back()->with('message-danger', 'You cannot delete your own account.');
        }

        try {
            DB::beginTransaction();

            $query = SuperAdmin::where('id', $id);
            if (session()->has('assigned_school_group_id')) {
                $query->where('school_group_id', session('assigned_school_group_id'));
            }
            $user = $query->firstOrFail();

            // Audit log before deletion
            SuperAdminAuditLog::log(
                $currentAdmin->id,
                'deleted',
                'SuperAdmin',
                $user->id,
                "Deleted SuperAdmin user: {$user->username}",
                $user->only(['username', 'email', 'full_name', 'role']),
                null
            );

            $user->delete();

            DB::commit();
            Cache::forget('superadmin_users_list');

            return redirect()->route('superadmin.users.index')
                ->with('message-success', 'SuperAdmin user deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('SuperAdmin user deletion failed', ['error' => $e->getMessage()]);

            return back()->with('message-danger', 'Failed to delete user. Please try again.');
        }
    }

    /**
     * Toggle the status of a SuperAdmin user.
     */
    public function toggleStatus($id)
    {
        $currentAdmin = Auth::guard('superadmin')->user();

        // Prevent self-deactivation
        if ($currentAdmin->id == $id) {
            return back()->with('message-danger', 'You cannot change your own status.');
        }

        try {
            $query = SuperAdmin::where('id', $id);
            if (session()->has('assigned_school_group_id')) {
                $query->where('school_group_id', session('assigned_school_group_id'));
            }
            $user = $query->firstOrFail();
            $oldStatus = $user->active_status;
            $user->update([
                'active_status' => !$user->active_status,
                'updated_by' => $currentAdmin->id,
            ]);

            // Audit log
            SuperAdminAuditLog::log(
                $currentAdmin->id,
                'status_changed',
                'SuperAdmin',
                $user->id,
                "Changed status of '{$user->username}' from " . ($oldStatus ? 'active' : 'inactive') . " to " . ($user->active_status ? 'active' : 'inactive'),
                ['active_status' => $oldStatus],
                ['active_status' => $user->active_status]
            );

            Cache::forget('superadmin_users_list');

            $status = $user->active_status ? 'activated' : 'deactivated';
            return back()->with('message-success', "User {$status} successfully.");

        } catch (\Exception $e) {
            Log::error('SuperAdmin user status toggle failed', ['error' => $e->getMessage()]);
            return back()->with('message-danger', 'Failed to change user status.');
        }
    }
}
