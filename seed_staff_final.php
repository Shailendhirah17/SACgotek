<?php
// seed_staff_final.php - Phase B: Staff Population (Direct SQL)

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

$school_id = 1;
$role_id = 4; // Teacher
$password = Hash::make('Testing@123');

echo "Starting Final Staff Seeding...\n";

// 1. Ensure Dept/Desig
$dept_id = DB::table('sm_human_departments')->where('name', 'Academic')->where('school_id', $school_id)->value('id');
if (!$dept_id) {
    $dept_id = DB::table('sm_human_departments')->insertGetId([
        'name' => 'Academic', 'school_id' => $school_id, 'active_status' => 1, 'created_at' => now(), 'updated_at' => now()
    ]);
}
$desig_id = DB::table('sm_designations')->where('title', 'Teacher')->where('school_id', $school_id)->value('id');
if (!$desig_id) {
    $desig_id = DB::table('sm_designations')->insertGetId([
        'title' => 'Teacher', 'school_id' => $school_id, 'active_status' => 1, 'created_at' => now(), 'updated_at' => now()
    ]);
}

for ($i = 1; $i <= 20; $i++) {
    $staff_no = "STF-" . str_pad($i, 3, "0", STR_PAD_LEFT);
    $email = "staff_$i@technosprint.online";
    
    // a. Ensure User exists and is linked
    $user = User::where('email', $email)->first();
    if (!$user) {
        $uid = DB::table('users')->insertGetId([
            'full_name' => "Test Staff $i",
            'email' => $email,
            'username' => $email,
            'password' => $password,
            'role_id' => $role_id,
            'school_id' => $school_id,
            'active_status' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    } else {
        $uid = $user->id;
    }

    // b. Ensure Staff Record exists
    $exists = DB::table('sm_staffs')->where('staff_no', $staff_no)->where('school_id', $school_id)->first();
    if (!$exists) {
        DB::table('sm_staffs')->insert([
            'staff_no' => $staff_no,
            'full_name' => "Test Staff $i",
            'email' => $email,
            'user_id' => $uid,
            'role_id' => $role_id,
            'department_id' => $dept_id,
            'designation_id' => $desig_id,
            'school_id' => $school_id,
            'active_status' => 1,
            'date_of_joining' => date('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "Inserted Staff: $staff_no\n";
    } else {
        echo "Staff $staff_no already exists\n";
    }
}

echo "Final Staff Seeding Completed!\n";
