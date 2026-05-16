<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== Search Sidebar Menu System ===\n";

$tables = Schema::getAllTables();
foreach ($tables as $t) {
    // Cast stdClass object to array to get the name
    $t_array = (array) $t;
    $t_name = array_values($t_array)[0];
    if (strpos($t_name, 'menu') !== false || strpos($t_name, 'sidebar') !== false) {
        echo "Found table: $t_name\n";
    }
}

// Let's do a brute force search in `sm_pages` or `sm_sidebar_menus`
$to_check = ['sm_pages', 'sidebar_menus', 'sm_sidebar_menus', 'infix_module_infos', 'sm_custom_menus'];
foreach ($to_check as $table) {
    if (Schema::hasTable($table)) {
        echo "Checking $table...\n";
        if (Schema::hasColumn($table, 'route')) {
            $updated = DB::table($table)->where('route', 'like', '%medical.vaccination%')->update(['route' => 'vaccination-records']);
            $updated2 = DB::table($table)->where('route', 'like', '%medical.records%')->update(['route' => 'medical.records']);
            if ($updated > 0 || $updated2 > 0) {
                 echo ">>> FIXED ROUTES IN $table (Updated $updated + $updated2 rows) <<<\n";
            }
        }
    }
}
