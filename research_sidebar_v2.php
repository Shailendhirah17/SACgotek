<?php
require "vendor/autoload.php";
$app = require_once "bootstrap/app.php";
$kernel = $app->make("Illuminate\Contracts\Console\Kernel");
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;

$sid = 11;
$roleId = 1;

$data = [
    "accounts_section" => DB::table("sm_menus")->where("school_id", $sid)->where("role_id", $roleId)->where("route", "accounts_section")->first(),
    "accounts_child" => DB::table("sm_menus")->where("school_id", $sid)->where("role_id", $roleId)->where("route", "wallet")->first(),
    "student_modules_section" => DB::table("sm_menus")->where("school_id", $sid)->where("role_id", $roleId)->where("route", "student_modules_section")->first(),
    "tc_child" => DB::table("sm_menus")->where("school_id", $sid)->where("role_id", $roleId)->where("route", "tc-list")->first(),
    "all_student_modules" => DB::table("sm_menus")->where("school_id", $sid)->where("role_id", $roleId)->where("route", "student_modules_section")->get()
];

echo "--- SIDEBAR RESEARCH ---\n";
echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
echo "--- END ---\n";
