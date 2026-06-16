<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ErpEnhancementMenuSeeder extends Seeder
{
    public function run()
    {
        $school_id = 1;

        // ==========================================
        // 1. HOSTEL MENU ENHANCEMENTS
        // ==========================================
        $hostelParentId = 69514; // Existing Hostel module parent ID

        DB::table('sm_menus')->where('parent_id', $hostelParentId)->delete(); 
        
        $hostelMenus = [
            ['name' => 'Hostel Dashboard', 'route' => 'hostel.dashboard'],
            ['name' => 'Hostel List', 'route' => 'hostel.index'],
            ['name' => 'Room Setup', 'route' => 'hostel.rooms'],
            ['name' => 'Room Allocation', 'route' => 'hostel.allocation'],
            ['name' => 'Student Movements', 'route' => 'hostel.movements'],
            ['name' => 'Leave & Permissions', 'route' => 'hostel.permissions'],
            ['name' => 'Discipline & Incidents', 'route' => 'hostel.discipline'],
            ['name' => 'Visitor Log', 'route' => 'hostel.visitors'],
            ['name' => 'Hostel Fees', 'route' => 'hostel.fee'],
            ['name' => 'Meals & Menu', 'route' => 'hostel.meals'],
        ];

        foreach ($hostelMenus as $menu) {
            DB::table('sm_menus')->insert([
                'name' => $menu['name'],
                'route' => $menu['route'],
                'parent_id' => $hostelParentId,
                'status' => 1,
                'is_saas' => 0,
                'role_id' => 1,
                'menu_status' => 1,
                'school_id' => $school_id,
            ]);
        }

        // ==========================================
        // 2. VENDOR MENU ENHANCEMENTS
        // ==========================================
        $vendorParentId = 69513; // Existing Vendor module parent ID
        
        DB::table('sm_menus')->where('parent_id', $vendorParentId)->delete();
        
        $vendorMenus = [
            ['name' => 'Vendor Dashboard', 'route' => 'vendor.dashboard'],
            ['name' => 'Vendor List', 'route' => 'vendor.index'],
            ['name' => 'Purchase Orders', 'route' => 'purchase-order.index'],
            ['name' => 'Payments', 'route' => 'vendor.payments'],
            ['name' => 'Evaluations', 'route' => 'vendor.evaluations'],
            ['name' => 'Penalties', 'route' => 'vendor.penalties'],
            ['name' => 'Documents', 'route' => 'vendor.documents'],
            ['name' => 'Agreements', 'route' => 'vendor.agreements'],
        ];

        foreach ($vendorMenus as $menu) {
            DB::table('sm_menus')->insert([
                'name' => $menu['name'],
                'route' => $menu['route'],
                'parent_id' => $vendorParentId,
                'status' => 1,
                'is_saas' => 0,
                'role_id' => 1,
                'menu_status' => 1,
                'school_id' => $school_id,
            ]);
        }

        // ==========================================
        // 3. CANTEEN MENU (NEW)
        // ==========================================
        // Create Parent
        $canteenParentId = DB::table('sm_menus')->insertGetId([
            'name' => 'Canteen Management',
            'route' => 'canteen_management',
            'parent_id' => null,
            'status' => 1,
            'is_saas' => 0,
            'role_id' => 1,
            'menu_status' => 1,
            'icon' => 'fas fa-utensils',
            'school_id' => $school_id,
        ]);

        $canteenMenus = [
            ['name' => 'Dashboard', 'route' => 'canteen.dashboard'],
            ['name' => 'Student Wallets', 'route' => 'canteen.wallets'],
            ['name' => 'Categories', 'route' => 'canteen.categories'],
            ['name' => 'Menu Items', 'route' => 'canteen.items'],
            ['name' => 'POS / Terminal', 'route' => 'canteen.pos'],
            ['name' => 'Transactions', 'route' => 'canteen.transactions'],
        ];

        foreach ($canteenMenus as $menu) {
            DB::table('sm_menus')->insert([
                'name' => $menu['name'],
                'route' => $menu['route'],
                'parent_id' => $canteenParentId,
                'status' => 1,
                'is_saas' => 0,
                'role_id' => 1,
                'menu_status' => 1,
                'school_id' => $school_id,
            ]);
        }
    }
}
