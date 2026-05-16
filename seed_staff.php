<?php
// seed_staff.php - Phase B: Staff Population (Updated)

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\User;
use App\SmStaff;
use App\SmHumanDepartment;
use App\SmDesignation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

$school_id = 1;
$role_id = 4; // Teacher
$password = Hash::make('Testing@123');

echo "Starting Phase B: Staff Seeding...\n";

DB::beginTransaction();
try {
    // 1. Ensure Department exists
    $dept = SmHumanDepartment::where('name', 'Academic')
        ->where('school_id', $school_id)
        ->first();
    if (!$dept) {
        $dept = new SmHumanDepartment();
        $dept->name = 'Academic';
        $dept->school_id = $school_id;
        $dept->active_status = 1;
        $dept->save();
        echo "Created Department: Academic\n";
    }

    // 2. Ensure Designation exists
    $desig = SmDesignation::where('title', 'Teacher')
        ->where('school_id', $school_id)
        ->first();
    if (!$desig) {
        $desig = new SmDesignation();
        $desig->title = 'Teacher';
        $desig->school_id = $school_id;
        $desig->active_status = 1;
        $desig->save();
        echo "Created Designation: Teacher\n";
    }

    // 3. Create 20 Staff
    for ($i = 1; $i <= 20; $i++) {
        $staff_no = "STF-" . str_pad($i, 3, "0", STR_PAD_LEFT);
        $email = "staff_$i@technosprint.online";
        $name = "Test Staff $i";

        // a. User
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = new User();
            $user->full_name = $name;
            $user->email = $email;
            $user->username = $email;
            $user->password = $password;
            $user->role_id = $role_id;
            $user->school_id = $school_id;
            $user->active_status = 1;
            $user->save();
        }

        // b. Staff Record
        $staff = SmStaff::where('staff_no', $staff_no)
            ->where('school_id', $school_id)
            ->first();
        if (!$staff) {
            $staff = new SmStaff();
            $staff->staff_no = $staff_no;
            $staff->full_name = $name;
            $staff->email = $email;
            $staff->user_id = $user->id;
            $staff->role_id = $role_id;
            $staff->department_id = $dept->id;
            $staff->designation_id = $desig->id;
            $staff->school_id = $school_id;
            $staff->active_status = 1;
            $staff->gender_id = null;
            $staff->date_of_joining = date('Y-m-d');
            $staff->save();
            echo "Created Staff: $staff_no\n";
        }
    }

    DB::commit();
    echo "Phase B Completed Successfully!\n";
} catch (\Exception $e) {
    DB::rollback();
    echo "Phase B Failed: " . $e->getMessage() . "\n";
}
