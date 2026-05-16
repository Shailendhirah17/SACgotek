<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\SmSchool;

$schools = SmSchool::all();
echo "Total Schools: " . $schools->count() . "\n";
foreach ($schools as $school) {
    echo "ID: {$school->id}, Name: {$school->school_name}, Email: '{$school->email}', Active: {$school->active_status}\n";
}
