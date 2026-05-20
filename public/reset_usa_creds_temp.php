<?php
/**
 * Web-accessible credential reset for Ultra Super Admin.
 * Access this via: https://test-sacgotek.test1-technosprint.online/reset_usa_creds_temp.php
 * 
 * IMPORTANT: Delete this file after use!
 */

// Security: only allow with correct secret token
$secretToken = 'sacgotek_reset_2026_secure';
if (!isset($_GET['token']) || $_GET['token'] !== $secretToken) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized. Provide ?token=<secret>']);
    exit;
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

header('Content-Type: application/json');

try {
    if (!Schema::hasTable('ultra_super_admins')) {
        echo json_encode(['error' => 'Table ultra_super_admins does not exist']);
        exit;
    }

    // Find any existing ultra super admin
    $admin = DB::table('ultra_super_admins')
        ->where('email', 'gotek@gmail.com')
        ->orWhere('username', 'gotek@gmail.com')
        ->orWhere('username', 'gotek')
        ->orWhere('email', 'admin@gotek.com')
        ->orWhere('username', 'technosprint')
        ->first();

    if ($admin) {
        // Update existing record
        DB::table('ultra_super_admins')
            ->where('id', $admin->id)
            ->update([
                'username' => 'gotek@gmail.com',
                'email' => 'gotek@gmail.com',
                'password' => Hash::make('gotek@2026'),
                'full_name' => 'GOTEK Admin',
                'active_status' => 1,
                'role' => 'ultra_super_admin',
                'updated_at' => now(),
            ]);
        
        echo json_encode([
            'success' => true,
            'action' => 'updated',
            'message' => 'Ultra Super Admin credentials updated successfully',
            'id' => $admin->id,
            'login_url' => '/ultrasuperadmin/login',
            'username' => 'gotek@gmail.com',
            'password' => 'gotek@2026',
        ]);
    } else {
        // Create new record
        $id = DB::table('ultra_super_admins')->insertGetId([
            'username' => 'gotek@gmail.com',
            'email' => 'gotek@gmail.com',
            'password' => Hash::make('gotek@2026'),
            'full_name' => 'GOTEK Admin',
            'active_status' => 1,
            'role' => 'ultra_super_admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        echo json_encode([
            'success' => true,
            'action' => 'created',
            'message' => 'Ultra Super Admin created successfully',
            'id' => $id,
            'login_url' => '/ultrasuperadmin/login',
            'username' => 'gotek@gmail.com',
            'password' => 'gotek@2026',
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
    ]);
}
