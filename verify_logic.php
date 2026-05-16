<?php
// verify_logic.php - Comprehensive Backend Logic Verification

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\SmStudent;
use App\SmClass;
use App\Models\StudentRecord;

header('Content-Type: application/json');

$report = [
    'timestamp' => date('Y-m-d H:i:s'),
    'core_integrity' => [],
    'custom_modules' => [],
    'errors' => []
];

// 1. Check Core Integrity
try {
    $studentCount = SmStudent::where('active_status', 1)->count();
    $classCount = SmClass::where('active_status', 1)->count();
    $report['core_integrity']['student_count'] = $studentCount;
    $report['core_integrity']['class_count'] = $classCount;

    if ($studentCount > 0) {
        $student = SmStudent::first();
        $report['core_integrity']['sample_student'] = [
            'id' => $student->id,
            'full_name' => $student->full_name,
            'admission_no' => $student->admission_no,
            'has_class' => $student->class_id > 0
        ];
    }
} catch (\Exception $e) {
    $report['errors'][] = "Core Integrity Error: " . $e->getMessage();
}

// 2. Check Custom Module Data Counts
$custom_tables = [
    'sm_transfer_certificates' => 'TC Records',
    'sm_medical_records' => 'Medical Records',
    'sm_book_banks' => 'Book Bank',
    'sm_hostels' => 'Hostels',
    'sm_vendors' => 'Vendors'
];

foreach ($custom_tables as $table => $label) {
    try {
        if (Schema::hasTable($table)) {
            $count = DB::table($table)->count();
            $report['custom_modules'][$label] = [
                'table' => $table,
                'count' => $count,
                'status' => 'OK'
            ];
        } else {
            $report['custom_modules'][$label] = ['status' => 'MISSING'];
        }
    } catch (\Exception $e) {
        $report['errors'][] = "$label Error: " . $e->getMessage();
    }
}

// 3. Dependency Check
try {
    $controller = new \App\Http\Controllers\Admin\SchoolExtensionController();
    $report['controllers']['SchoolExtensionController'] = 'Instantiable';
} catch (\Exception $e) {
    $report['errors'][] = "Controller Dependency Error: " . $e->getMessage();
}

echo json_encode($report, JSON_PRETTY_PRINT);
