<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\SuperAdmin;
use App\User;

echo "=== Updating Test Accounts for Distinct Access ===\n";

// 1. Setup Organization Head (Super Admin Level)
$orgHeadEmail = 'org_head_krm@technosprint.online';
$orgPassword = Hash::make('KrmHead@123');

// Find the KRM institution group
$krmGroup = DB::table('school_groups')->where('name', 'like', '%KRM%')->first();

if ($krmGroup) {
    if (Illuminate\Support\Facades\Schema::hasTable('super_admins')) {
        $sa = SuperAdmin::where('email', 'krm@gmail.com')->first();
        if ($sa) {
            $sa->email = $orgHeadEmail;
            $sa->password = $orgPassword;
            $sa->save();
            echo "Updated Super Admin (Org Head) in super_admins table.\n";
        } else {
            $sa2 = SuperAdmin::where('school_group_id', $krmGroup->id)->first();
            if ($sa2) {
                $sa2->email = $orgHeadEmail;
                $sa2->password = $orgPassword;
                $sa2->save();
                echo "Updated alternate Super Admin (Org Head) in super_admins table.\n";
            }
        }
    }
}

// 2. Setup Principal (School Admin Level)
$principalEmail = 'principal_krm@technosprint.online';
$principalPassword = Hash::make('KrmPrincipal@123');

// Find KRM school
$krmSchool = DB::table('sm_schools')->where('school_name', 'like', '%KRM%')->first();

if ($krmSchool) {
    $principal = User::where('school_id', $krmSchool->id)->where('role_id', 1)->first();
    if ($principal) {
        $principal->email = $principalEmail;
        $principal->password = $principalPassword;
        $principal->username = $principalEmail;
        $principal->save();
        echo "Updated Admin (Principal) in users table.\n";
    }
}

echo "\n--- New Credentials ---\n";
echo "Super Admin (Org Head):\n";
echo "Email: " . $orgHeadEmail . "\n";
echo "Pass:  KrmHead@123\n\n";

echo "School Admin (Principal):\n";
echo "Email: " . $principalEmail . "\n";
echo "Pass:  KrmPrincipal@123\n";

