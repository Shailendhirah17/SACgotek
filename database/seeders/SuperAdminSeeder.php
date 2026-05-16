<?php

namespace Database\Seeders;

use App\Models\SuperAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SuperAdminSeeder extends Seeder
{
    /**
     * Seed the default SuperAdmin user.
     *
     * Creates a default platform administrator with the following credentials:
     * - Username: superadmin
     * - Email: superadmin@infixedu.com
     * - Password: superadmin123
     *
     * IMPORTANT: Change the default password immediately after first login.
     *
     * @return void
     */
    public function run()
    {
        $existing = SuperAdmin::where('username', 'superadmin')
            ->orWhere('email', 'superadmin@infixedu.com')
            ->first();

        if ($existing) {
            $this->command->info('Default SuperAdmin already exists. Skipping...');
            return;
        }

        $superAdmin = SuperAdmin::create([
            'username' => 'superadmin',
            'email' => 'superadmin@infixedu.com',
            'password' => Hash::make('superadmin123'),
            'full_name' => 'System Super Admin',
            'phone_number' => null,
            'active_status' => true,
            'role' => 'super_admin',
        ]);

        $this->command->info('Default SuperAdmin created successfully:');
        $this->command->info('  Username: superadmin');
        $this->command->info('  Email: superadmin@infixedu.com');
        $this->command->info('  Password: superadmin123');
        $this->command->warn('  ⚠ Change this password immediately in production!');

        Log::info('Default SuperAdmin seeded', ['id' => $superAdmin->id]);
    }
}
