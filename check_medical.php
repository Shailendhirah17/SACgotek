<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== MEDICAL & VACCINATION CHECK ===\n";

if (\Illuminate\Support\Facades\Schema::hasTable('sm_vaccination_records')) {
    $vac_count = DB::table('sm_vaccination_records')->count();
    $by_school = DB::table('sm_vaccination_records')
                   ->select('school_id', DB::raw('count(*) as total'))
                   ->groupBy('school_id')
                   ->get();
    echo "Vaccination records total: $vac_count\n";
    foreach ($by_school as $b) {
        echo "  - School {$b->school_id}: {$b->total}\n";
    }
} else {
    echo "sm_vaccination_records table does not exist.\n";
}

if (\Illuminate\Support\Facades\Schema::hasTable('sm_medical_records')) {
    $med_count = DB::table('sm_medical_records')->count();
    $by_school_med = DB::table('sm_medical_records')
                      ->select('school_id', DB::raw('count(*) as total'))
                      ->groupBy('school_id')
                      ->get();
    echo "\nMedical records total: $med_count\n";
    foreach ($by_school_med as $b) {
        echo "  - School {$b->school_id}: {$b->total}\n";
    }
} else {
    echo "sm_medical_records table does not exist.\n";
}

$students = DB::table('sm_students')->where('school_id', 1)->count();
echo "\nTotal Students in School 1: $students\n";
