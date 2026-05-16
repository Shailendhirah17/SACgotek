<?php
require "vendor/autoload.php";
$app = require_once "bootstrap/app.php";
$kernel = $app->make("Illuminate\Contracts\Console\Kernel");
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;

$routes = [
    'student_modules_section', 'library_book_bank_section', 'vendor_accounts_section', 'hostel_management_section',
    'tc-list', 'medical-records', 'vaccination-records', 'book-bank', 'thirukkural', 'book-bank-issue', 
    'vendor-list', 'purchase-orders', 'vendor-payments', 'hostel-list', 'hostel-allocation', 'hostel-fee'
];

$perms = DB::table("permissions")->whereIn("route", $routes)->get();
$sidebars = DB::table("sidebars")->whereIn("permission_id", $perms->pluck('id'))->where('role_id', 1)->get();

echo "--- PERMISSIONS ---" . "\n";
echo json_encode($perms, JSON_PRETTY_PRINT) . "\n";
echo "--- SIDEBARS ---" . "\n";
echo json_encode($sidebars, JSON_PRETTY_PRINT) . "\n";
echo "--- END ---" . "\n";
