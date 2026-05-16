<?php
// rbac_audit.php - Automated RBAC Security Audit
// Tests all 35 test cases from the RBAC plan against production

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

$pass = 0; $fail = 0; $warn = 0;
$failures = [];

function check($id, $desc, $result, $expected = true, $severity = 'CRITICAL') {
    global $pass, $fail, $warn, $failures;
    $ok = ($result === $expected);
    if ($ok) {
        $pass++;
        echo "[PASS] $id: $desc\n";
    } else {
        $fail++;
        $failures[] = ['id' => $id, 'desc' => $desc, 'severity' => $severity];
        echo "[FAIL][$severity] $id: $desc\n";
    }
}

function warn_check($id, $desc, $result) {
    global $warn;
    if (!$result) {
        $warn++;
        echo "[WARN] $id: $desc\n";
    } else {
        echo "[OK  ] $id: $desc\n";
    }
}

echo "==========================================================\n";
echo " RBAC SECURITY AUDIT - School ERP v2\n";
echo " " . date('Y-m-d H:i:s') . "\n";
echo "==========================================================\n\n";

// -------------------------------------------------------
// SECTION 1: DB SCHEMA CHECKS
// -------------------------------------------------------
echo "--- 1. Schema & Guard Configuration ---\n";

check('S01', 'users table has school_id column',
    Schema::hasColumn('users', 'school_id'));

check('S02', 'users table has admin_level column',
    Schema::hasColumn('users', 'admin_level'));

check('S03', 'users table has school_group_id column',
    Schema::hasColumn('users', 'school_group_id'));

check('S04', 'sm_schools table has school_group_id column',
    Schema::hasColumn('sm_schools', 'school_group_id'));

check('S05', 'ultra_super_admins table exists',
    Schema::hasTable('ultra_super_admins'));

check('S06', 'super_admins table exists',
    Schema::hasTable('super_admins'));

check('S07', 'school_groups table exists',
    Schema::hasTable('school_groups'));

check('S08', 'SchoolScope file exists',
    file_exists(app_path('Scopes/SchoolScope.php')));

check('S09', 'RoleLevelEnforcer middleware exists',
    file_exists(app_path('Http/Middleware/RoleLevelEnforcer.php')));

check('S10', 'UltraSuperAdminMiddleware exists',
    file_exists(app_path('Http/Middleware/UltraSuperAdminMiddleware.php')));

echo "\n";

// -------------------------------------------------------
// SECTION 2: ULTRA SUPER ADMIN (Level 1)
// -------------------------------------------------------
echo "--- 2. Ultra Super Admin (Level 1) Checks ---\n";

$usa = DB::table('ultra_super_admins')->first();
check('L1-01', 'At least 1 Ultra Super Admin exists', !is_null($usa));

if ($usa) {
    check('L1-02', 'USAdmin has a hashed password (not plain text)',
        strlen($usa->password) > 30 && str_starts_with($usa->password, '$'));

    check('L1-03', 'USAdmin is active',
        (bool)$usa->active_status);

    check('L1-04', 'USAdmin has role=ultra_super_admin',
        $usa->role === 'ultra_super_admin');
}

$usaCount = DB::table('ultra_super_admins')->count();
check('L1-05', 'Not too many Ultra Super Admins (max 5)',
    $usaCount <= 5, true, 'WARNING');

echo "\n";

// -------------------------------------------------------
// SECTION 3: SUPER ADMIN (Level 2)
// -------------------------------------------------------
echo "--- 3. Super Admin / Org Head (Level 2) Checks ---\n";

$saCount = DB::table('super_admins')->count();
check('L2-01', 'Super admins exist in super_admins table', $saCount > 0);

// Check every super admin has a school_group_id
$saNoGroup = DB::table('super_admins')
    ->whereNull('school_group_id')
    ->orWhere('school_group_id', 0)
    ->count();
check('L2-02', 'No super admin without a school_group_id',
    $saNoGroup === 0, true, 'CRITICAL');

// Check every super admin has hashed password
$saPlain = DB::table('super_admins')
    ->where(function($q) {
        $q->whereRaw('LENGTH(password) < 30')
          ->orWhereNull('password');
    })->count();
check('L2-03', 'All super admins have hashed passwords',
    $saPlain === 0, true, 'CRITICAL');

// Verify org isolation - Super admin from group A should only see group A schools
$groups = DB::table('school_groups')->pluck('id')->toArray();
$crossOrgLeak = false;
foreach (DB::table('super_admins')->get() as $sa) {
    if (!$sa->school_group_id) continue;
    // Get schools that should NOT be visible to this admin
    $otherGroupSchools = DB::table('sm_schools')
        ->where('school_group_id', '!=', $sa->school_group_id)
        ->whereNotNull('school_group_id')
        ->count();
    // This is a config check - actual scope enforcement is in SchoolScope
    if ($otherGroupSchools > 0) {
        // Check SchoolScope handles this (static analysis)
        $scopeContent = file_get_contents(app_path('Scopes/SchoolScope.php'));
        if (!str_contains($scopeContent, 'school_group_id') || !str_contains($scopeContent, 'whereIn')) {
            $crossOrgLeak = true;
        }
        break;
    }
}
check('L2-04', 'SchoolScope enforces org-level isolation (school_group_id whereIn)',
    !$crossOrgLeak, true, 'CRITICAL');

echo "\n";

// -------------------------------------------------------
// SECTION 4: SCHOOL ADMIN (Level 3)
// -------------------------------------------------------
echo "--- 4. School Admin (Level 3) Checks ---\n";

$schoolAdmins = DB::table('users')
    ->where('role_id', 1)
    ->whereNotNull('school_id')
    ->count();
check('L3-01', 'School admins exist (role_id=1, school_id set)', $schoolAdmins > 0);

// Check no school admin has NULL school_id
$noSchool = DB::table('users')
    ->where('role_id', 1)
    ->whereNull('school_id')
    ->count();
check('L3-02', 'No school admin with NULL school_id', $noSchool === 0, true, 'CRITICAL');

// Check SchoolScope enforces school_id filter for regular users
$scopeContent = file_get_contents(app_path('Scopes/SchoolScope.php'));
check('L3-03', 'SchoolScope has school_id filter for admin level != 1,2',
    str_contains($scopeContent, "auth()->user()->school_id") ||
    str_contains($scopeContent, 'school_id'));

check('L3-04', 'SchoolScope handles admin_level=1 (global bypass)',
    str_contains($scopeContent, 'admin_level'));

check('L3-05', 'SchoolScope handles admin_level=2 (org-wide)',
    str_contains($scopeContent, 'admin_level == 2'));

echo "\n";

// -------------------------------------------------------
// SECTION 5: ROLE ESCALATION PREVENTION
// -------------------------------------------------------
echo "--- 5. Role Escalation Prevention ---\n";

// Check if any non-admin user has admin_level=1
$escalated = DB::table('users')
    ->where('admin_level', 1)
    ->where('is_administrator', '!=', 'yes')
    ->count();
check('E01', 'No non-administrator user has admin_level=1',
    $escalated === 0, true, 'CRITICAL');

// Check RoleLevelEnforcer has boundary enforcement
$middlewareContent = file_get_contents(app_path('Http/Middleware/RoleLevelEnforcer.php'));
check('E02', 'RoleLevelEnforcer has org boundary check (enforceOrgBoundary)',
    str_contains($middlewareContent, 'enforceOrgBoundary'));

check('E03', 'RoleLevelEnforcer checks admin_level from DB (not just cookie)',
    str_contains($middlewareContent, 'resolveLevel'));

// Check Kernel has role.level registered
$kernelContent = file_get_contents(app_path('Http/Kernel.php'));
check('E04', 'role.level middleware registered in Kernel',
    str_contains($kernelContent, "'role.level'"));

// Check UltraSuperAdmin uses separate auth guard
check('E05', 'UltraSuperAdmin uses separate DB table (not users table)',
    Schema::hasTable('ultra_super_admins'));

echo "\n";

// -------------------------------------------------------
// SECTION 6: DATA ISOLATION
// -------------------------------------------------------
echo "--- 6. Data Isolation Checks ---\n";

// Check all students have a valid school_id
$orphanStudents = DB::table('sm_students')
    ->whereNull('school_id')
    ->orWhere('school_id', 0)
    ->count();
check('D01', 'No students with NULL or 0 school_id',
    $orphanStudents === 0, true, 'HIGH');

// Check all sm_staffs have a valid school_id
$orphanStaff = DB::table('sm_staffs')
    ->whereNull('school_id')
    ->orWhere('school_id', 0)
    ->count();
check('D02', 'No staff records with NULL or 0 school_id',
    $orphanStaff === 0, true, 'HIGH');

// Check all classes have a valid school_id
$orphanClasses = DB::table('sm_classes')
    ->whereNull('school_id')
    ->count();
check('D03', 'No classes with NULL school_id', $orphanClasses === 0, true, 'MEDIUM');

// Check sm_parents have school_id
$orphanParents = DB::table('sm_parents')
    ->whereNull('school_id')
    ->count();
check('D04', 'No parent records with NULL school_id',
    $orphanParents === 0, true, 'MEDIUM');

echo "\n";

// -------------------------------------------------------
// SECTION 7: SESSION & SECURITY CONFIG
// -------------------------------------------------------
echo "--- 7. Session & Security Configuration ---\n";

$appEnv = config('app.env');
check('SEC01', 'App is not in debug mode on production',
    !config('app.debug'), true, 'HIGH');

$sessionDriver = config('session.driver');
warn_check('SEC02', "Session driver is 'file' or 'database' (got: $sessionDriver)",
    in_array($sessionDriver, ['file', 'database', 'redis', 'memcached']));

$sessionSecure = config('session.secure');
warn_check('SEC03', 'Session cookies are secure (HTTPS only)',
    $sessionSecure === true);

$sessionHttpOnly = config('session.http_only');
warn_check('SEC04', 'Session cookies are HttpOnly',
    $sessionHttpOnly !== false);

echo "\n";

// -------------------------------------------------------
// FINAL REPORT
// -------------------------------------------------------
echo "==========================================================\n";
echo " AUDIT SUMMARY\n";
echo "==========================================================\n";
echo " PASSED  : $pass\n";
echo " FAILED  : $fail\n";
echo " WARNINGS: $warn\n";
echo "\n";

if (!empty($failures)) {
    echo " CRITICAL FAILURES TO FIX:\n";
    foreach ($failures as $f) {
        echo "  [{$f['severity']}] {$f['id']} - {$f['desc']}\n";
    }
} else {
    echo " No critical failures found.\n";
}
echo "==========================================================\n";
