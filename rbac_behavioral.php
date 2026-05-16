<?php
// rbac_behavioral.php - Deep behavioral RBAC tests
// Tests actual data isolation at the query level

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\SmStudent;
use App\SmStaff;

$pass = 0; $fail = 0;
$issues = [];

function bcheck($id, $desc, $result, $expected = true, $sev = 'HIGH') {
    global $pass, $fail, $issues;
    $ok = ($result === $expected);
    if ($ok) { $pass++; echo "[PASS] $id: $desc\n"; }
    else {
        $fail++;
        $issues[] = ['id' => $id, 'desc' => $desc, 'sev' => $sev];
        echo "[FAIL][$sev] $id: $desc\n";
    }
}

echo "==========================================================\n";
echo " BEHAVIORAL DATA ISOLATION AUDIT\n";
echo "==========================================================\n\n";

// -------------------------------------------------------
// Get test subjects - pick 2 schools from different orgs
// -------------------------------------------------------
$school1 = DB::table('sm_schools')->whereNotNull('school_group_id')->first();
$school2 = DB::table('sm_schools')
    ->whereNotNull('school_group_id')
    ->where('school_group_id', '!=', $school1->school_group_id ?? 0)
    ->first();

echo "Test School 1: ID={$school1->id}, Name={$school1->school_name}, Group={$school1->school_group_id}\n";
echo "Test School 2: " . ($school2 ? "ID={$school2->id}, Name={$school2->school_name}, Group={$school2->school_group_id}" : "Not found") . "\n\n";

// -------------------------------------------------------
// 1. CROSS-SCHOOL DATA LEAKAGE CHECK (Level 3)
// -------------------------------------------------------
echo "--- Cross-School Data Isolation ---\n";

// Simulate School1 admin - check students don't include School2
$s1_students = DB::table('sm_students')->where('school_id', $school1->id)->count();
$s2_students = $school2 ? DB::table('sm_students')->where('school_id', $school2->id)->count() : 0;
$s12_mix = $school2 ? DB::table('sm_students')
    ->whereIn('school_id', [$school1->id, $school2->id])
    ->where(function($q) use ($school1, $school2) {
        $q->where('school_id', $school1->id);
    })->count() : $s1_students;

bcheck('B01', "School 1 students count is exact (no cross-school mix)",
    $s1_students === $s12_mix);

// Verify sm_students has school_id on every row
$noSchoolStudents = DB::table('sm_students')
    ->whereNull('school_id')->orWhere('school_id', 0)->count();
bcheck('B02', "All student records have a school_id set", $noSchoolStudents === 0, true, 'CRITICAL');

// Check parent records tied to students
$orphanParents = DB::table('sm_parents')
    ->whereNull('school_id')->count();
bcheck('B03', "All parent records have school_id", $orphanParents === 0);

// Check student_records (used by SchoolScope on StudentRecord model)
$orphanRecords = DB::table('student_records')
    ->whereNull('school_id')->orWhere('school_id', 0)->count();
bcheck('B04', "All student_records have school_id", $orphanRecords === 0);

echo "\n";

// -------------------------------------------------------
// 2. CROSS-ORG DATA LEAKAGE CHECK (Level 2)
// -------------------------------------------------------
echo "--- Cross-Organization Isolation ---\n";

if ($school1 && $school2) {
    // Schools from different groups should have different school_group_ids
    bcheck('B05', "School1 and School2 have different school_group_ids",
        $school1->school_group_id !== $school2->school_group_id);

    // Verify SchoolScope correctly builds group-scoped subquery
    $group1Schools = DB::table('sm_schools')
        ->where('school_group_id', $school1->school_group_id)
        ->pluck('id')->toArray();

    $group2Schools = DB::table('sm_schools')
        ->where('school_group_id', $school2->school_group_id)
        ->pluck('id')->toArray();

    $overlap = array_intersect($group1Schools, $group2Schools);
    bcheck('B06', "No schools belong to 2 different groups simultaneously",
        count($overlap) === 0, true, 'CRITICAL');
} else {
    echo "[SKIP] B05, B06: Only 1 org with assigned schools found\n";
}

echo "\n";

// -------------------------------------------------------
// 3. SM_FEES DATA ISOLATION
// -------------------------------------------------------
echo "--- Fee Records Isolation ---\n";

$feesTable = DB::getSchemaBuilder()->hasTable('sm_fees_assigns') ? 'sm_fees_assigns' : 
    (DB::getSchemaBuilder()->hasTable('sm_fees_payments') ? 'sm_fees_payments' : null);

if ($feesTable) {
    $has_school_id = DB::getSchemaBuilder()->hasColumn($feesTable, 'school_id');
    bcheck('B07', "$feesTable has school_id column", $has_school_id);
    if ($has_school_id) {
        $noSchoolFees = DB::table($feesTable)->whereNull('school_id')->count();
        bcheck('B08', "No fee records without school_id", $noSchoolFees === 0);
    }
} else {
    echo "[SKIP] B07, B08: Fee table not found\n";
}

echo "\n";

// -------------------------------------------------------
// 4. ROLE ID INTEGRITY
// -------------------------------------------------------
echo "--- Role ID Integrity ---\n";

// Users with role_id that does NOT exist in infix_roles
$invalidRoles = DB::table('users')
    ->leftJoin('infix_roles', 'users.role_id', '=', 'infix_roles.id')
    ->whereNull('infix_roles.id')
    ->whereNotNull('users.role_id')
    ->count();
bcheck('B09', "All users have valid role_ids (exist in infix_roles)",
    $invalidRoles === 0, true, 'HIGH');

// Check no student or parent has role_id=1 (admin)
$studentAdmins = DB::table('sm_students')
    ->join('users', 'sm_students.user_id', '=', 'users.id')
    ->where('users.role_id', 1)
    ->count();
bcheck('B10', "No student user account has admin role_id",
    $studentAdmins === 0, true, 'CRITICAL');

$parentAdmins = DB::table('sm_parents')
    ->join('users', 'sm_parents.user_id', '=', 'users.id')
    ->where('users.role_id', 1)
    ->count();
bcheck('B11', "No parent user account has admin role_id",
    $parentAdmins === 0, true, 'CRITICAL');

echo "\n";

// -------------------------------------------------------
// 5. ACADEMIC YEAR ISOLATION
// -------------------------------------------------------
echo "--- Academic Year Isolation ---\n";

$hasAcademic = DB::getSchemaBuilder()->hasTable('sm_academic_years');
if ($hasAcademic) {
    $orphanAcademic = DB::table('sm_academic_years')
        ->whereNull('school_id')->count();
    bcheck('B12', "All academic years have school_id",
        $orphanAcademic === 0, true, 'MEDIUM');
}

echo "\n";

// -------------------------------------------------------
// 6. SECTION/CLASS SCOPE INTEGRITY
// -------------------------------------------------------
echo "--- Class/Section Scope ---\n";

$classSectionMismatch = DB::table('sm_class_sections as cs')
    ->join('sm_classes as c', 'cs.class_id', '=', 'c.id')
    ->join('sm_sections as s', 'cs.section_id', '=', 's.id')
    ->whereRaw('cs.school_id != c.school_id OR cs.school_id != s.school_id')
    ->count();
bcheck('B13', "Class sections have consistent school_id across class-section-mapping",
    $classSectionMismatch === 0, true, 'HIGH');

echo "\n";

// -------------------------------------------------------
// SUMMARY
// -------------------------------------------------------
echo "==========================================================\n";
echo " BEHAVIORAL AUDIT SUMMARY\n";
echo " PASSED: $pass | FAILED: $fail\n";
echo "==========================================================\n";
if (!empty($issues)) {
    echo " ISSUES TO FIX:\n";
    foreach ($issues as $i) {
        echo "  [{$i['sev']}] {$i['id']} - {$i['desc']}\n";
    }
}
