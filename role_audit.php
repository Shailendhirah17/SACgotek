<?php
// role_audit.php - Checking user counts by role

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;

$res = DB::table('users')
    ->select('role_id', DB::raw('count(*) as count'))
    ->groupBy('role_id')
    ->get();

echo json_encode($res, JSON_PRETTY_PRINT);
