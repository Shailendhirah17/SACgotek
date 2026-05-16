<?php
require __DIR__ . '/vendor/autoload.php';
use phpseclib3\Net\SFTP;

$sftp = new SFTP('193.202.45.164', 65002);
$sftp->login('u841409365', 'Eash@2005');
$base = 'domains/test-technoprint.online/public_html/erpv2/';

echo $sftp->exec("cd $base && php artisan cache:clear && php artisan view:clear && touch public/.htaccess 2>&1");
echo "\nDone!\n";
