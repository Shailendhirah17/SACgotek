<?php
/**
 * Role Permission & Add Staff UI Fix - SFTP Push
 * Pushes the fixed blade views.
 */
require __DIR__ . '/vendor/autoload.php';

use phpseclib3\Net\SFTP;

$host     = '193.202.45.164';
$port     = 65002;
$username = 'u841409365';
$password = 'Eash@2005';
$remoteBase = 'domains/test-technoprint.online/public_html/erpv2/';

$filesToUpload = [
    'Modules/RolePermission/Resources/views/inc/permission_list.blade.php',
    'Modules/RolePermission/Resources/views/inc/permission_row.blade.php',
    'resources/views/backEnd/humanResource/addStaff.blade.php',
    'resources/views/backEnd/humanResource/editStaff.blade.php',
    'resources/views/backEnd/studentInformation/student_admission.blade.php',
    'resources/views/backEnd/studentInformation/student_edit.blade.php',
    'resources/views/backEnd/studentInformation/student_report.blade.php',
    'resources/views/backEnd/studentPanel/my_profile_update.blade.php',
    'resources/views/backEnd/parentPanel/update_my_children.blade.php',
    'resources/views/backEnd/frontSettings/donor/donor.blade.php',
    'Modules/Jitsi/Database/Migrations/2021_03_29_070403_create_jitsi_settings_table.php',
    'app/Http/Controllers/SmStudentAdmissionController.php',
    'Modules/Lesson/Http/Controllers/SmLessonController.php',
];

echo "=================================================\n";
echo " Pushing Core Logic & UI Fixes via SFTP\n";
echo "=================================================\n";
echo "Connecting to $host:$port...\n";

try {
    $sftp = new SFTP($host, $port);
    if (!$sftp->login($username, $password)) {
        throw new Exception("LOGIN FAILED.");
    }
    echo "✅ Login successful!\n\n";

    foreach ($filesToUpload as $localRelative) {
        $fullLocal  = __DIR__ . '/' . $localRelative;
        $fullRemote = $remoteBase . $localRelative;

        if (!file_exists($fullLocal)) {
            echo "⚠️  SKIP (not found): $localRelative\n";
            continue;
        }
        echo "Uploading: $localRelative ... ";
        if ($sftp->put($fullRemote, $fullLocal, SFTP::SOURCE_LOCAL_FILE)) {
            echo "✅ OK\n";
        } else {
            echo "❌ FAILED\n";
        }
    }

    // Clear view & config cache and run migration
    $cmds = [
        "php artisan migrate"      => "Running Migrations",
        "php artisan view:clear"   => "View clear",
        "php artisan cache:clear"  => "Cache clear",
        "touch public/.htaccess"   => "LiteSpeed bust",
    ];
    echo "\n";
    foreach ($cmds as $cmd => $label) {
        echo "Running: $label ... ";
        $out = trim($sftp->exec("cd $remoteBase && $cmd 2>&1"));
        echo ($out ?: "done") . "\n";
    }

    echo "\n✨ Done! Visit your site to verify.\n";

} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
