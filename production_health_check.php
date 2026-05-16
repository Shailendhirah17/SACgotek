<?php
/**
 * Production Health Check and Route Validator
 * Run: php artisan tinker production_health_check.php
 */

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

$routesToTest = [
    'Dashboard' => 'admin-dashboard',
    'Dashboard Analytics' => 'dashboardanalytics',
    'Advanced Student Portal' => 'asp.index',
    'Advanced HR Portal' => 'ahp.index',
    'Advanced Exam Portal' => 'aep.index',
    'Smart Transport' => 'stp.index',
    'Smart Communication' => 'scom.index',
    'Smart Front Office' => 'sfo.index',
    'Zoom' => 'zoom.meetings',
    'Jitsi' => 'jitsi.meetings'
];

echo "🔍 Starting Deep Route Verification on Production...\n";

foreach ($routesToTest as $label => $routeName) {
    if (Route::has($routeName)) {
        $url = route($routeName);
        echo "✅ [FOUND] $label -> $url\n";
    } else {
        echo "❌ [MISSING] Route for $label ($routeName) not registered!\n";
    }
}

echo "\n📊 Data Integrity Check:\n";
try {
    $staffCount = DB::table('sm_staffs')->count();
    $studentCount = DB::table('sm_students')->count();
    $activeSessions = DB::table('sessions')->count();
    
    echo "Staff Count: $staffCount\n";
    echo "Student Count: $studentCount\n";
    echo "Active Sessions: $activeSessions\n";
} catch (\Exception $e) {
    echo "⚠️ Database query issue: " . $e->getMessage() . "\n";
}

echo "\n✨ Health Check Complete.\n";
