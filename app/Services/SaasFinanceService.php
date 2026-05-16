<?php

namespace App\Services;

use App\Models\SchoolGroup;
use Illuminate\Support\Facades\DB;

class SaasFinanceService
{
    /**
     * Get total aggregated revenue for the entire platform.
     * Considers both subscription payments and potential platform fees
     * from individual school transactions if applicable.
     * 
     * @return array
     */
    public function getPlatformRevenueOverview()
    {
        $totalSubscriptionRevenue = 0;
        $totalSchoolRevenue = 0;
        $platformFeeRevenue = 0;

        // 1. Subscription Revenue (from groups)
        if (DB::getSchemaBuilder()->hasTable('sm_subscription_payments')) {
            $totalSubscriptionRevenue = DB::table('sm_subscription_payments')
                ->where('approve_status', 'approved')
                ->sum('amount');
        }

        // 2. School-Level Revenue Aggregation
        // We aggregate from sm_fees_payments and fm_fees_transactions
        if (DB::getSchemaBuilder()->hasTable('sm_fees_payments')) {
            $totalSchoolRevenue += DB::table('sm_fees_payments')
                ->where('active_status', 1)
                ->sum('amount');
        }

        if (DB::getSchemaBuilder()->hasTable('fm_fees_transactions')) {
            $totalSchoolRevenue += DB::table('fm_fees_transactions')
                ->where('paid_status', 'paid')
                ->sum('paid_amount');
        }

        // Example: Assume a 2% platform fee on all school transactions
        // (This can be made configurable later via Ultra Super Admin settings)
        $platformFeePercentage = 0.02; 
        $platformFeeRevenue = $totalSchoolRevenue * $platformFeePercentage;

        $netPlatformRevenue = $totalSubscriptionRevenue + $platformFeeRevenue;

        // 3. Profitability Trend (Last 12 months for subscriptions)
        $monthlyTrend = [];
        if (DB::getSchemaBuilder()->hasTable('sm_subscription_payments')) {
            $monthlyTrend = DB::table('sm_subscription_payments')
                ->select(DB::raw("DATE_FORMAT(payment_date, '%Y-%m') as month"), DB::raw('SUM(amount) as revenue'))
                ->where('approve_status', 'approved')
                ->where('payment_date', '>=', now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        }

        return [
            'total_subscription_revenue' => $totalSubscriptionRevenue,
            'total_school_revenue' => $totalSchoolRevenue,
            'platform_fee_revenue' => $platformFeeRevenue,
            'net_platform_revenue' => $netPlatformRevenue,
            'monthly_trend' => $monthlyTrend
        ];
    }

    /**
     * Get revenue and profitability data for a specific school group.
     * 
     * @param int $schoolGroupId
     * @return array
     */
    public function getGroupFinancials($schoolGroupId)
    {
        $group = SchoolGroup::with('schools')->find($schoolGroupId);
        if (!$group) return [];

        $schoolIds = $group->schools->pluck('id')->toArray();
        $totalGroupRevenue = 0;

        if (!empty($schoolIds)) {
            if (DB::getSchemaBuilder()->hasTable('sm_fees_payments')) {
                $totalGroupRevenue += DB::table('sm_fees_payments')
                    ->whereIn('school_id', $schoolIds)
                    ->where('active_status', 1)
                    ->sum('amount');
            }

            if (DB::getSchemaBuilder()->hasTable('fm_fees_transactions')) {
                $totalGroupRevenue += DB::table('fm_fees_transactions')
                    ->whereIn('school_id', $schoolIds)
                    ->where('paid_status', 'paid')
                    ->sum('paid_amount');
            }
        }

        // Calculate subscription cost paid by this group (heuristic or from actual payments)
        $subscriptionPaid = 0;
        if (DB::getSchemaBuilder()->hasTable('sm_subscription_payments')) {
             $subscriptionPaid = DB::table('sm_subscription_payments')
                 ->where('school_id', $schoolIds[0] ?? 0) // Sometimes payments are linked to the primary school
                 ->where('approve_status', 'approved')
                 ->sum('amount');
        }

        return [
            'total_revenue_generated' => $totalGroupRevenue,
            'subscription_cost_paid' => $subscriptionPaid,
            'net_profitability' => $totalGroupRevenue - $subscriptionPaid
        ];
    }
}
