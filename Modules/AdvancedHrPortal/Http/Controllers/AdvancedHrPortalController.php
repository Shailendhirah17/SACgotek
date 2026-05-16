<?php

namespace Modules\AdvancedHrPortal\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;

class AdvancedHrPortalController extends Controller
{
    public function index()
    {
        return view('advancedhrportal::index');
    }

    public function staffView()
    {
        try {
            $staffs = \App\SmStaff::with('designations', 'departments', 'genders')
                ->where('school_id', auth()->user()->school_id)
                ->where('active_status', 1)
                ->get();
            return view('advancedhrportal::staff_view', compact('staffs'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function payroll()
    {
        try {
            $payrolls = \App\SmHrPayrollGenerate::with('staffDetails', 'paymentMethods')
                ->where('school_id', auth()->user()->school_id)
                ->orderBy('id', 'desc')
                ->get();
            return view('advancedhrportal::payroll', compact('payrolls'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function attendance()
    {
        return view('advancedhrportal::attendance');
    }

    public function staffDashboard()
    {
        try {
            $staff = auth()->user()->staff;
            if (!$staff) {
                $staff = \App\SmStaff::where('user_id', auth()->user()->id)->first();
            }
            
            $leave_requests = \App\SmLeaveRequest::with('leaveType')
                ->where('staff_id', $staff->id)
                ->where('school_id', auth()->user()->school_id)
                ->get();

            $payroll_slips = \App\SmHrPayrollGenerate::where('staff_id', $staff->id)
                ->where('school_id', auth()->user()->school_id)
                ->get();
                
            return view('advancedhrportal::staff_dashboard', compact('staff', 'leave_requests', 'payroll_slips'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
