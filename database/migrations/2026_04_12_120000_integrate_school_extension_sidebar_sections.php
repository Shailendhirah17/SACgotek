<?php

use App\InfixModuleManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    private function permissionBase(array $overrides): array
    {
        return array_merge([
            'module' => null,
            'sidebar_menu' => null,
            'old_id' => null,
            'section_id' => 1,
            'parent_id' => 0,
            'type' => 2,
            'icon' => null,
            'svg' => null,
            'status' => 1,
            'menu_status' => 1,
            'position' => 1,
            'is_saas' => 0,
            'relate_to_child' => 0,
            'is_menu' => 1,
            'is_admin' => 1,
            'is_teacher' => 0,
            'is_student' => 0,
            'is_parent' => 0,
            'is_alumni' => 0,
            'created_by' => 1,
            'updated_by' => 1,
            'permission_section' => 0,
            'alternate_module' => null,
            'user_id' => null,
            'role_id' => null,
            'school_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'custom_menu_id' => null,
        ], $overrides);
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('sm_menus') || ! Schema::hasTable('permissions')) {
            return;
        }

        if (DB::table('sm_menus')->where('route', 'student_modules_section')->where('role_id', 1)->exists()) {
            return;
        }

        $permIds = [];

        $sections = [
            'student_modules_section' => $this->permissionBase([
                'name' => 'Student Modules',
                'route' => 'student_modules_section',
                'parent_route' => null,
                'type' => null,
                'lang_name' => 'common.student_modules',
                'permission_section' => 1,
                'position' => 8,
            ]),
            'library_book_bank_section' => $this->permissionBase([
                'name' => 'Library & Book Bank',
                'route' => 'library_book_bank_section',
                'parent_route' => null,
                'type' => null,
                'lang_name' => 'common.library_book_bank',
                'permission_section' => 1,
                'position' => 9,
            ]),
            'vendor_accounts_section' => $this->permissionBase([
                'name' => 'Vendor & Accounts',
                'route' => 'vendor_accounts_section',
                'parent_route' => null,
                'type' => null,
                'lang_name' => 'common.vendor_accounts',
                'permission_section' => 1,
                'position' => 10,
            ]),
            'hostel_management_section' => $this->permissionBase([
                'name' => 'Hostel Management',
                'route' => 'hostel_management_section',
                'parent_route' => null,
                'type' => null,
                'lang_name' => 'common.hostel_management',
                'permission_section' => 1,
                'position' => 11,
            ]),
        ];

        foreach ($sections as $route => $row) {
            $id = DB::table('permissions')->insertGetId($row);
            $permIds[$route] = $id;
        }

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

        foreach ($children as $c) {
            $id = DB::table('permissions')->insertGetId($this->permissionBase([
                'name' => $c['name'],
                'route' => $c['route'],
                'parent_route' => $c['parent'],
                'lang_name' => $c['lang'],
                'module' => $c['module'],
                'icon' => $c['icon'],
                'position' => $c['pos'],
                'type' => 2,
                'permission_section' => 0,
            ]));
            $permIds[$c['route']] = $id;
        }

        foreach (['sm_menus', 'default_menus'] as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            DB::table($table)->where('route', 'report_section')->where('role_id', 1)->update([
                'position' => 12,
                'default_position' => 12,
            ]);
            DB::table($table)->where('route', 'settings_section')->where('role_id', 1)->update([
                'position' => 13,
                'default_position' => 13,
            ]);
            DB::table($table)->where('route', 'module_section')->where('role_id', 1)->update([
                'position' => 14,
                'default_position' => 14,
            ]);
        }

        DB::table('permissions')->where('route', 'report_section')->update(['position' => 12]);
        DB::table('permissions')->where('route', 'settings_section')->update(['position' => 13]);
        DB::table('permissions')->where('route', 'module_section')->update(['position' => 14]);

        $schoolIds = DB::table('sm_menus')->where('route', 'utilities_section')->where('role_id', 1)
            ->pluck('school_id')->unique()->filter()->values()->all();

        if ($schoolIds === []) {
            $schoolIds = [1];
        }

        $ts = now();

        foreach ($schoolIds as $schoolId) {
            $sid = (int) $schoolId;

            $studentSectionId = DB::table('sm_menus')->insertGetId([
                'name' => 'Student Modules',
                'module' => null,
                'route' => 'student_modules_section',
                'lang_name' => 'common.student_modules',
                'section_id' => null,
                'icon' => 'fas fa-user-graduate',
                'status' => 1,
                'is_saas' => 0,
                'role_id' => 1,
                'is_alumni' => null,
                'menu_status' => 1,
                'permission_section' => 1,
                'position' => 8,
                'default_position' => 8,
                'parent' => null,
                'parent_id' => null,
                'school_id' => $sid,
                'alternate_module' => null,
                'permission_id' => $permIds['student_modules_section'],
                'ignore' => 0,
                'created_at' => $ts,
                'updated_at' => $ts,
            ]);

            $librarySectionId = DB::table('sm_menus')->insertGetId([
                'name' => 'Library & Book Bank',
                'module' => null,
                'route' => 'library_book_bank_section',
                'lang_name' => 'common.library_book_bank',
                'section_id' => null,
                'icon' => 'fas fa-book',
                'status' => 1,
                'is_saas' => 0,
                'role_id' => 1,
                'is_alumni' => null,
                'menu_status' => 1,
                'permission_section' => 1,
                'position' => 9,
                'default_position' => 9,
                'parent' => null,
                'parent_id' => null,
                'school_id' => $sid,
                'alternate_module' => null,
                'permission_id' => $permIds['library_book_bank_section'],
                'ignore' => 0,
                'created_at' => $ts,
                'updated_at' => $ts,
            ]);

            $vendorSectionId = DB::table('sm_menus')->insertGetId([
                'name' => 'Vendor & Accounts',
                'module' => null,
                'route' => 'vendor_accounts_section',
                'lang_name' => 'common.vendor_accounts',
                'section_id' => null,
                'icon' => 'fas fa-building',
                'status' => 1,
                'is_saas' => 0,
                'role_id' => 1,
                'is_alumni' => null,
                'menu_status' => 1,
                'permission_section' => 1,
                'position' => 10,
                'default_position' => 10,
                'parent' => null,
                'parent_id' => null,
                'school_id' => $sid,
                'alternate_module' => null,
                'permission_id' => $permIds['vendor_accounts_section'],
                'ignore' => 0,
                'created_at' => $ts,
                'updated_at' => $ts,
            ]);

            $hostelSectionId = DB::table('sm_menus')->insertGetId([
                'name' => 'Hostel Management',
                'module' => null,
                'route' => 'hostel_management_section',
                'lang_name' => 'common.hostel_management',
                'section_id' => null,
                'icon' => 'fas fa-hotel',
                'status' => 1,
                'is_saas' => 0,
                'role_id' => 1,
                'is_alumni' => null,
                'menu_status' => 1,
                'permission_section' => 1,
                'position' => 11,
                'default_position' => 11,
                'parent' => null,
                'parent_id' => null,
                'school_id' => $sid,
                'alternate_module' => null,
                'permission_id' => $permIds['hostel_management_section'],
                'ignore' => 0,
                'created_at' => $ts,
                'updated_at' => $ts,
            ]);

            foreach ($children as $c) {
                $parentDbId = match ($c['parent']) {
                    'student_modules_section' => $studentSectionId,
                    'library_book_bank_section' => $librarySectionId,
                    'vendor_accounts_section' => $vendorSectionId,
                    default => $hostelSectionId,
                };

                DB::table('sm_menus')->insert([
                    'name' => $c['name'],
                    'module' => $c['module'],
                    'route' => $c['route'],
                    'lang_name' => $c['lang'],
                    'section_id' => null,
                    'icon' => $c['icon'],
                    'status' => 1,
                    'is_saas' => 0,
                    'role_id' => 1,
                    'is_alumni' => null,
                    'menu_status' => 1,
                    'permission_section' => 0,
                    'position' => $c['pos'],
                    'default_position' => $c['pos'],
                    'parent' => $parentDbId,
                    'parent_id' => $parentDbId,
                    'school_id' => $sid,
                    'alternate_module' => null,
                    'permission_id' => $permIds[$c['route']],
                    'ignore' => 0,
                    'created_at' => $ts,
                    'updated_at' => $ts,
                ]);
            }
        }

        if (Schema::hasTable('default_menus') && ! DB::table('default_menus')->where('route', 'student_modules_section')->where('role_id', 1)->exists()) {
            $this->seedDefaultMenusTemplate($permIds, $children, $ts);
        }

        if (Schema::hasTable('infix_module_managers')) {
            foreach (['StudentModules', 'LibraryBookBank', 'VendorAccounts', 'HostelManagement'] as $moduleName) {
                DB::table('infix_module_managers')->updateOrInsert(
                    ['name' => $moduleName],
                    [
                        'email' => 'school@local',
                        'notes' => 'School extension — enable/disable affects sidebar items tied to this module name.',
                        'version' => '1.0',
                        'update_url' => url('/'),
                        'is_default' => 1,
                        'purchase_code' => (string) time(),
                        'installed_domain' => url('/'),
                        'activated_date' => now()->format('Y-m-d'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }

    private function seedDefaultMenusTemplate(array $permIds, array $children, $ts): void
    {
        $studentSectionId = DB::table('default_menus')->insertGetId([
            'name' => 'Student Modules',
            'module' => null,
            'route' => 'student_modules_section',
            'lang_name' => 'common.student_modules',
            'section_id' => null,
            'icon' => 'fas fa-user-graduate',
            'status' => 1,
            'is_saas' => 0,
            'role_id' => 1,
            'is_alumni' => null,
            'menu_status' => 1,
            'permission_section' => 1,
            'position' => 8,
            'default_position' => 8,
            'parent' => null,
            'parent_id' => null,
            'school_id' => 1,
            'alternate_module' => null,
            'permission_id' => $permIds['student_modules_section'],
            'ignore' => 0,
            'created_at' => $ts,
            'updated_at' => $ts,
        ]);

        $librarySectionId = DB::table('default_menus')->insertGetId([
            'name' => 'Library & Book Bank',
            'module' => null,
            'route' => 'library_book_bank_section',
            'lang_name' => 'common.library_book_bank',
            'section_id' => null,
            'icon' => 'fas fa-book',
            'status' => 1,
            'is_saas' => 0,
            'role_id' => 1,
            'is_alumni' => null,
            'menu_status' => 1,
            'permission_section' => 1,
            'position' => 9,
            'default_position' => 9,
            'parent' => null,
            'parent_id' => null,
            'school_id' => 1,
            'alternate_module' => null,
            'permission_id' => $permIds['library_book_bank_section'],
            'ignore' => 0,
            'created_at' => $ts,
            'updated_at' => $ts,
        ]);

        $vendorSectionId = DB::table('default_menus')->insertGetId([
            'name' => 'Vendor & Accounts',
            'module' => null,
            'route' => 'vendor_accounts_section',
            'lang_name' => 'common.vendor_accounts',
            'section_id' => null,
            'icon' => 'fas fa-building',
            'status' => 1,
            'is_saas' => 0,
            'role_id' => 1,
            'is_alumni' => null,
            'menu_status' => 1,
            'permission_section' => 1,
            'position' => 10,
            'default_position' => 10,
            'parent' => null,
            'parent_id' => null,
            'school_id' => 1,
            'alternate_module' => null,
            'permission_id' => $permIds['vendor_accounts_section'],
            'ignore' => 0,
            'created_at' => $ts,
            'updated_at' => $ts,
        ]);

        $hostelSectionId = DB::table('default_menus')->insertGetId([
            'name' => 'Hostel Management',
            'module' => null,
            'route' => 'hostel_management_section',
            'lang_name' => 'common.hostel_management',
            'section_id' => null,
            'icon' => 'fas fa-hotel',
            'status' => 1,
            'is_saas' => 0,
            'role_id' => 1,
            'is_alumni' => null,
            'menu_status' => 1,
            'permission_section' => 1,
            'position' => 11,
            'default_position' => 11,
            'parent' => null,
            'parent_id' => null,
            'school_id' => 1,
            'alternate_module' => null,
            'permission_id' => $permIds['hostel_management_section'],
            'ignore' => 0,
            'created_at' => $ts,
            'updated_at' => $ts,
        ]);

        foreach ($children as $c) {
            $parentDbId = match ($c['parent']) {
                'student_modules_section' => $studentSectionId,
                'library_book_bank_section' => $librarySectionId,
                'vendor_accounts_section' => $vendorSectionId,
                default => $hostelSectionId,
            };

            DB::table('default_menus')->insert([
                'name' => $c['name'],
                'module' => $c['module'],
                'route' => $c['route'],
                'lang_name' => $c['lang'],
                'section_id' => null,
                'icon' => $c['icon'],
                'status' => 1,
                'is_saas' => 0,
                'role_id' => 1,
                'is_alumni' => null,
                'menu_status' => 1,
                'permission_section' => 0,
                'position' => $c['pos'],
                'default_position' => $c['pos'],
                'parent' => $parentDbId,
                'parent_id' => $parentDbId,
                'school_id' => 1,
                'alternate_module' => null,
                'permission_id' => $permIds[$c['route']],
                'ignore' => 0,
                'created_at' => $ts,
                'updated_at' => $ts,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('sm_menus')) {
            return;
        }

        $routes = array_merge(
            [
                'student_modules_section',
                'library_book_bank_section',
                'vendor_accounts_section',
                'hostel_management_section',
            ],
            [
                'tc-list',
                'medical-records',
                'vaccination-records',
                'book-bank',
                'thirukkural',
                'book-bank-issue',
                'vendor-list',
                'purchase-orders',
                'vendor-payments',
                'hostel-list',
                'hostel-allocation',
                'hostel-fee',
            ]
        );

        DB::table('sm_menus')->whereIn('route', $routes)->delete();
        if (Schema::hasTable('default_menus')) {
            DB::table('default_menus')->whereIn('route', $routes)->delete();
        }

        if (Schema::hasTable('permissions')) {
            DB::table('permissions')->whereIn('route', $routes)->delete();
        }

        foreach (['sm_menus', 'default_menus'] as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            DB::table($table)->where('route', 'report_section')->where('role_id', 1)->update([
                'position' => 8,
                'default_position' => 8,
            ]);
            DB::table($table)->where('route', 'settings_section')->where('role_id', 1)->update([
                'position' => 9,
                'default_position' => 9,
            ]);
            DB::table($table)->where('route', 'module_section')->where('role_id', 1)->update([
                'position' => 10,
                'default_position' => 10,
            ]);
        }

        if (Schema::hasTable('permissions')) {
            DB::table('permissions')->where('route', 'report_section')->update(['position' => 8]);
            DB::table('permissions')->where('route', 'settings_section')->update(['position' => 9]);
            DB::table('permissions')->where('route', 'module_section')->update(['position' => 10]);
        }

        if (Schema::hasTable('infix_module_managers')) {
            DB::table('infix_module_managers')->whereIn('name', ['StudentModules', 'LibraryBookBank', 'VendorAccounts', 'HostelManagement'])->delete();
        }
    }
};
