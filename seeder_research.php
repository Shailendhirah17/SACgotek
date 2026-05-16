<?php
// seeder_research.php - Gathers all required metadata for bulk seeding

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\SmAcademicYear;
use App\SmHumanDepartment;
use App\SmDesignation;
use App\SmBaseSetup;
use Modules\RolePermission\Entities\InfixRole;

header('Content-Type: application/json');

$res = [
    'roles' => InfixRole::select('id', 'name')->get()->toArray(),
    'academic_year' => SmAcademicYear::where('active_status', 1)->first(),
    'departments' => SmHumanDepartment::select('id', 'name')->get()->toArray(),
    'designations' => SmDesignation::select('id', 'title')->get()->toArray(),
    'genders' => SmBaseSetup::where('base_group_id', 1)->select('id', 'base_setup_name')->get()->toArray(),
];

echo json_encode($res, JSON_PRETTY_PRINT);
