<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;

echo "ALL TABLES:\n";
$tables = DB::select('SHOW TABLES');
echo json_encode($tables, JSON_PRETTY_PRINT);
