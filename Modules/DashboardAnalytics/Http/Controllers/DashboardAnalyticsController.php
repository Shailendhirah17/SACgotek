<?php

namespace Modules\DashboardAnalytics\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\SmStudent;
use App\SmStaff;
use App\SmExamSchedule;
use App\SmFeesPayment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\YearCheck;

class DashboardAnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect('login');
            }
            $school_id = $user->school_id;
            $academic_id = getAcademicId();

            // 1. Total Students
            $totalStudents = SmStudent::where('active_status', 1)
                ->where('school_id', $school_id)
                ->count();

            // 2. Total Staff
            $totalStaffs = SmStaff::where('active_status', 1)
                ->where('school_id', $school_id)
                ->count();

            // 3. Pending Fees Total (Approximate based on payment vs assigned, or just total collected)
            // Let's get total collected for the month to build the KPI
            $totalFeesCollected = SmFeesPayment::where('active_status', 1)
                ->where('school_id', $school_id)
                ->whereMonth('payment_date', Carbon::now()->month)
                ->whereYear('payment_date', Carbon::now()->year)
                ->sum('amount');

            // 4. Scheduled Exams
            $totalExams = SmExamSchedule::where('school_id', $school_id)
                ->where('academic_id', $academic_id)
                ->select('exam_term_id')
                ->distinct()
                ->count();
                
            // --- Department: Academic ---
            $totalClasses = \App\SmClass::where('active_status', 1)->where('school_id', $school_id)->count();
            $totalSubjects = \App\SmSubject::where('active_status', 1)->where('school_id', $school_id)->count();
            
            // --- Department: Finance ---
            $monthlyIncome = \App\SmAddIncome::where('active_status', 1)->where('school_id', $school_id)->whereMonth('date', Carbon::now()->month)->sum('amount');
            $monthlyExpense = \App\SmAddExpense::where('active_status', 1)->where('school_id', $school_id)->whereMonth('date', Carbon::now()->month)->sum('amount');
            
            // --- Department: HR ---
            // Calculate Staff Attendance Today
            $todayAttendanceCount = \App\SmStaffAttendence::where('school_id', $school_id)->where('attendence_date', Carbon::now()->format('Y-m-d'))->where('attendence_type', 'P')->count();
            $hrAttendanceRate = ($totalStaffs > 0 && $todayAttendanceCount > 0) ? round(($todayAttendanceCount / $totalStaffs) * 100) : 0;

            // Chart Data: Monthly Fee Collection
            $chart_data_yearly = '';
            for ($i = 1; $i <= date('m'); $i++) {
                $monthStr = $i < 10 ? '0' . $i : $i;
                $yearlyIncome = SmFeesPayment::where('active_status', 1)
                    ->where('school_id', $school_id)
                    ->whereYear('payment_date', Carbon::now()->year)
                    ->whereMonth('payment_date', $monthStr)
                    ->sum('amount');
                $chart_data_yearly .= "{ y: '".$monthStr."', income: ".@$yearlyIncome.", expense: 0 },";
            }

            $data = [
                'totalStudents' => $totalStudents,
                'totalStaffs' => $totalStaffs,
                'totalFeesCollected' => $totalFeesCollected,
                'totalExams' => $totalExams,
                'totalClasses' => $totalClasses,
                'totalSubjects' => $totalSubjects,
                'monthlyIncome' => $monthlyIncome,
                'monthlyExpense' => $monthlyExpense,
                'hrAttendanceRate' => $hrAttendanceRate,
                'year' => YearCheck::getYear(),
                'chart_data_yearly' => $chart_data_yearly,
                'chart_data' => '' // mock for now
            ];

            return view('dashboardanalytics::index', $data);
            
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

}
