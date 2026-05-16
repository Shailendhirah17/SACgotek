<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;

echo "COLUMN CHECK (sm_schools):\n";
echo json_encode(DB::select("SHOW COLUMNS FROM sm_schools"), JSON_PRETTY_PRINT);
