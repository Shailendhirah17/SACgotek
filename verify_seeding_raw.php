<?php
// verify_seeding_raw.php - Phase D: Verification (RAW SQL)

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

header('Content-Type: application/json');

$res = [
    'users' => DB::table('users')->count(),
    'students' => DB::table('sm_students')->count(),
    'staff' => DB::table('sm_staffs')->count(),
    'parents' => DB::table('sm_parents')->count(),
    'classes' => DB::table('sm_classes')->count(),
    'sections' => DB::table('sm_sections')->count(),
    'recent_staff' => DB::table('sm_staffs')->where('staff_no', 'LIKE', 'STF-%')->get(),
    'recent_students' => DB::table('sm_students')->where('admission_no', 'LIKE', 'ADM-%')->limit(5)->get(),
];

echo json_encode($res, JSON_PRETTY_PRINT);
