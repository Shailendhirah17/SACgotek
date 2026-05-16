<?php
require "vendor/autoload.php";
$app = require_once "bootstrap/app.php";
$kernel = $app->make("Illuminate\Contracts\Console\Kernel");
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\InfixModuleManager;

$ts = now();
$schoolIds = DB::table("sm_schools")->pluck("id")->all();
$roleId = 1;

// Define exact permission maps from production
$perms = [
    'student_modules_section' => 70048,
    'library_book_bank_section' => 70049,
    'vendor_accounts_section' => 70050,
    'hostel_management_section' => 70051,
    'tc-list' => 70052,
    'medical-records' => 70053,
    'vaccination-records' => 70054,
    'book-bank' => 70055,
    'thirukkural' => 70056,
    'book-bank-issue' => 70057,
    'vendor-list' => 70058,
    'purchase-orders' => 70059,
    'vendor-payments' => 70060,
    'hostel-list' => 70061,
    'hostel-allocation' => 70062,
    'hostel-fee' => 70063
];

$children = [
    ['route' => 'tc-list', 'name' => 'Transfer Certificate (TC)', 'lang' => 'common.transfer_certificate', 'parent' => 'student_modules_section', 'module' => 'StudentModules', 'icon' => 'fas fa-file-alt', 'pos' => 1],
    ['route' => 'medical-records', 'name' => 'Medical Records', 'lang' => 'common.medical_records', 'parent' => 'student_modules_section', 'module' => 'StudentModules', 'icon' => 'fas fa-notes-medical', 'pos' => 2],
    ['route' => 'vaccination-records', 'name' => 'Vaccination Records', 'lang' => 'common.vaccination_records', 'parent' => 'student_modules_section', 'module' => 'StudentModules', 'icon' => 'fas fa-syringe', 'pos' => 3],
    ['route' => 'book-bank', 'name' => 'Book Bank (List)', 'lang' => 'common.book_bank_list', 'parent' => 'library_book_bank_section', 'module' => 'LibraryBookBank', 'icon' => 'fas fa-book', 'pos' => 1],
    ['route' => 'thirukkural', 'name' => 'Thirukkural', 'lang' => 'common.thirukkural_menu', 'parent' => 'library_book_bank_section', 'module' => 'LibraryBookBank', 'icon' => 'fas fa-book-open', 'pos' => 2],
    ['route' => 'book-bank-issue', 'name' => 'Issue Books', 'lang' => 'common.issue_books', 'parent' => 'library_book_bank_section', 'module' => 'LibraryBookBank', 'icon' => 'fas fa-book-reader', 'pos' => 3],
    ['route' => 'vendor-list', 'name' => 'Vendor Management', 'lang' => 'common.vendor_management', 'parent' => 'vendor_accounts_section', 'module' => 'VendorAccounts', 'icon' => 'fas fa-building', 'pos' => 1],
    ['route' => 'purchase-orders', 'name' => 'Purchase Orders', 'lang' => 'common.purchase_orders_menu', 'parent' => 'vendor_accounts_section', 'module' => 'VendorAccounts', 'icon' => 'fas fa-shopping-cart', 'pos' => 2],
    ['route' => 'vendor-payments', 'name' => 'Vendor Payments', 'lang' => 'common.vendor_payments_menu', 'parent' => 'vendor_accounts_section', 'module' => 'VendorAccounts', 'icon' => 'fas fa-money-check-alt', 'pos' => 3],
    ['route' => 'hostel-list', 'name' => 'Hostel List', 'lang' => 'common.hostel_list_menu', 'parent' => 'hostel_management_section', 'module' => 'HostelManagement', 'icon' => 'fas fa-hotel', 'pos' => 1],
    ['route' => 'hostel-allocation', 'name' => 'Room Allocation', 'lang' => 'common.room_allocation_menu', 'parent' => 'hostel_management_section', 'module' => 'HostelManagement', 'icon' => 'fas fa-bed', 'pos' => 2],
    ['route' => 'hostel-fee', 'name' => 'Hostel Fees', 'lang' => 'common.hostel_fees_menu', 'parent' => 'hostel_management_section', 'module' => 'HostelManagement', 'icon' => 'fas fa-coins', 'pos' => 3],
];

echo "Starting Sync for " . count($schoolIds) . " schools...\n";

foreach ($schoolIds as $sid) {
    echo "Processing School ID: $sid\n";

    // 1. Sections
    $sections = [
        'student_modules_section' => ['name' => 'Student Modules', 'icon' => 'fas fa-user-graduate', 'pos' => 8, 'lang' => 'common.student_modules'],
        'library_book_bank_section' => ['name' => 'Library & Book Bank', 'icon' => 'fas fa-book', 'pos' => 9, 'lang' => 'common.library_book_bank'],
        'vendor_accounts_section' => ['name' => 'Vendor & Accounts', 'icon' => 'fas fa-building', 'pos' => 10, 'lang' => 'common.vendor_accounts'],
        'hostel_management_section' => ['name' => 'Hostel Management', 'icon' => 'fas fa-hotel', 'pos' => 11, 'lang' => 'common.hostel_management'],
    ];

    $sectionIds = [];
    foreach ($sections as $route => $data) {
        $id = DB::table('sm_menus')->updateOrInsert(
            ['route' => $route, 'school_id' => $sid, 'role_id' => $roleId],
            [
                'name' => $data['name'],
                'lang_name' => $data['lang'],
                'icon' => $data['icon'],
                'permission_section' => 1,
                'position' => $data['pos'],
                'default_position' => $data['pos'],
                'status' => 1,
                'menu_status' => 1,
                'permission_id' => $perms[$route],
                'created_at' => $ts,
                'updated_at' => $ts,
            ]
        );
        $sectionIds[$route] = DB::table('sm_menus')->where('route', $route)->where('school_id', $sid)->where('role_id', $roleId)->value('id');
    }

    // 2. Children
    foreach ($children as $c) {
        $parentDbId = $sectionIds[$c['parent']];
        DB::table('sm_menus')->updateOrInsert(
            ['route' => $c['route'], 'school_id' => $sid, 'role_id' => $roleId],
            [
                'name' => $c['name'],
                'module' => $c['module'],
                'lang_name' => $c['lang'],
                'icon' => $c['icon'],
                'position' => $c['pos'],
                'default_position' => $c['pos'],
                'parent' => $parentDbId,
                'parent_id' => $parentDbId,
                'status' => 1,
                'menu_status' => 1,
                'permission_id' => $perms[$c['route']],
                'created_at' => $ts,
                'updated_at' => $ts,
            ]
        );
    }

    // 3. Permissions assigned
    foreach ($perms as $route => $pid) {
        DB::table('assign_permissions')->updateOrInsert(
            ['permission_id' => $pid, 'role_id' => $roleId, 'school_id' => $sid],
            ['created_at' => $ts, 'updated_at' => $ts]
        );
    }

    // 4. Module Registry
    foreach (['StudentModules', 'LibraryBookBank', 'VendorAccounts', 'HostelManagement'] as $mName) {
        DB::table('infix_module_managers')->updateOrInsert(
            ['name' => $mName],
            [
                'email' => 'tech@local',
                'is_default' => 1,
                'activated_date' => now()->format('Y-m-d'),
                'updated_at' => $ts
            ]
        );
    }
}

echo "Sync completed successfully.\n";
