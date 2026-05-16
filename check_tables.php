<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tables = [
    'sm_transfer_certificates',
    'sm_medical_records',
    'sm_vaccination_records',
    'sm_book_banks',
    'sm_book_bank_issues',
    'sm_thirukkurals',
    'sm_vendors',
    'sm_purchase_orders',
    'sm_vendor_payments',
    'sm_hostels',
    'sm_hostel_rooms',
    'sm_hostel_allocations',
    'sm_hostel_fees',
    'sm_hostel_meals'
];

foreach ($tables as $table) {
    if (Illuminate\Support\Facades\Schema::hasTable($table)) {
        echo "Table $table exists.\n";
    } else {
        echo "Table $table MISSING.\n";
    }
}
