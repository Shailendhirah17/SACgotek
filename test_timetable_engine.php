<?php
// Run this via: php artisan tinker test_timetable_engine.php

use Modules\AutomatedTimetable\Services\TimetableGeneratorService;
use Illuminate\Support\Facades\DB;

echo "🧪 Starting Automated Timetable Logic Test...\n";

try {
    // 1. Find a valid class and section with assigned subjects to test
    $assigned = DB::table('sm_assign_subjects')
        ->whereNotNull('teacher_id')
        ->where('school_id', 1)
        ->first();

    if (!$assigned) {
        echo "⚠️ No assigned subjects found in DB to test. Creating dummy data...\n";
        // Create dummy class, section, subject, teacher, and assign subject
        // For now, assume it exists since it's a functioning school DB
        die("Please assign a teacher to a subject first in the ERP.\n");
    }

    $class_id = $assigned->class_id;
    $section_id = $assigned->section_id;

    $className = DB::table('sm_classes')->where('id', $class_id)->value('class_name');
    $sectionName = DB::table('sm_sections')->where('id', $section_id)->value('section_name');

    echo "✅ Found Test Subject: Class '$className' - Section '$sectionName' (IDs: $class_id, $section_id)\n";

    // 2. Initialize Service and Run Generate
    $service = new TimetableGeneratorService();
    // Override auth checks in service for CLI execution if needed by mocking, 
    // but the service uses @auth()->user()->school_id ?? 1 so it defaults to 1 cleanly.
    
    echo "⚙️ Running CSP Algorithm...\n";
    $result = $service->generate($class_id, $section_id);

    // 3. Evaluate Results
    if ($result['status']) {
        echo "🎉 SUCCESS: " . $result['message'] . "\n";
        echo "📊 Unallocated Periods: " . $result['unallocated'] . "\n\n";
        
        // 4. Verify DB Output
        $count = DB::table('sm_class_routine_updates')
            ->where('class_id', $class_id)
            ->where('section_id', $section_id)
            ->count();
            
        echo "✅ Generated and persisted $count period records successfully in 'sm_class_routine_updates'.\n";

        // Check for double bookings (same teacher, same day, same period)
        $conflicts = DB::table('sm_class_routine_updates')
            ->select('day', 'class_period_id', 'teacher_id', DB::raw('count(*) as total'))
            ->whereNotNull('class_period_id')
            ->groupBy('day', 'class_period_id', 'teacher_id')
            ->having('total', '>', 1)
            ->get();

        if ($conflicts->count() > 0) {
            echo "❌ CRITICAL ERROR: Algorithm produced double bookings for teachers:\n";
            print_r($conflicts->toArray());
        } else {
            echo "✅ ZERO Logical Conflicts: No teacher is double-booked.\n";
        }
        
    } else {
        echo "❌ ALGORITHM FAILED: " . $result['message'] . "\n";
    }

} catch (\Exception $e) {
    echo "❌ EXCEPTION THROWN: " . $e->getMessage() . " on line " . $e->getLine() . " in " . $e->getFile() . "\n";
}

echo "🧪 Test Complete.\n";
