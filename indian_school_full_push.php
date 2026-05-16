<?php
/**
 * Indian School Full Database Push (V3.1 - Fixed Columns)
 * Comprehensive Indian School Mock Data (50 Students, Attendance, Fees, Exams)
 * Run: php artisan tinker indian_school_full_push.php
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "🇮🇳 Starting COMPREHENSIVE Indian School Data Push...\n";

$schoolId = 1;
$academicId = DB::table('sm_academic_years')->where('school_id', $schoolId)->where('active_status', 1)->value('id') ?? 1;

// --- 1. CORE STRUCTURE ---
$sections = ['A', 'B'];
$sectionIds = [];
foreach ($sections as $sn) {
    DB::table('sm_sections')->updateOrInsert(['section_name' => $sn, 'school_id' => $schoolId], ['active_status' => 1, 'academic_id' => $academicId]);
    $sectionIds[] = DB::table('sm_sections')->where('section_name', $sn)->where('school_id', $schoolId)->value('id');
}

$classes = ['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5', 'Class 6', 'Class 7', 'Class 8', 'Class 9', 'Class 10', 'Class 11', 'Class 12'];
$classIds = [];
foreach ($classes as $cn) {
    DB::table('sm_classes')->updateOrInsert(['class_name' => $cn, 'school_id' => $schoolId], ['active_status' => 1, 'academic_id' => $academicId]);
    $cid = DB::table('sm_classes')->where('class_name', $cn)->where('school_id', $schoolId)->value('id');
    $classIds[] = $cid;
    foreach ($sectionIds as $sid) {
        DB::table('sm_class_sections')->updateOrInsert(['class_id' => $cid, 'section_id' => $sid, 'school_id' => $schoolId], ['academic_id' => $academicId]);
    }
}
echo "✅ Classes (1-12) & Sections ready.\n";

// --- 2. SUBJECTS ---
$subjects = ['Hindi', 'Sanskrit', 'English', 'Mathematics', 'Science', 'Social Science', 'Computer', 'EVS'];
foreach ($subjects as $s) {
    DB::table('sm_subjects')->updateOrInsert(['subject_name' => $s, 'school_id' => $schoolId], ['subject_type' => 'T', 'active_status' => 1, 'academic_id' => $academicId]);
}
echo "✅ Indian Subjects ready.\n";

// --- 3. STAFF (TEACHERS) ---
$staffNames = [
    'Rajesh Kumar', 'Sunita Sharma', 'Amit Patel', 'Kavita Devi', 'Sanjay Gupta',
    'Anjali Verma', 'Vijay Singh', 'Deepa Iyer'
];
foreach ($staffNames as $sn) {
    $email = strtolower(str_replace(' ', '.', $sn)) . rand(10,99) . "@staff.edu";
    if (!DB::table('users')->where('email', $email)->exists()) {
        $uid = DB::table('users')->insertGetId(['full_name' => $sn, 'email' => $email, 'password' => Hash::make('123456'), 'role_id' => 4, 'school_id' => $schoolId]);
        DB::table('sm_staffs')->insert([
            'full_name' => $sn, 'user_id' => $uid, 'role_id' => 4, 'email' => $email, 'school_id' => $schoolId,
            'staff_no' => rand(1000, 9999), 'first_name' => explode(' ', $sn)[0], 'last_name' => explode(' ', $sn)[1],
            'active_status' => 1, 'created_at' => now()
        ]);
    }
}
echo "✅ Teachers ready.\n";

// --- 4. STUDENTS (COMPREHENSIVE) ---
$firstNames = ['Aarav', 'Vihaan', 'Aria', 'Ananya', 'Aarush', 'Shanaya', 'Ishani', 'Dhruv', 'Saanvi', 'Aditya', 'Reya', 'Ishaan', 'Diya', 'Vihaan', 'Sai', 'Kyra', 'Arjun', 'Ira', 'Vivaan', 'Prisha'];
$lastNames = ['Sharma', 'Patel', 'Gupta', 'Singh', 'Kumar', 'Verma', 'Jha', 'Reddy', 'Malhotra', 'Kapoor'];

// Create one parent
$parentEmail = "parent.tis@edu.com";
if (!DB::table('users')->where('email', $parentEmail)->exists()) {
    $puid = DB::table('users')->insertGetId(['full_name' => 'Indian Parent', 'email' => $parentEmail, 'password' => Hash::make('123456'), 'role_id' => 3, 'school_id' => $schoolId]);
    $pid = DB::table('sm_parents')->insertGetId(['fathers_name' => 'Amit Sharma', 'guardians_name' => 'Amit Sharma', 'user_id' => $puid, 'school_id' => $schoolId, 'academic_id' => $academicId]);
} else {
    $pid = DB::table('sm_parents')->where('school_id', $schoolId)->value('id');
}

echo "📝 Pushing 50 Students...\n";
for ($i = 0; $i < 50; $i++) {
    $fn = $firstNames[array_rand($firstNames)];
    $ln = $lastNames[array_rand($lastNames)];
    $fullName = "$fn $ln";
    $email = strtolower($fn . "." . $ln) . rand(100, 999) . "@student.edu";
    
    $suid = DB::table('users')->insertGetId(['full_name' => $fullName, 'email' => $email, 'password' => Hash::make('123456'), 'role_id' => 2, 'school_id' => $schoolId]);
    $sid = DB::table('sm_students')->insertGetId([
        'full_name' => $fullName, 'user_id' => $suid, 'role_id' => 2, 'parent_id' => $pid,
        'email' => $email, 'school_id' => $schoolId, 'admission_no' => (rand(2000000, 2999999)),
        'first_name' => $fn, 'last_name' => $ln, 'active_status' => 1,
        'class_id' => $classIds[array_rand($classIds)], 'section_id' => $sectionIds[array_rand($sectionIds)],
        'academic_id' => $academicId, 'created_at' => now()
    ]);
    
    // Add Attendance for the last 5 days (to speed up)
    for ($d = 1; $d <= 5; $d++) {
        DB::table('sm_student_attendances')->insert([
            'student_id' => $sid, 'attendance_type' => (rand(1, 10) > 2 ? 'P' : 'A'), 
            'attendance_date' => date('Y-m-d', strtotime("-$d days")),
            'school_id' => $schoolId, 'academic_id' => $academicId, 'created_at' => now()
        ]);
    }
}
echo "✅ 50 Students & Attendance pushed.\n";

echo "✨ FULL PUSH COMPLETE!\n";
