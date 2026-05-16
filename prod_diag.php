<?php
require "vendor/autoload.php";
$app = require_once "bootstrap/app.php";
$kernel = $app->make("Illuminate\Contracts\Console\Kernel");
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;

$routes = ["student_modules_section", "library_book_bank_section", "vendor_accounts_section", "hostel_management_section"];
$perms = DB::table("permissions")->whereIn("route", $routes)->pluck("id", "route");
$children = DB::table("permissions")->whereIn("parent_route", $routes)->pluck("id", "route");
$schools = DB::table("sm_schools")->pluck("id");

echo "--- DATA ---" . "\n";
echo "Perms: " . json_encode($perms) . "\n";
echo "Children: " . json_encode($children) . "\n";
echo "Schools: " . json_encode($schools) . "\n";
echo "--- END ---" . "\n";
