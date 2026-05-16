<?php
/**
 * ERP Extension Modules Live Deployment Script
 * Unzips extension_modules_update.zip, runs raw DB migrations if needed,
 * and clears Laravel caches.
 * 
 * Usage:
 * 1. Upload extension_modules_update.zip to public_html/erpv2/
 * 2. Upload apply_extension_update.php to public_html/erpv2/
 * 3. Hit https://erpv2.test-technoprint.online/apply_extension_update.php in browser
 */

$log = [];
$log[] = "Starting Deployment at " . date('Y-m-d H:i:s');

// 1. Unzip Files
$zipPath = __DIR__ . '/extension_modules_update.zip';
if (file_exists($zipPath)) {
    $zip = new ZipArchive;
    $res = $zip->open($zipPath);
    if ($res === TRUE) {
        $zip->extractTo(__DIR__);
        $zip->close();
        $log[] = "Successfully extracted extension_modules_update.zip";
    } else {
        $log[] = "Failed to extract zip file. Error code: $res";
    }
} else {
    $log[] = "Warning: extension_modules_update.zip not found. Proceeding with DB/Cache clear.";
}

// 2. Clear Caches
try {
    $artisan = __DIR__ . '/artisan';
    if(file_exists($artisan)) {
        $log[] = "Running php artisan optimize:clear...";
        $output = shell_exec('php ' . $artisan . ' optimize:clear 2>&1');
        $log[] = "Artisan Output: " . $output;

        $log[] = "Running migrations...";
        $output2 = shell_exec('php ' . $artisan . ' migrate --path=database/migrations/2026_04_14_000001_create_school_extension_custom_tables.php --force 2>&1');
        $log[] = "Migration Output: " . $output2;
    } else {
        $log[] = "Warning: artisan file not found, skipping artisan commands.";
    }
} catch (Exception $e) {
    $log[] = "Error clearing cache: " . $e->getMessage();
}

echo "<h3>Deployment Log</h3><ul>";
foreach($log as $line) {
    echo "<li>" . nl2br(htmlspecialchars($line)) . "</li>";
}
echo "</ul><hr />";
echo "<b>Done!</b> <a href='/'>Return to Application</a>";
