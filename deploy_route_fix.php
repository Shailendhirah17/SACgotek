<?php
require __DIR__ . '/vendor/autoload.php';

use phpseclib3\Net\SFTP;

$host = '193.202.45.164';
$port = 65002;
$username = 'u841409365';
$password = 'Eash@2005';

$filesToUpload = [
    'routes/superadmin.php' => 'domains/test-technoprint.online/public_html/erpv2/routes/superadmin.php'
];

$remoteDir = 'domains/test-technoprint.online/public_html/erpv2/';

echo "Initializing SFTP connection to $host:$port...\n";

try {
    $sftp = new SFTP($host, $port);

    echo "Authenticating as $username...\n";
    if (!$sftp->login($username, $password)) {
        throw new Exception('Login failed');
    }

    echo "Login successful!\n";

    foreach ($filesToUpload as $local => $remote) {
        $fullLocalPath = __DIR__ . '/' . $local;
        echo "Uploading $local to $remote...\n";
        if (!$sftp->put($remote, $fullLocalPath, SFTP::SOURCE_LOCAL_FILE)) {
            throw new Exception("File upload failed for $local");
        }
        echo "SUCCESS: $local uploaded.\n";
    }

    echo "Clearing remote route cache...\n";
    $output = $sftp->exec("cd $remoteDir && php artisan route:clear");
    echo $output . "\n";
    echo "Route cache cleared successfully!\n";

    echo "\nEmergency Route Fix Complete!\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
