<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make("Illuminate\Contracts\Console\Kernel");
$kernel->bootstrap();

use Modules\RolePermission\Entities\Permission;
use Illuminate\Support\Facades\DB;

try {
    $all_permissions = Permission::with('subModule.subModule')
        ->whereNull('custom_menu_id')
        ->where('is_saas', 0)
        ->whereNull('parent_route')
        ->where(function($q){
            $q->where('permission_section', '!=', 1)->orWhereNull('permission_section');
        })
        ->whereNotNull('route')->where('route', '!=', '')
        // ->whereNotInDeaActiveModulePermission()
        ->where('menu_status', 1)
        ->where('is_admin', 1)
        ->where('role_id', null)
        ->orderBy('position', 'ASC')
        ->get();

    echo "Found " . count($all_permissions) . " permissions.\n";
    foreach ($all_permissions as $p) {
        echo "- " . $p->name . " (module: " . $p->module . ")\n";
    }

    echo "\n=== ALL PARENT ROUTES ===\n";
    // Check all parent routes without constraints to see what is missing
    $any_parents = Permission::whereNull('parent_route')->get();
    echo "Total Parent Routes: " . count($any_parents) . "\n";
    if (count($any_parents) > 0) {
        $first = $any_parents->first();
        echo "Example parent route values for '{$first->name}':\n";
        echo "is_saas: {$first->is_saas}, permission_section: {$first->permission_section}, route: {$first->route}, menu_status: {$first->menu_status}, is_admin: {$first->is_admin}, role_id: {$first->role_id}\n";
    }

    echo "\n=== WHATSAPP MODULE DETAILS ===\n";
    $wa = Permission::where('name', 'like', '%Whatsapp%')->first();
    if ($wa) {
        echo "is_saas: {$wa->is_saas}, permission_section: {$wa->permission_section}, route: {$wa->route}, menu_status: {$wa->menu_status}, is_admin: {$wa->is_admin}, role_id: {$wa->role_id}, custom_menu_id: {$wa->custom_menu_id}, parent_route: {$wa->parent_route}\n";
    }

    echo "\n=== DASHBOARD MODULE DETAILS ===\n";
    $db = Permission::where('name', 'like', '%Dashboard%')->first();
    if ($db) {
        echo "is_saas: {$db->is_saas}, permission_section: {$db->permission_section}, route: {$db->route}, menu_status: {$db->menu_status}, is_admin: {$db->is_admin}, role_id: {$db->role_id}, custom_menu_id: {$db->custom_menu_id}, parent_route: {$db->parent_route}\n";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
