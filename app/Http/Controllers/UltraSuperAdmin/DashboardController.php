<?php

namespace App\Http\Controllers\UltraSuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SchoolGroup;
use App\Models\SuperAdmin;
use App\SmSchool;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Ultra Super Admin Dashboard Controller
 *
 * Provides the master command center with platform-wide metrics
 * across all school groups, schools, and subscriptions.
 */
class DashboardController extends Controller
{
    /**
     * Display the Ultra Super Admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $ultraSuperAdmin = Auth::guard('ultrasuperadmin')->user();

        // Initialize metrics
        $totalSchoolGroups = 0;
        $activeSchoolGroups = 0;
        $totalSchools = 0;
        $activeSchools = 0;
        $totalStudents = 0;
        $totalStaff = 0;
        $totalParents = 0;
        $totalUsers = 0;
        $totalSuperAdmins = 0;
        $activeSubscriptions = 0;
        $expiringSubscriptions = 0;
        $totalRevenue = 0;

        try {
            // School Group Statistics
            if (DB::getSchemaBuilder()->hasTable('school_groups')) {
                $totalSchoolGroups = SchoolGroup::count();
                $activeSchoolGroups = SchoolGroup::where('active_status', true)->count();
                $activeSubscriptions = SchoolGroup::withActiveSubscription()->count();
                $expiringSubscriptions = SchoolGroup::where('subscription_end', '>=', now())
                    ->where('subscription_end', '<=', now()->addDays(30))
                    ->count();
            }

            // School Statistics
            if (DB::getSchemaBuilder()->hasTable('sm_schools')) {
                $totalSchools = SmSchool::count();
                $activeSchools = SmSchool::where('active_status', 1)->count();
            }

            // Super Admin Statistics
            $superAdminsList = collect();
            if (DB::getSchemaBuilder()->hasTable('super_admins')) {
                $totalSuperAdmins = SuperAdmin::count();
                $superAdminsList = SuperAdmin::with('schoolGroup')->get();
            }

            // User Statistics
            if (DB::getSchemaBuilder()->hasTable('sm_students')) {
                $totalStudents = DB::table('sm_students')->count();
            }
            if (DB::getSchemaBuilder()->hasTable('sm_staffs')) {
                $totalStaff = DB::table('sm_staffs')->count();
            }
            if (DB::getSchemaBuilder()->hasTable('sm_parents')) {
                $totalParents = DB::table('sm_parents')->count();
            }
            if (DB::getSchemaBuilder()->hasTable('users')) {
                $totalUsers = DB::table('users')->count();
            }

            // Revenue Statistics
            if (DB::getSchemaBuilder()->hasTable('sm_subscription_payments')) {
                $totalRevenue = DB::table('sm_subscription_payments')
                    ->where('approve_status', 'approved')
                    ->sum('amount');
            }

            // Recent School Groups
            $recentGroups = DB::getSchemaBuilder()->hasTable('school_groups')
                ? SchoolGroup::withCount('schools')->orderBy('created_at', 'desc')->limit(5)->get()
                : collect();

            // Recent Schools
            $recentSchools = DB::getSchemaBuilder()->hasTable('sm_schools')
                ? SmSchool::orderBy('created_at', 'desc')->limit(5)->get()
                : collect();

            // Subscription Plan Distribution
            $planDistribution = DB::getSchemaBuilder()->hasTable('school_groups')
                ? SchoolGroup::select('subscription_plan', DB::raw('count(*) as total'))
                    ->groupBy('subscription_plan')
                    ->get()
                : collect();

            // System Health
            $systemHealth = [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'server_time' => now()->format('Y-m-d H:i:s'),
                'cache_driver' => config('cache.default'),
                'session_driver' => config('session.driver'),
                'queue_driver' => config('queue.default'),
                'disk_free' => @disk_free_space('/') ? round(disk_free_space('/') / (1024 * 1024 * 1024), 2) . ' GB' : 'N/A',
            ];

            // Geographic Intelligence
            $geoService = new \App\Services\GeographicIntelligenceService();
            $geoData = $geoService->getPlatformGeoData();

            // Advanced Financial Aggregation
            $financeService = new \App\Services\SaasFinanceService();
            $financeData = $financeService->getPlatformRevenueOverview();

            return view('backEnd.ultraSuperAdmin.dashboard', compact(
                'ultraSuperAdmin',
                'totalSchoolGroups',
                'activeSchoolGroups',
                'totalSchools',
                'activeSchools',
                'totalStudents',
                'totalStaff',
                'totalParents',
                'totalUsers',
                'totalSuperAdmins',
                'superAdminsList',
                'activeSubscriptions',
                'expiringSubscriptions',
                'totalRevenue',
                'recentGroups',
                'recentSchools',
                'planDistribution',
                'systemHealth',
                'geoData',
                'financeData'
            ));

        } catch (\Exception $e) {
            Log::error('UltraSuperAdmin Dashboard error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return view('backEnd.ultraSuperAdmin.dashboard', [
                'ultraSuperAdmin' => $ultraSuperAdmin,
                'totalSchoolGroups' => $totalSchoolGroups,
                'activeSchoolGroups' => $activeSchoolGroups,
                'totalSchools' => $totalSchools,
                'activeSchools' => $activeSchools,
                'totalStudents' => $totalStudents,
                'totalStaff' => $totalStaff,
                'totalParents' => $totalParents,
                'totalUsers' => $totalUsers,
                'totalSuperAdmins' => $totalSuperAdmins,
                'superAdminsList' => collect(),
                'activeSubscriptions' => $activeSubscriptions,
                'expiringSubscriptions' => $expiringSubscriptions,
                'totalRevenue' => $totalRevenue,
                'recentGroups' => collect(),
                'recentSchools' => collect(),
                'planDistribution' => collect(),
                'systemHealth' => [],
                'geoData' => ['map_points' => [], 'state_distribution' => [], 'city_distribution' => [], 'growth_opportunities' => []],
                'financeData' => ['total_subscription_revenue' => 0, 'total_school_revenue' => 0, 'platform_fee_revenue' => 0, 'net_platform_revenue' => 0, 'monthly_trend' => []],
            ]);
        }
    }
}
