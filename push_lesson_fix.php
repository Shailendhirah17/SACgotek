<?php
/**
 * Lesson Module Fix - SFTP Push
 * Pushes the fixed SmLessonController to fix "Operation Failed" on /lesson
 */
require __DIR__ . '/vendor/autoload.php';

use phpseclib3\Net\SFTP;

$host     = '193.202.45.164';
$port     = 65002;
$username = 'u841409365';
$password = 'Eash@2005';
$remoteBase = 'domains/test-technoprint.online/public_html/erpv2/';

$filesToUpload = [
    'Modules/Lesson/Http/Controllers/SmLessonController.php',
];

echo "=================================================\n";
echo " Lesson Fix - SFTP Push\n";
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

    // Clear view & config cache
    $cmds = [
        "php artisan view:clear"   => "View clear",
        "php artisan cache:clear"  => "Cache clear",
        "php artisan config:clear" => "Config clear",
        "touch public/.htaccess"   => "LiteSpeed bust",
    ];
    echo "\n";
    foreach ($cmds as $cmd => $label) {
        echo "Running: $label ... ";
        $out = trim($sftp->exec("cd $remoteBase && $cmd 2>&1"));
        echo ($out ?: "done") . "\n";
    }

    echo "\n✨ Done! Visit https://erpv2.test-technoprint.online/lesson to verify.\n";

} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
