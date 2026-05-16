<?php

namespace Modules\AdvancedStudentPortal\Http\Controllers;

use App\SmClass;
use App\SmStudent;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;

class AdvancedStudentPortalController extends Controller
{
    public function index()
    {
        return view('advancedstudentportal::index');
    }

    public function studentView(Request $request)
    {
        try {
            $classes = SmClass::where('active_status', 1)
                ->where('school_id', auth()->user()->school_id)
                ->get();
                
            $students = [];
            
            if ($request->class_id) {
                $query = SmStudent::with('class', 'section', 'parents', 'gender', 'bloodGroup', 'religion')
                    ->where('active_status', 1)
                    ->where('school_id', auth()->user()->school_id)
                    ->where('class_id', $request->class_id);
                    
                if ($request->section_id) {
                    $query->where('section_id', $request->section_id);
                }
                if ($request->name) {
                    $query->where('full_name', 'like', '%' . $request->name . '%');
                }
                
                $students = $query->get();
            }

            return view('advancedstudentportal::student_view', compact('classes', 'students'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function performance()
    {
        return view('advancedstudentportal::performance');
    }

    public function behavior()
    {
        return view('advancedstudentportal::behavior');
    }

    public function parentDashboard()
    {
        try {
            $parent = auth()->user()->parent; // Assumes relationship on User model
            if (!$parent) {
                // Fallback query if relation is missing
                $parent = \App\SmParent::where('user_id', auth()->user()->id)->first();
            }

            $children = collect();
            if ($parent) {
                // Fetch students associated with this parent
                $children = \App\SmStudent::with('class', 'section', 'attendances', 'marks')
                    ->where('parent_id', $parent->id)
                    ->where('school_id', auth()->user()->school_id)
                    ->get();
            }

            return view('advancedstudentportal::parent_dashboard', compact('parent', 'children'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentDashboard()
    {
        try {
            $student = auth()->user()->student;
            if (!$student) {
                $student = \App\SmStudent::where('user_id', auth()->user()->id)->first();
            }

            // Fetch assignments, attendance and timetables
            $homeworks = \App\SmHomework::where('class_id', $student->class_id)
                ->where('section_id', $student->section_id)
                ->where('school_id', auth()->user()->school_id)
                ->get();
                
            $study_materials = \App\SmTeacherUploadContent::where('available_for_all_classes', 1)
                ->orWhere(function($q) use ($student) {
                    $q->where('class', $student->class_id)->where('section', $student->section_id);
                })->get();

            return view('advancedstudentportal::student_dashboard', compact('student', 'homeworks', 'study_materials'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
