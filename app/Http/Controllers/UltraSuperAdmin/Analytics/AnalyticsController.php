<?php

namespace App\Http\Controllers\UltraSuperAdmin\Analytics;

use App\Http\Controllers\Controller;
use App\Models\SchoolGroup;
use App\SmSchool;
use Illuminate\Support\Facades\DB;

/**
 * Analytics Controller
 *
 * Cross-organization analytics for the Ultra Super Admin.
 * Provides insights across all school groups.
 */
class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard.
     */
    public function index()
    {
        // School Group Distribution
        $groupStats = SchoolGroup::withCount('schools')
            ->active()
            ->get()
            ->map(function ($group) {
                return [
                    'name' => $group->name,
                    'schools_count' => $group->schools_count,
                    'plan' => $group->subscription_plan,
                    'subscription_status' => $group->subscription_status,
                ];
            });

        // Plan Distribution
        $planDistribution = SchoolGroup::select('subscription_plan', DB::raw('count(*) as total'))
            ->groupBy('subscription_plan')
            ->get();

        // Monthly school creation trend (last 12 months)
        $schoolTrend = [];
        if (DB::getSchemaBuilder()->hasTable('sm_schools')) {
            $schoolTrend = DB::table('sm_schools')
                ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('count(*) as total'))
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        }

        // Students per group
        $studentsPerGroup = [];
        if (DB::getSchemaBuilder()->hasTable('sm_students') && DB::getSchemaBuilder()->hasTable('school_groups')) {
            $studentsPerGroup = DB::table('school_groups')
                ->leftJoin('sm_schools', 'school_groups.id', '=', 'sm_schools.school_group_id')
                ->leftJoin('sm_students', 'sm_schools.id', '=', 'sm_students.school_id')
                ->select('school_groups.name', DB::raw('count(sm_students.id) as total_students'))
                ->groupBy('school_groups.id', 'school_groups.name')
                ->get();
        }

        // Top schools by student count
        $topSchools = [];
        if (DB::getSchemaBuilder()->hasTable('sm_schools') && DB::getSchemaBuilder()->hasTable('sm_students')) {
            $topSchools = DB::table('sm_schools')
                ->leftJoin('sm_students', 'sm_schools.id', '=', 'sm_students.school_id')
                ->select('sm_schools.school_name', DB::raw('count(sm_students.id) as student_count'))
                ->groupBy('sm_schools.id', 'sm_schools.school_name')
                ->orderByDesc('student_count')
                ->limit(10)
                ->get();
        }

        return view('backEnd.ultraSuperAdmin.analytics.index', compact(
            'groupStats', 'planDistribution', 'schoolTrend', 'studentsPerGroup', 'topSchools'
        ));
    }
}
