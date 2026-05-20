<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\UltraSuperAdmin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

echo "=== Ultra Super Admin Credential Reset ===\n\n";

// 1. Ensure the table exists
if (!Schema::hasTable('ultra_super_admins')) {
    echo "❌ Table 'ultra_super_admins' does not exist. Running migration...\n";
    Artisan::call('migrate', ['--path' => 'database/migrations/2026_04_11_000001_create_ultra_super_admins_table.php', '--force' => true]);
    echo "✅ Migration completed.\n\n";
}

// 2. Check for any existing ultra super admin records
$existingRecords = DB::table('ultra_super_admins')->get();
echo "Found " . $existingRecords->count() . " existing ultra super admin record(s).\n";

if ($existingRecords->count() > 0) {
    foreach ($existingRecords as $record) {
        echo "  - ID: {$record->id}, Username: {$record->username}, Email: {$record->email}\n";
    }
}

// 3. Update or create the correct ultra super admin
$admin = UltraSuperAdmin::where('email', 'gotek@gmail.com')
    ->orWhere('username', 'gotek@gmail.com')
    ->orWhere('username', 'gotek')
    ->orWhere('email', 'admin@gotek.com')
    ->orWhere('username', 'technosprint')
    ->first();

if ($admin) {
    // Update existing record
    $admin->update([
        'username' => 'gotek@gmail.com',
        'email' => 'gotek@gmail.com',
        'password' => Hash::make('gotek@2026'),
        'full_name' => 'GOTEK Admin',
        'active_status' => true,
        'role' => 'ultra_super_admin',
        'updated_at' => now(),
    ]);
    echo "\n✅ Updated existing Ultra Super Admin (ID: {$admin->id})\n";
} else {
    // Create new record
    $admin = UltraSuperAdmin::create([
        'username' => 'gotek@gmail.com',
        'email' => 'gotek@gmail.com',
        'password' => Hash::make('gotek@2026'),
        'full_name' => 'GOTEK Admin',
        'phone_number' => null,
        'active_status' => true,
        'role' => 'ultra_super_admin',
    ]);
    echo "\n✅ Created new Ultra Super Admin (ID: {$admin->id})\n";
}

echo "\n=== Login Credentials ===\n";
echo "URL:      /ultrasuperadmin/login\n";
echo "Username: gotek@gmail.com\n";
echo "Password: gotek@2026\n";
echo "========================\n";
