<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;
echo "COUNT: " . DB::table('sm_staffs')->count() . "\n";
echo "ALL STAFF DATA:\n";
echo json_encode(DB::table('sm_staffs')->select('id', 'staff_no', 'full_name', 'user_id', 'school_id')->get(), JSON_PRETTY_PRINT);
