<?php
/**
 * ERP Final Sidebar Restoration (Correct Permission IDs)
 * Run: php artisan tinker final_sidebar_fix.php
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

echo "🛠️ Restoring Correct Sidebar Items (DASHBOARD, Dashboard, Sidebar Manager)...\n";

$schools = DB::table('sm_schools')->pluck('id')->toArray();

// Core IDs from PERMISSIONS table
$correctItems = [
    ['id' => 1701, 'parent' => null, 'pos' => 1], // Dashboard Section
    ['id' => 868, 'parent' => 1701, 'pos' => 2],  // Dashboard Item
    ['id' => 878, 'parent' => 1701, 'pos' => 3],  // Sidebar Manager Item
];

foreach ($schools as $schoolId) {
    echo "Processing School ID: $schoolId\n";
    foreach ($correctItems as $item) {
        try {
            $exists = DB::table('sidebars')
                ->where('permission_id', $item['id'])
                ->where('role_id', 1)
                ->where('school_id', $schoolId)
                ->where('user_id', null)
                ->first();

            if ($exists) {
                DB::table('sidebars')
                    ->where('id', $exists->id)
                    ->update([
                        'active_status' => 1,
                        'parent' => $item['parent'],
                        'position' => $item['pos'],
                        'updated_at' => now(),
                    ]);
                echo "  ✅ ID {$item['id']} UPDATED for School $schoolId.\n";
            } else {
                DB::table('sidebars')->insert([
                    'permission_id' => $item['id'],
                    'role_id' => 1,
                    'user_id' => null,
                    'school_id' => $schoolId,
                    'active_status' => 1,
                    'parent' => $item['parent'],
                    'position' => $item['pos'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                echo "  ✅ ID {$item['id']} CREATED for School $schoolId.\n";
            }
        } catch (\Exception $e) {
            echo "  ❌ Error for ID {$item['id']} in School $schoolId: " . $e->getMessage() . "\n";
        }
    }
}

echo "\n🛠️ Enabling Role-Based Sidebar globally...\n";
DB::table('sm_general_settings')->update(['role_based_sidebar' => 1]);

echo "\n🧹 Final Cache Purge...\n";
try {
    Cache::flush();
    Artisan::call('optimize:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    echo "✅ Caches Purged.\n";
} catch (\Exception $e) {
    echo "⚠️ Cache issue: " . $e->getMessage() . "\n";
}

echo "\n✨ DONE! Please check the dashboard now.\n";
