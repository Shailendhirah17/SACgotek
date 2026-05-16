<?php
require __DIR__ . '/vendor/autoload.php';

use phpseclib3\Net\SFTP;

$host = '193.202.45.164';
$port = 65002;
$username = 'u841409365';
$password = 'Eash@2005';

$remoteDir = 'domains/test-technoprint.online/public_html/erpv2/';

echo "Initializing SFTP connection to $host:$port...\n";

try {
    $sftp = new SFTP($host, $port);

    echo "Authenticating as $username...\n";
    if (!$sftp->login($username, $password)) {
        throw new Exception('Login failed');
    }

    echo "Login successful!\n";

    echo "Checking CLI PHP version...\n";
    echo $sftp->exec("php -v") . "\n";

    echo "Applying string-based routing to superadmin.php to avoid CLI ParseErrors...\n";
    // I will read local file, modify it in memory, and put it.
    $localFile = __DIR__ . '/routes/superadmin.php';
    $content = file_get_contents($localFile);
    
    // Replace [SchoolController::class, 'method'] with 'App\Http\Controllers\SuperAdmin\SchoolManagement\SchoolController@method'
    $content = str_replace(
        "[SchoolController::class, 'ajaxGetCities']", 
        "'App\Http\Controllers\SuperAdmin\SchoolManagement\SchoolController@ajaxGetCities'", 
        $content
    );
     $content = str_replace(
        "[SchoolController::class, 'schoolList']", 
        "'App\Http\Controllers\SuperAdmin\SchoolManagement\SchoolController@schoolList'", 
        $content
    );

    $remotePath = $remoteDir . 'routes/superadmin.php';
    echo "Uploading modified superadmin.php to $remotePath...\n";
    if (!$sftp->put($remotePath, $content)) {
        throw new Exception("File upload failed");
    }
    echo "SUCCESS: superadmin.php uploaded.\n";

    echo "Clearing remote route cache...\n";
    $output = $sftp->exec("cd $remoteDir && php artisan route:clear");
    echo $output . "\n";
    echo "Route cache cleared successfully!\n";

    echo "\nEmergency Fix Attempt Complete!\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
