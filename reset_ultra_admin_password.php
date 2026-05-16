<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "Resetting Ultra Super Admin password...\n";

// Reset password to a known value
DB::table('ultra_super_admins')
    ->where('username', 'technosprint')
    ->update([
        'password' => Hash::make('123456'),
        'updated_at' => now()
    ]);

echo "✅ Password reset to: 123456\n";
echo "✅ Username: technosprint\n";
echo "✅ Email: admin@technosprint.com\n";
