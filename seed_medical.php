<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\SmVaccinationRecord;
use App\Models\SmMedicalRecord;
use App\SmStudent;

echo "=== Seeding Sample Medical & Vaccination Data ===\n";

$school_id = 1;

// Get a random student from school 1
$student = SmStudent::where('school_id', $school_id)->first();

if (!$student) {
    die("No active students found in school ID 1 to attach records to.\n");
}

// 1. Seed Vaccination Record
$vaccine = SmVaccinationRecord::create([
    'student_id' => $student->id,
    'vaccine_name' => 'COVID-19 Pfizer (Demo)',
    'date_given' => '2026-01-15',
    'dose' => '2nd Dose',
    'administered_by' => 'Dr. Rajesh Kumar',
    'remarks' => 'Patient showed no side effects.',
    'school_id' => $school_id,
    'academic_id' => 1
]);

echo "Created Dummy Vaccination Record ID: {$vaccine->id}\n";

// 2. Seed Medical Record
$medical = SmMedicalRecord::create([
    'student_id' => $student->id,
    'blood_group' => 'O+',
    'weight' => '45',
    'height' => '152',
    'allergies' => 'Peanuts, Dust',
    'medical_history' => 'Asthma diagnosed in 2020. Currently stable.',
    'current_medications' => 'Inhaler as needed.',
    'school_id' => $school_id,
    'academic_id' => 1
]);

echo "Created Dummy Medical Record ID: {$medical->id}\n";

echo "\nDone! The tables should no longer be empty for School Admin.\n";

