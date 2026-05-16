<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SchoolHierarchyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            $school_id = Auth::user()->school_id ?? 1;
            
            // 1. Fetch relevant roles (Excluding Students/Parents)
            $roles = DB::table('infix_roles')
                ->whereNotIn('id', [2, 3])
                ->orderByRaw("FIELD(id, 1, 5, 4, 6, 7, 8, 9)") // Super Admin, Admin, Teacher...
                ->get();

            foreach ($roles as $role) {
                $role->staffs = DB::table('sm_staffs')
                    ->where('role_id', $role->id)
                    ->where('school_id', $school_id)
                    ->where('active_status', 1)
                    ->select('id', 'full_name', 'designation_id', 'staff_photo')
                    ->get();
                
                foreach ($role->staffs as $staff) {
                    $staff->designation_title = DB::table('sm_designations')
                        ->where('id', $staff->designation_id)
                        ->value('title') ?? 'Staff';
                }
            }
            
            $orgTree = $roles->filter(function($role) {
                return $role->staffs->isNotEmpty();
            });

            return view('backEnd.humanResource.school_hierarchy', compact('orgTree'));

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            $orgTree = collect([]);
            return view('backEnd.humanResource.school_hierarchy', compact('orgTree'));
        }
    }
}
