<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;

$school_id = 1;
echo "DEPARTMENTS:\n";
echo json_encode(DB::table('sm_human_departments')->where('school_id', $school_id)->get(), JSON_PRETTY_PRINT);
echo "\nDESIGNATIONS:\n";
echo json_encode(DB::table('sm_designations')->where('school_id', $school_id)->get(), JSON_PRETTY_PRINT);
