<?php
// seed_students.php - Phase C: Student & Parent Population (Robust)

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\User;
use App\SmStudent;
use App\SmParent;
use App\SmClass;
use App\SmSection;
use App\SmClassSection;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

$school_id = 1;
$academic_id = 1;
$student_role = 2;
$parent_role = 3;
$password = Hash::make('Testing@123');

echo "Starting Phase C: Student & Parent Seeding...\n";

// Bypassing global scopes for absolute visibility
$classes = SmClass::withoutGlobalScopes()
    ->where('class_name', 'LIKE', 'Class %')
    ->where('school_id', $school_id)
    ->get();

$total_created = 0;

foreach ($classes as $class) {
    echo "Seeding {$class->class_name} (ID: {$class->id})...\n";
    
    // Find sections for this class via sm_class_sections
    $classSections = SmClassSection::where('class_id', $class->id)
        ->where('school_id', $school_id)
        ->get();
        
    foreach ($classSections as $cs) {
        $section = SmSection::find($cs->section_id);
        if (!$section) continue;
        
        echo "  Section {$section->section_name} (ID: {$section->id}):\n";
        
        DB::beginTransaction();
        try {
            for ($i = 1; $i <= 10; $i++) {
                // Unique email per student/parent
                $unique_suffix = $class->id . "_" . $section->id . "_" . $i . "_" . time(); 
                $s_email = "student_$unique_suffix@technosprint.online";
                $p_email = "parent_$unique_suffix@technosprint.online";
                $admission_no = "ADM-" . $class->id . str_pad($section->id, 2, "0", STR_PAD_LEFT) . str_pad($i, 2, "0", STR_PAD_LEFT);

                // 1. Create Parent User
                $p_user = new User();
                $p_user->full_name = "Parent of Student $admission_no";
                $p_user->email = $p_email;
                $p_user->username = $p_email;
                $p_user->password = $password;
                $p_user->role_id = $parent_role;
                $p_user->school_id = $school_id;
                $p_user->active_status = 1;
                $p_user->save();

                // 2. Create Parent Record
                $parent = new SmParent();
                $parent->user_id = $p_user->id;
                $parent->fathers_name = "Father of $admission_no";
                $parent->guardians_name = "Guardian of $admission_no";
                $parent->guardians_relation = "Father";
                $parent->guardians_email = $p_email;
                $parent->school_id = $school_id;
                $parent->academic_id = $academic_id;
                $parent->active_status = 1;
                $parent->save();

                // 3. Create Student User
                $s_user = new User();
                $s_user->full_name = "Student $admission_no";
                $s_user->email = $s_email;
                $s_user->username = $s_email;
                $s_user->password = $password;
                $s_user->role_id = $student_role;
                $s_user->school_id = $school_id;
                $s_user->active_status = 1;
                $s_user->save();

                // 4. Create Student Record
                $student = new SmStudent();
                $student->user_id = $s_user->id;
                $student->parent_id = $parent->id;
                $student->full_name = "Student $admission_no";
                $student->admission_no = $admission_no;
                $student->school_id = $school_id;
                $student->academic_id = $academic_id;
                $student->class_id = $class->id;
                $student->section_id = $section->id;
                $student->gender_id = null;
                $student->active_status = 1;
                $student->save();

                // 5. Create Student Promotion Record
                $record = new StudentRecord();
                $record->student_id = $student->id;
                $record->class_id = $class->id;
                $record->section_id = $section->id;
                $record->school_id = $school_id;
                $record->academic_id = $academic_id;
                $record->is_default = 1;
                $record->save();

                $total_created++;
            }
            DB::commit();
            echo "    -> Committed 10 students\n";
        } catch (\Exception $e) {
            DB::rollback();
            echo "    !! Error: " . $e->getMessage() . "\n";
        }
    }
}

echo "Phase C Completed! Total Created: $total_created students and parents.\n";
