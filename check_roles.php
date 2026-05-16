<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Modules\RolePermission\Entities\InfixRole;

echo "ROLES LIST:\n";
foreach(InfixRole::all() as $role) {
    echo "ID: " . $role->id . " - Name: " . $role->name . " - School: " . $role->school_id . "\n";
}
