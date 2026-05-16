<?php
// db_route_repair.php - Remote route fix
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

echo "=== System Route Repair ===\n";

// 1. Fix Custom Menu entries
if (\Illuminate\Support\Facades\Schema::hasTable('custom_menus')) {
    $updated = DB::table('custom_menus')
        ->where('slug', 'like', '%medical.vaccination%')
        ->update([
            'menu_type' => 'url',
            'url_link' => 'vaccination-records'
        ]);
    echo "Updated custom_menus: $updated rows\n";
}

// 2. Fix Sidebar Sub-menu entries
// Many Infix versions use 'menus' or 'sm_menus' for the sidebar
$menu_tables = ['sm_menus', 'menus', 'sidebar_menus'];
foreach ($menu_tables as $table) {
    if (\Illuminate\Support\Facades\Schema::hasTable($table)) {
        $updated = DB::table($table)
            ->where('route', 'like', '%medical.vaccination%')
            ->orWhere('route', 'vaccination-records')
            ->update(['route' => 'medical.vaccination']);
        echo "Updated $table: $updated rows\n";
    }
}

// 3. Clear all caches
echo "Clearing Caches...\n";
Artisan::call('route:clear');
Artisan::call('view:clear');
Artisan::call('cache:clear');
echo "Caches Cleared Successfully!\n";

echo "=== Repair Process Completed ===\n";
