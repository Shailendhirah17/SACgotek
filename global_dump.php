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

$all = DB::table("sm_menus")->whereIn("route", $routes)->get(["id", "route", "school_id", "role_id", "permission_section", "parent_id"]);

echo "--- GLOBAL DUMP ---\n";
echo json_encode($all, JSON_PRETTY_PRINT) . "\n";
echo "--- END ---\n";
