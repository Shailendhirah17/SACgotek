<?php

namespace App\Http\Controllers\SuperAdmin\Analytics;

use App\Http\Controllers\Controller;
use App\SmSchool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard.
     */
    public function index()
    {
        // School growth data (last 12 months)
        $schoolGrowth = SmSchool::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Student enrollment trend
        $studentTrend = DB::table('sm_students')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Top schools by student count
        $topSchools = DB::table('sm_students')
            ->join('sm_schools', 'sm_students.school_id', '=', 'sm_schools.id')
            ->selectRaw('sm_schools.school_name, sm_schools.id, COUNT(sm_students.id) as student_count')
            ->groupBy('sm_schools.id', 'sm_schools.school_name')
            ->orderBy('student_count', 'desc')
            ->limit(10)
            ->get();

        // Revenue analytics (if Saas module available)
        $revenueData = [];
        if (class_exists(\Modules\Saas\Entities\SmSubscriptionPayment::class)) {
            try {
                $revenueData = \Modules\Saas\Entities\SmSubscriptionPayment::where('approve_status', 'approved')
                    ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total')
                    ->where('created_at', '>=', now()->subMonths(12))
                    ->groupBy('month')
                    ->orderBy('month')
                    ->pluck('total', 'month')
                    ->toArray();
            } catch (\Exception $e) {
                Log::warning('Analytics: Failed to load revenue data', ['error' => $e->getMessage()]);
            }
        }

        return view('backEnd.superAdmin.analytics.index', compact(
            'schoolGrowth',
            'studentTrend',
            'topSchools',
            'revenueData'
        ));
    }
}
