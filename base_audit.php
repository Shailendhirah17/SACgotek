<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use App\SmBaseSetup;
echo json_encode(SmBaseSetup::all(), JSON_PRETTY_PRINT);
