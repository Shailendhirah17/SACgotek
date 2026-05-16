<?php
$zipFile = 'hostinger_deploy.zip';
$extractPath = './';

if (file_exists($zipFile)) {
    $zip = new ZipArchive;
    if ($zip->open($zipFile) === TRUE) {
        $zip->extractTo($extractPath);
        $zip->close();
        echo "Extraction successful!";
        // Optional: delete zip after extraction
        // unlink($zipFile);
    } else {
        echo "Failed to open zip file.";
    }
} else {
    echo "Zip file not found.";
}
?>
