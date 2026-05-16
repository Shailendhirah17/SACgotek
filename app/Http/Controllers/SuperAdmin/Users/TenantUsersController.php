<?php

namespace App\Http\Controllers\SuperAdmin\Users;

use App\Http\Controllers\Controller;
use App\SmParent;
use App\SmStaff;
use App\SmStudent;
use App\SmSchool;
use App\SmClass;
use App\SmSection;
use App\SmHumanDepartment;
use App\SmDesignation;
use App\SmBaseSetup;
use Modules\RolePermission\Entities\InfixRole;
use Illuminate\Http\Request;

class TenantUsersController extends Controller
{
    /**
     * Display a global listing of all SaaS Students.
     */
    public function students(Request $request)
    {
        $query = SmStudent::with(['school', 'class', 'section', 'parents']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('admission_no', 'like', "%{$search}%");
            });
        }

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        if ($request->filled('status')) {
            $query->where('active_status', $request->status == 'active' ? 1 : 0);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->filled('gender_id')) {
            $query->where('gender_id', $request->gender_id);
        }

        $students = $query->orderBy('id', 'desc')->paginate(20);
        
        $schoolsQuery = SmSchool::where('active_status', 1);
        if (session()->has('assigned_school_group_id')) {
            $schoolsQuery->where('school_group_id', session('assigned_school_group_id'));
        }
        $schools = $schoolsQuery->orderBy('school_name', 'asc')->get();
        $genders = SmBaseSetup::where('base_group_id', 1)->get();
        
        $classes = collect();
        $sections = collect();
        if ($request->filled('school_id')) {
            $classes = SmClass::where('school_id', $request->school_id)->get();
            if ($request->filled('class_id')) {
                $sections = SmSection::where('school_id', $request->school_id)->get();
            }
        }

        return view('backEnd.superAdmin.tenantUsers.students', compact('students', 'schools', 'genders', 'classes', 'sections'));
    }

    /**
     * Display a global listing of all SaaS Staff.
     */
    public function staff(Request $request)
    {
        $query = SmStaff::with(['school', 'roles.saasAssignments.permissionInfo', 'departments', 'designations']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('staff_no', 'like', "%{$search}%");
            });
        }

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        if ($request->filled('status')) {
            $query->where('active_status', $request->status == 'active' ? 1 : 0);
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('designation_id')) {
            $query->where('designation_id', $request->designation_id);
        }

        $staffs = $query->orderBy('id', 'desc')->paginate(20);
        
        $schoolsQuery = SmSchool::where('active_status', 1);
        if (session()->has('assigned_school_group_id')) {
            $schoolsQuery->where('school_group_id', session('assigned_school_group_id'));
        }
        $schools = $schoolsQuery->orderBy('school_name', 'asc')->get();
        $roles = InfixRole::orderBy('name', 'asc')->get();
        
        $departments = collect();
        $designations = collect();
        if ($request->filled('school_id')) {
            $departments = SmHumanDepartment::where('school_id', $request->school_id)->get();
            $designations = SmDesignation::where('school_id', $request->school_id)->get();
        }

        return view('backEnd.superAdmin.tenantUsers.staff', compact('staffs', 'schools', 'roles', 'departments', 'designations'));
    }

    /**
     * Display a global listing of all SaaS Parents.
     */
    public function parents(Request $request)
    {
        $query = SmParent::with(['school']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('fathers_name', 'like', "%{$search}%")
                  ->orWhere('mothers_name', 'like', "%{$search}%")
                  ->orWhere('guardians_email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        if ($request->filled('status')) {
            $query->where('active_status', $request->status == 'active' ? 1 : 0);
        }

        if ($request->filled('occupation')) {
            $query->where(function($q) use ($request) {
                $q->where('fathers_occupation', 'like', "%{$request->occupation}%")
                  ->orWhere('mothers_occupation', 'like', "%{$request->occupation}%")
                  ->orWhere('guardians_occupation', 'like', "%{$request->occupation}%");
            });
        }

        $parents = $query->orderBy('id', 'desc')->paginate(20);
        
        $schoolsQuery = SmSchool::where('active_status', 1);
        if (session()->has('assigned_school_group_id')) {
            $schoolsQuery->where('school_group_id', session('assigned_school_group_id'));
        }
        $schools = $schoolsQuery->orderBy('school_name', 'asc')->get();

        return view('backEnd.superAdmin.tenantUsers.parents', compact('parents', 'schools'));
    }
}
