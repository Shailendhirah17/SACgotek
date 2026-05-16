<?php
// hierarchy_deploy.php  
// Creates all roles, adds school_group_id to users, seeds Organization Head user
// and hardens SchoolScope for 3-level hierarchy

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

echo "=== Hierarchy Implementation ===\n";

// -------------------------------------------------------
// STEP 1: Add school_group_id to users table if missing
// -------------------------------------------------------
if (!Schema::hasColumn('users', 'school_group_id')) {
    Schema::table('users', function ($table) {
        $table->unsignedBigInteger('school_group_id')->nullable()->after('school_id');
    });
    echo "✓ Added school_group_id column to users table\n";
} else {
    echo "✓ users.school_group_id already exists\n";
}

// -------------------------------------------------------
// STEP 2: Add admin_level to users table if missing
// -------------------------------------------------------
if (!Schema::hasColumn('users', 'admin_level')) {
    Schema::table('users', function ($table) {
        $table->tinyInteger('admin_level')->default(0)->after('school_group_id')
              ->comment('0=Regular,1=UltraSuperAdmin(Technosprint),2=OrgAdmin,3=SchoolAdmin');
    });
    echo "✓ Added admin_level column to users table\n";
} else {
    echo "✓ users.admin_level already exists\n";
}

// -------------------------------------------------------
// STEP 3: Identify existing school groups
// -------------------------------------------------------
$groups = DB::table('school_groups')->get();
echo "\n--- School Groups in Database ---\n";
foreach ($groups as $g) {
    echo "  ID: {$g->id} | Name: {$g->name} | Created: {$g->created_at}\n";
}

// -------------------------------------------------------
// STEP 4: Insert/ensure the 3 system roles exist
// -------------------------------------------------------
echo "\n--- Ensuring Hierarchy Roles ---\n";
$hierarchyRoles = [
    // Ultra Super Admin (Technosprint) — uses existing SaaS Admin Role ID 1
    // We don't recreate it, but mark admin_level = 1 on existing superadmin user
    
    // Organization Head role
    ['name' => 'Organization Head', 'type' => 'System', 'school_id' => 1, 'is_saas' => 1, 'expected_level' => 2],
    // School Admin role
    ['name' => 'School Admin', 'type' => 'System', 'school_id' => 1, 'is_saas' => 1, 'expected_level' => 3],
];

$orgHeadRoleId = null;
$schoolAdminRoleId = null;

foreach ($hierarchyRoles as $roleData) {
    $existing = DB::table('infix_roles')->where('name', $roleData['name'])->first();
    if (!$existing) {
        $id = DB::table('infix_roles')->insertGetId([
            'name' => $roleData['name'],
            'type' => $roleData['type'],
            'school_id' => $roleData['school_id'],
            'is_saas' => $roleData['is_saas'],
            'active_status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "✓ Created Role: {$roleData['name']} (ID: $id)\n";
        if ($roleData['expected_level'] === 2) $orgHeadRoleId = $id;
        if ($roleData['expected_level'] === 3) $schoolAdminRoleId = $id;
    } else {
        echo "✓ Role already exists: {$roleData['name']} (ID: {$existing->id})\n";
        if ($roleData['expected_level'] === 2) $orgHeadRoleId = $existing->id;
        if ($roleData['expected_level'] === 3) $schoolAdminRoleId = $existing->id;
    }
}

// -------------------------------------------------------
// STEP 5: Tag existing Super Admin (role_id=1) as Level 1
// -------------------------------------------------------
DB::table('users')
    ->where('role_id', 1)
    ->where('is_administrator', 'yes')
    ->update(['admin_level' => 1]);
echo "\n✓ Tagged existing Ultra Super Admin users with admin_level=1\n";

// -------------------------------------------------------
// STEP 6: Create a sample Organization Head for Group 1
// -------------------------------------------------------
$sampleEmail = 'org_head_1@technosprint.online';
$existingOrgHead = DB::table('users')->where('email', $sampleEmail)->first();
if (!$existingOrgHead && $orgHeadRoleId && $groups->count() > 0) {
    $firstGroupId = $groups->first()->id;
    DB::table('users')->insert([
        'full_name'       => 'Organization Head (Group ' . $firstGroupId . ')',
        'email'           => $sampleEmail,
        'username'        => $sampleEmail,
        'password'        => Hash::make('OrgHead@123'),
        'role_id'         => $orgHeadRoleId,
        'school_group_id' => $firstGroupId,
        'school_id'       => 1,
        'admin_level'     => 2,
        'active_status'   => 1,
        'is_administrator' => 'yes',
        'created_at'      => now(),
        'updated_at'      => now(),
    ]);
    echo "✓ Created Organization Head: $sampleEmail (Password: OrgHead@123)\n";
} elseif ($existingOrgHead) {
    echo "✓ Organization Head already exists: $sampleEmail\n";
} else {
    echo "⚠ Could not create Organization Head (no groups or role found)\n";
}

// -------------------------------------------------------
// STEP 7: Report final state
// -------------------------------------------------------
echo "\n=== Final Summary ===\n";
echo "Ultra Super Admin Role ID : 1  (is_administrator=yes, admin_level=1)\n";
echo "Organization Head Role ID : $orgHeadRoleId  (admin_level=2, assigned school_group_id)\n";
echo "School Admin Role ID      : $schoolAdminRoleId  (admin_level=3, assigned school_id)\n";
echo "\nAll existing Admin (role_id=1, is_administrator=yes) users -> admin_level=1\n";
echo "Sample Org Head: org_head_1@technosprint.online | Password: OrgHead@123\n";
echo "\nDone!\n";
