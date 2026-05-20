<?php

use App\User;
use App\SmStaff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

    // 2. Create the User
    $user = User::updateOrCreate(
        ['email' => 'ultrasuperadmin@sacgotek.com'],
        [
            'full_name' => 'Ultra Super Admin',
            'username' => 'ultrasuperadmin@sacgotek.com',
            'password' => Hash::make('123456'),
            'active_status' => 1,
            'school_id' => 1,
            'role_id' => 10,
            'is_administrator' => 'yes',
            'created_at' => now(),
            'updated_at' => now()
        ]
    );

    // 3. Create the SmStaff
    SmStaff::updateOrCreate(
        ['email' => 'ultrasuperadmin@sacgotek.com'],
        [
            'staff_no' => 'USA-100',
            'first_name' => 'Ultra',
            'last_name' => 'Super Admin',
            'full_name' => 'Ultra Super Admin',
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
