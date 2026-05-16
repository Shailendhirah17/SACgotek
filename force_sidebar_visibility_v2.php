<?php
/**
 * ERP Force Sidebar Visibility V2 (Nesting)
 * Run: php artisan tinker force_sidebar_visibility_v2.php
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

$customModules = [
    'DashboardAnalytics', 'AdvancedStudentPortal', 'AdvancedHrPortal', 
    'AdvancedExamPortal', 'SmartTransport', 'SmartCommunication', 'SmartFrontOffice'
];

$parentId = 69077; // Super Admin Dashboard Parent ID
$permissionId = 1701; // Super Admin Dashboard Permission

echo "🛠️ Nesting Custom Modules under Parent ID $parentId...\n";

foreach ($customModules as $module) {
    try {
        $updated = DB::table('sm_menus')
            ->where('module', $module)
            ->update([
                'parent_id' => $parentId,
                'permission_id' => $permissionId,
                'role_id' => 1,
                'status' => 1,
                'menu_status' => 1,
                'is_saas' => 0,
                'school_id' => 1
            ]);

        if ($updated) {
            echo "✅ Nested & Active: $module\n";
        } else {
            echo "❓ Menu not found for $module\n";
        }
    } catch (\Exception $e) {
        echo "❌ Error for $module: " . $e->getMessage() . "\n";
    }
}

// Global Cleanup
try {
    Cache::flush();
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    echo "🧹 Sidebar Caches Purged.\n";
} catch (\Exception $e) {
    echo "⚠️ Cleanup issue: " . $e->getMessage() . "\n";
}

echo "\n✨ DONE! Please check the Dashboard section in the sidebar.\n";
