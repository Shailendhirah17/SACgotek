<?php
/**
 * Smart Auto-Attendance Cron Job
 * Run daily at 00:05 via server CRON (e.g. 5 0 * * * php /path/to/artisan tinker automatic_attendance_cron.php)
 * Logic: Finds active students without an attendance record for today. 
 *        Marks them as 'P' (Present) automatically. 
 *        Teachers then only need to manually mark Absentees ('A' or 'L').
 */

use Illuminate\Support\Facades\DB;

echo "🤖 Starting Smart Auto-Attendance Sequence...\n";

try {
    $today = date('Y-m-d');
    $school_id = 1; // Defaulting to main school
    $academic_id = 1;

    // 1. Fetch all active students
    $students = DB::table('sm_students')
        ->where('active_status', 1)
        ->where('school_id', $school_id)
        ->get();

    $presentCount = 0;
    $skippedCount = 0;

    foreach ($students as $student) {
        // 2. Check if an attendance record already exists for today
        $exists = DB::table('sm_student_attendances')
            ->where('student_id', $student->id)
            ->where('attendance_date', $today)
            ->where('school_id', $school_id)
            ->exists();

        if (!$exists) {
            // 3. Mark as Present (P)
            DB::table('sm_student_attendances')->insert([
                'student_id' => $student->id,
                'class_id' => $student->class_id,
                'section_id' => $student->section_id,
                'attendance_type' => 'P',
                'notes' => 'Auto-Marked by System',
                'attendance_date' => $today,
                'school_id' => $school_id,
                'academic_id' => $academic_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $presentCount++;
        } else {
            $skippedCount++;
        }
    }

    echo "✅ Auto-Attendance Complete for $today.\n";
    echo "📊 Automatically Marked Present: $presentCount students.\n";
    echo "⏭️ Skipped (Already Marked/Absent): $skippedCount students.\n";

} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
