<?php
// data_discovery.php - Detailed Backend Investigation

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\SmStudent;
use App\SmAcademicYear;
use App\SmClass;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\DB;

header('Content-Type: application/json');

$res = [
    'timestamp' => date('Y-m-d H:i:s'),
    'active_academic_year' => SmAcademicYear::where('active_status', 1)->first(),
    'student_audit' => [],
    'raw_counts' => [
        'students' => DB::table('sm_students')->count(),
        'student_records' => DB::table('student_records')->count(),
        'classes' => DB::table('sm_classes')->count(),
    ]
];

// 1. Audit Recent Students
$res['student_audit'] = SmStudent::where('admission_no', 'LIKE', 'TEST%')
    ->select('id', 'full_name', 'admission_no', 'school_id', 'academic_id', 'class_id')
    ->get();

// 2. Check which classes have students
$res['class_distribution'] = DB::table('sm_students')
    ->select('class_id', DB::raw('count(*) as count'))
    ->groupBy('class_id')
    ->get();

echo json_encode($res, JSON_PRETTY_PRINT);
