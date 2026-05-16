<?php
/**
 * Global Database Mock Data Wiper (Across ALL Schools)
 * Removes Students, Staff (except Role 1), Parents, Academics, etc. from EVERY school.
 * Run: php artisan tinker db_mock_wiper_global.php
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "🌎 Starting GLOBAL Database Mock Data Cleanup (All Schools)...\n";

$tables = [
    'sm_students', 'sm_student_attendances', 'sm_student_records', 'sm_assign_students',
    'sm_parents', 'sm_staffs', 'sm_staff_attendances', 'sm_staff_payrolls',
    'sm_classes', 'sm_sections', 'sm_class_sections', 'sm_subjects', 'sm_assign_subjects', 'sm_assign_class_teachers',
    'sm_fees_masters', 'sm_fees_groups', 'sm_fees_assigns', 'sm_fees_payments',
    'sm_exam_types', 'sm_exam_schedules', 'sm_mark_stores', 'sm_result_stores',
    'sm_homeworks', 'sm_homework_students', 'sm_leave_requests', 'sm_book_issues',
    'sm_student_categories', 'sm_student_groups', 'sm_student_certificates',
    'sm_student_id_cards', 'sm_admission_queries', 'sm_student_promotes'
];

foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        try {
            // Delete ALL records except for Super Admin staff
            if ($table == 'sm_staffs') {
                DB::table($table)->where('role_id', '!=', 1)->delete();
            } else {
                DB::table($table)->delete();
            }
            echo "✅ Wiped: $table (Global)\n";
        } catch (\Exception $e) {
            echo "⚠️ Error wiping $table: " . $e->getMessage() . "\n";
        }
    }
}

// 2. Wipe Users (except Role 1)
echo "📍 Cleaning up GLOBAL Users (except Super Admin)...\n";
DB::table('users')->where('role_id', '!=', 1)->delete();
echo "✅ Users cleaned.\n";

echo "\n✨ GLOBAL Database Cleanup Complete!\n";
