<?php
/**
 * Multi-School UI Visibility Fix
 * Synchronizes Academic IDs and initializes General Settings for all schools.
 * Run: php artisan tinker fix_ui_visibility.php
 */

use Illuminate\Support\Facades\DB;

echo "🔧 Starting Multi-School UI Visibility Fix...\n";

$targetAcademicId = 2; // The one School 1 is using
$targetSessionId = 2;

// 1. Fix School 1's Data Alignment
echo "📍 Aligning School 1 data to Academic ID $targetAcademicId...\n";
DB::table('sm_students')->where('school_id', 1)->update(['academic_id' => $targetAcademicId, 'session_id' => $targetSessionId]);
DB::table('sm_staffs')->where('school_id', 1)->update(['active_status' => 1]); // Ensure staff are active
DB::table('sm_classes')->where('school_id', 1)->update(['academic_id' => $targetAcademicId]);
DB::table('sm_sections')->where('school_id', 1)->update(['academic_id' => $targetAcademicId]);
DB::table('sm_subjects')->where('school_id', 1)->update(['academic_id' => $targetAcademicId]);
DB::table('sm_class_sections')->where('school_id', 1)->update(['academic_id' => $targetAcademicId]);
echo "✅ School 1 data aligned.\n";

// 2. Initialize General Settings for Schools 2-7
echo "📍 Initializing General Settings for Schools 2-7...\n";
$baseSettings = (array) DB::table('sm_general_settings')->where('school_id', 1)->first();
unset($baseSettings['id']); // Remove ID to allow auto-increment

$schools = DB::table('sm_schools')->where('id', '>', 1)->pluck('id')->toArray();
foreach ($schools as $schoolId) {
    if (!DB::table('sm_general_settings')->where('school_id', $schoolId)->exists()) {
        $newSettings = $baseSettings;
        $newSettings['school_id'] = $schoolId;
        $newSettings['academic_id'] = $targetAcademicId;
        $newSettings['session_id'] = $targetSessionId;
        $newSettings['created_at'] = now();
        $newSettings['updated_at'] = now();
        DB::table('sm_general_settings')->insert($newSettings);
        echo "✅ Initialized Settings for School $schoolId.\n";
    } else {
        DB::table('sm_general_settings')->where('school_id', $schoolId)->update([
            'academic_id' => $targetAcademicId,
            'session_id' => $targetSessionId
        ]);
        echo "✅ Updated Settings for School $schoolId.\n";
    }
    
    // Ensure Academic IDs for data in these schools also match
    DB::table('sm_students')->where('school_id', $schoolId)->update(['academic_id' => $targetAcademicId, 'session_id' => $targetSessionId]);
    DB::table('sm_classes')->where('school_id', $schoolId)->update(['academic_id' => $targetAcademicId]);
    DB::table('sm_sections')->where('school_id', $schoolId)->update(['academic_id' => $targetAcademicId]);
    DB::table('sm_subjects')->where('school_id', $schoolId)->update(['academic_id' => $targetAcademicId]);
    DB::table('sm_class_sections')->where('school_id', $schoolId)->update(['academic_id' => $targetAcademicId]);
}

echo "\n✨ Multi-School UI Visibility Fix Complete! Please refresh your browser.\n";
