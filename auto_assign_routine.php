<?php
/**
 * Automated Timetable Allocation Script (Tinker)
 * Automatically assigns subjects and teachers into Period 1-7 for Class 87, Section 26.
 * Run: php auto_assign_routine.php
 */

// Bootstrap Laravel application
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Modules\AutomatedTimetable\Services\TimetableGeneratorService;
use Illuminate\Support\Facades\DB;

echo "🚦 Starting Automated Timetable Allocation for Class 1, Section 1...\n";

$class_id = 1;
$section_id = 1;

// 1. Initial Checks
$classExists = DB::table('sm_classes')->where('id', $class_id)->exists();
$sectionExists = DB::table('sm_sections')->where('id', $section_id)->exists();

if (!$classExists || !$sectionExists) {
    echo "❌ Error: Class ID $class_id or Section ID $section_id missing.\n";
    exit;
}

// 2. Initialize Service and Generate
try {
    $service = new TimetableGeneratorService();
    $result = $service->generate($class_id, $section_id);

    if ($result['status']) {
        echo "✅ Timetable generated successfully for Class 87, Section 26.\n";
        if (isset($result['unallocated']) && $result['unallocated'] > 0) {
            echo "⚠️ Warning: " . $result['unallocated'] . " periods could not be allocated due to constraints.\n";
        }
    } else {
        echo "❌ Service Error: " . ($result['message'] ?? 'Unknown error') . "\n";
    }
} catch (\Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
    echo "  Trace: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}

// 3. Verify counts in sm_class_routine_updates
$count = DB::table('sm_class_routine_updates')
    ->where('class_id', $class_id)
    ->where('section_id', $section_id)
    ->count();

echo "📝 Total Assigned Periods in Routine: $count\n";

echo "\n✨ Allocation Complete! Please refresh your browser.\n";
