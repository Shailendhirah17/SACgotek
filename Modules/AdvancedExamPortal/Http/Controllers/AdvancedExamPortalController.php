<?php

namespace Modules\AdvancedExamPortal\Http\Controllers;

use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;

class AdvancedExamPortalController extends Controller
{
    public function index()
    {
        return view('advancedexamportal::index');
    }

    public function aiGenerator()
    {
        try {
            $subjects = \App\SmAssignSubject::with('subject')
                ->where('school_id', auth()->user()->school_id)
                ->get()
                ->unique('subject_id');
            $question_banks = \App\SmQuestionBank::with('questionGroup', 'questionLevel')
                ->where('school_id', auth()->user()->school_id)
                ->orderBy('id', 'desc')
                ->limit(50)
                ->get();
            return view('advancedexamportal::ai_generator', compact('subjects', 'question_banks'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function resultHeatmap()
    {
        try {
            $classes = \App\SmClass::where('school_id', auth()->user()->school_id)->where('active_status', 1)->get();
            $exams = \App\SmExam::where('school_id', auth()->user()->school_id)->get();
            return view('advancedexamportal::result_heatmap', compact('classes', 'exams'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function portfolio()
    {
        return view('advancedexamportal::portfolio');
    }
}
