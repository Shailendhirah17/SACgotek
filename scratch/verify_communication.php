<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\SmSchool;
use App\Models\SchoolGroup;
use App\Models\SuperAdmin;

echo "Verification Log:\n";

// 1. Test Filter Logic Mock
function testFilter($adminId) {
    echo "\nTesting for Admin ID: $adminId\n";
    $currentAdmin = SuperAdmin::find($adminId);
    if (!$currentAdmin) {
        echo "Admin not found.\n";
        return;
    }
    
    $schoolQuery = SmSchool::where('active_status', 1);
    if ($currentAdmin->school_group_id) {
        $schoolQuery->where('school_group_id', $currentAdmin->school_group_id);
    }
    $schools = $schoolQuery->get();
    echo "Schools found: " . $schools->count() . " (IDs: " . implode(',', $schools->pluck('id')->toArray()) . ")\n";
}

// Just checking the models and relations
$school = SmSchool::where('active_status', 1)->first();
if ($school) {
    echo "Found school: {$school->school_name}\n";
    if ($school->schoolGroup) {
        echo "Belongs to group: {$school->schoolGroup->name}\n";
    } else {
        echo "No group assigned.\n";
    }
} else {
    echo "No active schools found.\n";
}

$group = SchoolGroup::where('active_status', 1)->first();
if ($group) {
    echo "Found group: {$group->name}\n";
    echo "Active schools count: " . $group->activeSchoolsCount() . "\n";
} else {
    echo "No active groups found.\n";
}

echo "\nVerification complete (Mock logic).\n";
