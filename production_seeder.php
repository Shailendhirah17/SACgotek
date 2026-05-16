<?php
// production_seeder.php - Injecting test data for functional validation

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\SmStudent;
use App\Models\StudentRecord;
use App\Models\SmTransferCertificate;
use App\Models\SmVaccinationRecord;
use App\Models\SmMedicalRecord;
use App\Models\SmBookBank;
use Illuminate\Support\Facades\DB;

$school_id = 1;
$academic_id = 1;
$class_id = 1; // Updated to match likely local class ID
$section_id = 1;

echo "Starting Production Seeding...\n";

DB::beginTransaction();
try {
    for ($i = 1; $i <= 5; $i++) {
        $admission_no = "TEST" . str_pad($i, 4, "0", STR_PAD_LEFT);
        
        // 1. Create Student Detail
        $student = new SmStudent();
        $student->first_name = "Test";
        $student->last_name = "Student $i";
        $student->full_name = "Test Student $i";
        $student->admission_no = $admission_no;
        $student->school_id = $school_id;
        $student->academic_id = $academic_id;
        $student->class_id = $class_id;
        $student->section_id = $section_id;
        $student->age = 15;
        $student->gender_id = 1;
        $student->active_status = 1;
        $student->save();

        // 2. Create Student Record
        $record = new StudentRecord();
        $record->student_id = $student->id;
        $record->class_id = $class_id;
        $record->section_id = $section_id;
        $record->school_id = $school_id;
        $record->academic_id = $academic_id;
        $record->is_default = 1;
        $record->save();

        echo "Created Student: $admission_no (ID: {$student->id})\n";

        // 3. Seed TC
        if ($i == 1) {
            SmTransferCertificate::create([
                'student_id' => $student->id,
                'tc_no' => 'TC-2026-001',
                'reason' => 'Relocation',
                'date' => date('Y-m-d'),
                'school_id' => $school_id,
                'academic_id' => $academic_id,
            ]);
            echo "  Seeded TC for Student 1\n";
        }

        // 4. Seed Medical Record
        if ($i == 2) {
            SmMedicalRecord::create([
                'student_id' => $student->id,
                'medical_history' => 'Healthy (Test)',
                'blood_group' => 'O+',
                'weight' => 45.5,
                'height' => 150.0,
                'school_id' => $school_id,
                'academic_id' => $academic_id,
            ]);
            echo "  Seeded Medical Record for Student 2\n";
        }

        // 5. Seed Vaccination Record
        if ($i == 3) {
            SmVaccinationRecord::create([
                'student_id' => $student->id,
                'vaccine_name' => 'COVID-19 (Demo)',
                'date_given' => date('Y-m-d'),
                'dose' => '1st Dose',
                'administered_by' => 'Dr. Smith',
                'school_id' => $school_id,
                'academic_id' => $academic_id,
            ]);
            echo "  Seeded Vaccination Record for Student 3\n";
        }
    }

    DB::commit();
    echo "Production Seeding Completed Successfully!\n";
} catch (\Exception $e) {
    DB::rollback();
    echo "Seeding Failed: " . $e->getMessage() . "\n";
}
