<?php
include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$databases = DB::select('SHOW DATABASES');
foreach ($databases as $db) {
    if (strpos($db->Database, 'u841409365_') !== false) {
        $dbName = $db->Database;
        echo "=== DATABASE: $dbName ===\n";
        try {
            DB::statement("USE $dbName");
            $tables = DB::select("SHOW TABLES LIKE '%class_routine%'");
            foreach ($tables as $table) {
                $tname = array_values((array)$table)[0];
                echo "--- Table: $tname ---\n";
                $cols = DB::select("DESCRIBE $tname");
                foreach ($cols as $col) {
                    echo "  Field: {$col->Field}\n";
                }
            }
        } catch (\Exception $e) {
            echo "Error accessing $dbName\n";
        }
    }
}
