<?php
/**
 * Global Indian School Mock Data Seeder (V4 - Multi-School)
 * Synchronizes Schools 1-7 with Indian mock data.
 * Run: php artisan tinker indian_school_global_push.php
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "🌎 Starting GLOBAL Multi-School Indian Data Push (Schools 1-10)...\n";

$schools = DB::table('sm_schools')->pluck('id')->toArray();
if (empty($schools)) $schools = [1];

foreach ($schools as $schoolId) {
    echo "🏗️ Populating School ID: $schoolId...\n";
    $academicId = DB::table('sm_academic_years')->where('school_id', $schoolId)->where('active_status', 1)->value('id') ?? 1;

    // 1. Sections
    $sections = ['A', 'B'];
    $sectionIds = [];
    foreach ($sections as $sn) {
        DB::table('sm_sections')->updateOrInsert(['section_name' => $sn, 'school_id' => $schoolId], ['active_status' => 1, 'academic_id' => $academicId]);
        $sectionIds[] = DB::table('sm_sections')->where('section_name', $sn)->where('school_id', $schoolId)->value('id');
    }

    // 2. Classes
    $classes = ['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5', 'Class 6', 'Class 7', 'Class 8', 'Class 9', 'Class 10'];
    $classIds = [];
    foreach ($classes as $cn) {
        DB::table('sm_classes')->updateOrInsert(['class_name' => $cn, 'school_id' => $schoolId], ['active_status' => 1, 'academic_id' => $academicId]);
        $cid = DB::table('sm_classes')->where('class_name', $cn)->where('school_id', $schoolId)->value('id');
        $classIds[] = $cid;
        foreach ($sectionIds as $sid) {
            DB::table('sm_class_sections')->updateOrInsert(['class_id' => $cid, 'section_id' => $sid, 'school_id' => $schoolId], ['academic_id' => $academicId]);
        }
    }

    // 3. Subjects
    $subjects = ['Hindi', 'English', 'Mathematics', 'Science', 'Social Science'];
    foreach ($subjects as $s) {
        DB::table('sm_subjects')->updateOrInsert(['subject_name' => $s, 'school_id' => $schoolId], ['subject_type' => 'T', 'active_status' => 1, 'academic_id' => $academicId]);
    }

    // 4. Staff/Teachers
    $staffNames = ['Amit Kumar', 'Sunita Devi', 'Rajesh Sharma', 'Kavita Singh'];
    foreach ($staffNames as $sn) {
        $email = strtolower(str_replace(' ', '.', $sn)) . "s$schoolId@staff.edu";
        if (!DB::table('users')->where('email', $email)->exists()) {
            $uid = DB::table('users')->insertGetId(['full_name' => $sn, 'email' => $email, 'password' => Hash::make('123456'), 'role_id' => 4, 'school_id' => $schoolId]);
            DB::table('sm_staffs')->insert([
                'full_name' => $sn, 'user_id' => $uid, 'role_id' => 4, 'email' => $email, 'school_id' => $schoolId,
                'staff_no' => rand(1000, 9999), 'first_name' => explode(' ', $sn)[0], 'last_name' => explode(' ', $sn)[1],
                'active_status' => 1, 'created_at' => now()
            ]);
        }
    }

    // 5. Students (20 per school)
    $firstNames = ['Aarav', 'Vihaan', 'Aria', 'Ananya', 'Aarush', 'Shanaya', 'Ishani', 'Dhruv'];
    $lastNames = ['Sharma', 'Patel', 'Gupta', 'Singh'];
    $parentEmail = "parent.s$schoolId@edu.com";
    if (!DB::table('users')->where('email', $parentEmail)->exists()) {
        $puid = DB::table('users')->insertGetId(['full_name' => 'Parent S'.$schoolId, 'email' => $parentEmail, 'password' => Hash::make('123456'), 'role_id' => 3, 'school_id' => $schoolId]);
        $pid = DB::table('sm_parents')->insertGetId(['fathers_name' => 'Amit Sharma', 'guardians_name' => 'Amit Sharma', 'user_id' => $puid, 'school_id' => $schoolId, 'academic_id' => $academicId]);
    } else {
        $pid = DB::table('sm_parents')->where('school_id', $schoolId)->value('id');
    }

    for ($i = 0; $i < 20; $i++) {
        $fn = $firstNames[array_rand($firstNames)];
        $ln = $lastNames[array_rand($lastNames)];
        $fullName = "$fn $ln";
        $email = strtolower($fn . "." . $ln) . "s$schoolId$i@student.edu";
        
        $suid = DB::table('users')->insertGetId(['full_name' => $fullName, 'email' => $email, 'password' => Hash::make('123456'), 'role_id' => 2, 'school_id' => $schoolId]);
        DB::table('sm_students')->insert([
            'full_name' => $fullName, 'user_id' => $suid, 'role_id' => 2, 'parent_id' => $pid,
            'email' => $email, 'school_id' => $schoolId, 'admission_no' => (rand(3000000, 3999999)),
            'first_name' => $fn, 'last_name' => $ln, 'active_status' => 1,
            'class_id' => $classIds[array_rand($classIds)], 'section_id' => $sectionIds[array_rand($sectionIds)],
            'academic_id' => $academicId, 'created_at' => now()
        ]);
    }
}
echo "✨ GLOBAL PUSH COMPLETE! All schools synchronized with Indian mock data.\n";
