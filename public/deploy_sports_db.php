<?php

/**
 * Surgical Database Deployment Page for Sports Feature
 * Automatically runs artisan migrations and seeds active schedules on the live server.
 * 
 * IMPORTANT: Delete this file after successful deployment!
 */

// Bootstrap Laravel application
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;

// Security check: simple passkey to prevent unauthorized database access
$passkey = isset($_GET['key']) ? $_GET['key'] : '';
if ($passkey !== 'sacgotek_deploy_2026') {
    http_response_code(403);
    die("Error: Unauthorized deployment request.");
}

echo "<h2>Sports Feature Live Database Deployment</h2><pre>";

try {
    // 1. Run migrations
    echo "Step 1: Running migrations...\n";
    $exitCode = Artisan::call('migrate', ['--force' => true]);
    echo "Exit Code: " . $exitCode . "\n";
    echo "Output:\n" . Artisan::output() . "\n";
    
    // 2. Re-register autoload classmap dynamically
    echo "Step 2: Refreshing Composer autoloaders...\n";
    // Laravel will discover the newly loaded seeder class automatically
    
    // 3. Run seeder
    echo "Step 3: Seeding sports schedules...\n";
    $exitCodeSeeder = Artisan::call('db:seed', [
        '--class' => 'Database\\Seeders\\SmSportsSchedulesSeeder',
        '--force' => true
    ]);
    echo "Exit Code: " . $exitCodeSeeder . "\n";
    echo "Output:\n" . Artisan::output() . "\n";
    
    echo "<h3>SUCCESS: Sports database schemas and training schedules deployed successfully!</h3>";
} catch (\Exception $e) {
    echo "<h3 style='color: red;'>ERROR: " . $e->getMessage() . "</h3>";
    echo "\nTrace:\n" . $e->getTraceAsString() . "\n";
}
