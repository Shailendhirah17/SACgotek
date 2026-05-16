<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\UltraSuperAdmin;
use App\Models\SuperAdmin;
use App\Models\SchoolGroup;
use App\SmSchool;
use App\User;

class EndToEndDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Ultra Super Admin (Master Layer)
        $usa = UltraSuperAdmin::firstOrCreate(
            ['username' => 'technosprint'],
            [
                'email' => 'admin@technosprint.com',
                'password' => Hash::make('Technosprint@2026'),
                'full_name' => 'Technosprint Master',
                'active_status' => true,
            ]
        );

        // 2. Super Admin (Organization Layer)
        $sa = SuperAdmin::firstOrCreate(
            ['username' => 'superadmin'],
            [
                'email' => 'superadmin@infixedu.com',
                'password' => Hash::make('SuperAdmin@123'),
                'full_name' => 'Global Super Admin',
                'role' => 'super_admin',
                'active_status' => true,
            ]
        );

        // 3. School Group (Created by USA, managed by SA)
        $group = SchoolGroup::firstOrCreate(
            ['code' => 'DUMMY-GRP'],
            [
                'name' => 'Dummy Educational Group',
                'active_status' => true,
                'created_by' => $usa->id,
                'max_schools' => 10,
                'max_students_per_school' => 1000,
                'subscription_plan' => 'enterprise',
            ]
        );

        // 4. Dummy School (Managed by Admin, belongs to School Group)
        $school = SmSchool::firstOrCreate(
            ['school_code' => 'DIS-101'],
            [
                'school_name' => 'Dummy International School',
                'email' => 'dummy@school.com',
                'active_status' => 1,
                'school_group_id' => $group->id,
                'created_by' => 1, // System default
            ]
        );

        // 5. Generic School Admin (School Layer)
        $admin = User::firstOrCreate(
            ['email' => 'admin_dummy@school.com'],
            [
                'username' => 'admindummy',
                'full_name' => 'Dummy School Admin',
                'password' => Hash::make('123456'),
                'role_id' => 1, // Assuming 1 is Admin in roles table
                'school_id' => $school->id,
                'active_status' => 1,
            ]
        );

        $this->command->info('---- Dummy Data Hierarchy Seeded Successfully ----');
        $this->command->info('Ultra Super Admin: technosprint / Technosprint@2026');
        $this->command->info('Super Admin: superadmin / SuperAdmin@123');
        $this->command->info('School Admin: admin_dummy@school.com / 123456');
    }
}
