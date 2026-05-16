<?php
/**
 * ERP Universal Registry Sync
 * Run: php artisan tinker universal_registry_sync.php
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

$customModules = [
    'DashboardAnalytics' => ['name' => 'Dashboard & Analytics', 'route' => 'dashboardanalytics'],
    'AdvancedStudentPortal' => ['name' => 'Advanced Student Portal', 'route' => 'asp.index'],
    'AdvancedHrPortal' => ['name' => 'Advanced HR Portal', 'route' => 'ahp.index'],
    'AdvancedExamPortal' => ['name' => 'Advanced Exam Portal', 'route' => 'aep.index'],
    'SmartTransport' => ['name' => 'Smart Transport', 'route' => 'stp.index'],
    'SmartCommunication' => ['name' => 'Smart Communication', 'route' => 'scom.index'],
    'SmartFrontOffice' => ['name' => 'Smart Front Office', 'route' => 'sfo.index'],
];

$coreModules = ['Zoom' => 'zoom', 'Jitsi' => 'jitsi'];

echo "🚀 Starting Universal Registry Sync...\n";

// 1. Fix Core Module Admin Access in Permissions
foreach ($coreModules as $mod => $route) {
    DB::table('permissions')->where('module', $mod)->update(['is_admin' => 1, 'status' => 1, 'menu_status' => 1]);
    echo "✅ Core Permission Fixed: $mod\n";
}

// 2. Inject Custom Module Permissions (Route Registration)
$idCounter = 10000;
foreach ($customModules as $mod => $data) {
    try {
        DB::table('permissions')->updateOrInsert(
            ['module' => $mod, 'route' => $data['route']],
            [
                'name' => $data['name'],
                'section_id' => 1,
                'parent_id' => 69077, // Dashboard Parent
                'type' => 1,
                'status' => 1,
                'menu_status' => 1,
                'is_admin' => 1,
                'is_teacher' => 1,
                'is_student' => 1,
                'school_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
        echo "✅ Custom Permission Injected: $mod\n";
    } catch (\Exception $e) {
        echo "❌ Error for $mod: " . $e->getMessage() . "\n";
    }
}

// 3. Clear ALL Caches for Route Registration
try {
    Cache::flush();
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    echo "🧹 Production Caches Purged.\n";
} catch (\Exception $e) {
    echo "⚠️ Cleanup issue: " . $e->getMessage() . "\n";
}

echo "\n✨ DONE! Routes should now be registered and Sidebar accessible.\n";
