<?php

namespace App\Http\Controllers\SuperAdmin\Users;

use App\Http\Controllers\Controller;
use App\Models\SuperAdminAuditLog;
use App\User;
use App\SmSchool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SchoolAdminController extends Controller
{
    /**
     * Display a listing of School Administrators (App\User with role_id=1).
     */
    public function index()
    {
        $query = User::where('role_id', 1);

        // Group Isolation: Only show admins for schools within the Super Admin's group
        if (session()->has('assigned_school_group_id')) {
            $groupId = session('assigned_school_group_id');
            $query->whereHas('school', function($q) use ($groupId) {
                $q->where('school_group_id', $groupId);
            });
        }

        $users = $query->with('school')->orderBy('created_at', 'desc')->paginate(20);

        return view('backEnd.superAdmin.schoolAdmins.index', compact('users'));
    }

    /**
     * Show the form for creating a new School Administrator.
     */
    public function create()
    {
        $query = SmSchool::where('active_status', 1);
        
        if (session()->has('assigned_school_group_id')) {
            $query->where('school_group_id', session('assigned_school_group_id'));
        }

        $schools = $query->orderBy('school_name')->get();

        return view('backEnd.superAdmin.schoolAdmins.create', compact('schools'));
    }

    /**
     * Store a newly created School Administrator.
     */
    public function store(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:sm_schools,id',
            'full_name' => 'required|string|max:200',
            'email' => 'required|email|max:200|unique:users,email',
            'username' => 'required|string|max:100|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        // Security: Ensure requested school_id belongs to the Super Admin's group
        if (session()->has('assigned_school_group_id')) {
            $groupId = session('assigned_school_group_id');
            $belongs = DB::table('sm_schools')
                ->where('id', $request->school_id)
                ->where('school_group_id', $groupId)
                ->exists();
            if (!$belongs) {
                return back()->withInput()->with('message-danger', 'Invalid school selected for your organization.');
            }
        }

        try {
            DB::beginTransaction();

            $currentAdmin = Auth::guard('superadmin')->user();

            $user = User::create([
                'name' => $request->full_name,
                'full_name' => $request->full_name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'role_id' => 1, // System Admin
                'school_id' => $request->school_id,
                'active_status' => 1,
                'is_administrator' => 'yes',
            ]);

            SuperAdminAuditLog::log(
                $currentAdmin->id,
                'created',
                'SchoolAdmin',
                $user->id,
                "Created School Admin user: {$user->username} for school ID: {$request->school_id}"
            );

            DB::commit();

            return redirect()->route('superadmin.school-admins.index')
                ->with('message-success', 'School Administrator created successfully. They can now log in at /login');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('School Admin creation failed', ['error' => $e->getMessage()]);
            return back()->withInput()->with('message-danger', 'Failed to create user. Error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing.
     */
    public function edit($id)
    {
        $query = User::where('role_id', 1);
        if (session()->has('assigned_school_group_id')) {
            $groupId = session('assigned_school_group_id');
            $query->whereHas('school', function($q) use ($groupId) {
                $q->where('school_group_id', $groupId);
            });
        }
        $user = $query->findOrFail($id);
        
        $query = SmSchool::where('active_status', 1);
        if (session()->has('assigned_school_group_id')) {
            $query->where('school_group_id', session('assigned_school_group_id'));
        }
        $schools = $query->orderBy('school_name')->get();

        return view('backEnd.superAdmin.schoolAdmins.edit', compact('user', 'schools'));
    }

    /**
     * Update.
     */
    public function update(Request $request, $id)
    {
        $query = User::where('role_id', 1);
        if (session()->has('assigned_school_group_id')) {
            $groupId = session('assigned_school_group_id');
            $query->whereHas('school', function($q) use ($groupId) {
                $q->where('school_group_id', $groupId);
            });
        }
        $user = $query->findOrFail($id);

        $request->validate([
            'school_id' => 'required|exists:sm_schools,id',
            'full_name' => 'required|string|max:200',
            'email' => 'required|email|max:200|unique:users,email,' . $id,
            'username' => 'required|string|max:100|unique:users,username,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        try {
            DB::beginTransaction();

            $updateData = [
                'name' => $request->full_name,
                'full_name' => $request->full_name,
                'email' => $request->email,
                'username' => $request->username,
                'phone' => $request->phone,
                'school_id' => $request->school_id,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            DB::commit();

            return redirect()->route('superadmin.school-admins.index')
                ->with('message-success', 'School Administrator updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('School Admin update failed', ['error' => $e->getMessage()]);
            return back()->withInput()->with('message-danger', 'Failed to update user.');
        }
    }

    /**
     * Delete.
     */
    public function destroy($id)
    {
        try {
            $query = User::where('role_id', 1);
            if (session()->has('assigned_school_group_id')) {
                $groupId = session('assigned_school_group_id');
                $query->whereHas('school', function($q) use ($groupId) {
                    $q->where('school_group_id', $groupId);
                });
            }
            $user = $query->findOrFail($id);
            $user->delete();

            return redirect()->route('superadmin.school-admins.index')
                ->with('message-success', 'School Administrator deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('message-danger', 'Failed to delete user.');
        }
    }
}
