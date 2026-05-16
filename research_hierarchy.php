<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;

echo "TABLE: infix_roles\n";
echo json_encode(DB::table('infix_roles')->get(), JSON_PRETTY_PRINT);
echo "\nTABLE: sm_schools\n";
echo json_encode(DB::table('sm_schools')->get(), JSON_PRETTY_PRINT);
echo "\nCOLUMN CHECK (users):\n";
echo json_encode(DB::select("SHOW COLUMNS FROM users"), JSON_PRETTY_PRINT);
