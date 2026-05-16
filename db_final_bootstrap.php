<?php
/**
 * InfixEdu Final Bootstrap & Mock Data Generator
 * Force Database: u841409365_erpv0
 */

use App\SmClass;
use App\SmSection;
use App\SmSubject;
use App\SmStudent;
use App\SmFeesMaster;
use App\SmFeesAssign;
use App\SmFeesPayment;
use App\SmExamType;
use App\SmResultStore;
use App\SmStudentAttendance;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

echo "🚀 Starting FORCED Bootstrap for u841409365_erpv0...\n";

// Force database connection
Config::set('database.connections.mysql.database', 'u841409365_erpv0');
Config::set('database.connections.mysql.username', 'u841409365_erpv0');
Config::set('database.connections.mysql.password', 'Eash@2005');
DB::purge('mysql');
DB::reconnect('mysql');

echo "📍 Connected to: " . DB::getDatabaseName() . "\n";

$schoolId = 1;
$academicId = 1;

// Ensure Academic Year exists
DB::table('sm_academic_years')->updateOrInsert(
    ['id' => 1, 'school_id' => $schoolId],
    ['year' => '2024', 'title' => '2024 Academic Year', 'active_status' => 1]
);

// 1. Structural Bootstrap
$classes = ['Class 10', 'Class 11', 'Class 12'];
$sections = ['Section A', 'Section B'];
$subjects = ['Mathematics', 'Science', 'English', 'History', 'Arts'];

$sectionIds = [];
foreach ($sections as $secName) {
    $sec = SmSection::firstOrCreate(['section_name' => $secName, 'school_id' => $schoolId], ['academic_id' => $academicId]);
    $sectionIds[] = $sec->id;
}

$classIds = [];
foreach ($classes as $className) {
    $cls = SmClass::firstOrCreate(['class_name' => $className, 'school_id' => $schoolId], ['academic_id' => $academicId]);
    $classIds[] = $cls->id;
    DB::table('sm_class_sections')->updateOrInsert(['class_id' => $cls->id, 'section_id' => $sectionIds[0], 'school_id' => $schoolId], ['academic_id' => $academicId]);
}

$subjectIds = [];
foreach ($subjects as $subName) {
    $sub = SmSubject::firstOrCreate(['subject_name' => $subName, 'school_id' => $schoolId], ['subject_code' => strtoupper(substr($subName, 0, 3)), 'subject_type' => 'T', 'academic_id' => $academicId]);
    $subjectIds[] = $sub->id;
}

$feesGroup = DB::table('sm_fees_groups')->where('school_id', $schoolId)->first();
if (!$feesGroup) {
    $groupId = DB::table('sm_fees_groups')->insertGetId(['name' => 'General Fees', 'school_id' => $schoolId, 'academic_id' => $academicId]);
} else {
    $groupId = $feesGroup->id;
}

$feesType = DB::table('sm_fees_types')->where('school_id', $schoolId)->first();
if (!$feesType) {
    $typeId = DB::table('sm_fees_types')->insertGetId(['name' => 'Tuition Fee', 'school_id' => $schoolId, 'academic_id' => $academicId]);
} else {
    $typeId = $feesType->id;
}

$master = SmFeesMaster::firstOrCreate(['fees_group_id' => $groupId, 'fees_type_id' => $typeId, 'school_id' => $schoolId], ['amount' => 1200, 'academic_id' => $academicId]);
$exam = SmExamType::firstOrCreate(['title' => 'First Term', 'school_id' => $schoolId], ['academic_id' => $academicId]);

// 2. Enroll 40 Students (to be safe)
for ($i = 1; $i <= 40; $i++) {
    $classIdx = floor(($i-1) / 14); // Distribute across classes
    if ($classIdx > 2) $classIdx = 2;
    $clsId = $classIds[$classIdx];
    $secId = $sectionIds[0];

    // Create User if missing
    $email = "demo_student$i@infixedu.com";
    $user = DB::table('users')->where('email', $email)->first();
    if (!$user) {
        $userId = DB::table('users')->insertGetId([
            'full_name' => "Student $i",
            'email' => $email,
            'username' => "student_$i",
            'password' => bcrypt('123456'),
            'role_id' => 2,
            'school_id' => $schoolId,
            'active_status' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    } else {
        $userId = $user->id;
    }

    $student = SmStudent::where('user_id', $userId)->first();
    if (!$student) {
        $student = new SmStudent();
        $student->user_id = $userId;
        $student->admission_no = 2000 + $i;
        $student->first_name = "Student";
        $student->last_name = "$i";
        $student->full_name = "Student $i";
        $student->gender_id = 1;
        $student->school_id = $schoolId;
        $student->academic_id = $academicId;
        $student->active_status = 1;
        $student->save();
    }

    $record = StudentRecord::where('student_id', $student->id)->where('school_id', $schoolId)->first();
    if (!$record) {
        $record = new StudentRecord();
        $record->student_id = $student->id;
        $record->class_id = $clsId;
        $record->section_id = $secId;
        $record->school_id = $schoolId;
        $record->academic_id = $academicId;
        $record->is_default = 1;
        $record->save();
    }

    // Fees
    SmFeesAssign::firstOrCreate([
        'student_id' => $student->id,
        'fees_master_id' => $master->id,
        'school_id' => $schoolId
    ], [
        'academic_id' => $academicId,
        'record_id' => $record->id,
        'student_record_id' => $record->id
    ]);

    // Marks
    foreach ($subjectIds as $subId) {
        SmResultStore::firstOrCreate([
            'student_id' => $student->id,
            'exam_type_id' => $exam->id,
            'subject_id' => $subId,
            'school_id' => $schoolId
        ], [
            'class_id' => $clsId,
            'section_id' => $secId,
            'total_marks' => rand(75, 99),
            'total_gpa_point' => 5.0,
            'total_gpa_grade' => 'A+',
            'academic_id' => $academicId
        ]);
    }

    // Attendance
    for ($d = 1; $d <= 15; $d++) {
        $date = date('Y-m-d', strtotime("-$d days"));
        SmStudentAttendance::firstOrCreate([
            'student_id' => $student->id,
            'attendance_date' => $date,
            'school_id' => $schoolId
        ], [
            'attendance_type' => 'P',
            'academic_id' => $academicId,
            'record_id' => $record->id,
            'student_record_id' => $record->id,
            'class_id' => $clsId,
            'section_id' => $secId
        ]);
    }
}

echo "✅ Success! Added 40 Students and all associated data to " . DB::getDatabaseName() . "\n";
