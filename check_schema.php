<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;

$res = [
    'sm_transfer_certificates' => Schema::getColumnListing('sm_transfer_certificates'),
    'sm_medical_records' => Schema::getColumnListing('sm_medical_records')
];

echo json_encode($res, JSON_PRETTY_PRINT);
