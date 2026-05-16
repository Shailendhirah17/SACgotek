<?php

namespace Modules\SmartFrontOffice\Http\Controllers;

use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;

class SmartFrontOfficeController extends Controller
{
    public function index()
    {
        return view('smartfrontoffice::index');
    }

    public function admissionPipeline()
    {
        try {
            $queries = \App\SmAdmissionQuery::with('followUps')
                ->where('school_id', auth()->user()->school_id)
                ->orderBy('id', 'desc')
                ->get();
            return view('smartfrontoffice::admission_pipeline', compact('queries'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function visitorPasses()
    {
        try {
            $visitors = \App\SmVisitor::where('school_id', auth()->user()->school_id)
                ->orderBy('id', 'desc')
                ->get();
            return view('smartfrontoffice::visitor_passes', compact('visitors'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function leads()
    {
        try {
            $leads = \App\SmAdmissionQuery::where('school_id', auth()->user()->school_id)
                ->whereNull('student_id')
                ->orderBy('id', 'desc')
                ->get();
            return view('smartfrontoffice::leads', compact('leads'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
