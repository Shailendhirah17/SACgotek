<?php
// surgical_fixer.php - Improved version

$files = [
    'app/Http/Controllers/SmStudentAdmissionController.php',
    'app/Http/Controllers/Admin/FeesCollection/SmFeesCarryForwardController.php',
    'app/Http/Controllers/Admin/SchoolExtensionController.php',
    'app/Http/Controllers/Student/SmFeesController.php',
    'app/Http/Controllers/SmFeesController.php',
    'app/Http/Controllers/Admin/Hr/SmStaffController.php',
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    
    echo "Processing $file...\n";
    $content = file_get_contents($file);
    
    // Normalize newlines to LF for processing (we'll keep CRLF on write if it was there)
    $hasCRLF = strpos($content, "\r\n") !== false;
    $content = str_replace("\r\n", "\n", $content);

    // Pattern 1: Toastr::error('Operation Failed', 'Failed');
    // Flexible spacing and optional whitespace after {
    $pattern1 = "/(\n\s*)\} catch \((Exception|Throwable) \\\$exception\) \{\n\s*Toastr::error\('Operation Failed', 'Failed'\);/i";
    $replacement1 = "$1} catch ($2 \$exception) {\n$1    \Illuminate\Support\Facades\Log::error(\$exception);\n$1    Toastr::error('Operation Failed: ' . \$exception->getMessage(), 'Failed');";
    
    $count = 0;
    $newContent = preg_replace($pattern1, $replacement1, $content, -1, $count);
    echo "  Applied Pattern 1 to $count places\n";

    // Pattern 2: Generic session flash
    $pattern2 = "/Session::flash\('message-danger', 'Something went wrong, please try again'\);/i";
    $replacement2 = "\Illuminate\Support\Facades\Log::error('Generic error triggered'); Session::flash('message-danger', 'Something went wrong, please try again');";
    $newContent = preg_replace($pattern2, $replacement2, $newContent, -1, $count);
    echo "  Applied Pattern 2 to $count places\n";

    if ($hasCRLF) {
        $newContent = str_replace("\n", "\r\n", $newContent);
    }
    
    file_put_contents($file, $newContent);
}
echo "Done.\n";
