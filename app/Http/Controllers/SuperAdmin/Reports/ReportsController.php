<?php

namespace App\Http\Controllers\SuperAdmin\Reports;

use App\Http\Controllers\Controller;
use App\SmSchool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportsController extends Controller
{
    /**
     * Display the reports hub.
     */
    public function index()
    {
        return view('backEnd.superAdmin.reports.index');
    }

    /**
     * Generate a school report.
     */
    public function schoolReport(Request $request)
    {
        $schools = SmSchool::orderBy('school_name')
            ->get()
            ->map(function ($school) {
                $school->student_count = DB::table('sm_students')->where('school_id', $school->id)->count();
                $school->staff_count = DB::table('sm_staffs')->where('school_id', $school->id)->count();
                $school->parent_count = DB::table('sm_parents')->where('school_id', $school->id)->count();
                return $school;
            });

        return view('backEnd.superAdmin.reports.school_report', compact('schools'));
    }

    /**
     * Export school report as CSV.
     */
    public function exportSchoolReport()
    {
        $schools = SmSchool::all();

        $csvData = "ID,School Name,Email,Phone,Status,Students,Staff,Created At\n";

        foreach ($schools as $school) {
            $studentCount = DB::table('sm_students')->where('school_id', $school->id)->count();
            $staffCount = DB::table('sm_staffs')->where('school_id', $school->id)->count();
            $status = $school->active_status ? 'Active' : 'Inactive';

            $csvData .= "{$school->id},\"{$school->school_name}\",{$school->email},{$school->phone},{$status},{$studentCount},{$staffCount},{$school->created_at}\n";
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="school_report_' . date('Y-m-d') . '.csv"');
    }
}
