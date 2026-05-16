<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Fixing Sidebar Menu Links ===\n";

if (\Illuminate\Support\Facades\Schema::hasTable('sidebar_menus')) {
    echo "Checking sidebar_menus table...\n";
    $updated = DB::table('sidebar_menus')
        ->where('route', 'like', '%medical.vaccination%')
        ->update(['route' => 'vaccination-records']);
    
    $updated2 = DB::table('sidebar_menus')
        ->where('route', 'like', '%medical.records%')
        ->update(['route' => 'medical.records']);

    echo "sidebar_menus table updated. Rows matched: " . ($updated + $updated2) . "\n";
}

if (\Illuminate\Support\Facades\Schema::hasTable('permissions')) {
    echo "Checking permissions table (sometimes used for sidebar in old versions)...\n";
    $updated = DB::table('permissions')
        ->where('route', 'like', '%user-custom-menu/medical.vaccination%')
        ->update(['route' => 'vaccination-records']);
    echo "permissions updated: $updated\n";
}

if (\Illuminate\Support\Facades\Schema::hasTable('modules\custommenu\entities\custommenu')) {
    // Check if CustomMenu module is holding this
    $updated = DB::table('custom_menus')
        ->where('slug', 'like', '%medical.vaccination%')
        ->update(['menu_type' => 'url', 'url_link' => 'vaccination-records']);
    echo "custom_menus updated: $updated\n";
}

// Sidebar is generally rebuilt dynamically. In Infix 7, menus often sit in `sm_modules` or `infix_module_infos`
if (\Illuminate\Support\Facades\Schema::hasTable('infix_module_infos')) {
    echo "Checking infix_module_infos table...\n";
    $updated = DB::table('infix_module_infos')
        ->where('route', 'like', '%medical.vaccination%')
        ->update(['route' => 'vaccination-records']);
    echo "infix_module_infos updated: $updated\n";
}


