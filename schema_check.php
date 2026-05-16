<?php
include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$tables = ['sm_class_routines', 'sm_staffs', 'sm_students', 'sm_student_attendances', 'sm_fees_payments'];
foreach ($tables as $table) {
    echo "--- TABLE: $table ---\n";
    if (Schema::hasTable($table)) {
        $columns = Schema::getColumnListing($table);
        print_r($columns);
    } else {
        echo "Table does not exist.\n";
    }
}
