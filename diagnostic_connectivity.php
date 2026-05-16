<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- ERP SYSTEM CONNECTIVITY & DATA FETCHING AUDIT ---\n\n";

$customModules = [
    'TC List' => ['table' => 'sm_transfer_certificates', 'route' => 'tc-list'],
    'Medical Records' => ['table' => 'sm_medical_records', 'route' => 'medical-records'],
    'Vaccination' => ['table' => 'sm_vaccination_records', 'route' => 'vaccination-records'],
    'Book Bank' => ['table' => 'sm_book_banks', 'route' => 'book-bank'],
    'Thirukkural' => ['table' => 'sm_thirukkurals', 'route' => 'thirukkural'],
    'Vendors' => ['table' => 'sm_vendors', 'route' => 'vendor-list'],
    'Purchase Orders' => ['table' => 'sm_purchase_orders', 'route' => 'purchase-orders'],
    'Hostels' => ['table' => 'sm_hostels', 'route' => 'hostel-list'],
];

echo "SECTION 1: CUSTOM MODULES STATUS\n";
echo str_pad("Module", 20) . " | " . str_pad("Table", 30) . " | " . str_pad("Status", 10) . " | " . "Count\n";
echo str_repeat("-", 80) . "\n";

foreach ($customModules as $name => $info) {
    $exists = Schema::hasTable($info['table']);
    $count = $exists ? DB::table($info['table'])->count() : 0;
    $status = $exists ? "OK" : "MISSING";
    
    echo str_pad($name, 20) . " | " . str_pad($info['table'], 30) . " | " . str_pad($status, 10) . " | " . $count . "\n";
}

echo "\nSECTION 2: SIDEBAR MENU REGISTRATION\n";
echo str_pad("Module Route", 25) . " | " . "Sidebar Presence\n";
echo str_repeat("-", 50) . "\n";

foreach ($customModules as $name => $info) {
    $menu = DB::table('sm_menus')->where('route', $info['route'])->first();
    $presence = $menu ? "FOUND (ID: {$menu->id})" : "NOT FOUND";
    echo str_pad($info['route'], 25) . " | " . $presence . "\n";
}

echo "\nSECTION 3: CORE MODULE HEALTH CHECK\n";
$coreTables = ['sm_students', 'sm_staffs', 'sm_classes', 'sm_sections', 'sm_subjects'];
foreach ($coreTables as $table) {
    $count = Schema::hasTable($table) ? DB::table($table)->count() : "N/A";
    echo str_pad($table, 20) . ": " . $count . " records\n";
}

echo "\n--- AUDIT COMPLETE ---\n";
