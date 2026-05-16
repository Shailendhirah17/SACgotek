<?php
/**
 * Indian School Mock Data Seeder (V2 - Direct Table Architecture)
 * Populates Classes, Sections, Subjects, Students, and Staff with Indian names/structure.
 * Run: php artisan tinker indian_school_seeder.php
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "🇮🇳 Starting Indian School Mock Data Seeder...\n";

$schoolId = 1; // Primary School
$academicId = DB::table('sm_academic_years')->where('school_id', $schoolId)->where('active_status', 1)->value('id') ?? 1;

// 1. Sections
$sections = ['A', 'B', 'C'];
$sectionIds = [];
foreach ($sections as $section) {
    DB::table('sm_sections')->updateOrInsert(
        ['section_name' => $section, 'school_id' => $schoolId],
        ['active_status' => 1, 'created_at' => now(), 'academic_id' => $academicId]
    );
    $sectionIds[] = DB::table('sm_sections')->where('section_name', $section)->where('school_id', $schoolId)->value('id');
}
echo "✅ Sections A, B, C ready.\n";

// 2. Classes (Indian Standard)
$classes = [
    'Nursery', 'LKG', 'UKG',
    'Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5',
    'Class 6', 'Class 7', 'Class 8', 'Class 9', 'Class 10',
    'Class 11', 'Class 12'
];

foreach ($classes as $className) {
    DB::table('sm_classes')->updateOrInsert(
        ['class_name' => $className, 'school_id' => $schoolId],
        ['active_status' => 1, 'academic_id' => $academicId, 'created_at' => now()]
    );
    
    $classId = DB::table('sm_classes')->where('class_name', $className)->where('school_id', $schoolId)->value('id');
    
    // Link Sections to Classes
    foreach ($sectionIds as $sid) {
        if ($sid) {
            DB::table('sm_class_sections')->updateOrInsert(
                ['class_id' => $classId, 'section_id' => $sid, 'school_id' => $schoolId],
                ['academic_id' => $academicId]
            );
        }
    }
}
echo "✅ Classes (Nursery to 12) populated.\n";

// 3. Subjects (Indian Context)
$subjects = [
    ['Hindi', 'T'], ['Sanskrit', 'T'], ['English', 'T'], ['Mathematics', 'T'],
    ['EVS', 'T'], ['Social Science', 'T'], ['Science', 'T']
];

foreach ($subjects as $sub) {
    DB::table('sm_subjects')->updateOrInsert(
        ['subject_name' => $sub[0], 'school_id' => $schoolId],
        ['subject_type' => $sub[1], 'active_status' => 1, 'academic_id' => $academicId, 'created_at' => now()]
    );
}
echo "✅ Indian Subjects (Hindi, Sanskrit, etc.) added.\n";

// 4. Create a Generic Parent for all mock students
$parentName = "Indian Parent";
$parentEmail = "parent@indian.edu";
$parentUserId = DB::table('users')->where('email', $parentEmail)->value('id');
if (!$parentUserId) {
    $parentUserId = DB::table('users')->insertGetId([
        'full_name' => $parentName, 'email' => $parentEmail, 'password' => Hash::make('123456'),
        'role_id' => 3, 'school_id' => $schoolId, 'created_at' => now()
    ]);
}
$parentId = DB::table('sm_parents')->where('user_id', $parentUserId)->value('id');
if (!$parentId) {
    $parentId = DB::table('sm_parents')->insertGetId([
        'fathers_name' => $parentName, 'guardians_name' => $parentName,
        'user_id' => $parentUserId, 'school_id' => $schoolId, 'academic_id' => $academicId,
        'created_at' => now()
    ]);
}

// 5. Staff/Teachers (Indian Names)
$indianStaff = [
    'Rajesh Kumar', 'Sunita Sharma', 'Amit Patel', 'Kavita Devi', 'Sanjay Gupta',
    'Anjali Verma', 'Vijay Singh', 'Deepa Iyer'
];

foreach ($indianStaff as $name) {
    $email = str_replace(' ', '.', strtolower($name)) . rand(1,99) . "@staff.edu";
    $userId = DB::table('users')->insertGetId([
        'full_name' => $name, 'email' => $email, 'password' => Hash::make('123456'),
        'role_id' => 4, 'school_id' => $schoolId, 'created_at' => now()
    ]);
    
    DB::table('sm_staffs')->insert([
        'full_name' => $name, 'user_id' => $userId, 'role_id' => 4,
        'email' => $email, 'school_id' => $schoolId, 'staff_no' => rand(1000, 9999),
        'first_name' => explode(' ', $name)[0], 'last_name' => explode(' ', $name)[1] ?? '',
        'active_status' => 1, 'created_at' => now()
    ]);
}
echo "✅ Indian Teachers added.\n";

// 6. Students (Indian Names)
$indianStudents = [
    'Aarav Sharma', 'Priya Patel', 'Vihaan Gupta', 'Ananya Singh', 'Sai Kumar',
    'Riya Verma', 'Ishaan Jha', 'Avani Reddy', 'Arjun Malhotra', 'Diya Kapoor'
];

$classIds = DB::table('sm_classes')->where('school_id', $schoolId)->pluck('id')->toArray();

foreach ($indianStudents as $name) {
    $email = str_replace(' ', '.', strtolower($name)) . rand(10,99) . "@student.edu";
    $userId = DB::table('users')->insertGetId([
        'full_name' => $name, 'email' => $email, 'password' => Hash::make('123456'),
        'role_id' => 2, 'school_id' => $schoolId, 'created_at' => now()
    ]);
    
    DB::table('sm_students')->insert([
        'full_name' => $name, 'user_id' => $userId, 'role_id' => 2, 'parent_id' => $parentId,
        'email' => $email, 'school_id' => $schoolId, 'admission_no' => rand(202400, 202499),
        'first_name' => explode(' ', $name)[0], 'last_name' => explode(' ', $name)[1] ?? '',
        'class_id' => $classIds[array_rand($classIds)],
        'section_id' => $sectionIds[array_rand($sectionIds)],
        'academic_id' => $academicId,
        'active_status' => 1,
        'created_at' => now()
    ]);
}
echo "✅ Indian Students added.\n";

echo "\n✨ Seeder Complete! Data is now logical for an Indian school.\n";
