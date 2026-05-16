<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Creating users table...\n";

if (!Schema::hasTable('users')) {
    DB::statement("
        CREATE TABLE users (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            full_name VARCHAR(192) NULL,
            username VARCHAR(192) NULL,
            phone_number VARCHAR(191) NULL,
            email VARCHAR(192) NULL,
            password VARCHAR(100) NULL,
            usertype VARCHAR(210) NULL,
            active_status TINYINT DEFAULT 1,
            random_code TEXT NULL,
            notificationToken TEXT NULL,
            remember_token VARCHAR(100) NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            language VARCHAR(255) NULL DEFAULT 'en',
            style_id INT NULL DEFAULT 1,
            rtl_ltl INT NULL DEFAULT 2,
            selected_session INT NULL DEFAULT 1,
            created_by INT NULL DEFAULT 1,
            updated_by INT NULL DEFAULT 1,
            access_status INT NULL DEFAULT 1,
            school_id INT NULL DEFAULT 1,
            role_id INT NULL,
            is_administrator ENUM('yes', 'no') DEFAULT 'no',
            is_registered TINYINT DEFAULT 0,
            device_token TEXT NULL,
            stripe_id VARCHAR(255) NULL,
            card_brand VARCHAR(255) NULL,
            card_last_four VARCHAR(4) NULL,
            verified VARCHAR(255) NULL,
            trial_ends_at TIMESTAMP NULL
        )
    ");
    echo "✅ Users table created.\n";
} else {
    echo "⚠️ Users table already exists.\n";
}

echo "Inserting admin user...\n";
$existing = DB::table('users')->where('id', 1)->first();
if (!$existing) {
    DB::table('users')->insert([
        'id' => 1,
        'created_by' => 1,
        'updated_by' => 1,
        'school_id' => 1,
        'role_id' => 1,
        'full_name' => 'admin',
        'email' => 'admin@infixedu.com',
        'username' => 'admin@infixedu.com',
        'password' => bcrypt('123456'),
        'is_administrator' => 'yes',
        'language' => 'en',
        'active_status' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Admin user created.\n";
} else {
    echo "⚠️ Admin user already exists.\n";
}

echo "✅ Setup complete!\n";
