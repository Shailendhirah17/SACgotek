<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;

$school_id = 1;
$role_id = 4;
$dept_id = DB::table('sm_human_departments')->where('school_id', $school_id)->value('id');
$desig_id = DB::table('sm_designations')->where('school_id', $school_id)->value('id');

echo "Brute force staff record creation...\n";

for ($i = 1; $i <= 20; $i++) {
    $email = "staff_$i@technosprint.online";
    $staff_no = "STF-" . str_pad($i, 3, "0", STR_PAD_LEFT);
    
    $user_id = DB::table('users')->where('email', $email)->value('id');
    if (!$user_id) {
        echo "User $email not found, skipping staff record.\n";
        continue;
    }

    $exists = DB::table('sm_staffs')->where('user_id', $user_id)->first();
    if ($exists) {
        echo "Staff Record for User ID $user_id already exists (ID: {$exists->id}).\n";
        continue;
    }

    try {
        DB::table('sm_staffs')->insert([
            'staff_no' => $staff_no,
            'full_name' => "Test Staff $i",
            'email' => $email,
            'user_id' => $user_id,
            'role_id' => $role_id,
            'department_id' => $dept_id,
            'designation_id' => $desig_id,
            'school_id' => $school_id,
            'active_status' => 1,
            'gender_id' => null,
            'date_of_joining' => date('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "Created Staff Record for $email\n";
    } catch (\Exception $e) {
        echo "Failed for $email: " . $e->getMessage() . "\n";
    }
}
