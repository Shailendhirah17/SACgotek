<?php
/**
 * Database Mock Data Wiper (Robust Version)
 * Removes Students, Staff (except Super Admin), Parents, Classes, Sections, etc.
 * Run: php artisan tinker db_mock_wiper.php
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "🧹 Starting Database Mock Data Cleanup (Robust Mode)...\n";

$schoolId = 1;

// 1. Tables to Wipe (if they exist)
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
            DB::table($table)->where('school_id', $schoolId)->delete();
            echo "✅ Wiped: $table\n";
        } catch (\Exception $e) {
            echo "⚠️ Error wiping $table: " . $e->getMessage() . "\n";
        }
    } else {
        echo "ℹ️ Table missing (skipping): $table\n";
    }
}

// 2. Wipe Users (except Role 1)
echo "📍 Cleaning up Users (except Super Admin)...\n";
DB::table('users')->where('role_id', '!=', 1)->where('school_id', $schoolId)->delete();
echo "✅ Users cleaned.\n";

echo "\n✨ Database Cleanup Complete! Please refresh your dashboard.\n";
