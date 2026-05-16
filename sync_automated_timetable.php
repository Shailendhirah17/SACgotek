<?php
/**
 * Automated Timetable Sync Script (Horizontal Bridge)
 * Flattens vertical data from sm_class_routine_updates into horizontal sm_class_routines.
 * Run: php artisan tinker sync_automated_timetable.php
 */

use Illuminate\Support\Facades\DB;

echo "🔄 Starting Timetable Synchronization (Vertical to Horizontal)...\n";

$class_id = 87;
$section_id = 26;
$school_id = 1;
$academic_id = 2; // Site's active academic year

// 1. Fetch Vertical Data (Module Table)
$updates = DB::table('sm_class_routine_updates')
    ->where('class_id', $class_id)
    ->where('section_id', $section_id)
    ->where('school_id', $school_id)
    ->get();

if ($updates->isEmpty()) {
    echo "ℹ️ No automated routine found in module's table for Class 87.\n";
    exit;
}

// 2. Day Mapping (Vertical IDs to Horizontal Columns)
$dayMap = [
    2 => 'monday',
    3 => 'tuesday',
    4 => 'wednesday',
    5 => 'thursday',
    6 => 'friday',
    7 => 'saturday',
    1 => 'sunday'
];

// 3. Purge existing Horizontal data for this class/section (Core Table)
// Important to prevent duplicates or ghosts
DB::table('sm_class_routines')
    ->where('class_id', $class_id)
    ->where('section_id', $section_id)
    ->where('school_id', $school_id)
    ->delete();

// 4. Flatten and Insert
foreach ($updates as $record) {
    if ($record->is_break) continue;

    $dayKey = $dayMap[$record->day] ?? null;
    if (!$dayKey) continue;

    echo "📍 Mapping Period: {$record->start_time} to $dayKey for Subject {$record->subject_id}...\n";

    DB::table('sm_class_routines')->insert([
        'class_id' => $class_id,
        'section_id' => $section_id,
        'subject_id' => $record->subject_id,
        'school_id' => $school_id,
        'academic_id' => $academic_id,
        'created_at' => now(),
        'updated_at' => now(),
        'created_by' => 1,
        'updated_by' => 1,
        // Day-specific columns
        $dayKey => $record->subject_id,
        "{$dayKey}_start_from" => date('h:i A', strtotime($record->start_time)),
        "{$dayKey}_end_to" => date('h:i A', strtotime($record->end_time)),
        "{$dayKey}_room_id" => $record->room_id ?? 1
    ]);
}

echo "\n✨ Synchronization Complete! Core ERP tables now contain the automated routine.\n";
