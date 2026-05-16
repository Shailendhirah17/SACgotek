<?php

use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "--- BILLING DIAGNOSTICS ---\n";

// 1. Check Student Logins
$uniqueLogins = DB::table('sm_user_logs')
    ->where('role_id', 2)
    ->distinct('user_id')
    ->count();
echo "Unique Student Logins: $uniqueLogins\n";

// 2. Check Rate
$rate = 7.00;
if (Schema::hasColumn('sm_general_settings', 'subscription_rate')) {
    $rate = DB::table('sm_general_settings')->where('id', 1)->value('subscription_rate') ?? 7.00;
}
echo "Current Rate: ₹$rate\n";

// 3. Total Usage
$totalUsage = $uniqueLogins * $rate;
echo "Total Usage Charge: ₹$totalUsage\n";

// 4. Total Paid
$totalPaid = DB::table('sm_subscription_payments')
    ->where('approve_status', 'approved')
    ->sum('amount');
echo "Total Paid (Approved): ₹$totalPaid\n";

// 5. Total Discount
$totalDiscount = 0;
if (Schema::hasTable('sm_applied_coupons')) {
    $totalDiscount = DB::table('sm_applied_coupons')->sum('discount_amount');
}
echo "Total Coupon Discounts: ₹$totalDiscount\n";

// 6. Outstanding
$outstanding = $totalUsage - $totalPaid - $totalDiscount;
echo "FINAL OUTSTANDING BALANCE: ₹" . number_format($outstanding, 2) . "\n";
echo "---------------------------\n";
