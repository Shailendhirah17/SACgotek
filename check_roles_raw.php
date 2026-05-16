<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;

echo "TABLE: infix_roles\n";
echo json_encode(DB::table('infix_roles')->get(), JSON_PRETTY_PRINT);
echo "\nTABLE: roles\n";
if(Illuminate\Support\Facades\Schema::hasTable('roles')) {
    echo json_encode(DB::table('roles')->get(), JSON_PRETTY_PRINT);
} else {
    echo "No roles table found\n";
}
