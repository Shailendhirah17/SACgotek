<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * OrganizationController
 * 
 * Manages Level-2 (Super Admin / Organization Head) accounts and their
 * assigned school groups. Only accessible by Level-1 (Technosprint).
 */
class OrganizationController extends Controller
{
    /**
     * List all school groups (organizations) with their assigned heads.
     */
    public function index()
    {
        $organizations = DB::table('school_groups as sg')
            ->leftJoin('users as u', function ($join) {
                $join->on('sg.id', '=', 'u.school_group_id')
                     ->where('u.admin_level', '=', 2);
            })
            ->leftJoin('sm_schools', 'sm_schools.school_group_id', '=', 'sg.id')
            ->select(
                'sg.id',
                'sg.name',
                'sg.created_at',
                DB::raw('COUNT(DISTINCT sm_schools.id) as school_count'),
                DB::raw("GROUP_CONCAT(DISTINCT u.full_name SEPARATOR ', ') as org_heads"),
                DB::raw("GROUP_CONCAT(DISTINCT u.email SEPARATOR ', ') as org_head_emails")
            )
            ->groupBy('sg.id', 'sg.name', 'sg.created_at')
            ->orderBy('sg.id', 'desc')
            ->get();

        return view('backEnd.superAdmin.organizations.index', compact('organizations'));
    }

    /**
     * Create a new Organization Head (Level 2 user) for a school group.
     */
    public function createHead(Request $request)
    {
        $request->validate([
            'full_name'       => 'required|string|max:191',
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|string|min:8',
            'school_group_id' => 'required|integer|exists:school_groups,id',
        ]);

        try {
            DB::beginTransaction();

            // Resolve or create the "Organization Head" role
            $orgHeadRole = DB::table('infix_roles')
                ->where('name', 'Organization Head')
                ->first();

            $roleId = $orgHeadRole ? $orgHeadRole->id : 1;

            // Create the Org Head user
            $userId = DB::table('users')->insertGetId([
                'full_name'       => $request->full_name,
                'email'           => $request->email,
                'username'        => $request->email,
                'password'        => Hash::make($request->password),
                'role_id'         => $roleId,
                'school_group_id' => $request->school_group_id,
                'school_id'       => 1, // Platform default
                'admin_level'     => 2,
                'active_status'   => 1,
                'is_administrator' => 'yes',
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            DB::commit();

            return redirect()->back()->with(
                'message-success',
                "Organization Head '{$request->full_name}' ({$request->email}) created successfully!"
            );

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Org Head creation failed', ['error' => $e->getMessage()]);
            return back()->withInput()->with('message-danger', 'Failed: ' . $e->getMessage());
        }
    }

    /**
     * List all Organization Heads (Level 2 users).
     */
    public function listHeads()
    {
        $heads = DB::table('users as u')
            ->leftJoin('school_groups as sg', 'u.school_group_id', '=', 'sg.id')
            ->where('u.admin_level', 2)
            ->select(
                'u.id',
                'u.full_name',
                'u.email',
                'u.active_status',
                'u.school_group_id',
                'sg.name as group_name',
                'u.created_at'
            )
            ->orderBy('u.id', 'desc')
            ->get();

        $groups = DB::table('school_groups')->orderBy('name')->get();

        return view('backEnd.superAdmin.organizations.heads', compact('heads', 'groups'));
    }

    /**
     * Toggle an Organization Head's active status.
     */
    public function toggleHead(Request $request)
    {
        $request->validate(['id' => 'required|integer']);

        $user = DB::table('users')->where('id', $request->id)->where('admin_level', 2)->first();
        if (!$user) {
            return back()->with('message-danger', 'Organization Head not found.');
        }

        $newStatus = $user->active_status ? 0 : 1;
        DB::table('users')->where('id', $request->id)->update(['active_status' => $newStatus]);

        return back()->with(
            'message-success',
            "Organization Head {$user->full_name} has been " . ($newStatus ? 'activated' : 'deactivated') . "."
        );
    }
}
