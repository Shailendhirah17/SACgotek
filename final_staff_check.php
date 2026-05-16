<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;
echo json_encode(DB::table('sm_staffs')->select('staff_no')->where('staff_no', 'LIKE', 'STF-%')->get(), JSON_PRETTY_PRINT);
