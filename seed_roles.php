<?php
// seed_roles.php - Restores missing system roles for school_id 1

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Modules\RolePermission\Entities\InfixRole;
use Illuminate\Support\Facades\DB;

$school_id = 1;
$roles = [
    2 => 'Student',
    3 => 'Parent',
    4 => 'Teacher',
];

echo "Restoring system roles for school_id $school_id...\n";

DB::beginTransaction();
try {
    foreach ($roles as $id => $name) {
        $role = InfixRole::find($id);
        if (!$role) {
            // Using raw insert to preserve default IDs
            DB::table('infix_roles')->insert([
                'id' => $id,
                'name' => $name,
                'type' => 'System',
                'active_status' => 1,
                'school_id' => $school_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "Created System Role: $name (ID $id)\n";
        } else {
            echo "Role $id already exists ($role->name)\n";
        }
    }
    DB::commit();
} catch (\Exception $e) {
    DB::rollback();
    echo "Failed to restore roles: " . $e->getMessage() . "\n";
}
