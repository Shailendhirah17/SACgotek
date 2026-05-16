<?php
/**
 * Student Settings Fix - Remote SFTP Deploy & Execute
 */
require __DIR__ . '/vendor/autoload.php';

use phpseclib3\Net\SFTP;

$host     = '193.202.45.164';
$port     = 65002;
$username = 'u841409365';
$password = 'Eash@2005';
$remoteBase = 'domains/test-technoprint.online/public_html/erpv2/';

echo "=================================================\n";
echo " Student Settings Fix - SFTP Push & Execute\n";
echo "=================================================\n";

try {
    $sftp = new SFTP($host, $port);
    if (!$sftp->login($username, $password)) {
        throw new Exception("LOGIN FAILED.");
    }
    echo "✅ Connected!\n\n";

    // Upload seeder
    $localFile  = __DIR__ . '/seed_student_fields_fix.php';
    $remoteFile = $remoteBase . 'seed_student_fields_fix.php';
    echo "Uploading seeder script... ";
    if ($sftp->put($remoteFile, $localFile, SFTP::SOURCE_LOCAL_FILE)) {
        echo "✅ OK\n";
    } else {
        throw new Exception("Upload failed!");
    }

    // Run via artisan tinker
    echo "\nRunning seeder remotely via artisan tinker...\n";
    echo "--------------------------------------------\n";
    $output = $sftp->exec("cd {$remoteBase} && php artisan tinker seed_student_fields_fix.php 2>&1");
    echo $output . "\n";

    // Clear cache and clean up
    echo "--------------------------------------------\n";
    echo "Clearing cache... ";
    $sftp->exec("cd {$remoteBase} && php artisan cache:clear && php artisan view:clear 2>&1");
    echo "✅ Done\n";

    echo "\nRemoving temp seeder from server... ";
    $sftp->exec("rm {$remoteBase}seed_student_fields_fix.php");
    echo "✅ Done\n";

    echo "\n✨ Fix Complete! Visit https://erpv2.test-technoprint.online/student-settings\n";

} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
