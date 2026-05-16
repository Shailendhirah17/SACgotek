<?php
// verify_seeding.php - Phase D: Verification

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\User;
use App\SmStudent;
use App\SmStaff;
use App\SmParent;
use App\SmClass;
use App\SmSection;
use Illuminate\Support\Facades\DB;

header('Content-Type: application/json');

$res = [
    'counts' => [
        'users' => User::count(),
        'students' => SmStudent::count(),
        'staff' => SmStaff::count(),
        'parents' => SmParent::count(),
        'classes' => SmClass::where('school_id', 1)->count(),
        'sections' => SmSection::where('school_id', 1)->count(),
    ],
    'sample_student' => SmStudent::where('admission_no', 'LIKE', 'ADM-%')->orderBy('id', 'DESC')->first(),
    'sample_staff' => SmStaff::where('staff_no', 'LIKE', 'STF-%')->orderBy('id', 'DESC')->first(),
];

echo json_encode($res, JSON_PRETTY_PRINT);
