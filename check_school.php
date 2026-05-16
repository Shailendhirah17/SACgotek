<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use App\SmSchool;
use App\User;
echo "SCHOOLS:\n";
echo json_encode(SmSchool::all(), JSON_PRETTY_PRINT);
echo "\nSUPER ADMIN USER:\n";
echo json_encode(User::where('role_id', 1)->first(), JSON_PRETTY_PRINT);
