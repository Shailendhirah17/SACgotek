<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Modules\RolePermission\Entities\InfixRole;
use App\SmBaseSetup;

$gender = SmBaseSetup::where('base_group_id', 1)->first(); // Usually 1 is Gender
if (!$gender) {
    $gender = SmBaseSetup::first();
}

$res = [
    'gender_id' => $gender->id ?? null,
    'gender_name' => $gender->base_setup_name ?? 'none'
];

echo json_encode($res, JSON_PRETTY_PRINT);
