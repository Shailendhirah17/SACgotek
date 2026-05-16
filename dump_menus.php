<?php
require "vendor/autoload.php";
$app = require_once "bootstrap/app.php";
$kernel = $app->make("Illuminate\Contracts\Console\Kernel");
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;

$sid = 11;
$roleId = 1;

$routes = [
    'student_modules_section', 'library_book_bank_section', 'vendor_accounts_section', 'hostel_management_section',
    'tc-list', 'medical-records', 'vaccination-records', 'book-bank', 'thirukkural', 'book-bank-issue', 
    'vendor-list', 'purchase-orders', 'vendor-payments', 'hostel-list', 'hostel-allocation', 'hostel-fee'
];

$all = DB::table("sm_menus")->where("school_id", $sid)->where("role_id", $roleId)->whereIn("route", $routes)->get();

echo "--- DATA DUMP ---" . "\n";
echo json_encode($all, JSON_PRETTY_PRINT) . "\n";
echo "--- END ---" . "\n";
