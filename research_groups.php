<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "TABLE: school_groups\n";
echo json_encode(DB::table('school_groups')->get(), JSON_PRETTY_PRINT);
echo "\nTABLE: school_group_features\n";
echo json_encode(DB::table('school_group_features')->get(), JSON_PRETTY_PRINT);
echo "\nChecking if 'users' table has school_group_id:\n";
echo json_encode(Schema::hasColumn('users', 'school_group_id'), JSON_PRETTY_PRINT);
