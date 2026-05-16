<?php
// require "vendor/autoload.php";
// $app = require_once "bootstrap/app.php";
// $kernel = $app->make("Illuminate\Contracts\Console\Kernel");
// $kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$schoolId = 11;
$roleId = 1;

try {
    $menus = DB::table("sm_menus")->where("school_id", $schoolId)->where("role_id", $roleId)->whereIn("route", [
        "student_modules_section", "library_book_bank_section", "vendor_accounts_section", "hostel_management_section"
    ])->get();

    $perms = DB::table("assign_permissions")->where("school_id", $schoolId)->where("role_id", $roleId)
        ->join("permissions", "assign_permissions.permission_id", "Permissions.id")
        ->whereIn("permissions.route", ["student_modules_section", "tc-list"])
        ->select("permissions.route")
        ->get();

    echo "--- SIDEBAR DIAGNOSTICS ---\n";
    echo "School ID: $schoolId, Role ID: $roleId\n";
    echo "Found " . $menus->count() . " sections in sm_menus for this context.\n";
    foreach ($menus as $m) { echo "- Menu Route: {$m->route}\n"; }
    echo "--- END ---\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
