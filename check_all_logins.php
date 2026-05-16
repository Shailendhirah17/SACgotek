<?php
// check_all_logins.php - Verify all 3 login panels work

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\UltraSuperAdmin;
use App\Models\SuperAdmin;

echo "=== Login Panel Verification ===\n\n";

// -------------------------------------------------------
// 1. ULTRA SUPER ADMIN (Technosprint)
//    URL: /ultrasuperadmin/
//    Guard: 'ultrasuperadmin'
//    Model: ultra_super_admins table
// -------------------------------------------------------
echo "--- Level 1: Ultra Super Admin ---\n";
echo "URL: https://erpv2.test-technoprint.online/ultrasuperadmin/login\n";
$usa = UltraSuperAdmin::get(['id', 'username', 'email', 'active_status', 'role']);
if ($usa->count()) {
    foreach ($usa as $u) {
        echo "  ID:{$u->id} | Username:{$u->username} | Email:{$u->email} | Status:" . ($u->active_status ? 'Active' : 'Inactive') . " | Role:{$u->role}\n";
    }
} else {
    echo "  NO USERS FOUND! Need to create one.\n";
}

echo "\n";

// -------------------------------------------------------
// 2. SUPER ADMIN (Organization Head)
//    URL: /superadmin
//    Guard: 'superadmin'
//    Model: super_admins table (if exists) or users with admin_level=2
// -------------------------------------------------------
echo "--- Level 2: Super Admin (Org Head) ---\n";
echo "URL: https://erpv2.test-technoprint.online/superadmin\n";

// Check if separate super_admins table exists
if (Illuminate\Support\Facades\Schema::hasTable('super_admins')) {
    $sa = SuperAdmin::get(['id', 'username', 'email', 'active_status', 'school_group_id']);
    if ($sa->count()) {
        foreach ($sa as $s) {
            $grp = DB::table('school_groups')->find($s->school_group_id);
            echo "  ID:{$s->id} | Email:{$s->email} | Group:" . ($grp ? $grp->name : 'None') . " | Status:" . ($s->active_status ? 'Active' : 'Inactive') . "\n";
        }
    } else {
        echo "  NO SUPER ADMIN USERS FOUND!\n";
    }
} else {
    echo "  super_admins table not found, checking users table...\n";
    $orgHeads = DB::table('users')->where('admin_level', 2)->get(['id', 'email', 'school_group_id', 'active_status']);
    foreach ($orgHeads as $o) {
        $grp = DB::table('school_groups')->find($o->school_group_id);
        echo "  ID:{$o->id} | Email:{$o->email} | Group:" . ($grp ? $grp->name : 'None') . " | Status:" . ($o->active_status ? 'Active' : 'Inactive') . "\n";
    }
}

echo "\n";

// -------------------------------------------------------
// 3. SCHOOL ADMIN (Principal)
//    URL: /login
//    Guard: 'web' (default users table)
// -------------------------------------------------------
echo "--- Level 3: School Admin (Principal) ---\n";
echo "URL: https://erpv2.test-technoprint.online/login\n";
$admins = DB::table('users')
    ->where('role_id', 1)
    ->orWhere('admin_level', 3)
    ->get(['id', 'full_name', 'email', 'school_id', 'admin_level', 'active_status']);
foreach ($admins as $a) {
    $school = DB::table('sm_schools')->find($a->school_id);
    echo "  ID:{$a->id} | Email:{$a->email} | School:" . ($school ? $school->school_name : 'N/A') . " | Level:{$a->admin_level}\n";
}

echo "\n=== SUMMARY ===\n";
echo "Ultra Super Admin login : /ultrasuperadmin/login\n";
echo "Super Admin login       : /superadmin (check login route)\n";
echo "School Admin login      : /login\n";
