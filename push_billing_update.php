<?php
/**
 * Billing & Coupon System - Surgical SFTP Push
 * Pushes ONLY the edited files to production
 */
require __DIR__ . '/vendor/autoload.php';

use phpseclib3\Net\SFTP;

$host     = '193.202.45.164';
$port     = 65002;
$username = 'u841409365';
$password = 'Eash@2005';
$remoteBase = 'domains/test-technoprint.online/public_html/erpv2/';

// Exact list of edited/new files for this billing feature
$filesToUpload = [
    // Dashboard
    'app/Http/Controllers/SuperAdmin/DashboardController.php',
    'resources/views/backEnd/superAdmin/dashboard.blade.php',

    // Migration
    'database/migrations/2024_04_13_000001_create_subscription_billing_tables.php',

    // Models
    'app/Models/SubscriptionCoupon.php',
    'app/Models/AppliedCoupon.php',

    // Controllers
    'app/Http/Controllers/SuperAdmin/Subscription/CouponController.php',
    'app/Http/Controllers/SuperAdmin/Subscription/SubscriptionController.php',

    // Routes
    'routes/superadmin.php',

    // Views
    'resources/views/backEnd/superAdmin/subscriptions/coupons.blade.php',
    'resources/views/backEnd/superAdmin/subscriptions/index.blade.php',
    'resources/views/backEnd/superAdmin/layouts/sidebar.blade.php',
];

echo "=================================================\n";
echo " Billing & Coupon System - SFTP Surgical Push\n";
echo "=================================================\n";
echo "Connecting to $host:$port...\n";

try {
    $sftp = new SFTP($host, $port);

    if (!$sftp->login($username, $password)) {
        throw new Exception("LOGIN FAILED. Check credentials.");
    }
    echo "✅ Login successful!\n\n";

    $successCount = 0;
    $failCount    = 0;

    foreach ($filesToUpload as $localRelative) {
        $fullLocal  = __DIR__ . '/' . $localRelative;
        $fullRemote = $remoteBase . $localRelative;

        if (!file_exists($fullLocal)) {
            echo "⚠️  SKIP (not found locally): $localRelative\n";
            continue;
        }

        // Ensure remote directory exists
        $remoteDir = dirname($fullRemote);
        $sftp->mkdir($remoteDir, -1, true);

        echo "Uploading: $localRelative ... ";
        if ($sftp->put($fullRemote, $fullLocal, SFTP::SOURCE_LOCAL_FILE)) {
            echo "✅ OK\n";
            $successCount++;
        } else {
            echo "❌ FAILED\n";
            $failCount++;
        }
    }

    echo "\n----- Files Uploaded: $successCount | Failed: $failCount -----\n\n";

    // Run migration + cache clear remotely
    $cmds = [
        "php artisan migrate --force"  => "Migration",
        "php artisan cache:clear"      => "Cache clear",
        "php artisan view:clear"       => "View clear",
        "php artisan config:clear"     => "Config clear",
        "php artisan route:clear"      => "Route clear",
        "touch public/.htaccess"       => "LiteSpeed cache bust",
    ];

    foreach ($cmds as $cmd => $label) {
        echo "Running: $label ... ";
        $output = $sftp->exec("cd $remoteBase && $cmd 2>&1");
        $output = trim($output);
        echo ($output ?: "done") . "\n";
    }

    echo "\n✨ Deployment Complete! Visit https://erpv2.test-technoprint.online/superadmin/dashboard to verify.\n";

} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
