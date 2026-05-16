<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "TABLES CONTAINING 'org' OR 'group':\n";
$tables = DB::select('SHOW TABLES');
foreach($tables as $table) {
    $tableName = array_values((array)$table)[0];
    if (strpos($tableName, 'org') !== false || strpos($tableName, 'group') !== false) {
        echo "- $tableName\n";
    }
}
echo "\nChecking schema of sm_schools for hierarchy fields:\n";
echo json_encode(Schema::getColumnListing('sm_schools'), JSON_PRETTY_PRINT);
