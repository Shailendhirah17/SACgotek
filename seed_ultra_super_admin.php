<?php

use App\User;
use App\SmStaff;
use App\Models\UltraSuperAdmin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

DB::transaction(function() {
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    // 1. Create or update the role in infix_roles table
    DB::table('infix_roles')->updateOrInsert(
        ['id' => 10],
        [
            'name' => 'Ultra Super Admin',
            'type' => 'System',
            'active_status' => 1,
            'school_id' => 1,
            'is_saas' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]
    );

    // 2. Create or update the role in roles table
    if (Schema::hasTable('roles')) {
        DB::table('roles')->updateOrInsert(
            ['id' => 10],
            [
                'name' => 'Ultra Super Admin',
                'type' => 'System',
                'active_status' => 1,
                'school_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }

    // 3. Create/Update the Ultra Super Admin in the dedicated table
    if (Schema::hasTable('ultra_super_admins')) {
        UltraSuperAdmin::updateOrCreate(
            ['email' => 'gotek@gmail.com'],
            [
                'username' => 'gotek@gmail.com',
                'password' => Hash::make('gotek@2026'),
                'full_name' => 'GOTEK Admin',
                'active_status' => true,
                'role' => 'ultra_super_admin',
            ]
        );
        echo "Ultra Super Admin seeded in ultra_super_admins table.\n";
    }

    // 4. Also create the User record (for backward compatibility)
    $user = User::updateOrCreate(
        ['email' => 'gotek@gmail.com'],
        [
            'full_name' => 'Ultra Super Admin',
            'username' => 'gotek@gmail.com',
            'password' => Hash::make('gotek@2026'),
            'active_status' => 1,
            'school_id' => 1,
            'role_id' => 10,
            'is_administrator' => 'yes',
            'created_at' => now(),
            'updated_at' => now()
        ]
    );

    // 5. Create the SmStaff record
    SmStaff::updateOrCreate(
        ['email' => 'gotek@gmail.com'],
        [
            'staff_no' => 'USA-100',
            'first_name' => 'GOTEK',
            'last_name' => 'Admin',
            'full_name' => 'GOTEK Admin',
            'role_id' => 10,
            'user_id' => $user->id,
            'active_status' => 1,
            'school_id' => 1,
            'is_saas' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]
    );

    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
});

echo "Ultra Super Admin seeded successfully!\n";
echo "Credentials: gotek@gmail.com / gotek@2026\n";
