<?php
/**
 * Student Settings Field Seeder Fix
 * Seeds missing sm_student_registration_fields for all schools
 * Run via: php artisan tinker seed_student_fields_fix.php
 */

use App\SmSchool;
use App\Models\SmStudentRegistrationField;

$request_fields = [
    'session', 'class', 'section', 'roll_number', 'admission_number',
    'first_name', 'last_name', 'gender', 'date_of_birth', 'blood_group',
    'email_address', 'caste', 'phone_number', 'religion', 'admission_date',
    'student_category_id', 'student_group_id', 'height', 'weight', 'photo',
    'fathers_name', 'fathers_occupation', 'fathers_phone', 'fathers_photo',
    'mothers_name', 'mothers_occupation', 'mothers_phone', 'mothers_photo',
    'guardians_name', 'guardians_email', 'guardians_photo', 'guardians_phone',
    'guardians_occupation', 'guardians_address', 'current_address',
    'permanent_address', 'route', 'vehicle', 'dormitory_name', 'room_number',
    'national_id_number', 'local_id_number', 'bank_account_number', 'bank_name',
    'previous_school_details', 'additional_notes', 'ifsc_code',
    'document_file_1', 'document_file_2', 'document_file_3', 'document_file_4',
    'custom_field',
];

$required_fields = ['session', 'class', 'section', 'first_name', 'last_name', 'gender', 'date_of_birth', 'relation', 'guardians_email', 'phone_number'];
$student_edit    = ['roll_number', 'first_name', 'last_name', 'gender', 'date_of_birth', 'phone_number', 'email_address'];
$parent_edit     = ['first_name', 'last_name', 'gender', 'date_of_birth', 'phone_number', 'email_address', 'fathers_name', 'fathers_occupation', 'fathers_phone', 'fathers_photo', 'mothers_name', 'mothers_occupation', 'mothers_phone', 'mothers_photo', 'guardians_name', 'guardians_email', 'guardians_photo', 'guardians_phone', 'guardians_occupation', 'guardians_address', 'current_address', 'permanent_address'];

$all_schools = SmSchool::all();
$totalInserted = 0;

foreach ($all_schools as $school) {
    foreach ($request_fields as $key => $fieldName) {
        $exists = SmStudentRegistrationField::where('school_id', $school->id)
            ->where('field_name', $fieldName)->exists();

        if (!$exists) {
            $field = new SmStudentRegistrationField;
            $field->position   = $key + 1;
            $field->field_name = $fieldName;
            $field->label_name = $fieldName;
            $field->type       = 1;
            $field->school_id  = $school->id;
            $field->is_show    = 1;
            $field->active_status = 1;
            $field->save();
            $totalInserted++;
            echo "  + Inserted: [{$school->school_name}] → $fieldName\n";
        }
    }

    // Set required, student_edit, parent_edit flags
    SmStudentRegistrationField::where('school_id', $school->id)
        ->whereIn('field_name', $required_fields)
        ->update(['is_required' => 1, 'is_system_required' => 1]);

    SmStudentRegistrationField::where('school_id', $school->id)
        ->whereIn('field_name', $student_edit)
        ->update(['student_edit' => 1]);

    SmStudentRegistrationField::where('school_id', $school->id)
        ->whereIn('field_name', $parent_edit)
        ->update(['parent_edit' => 1]);

    echo "✅ School [{$school->school_name}] processed.\n";
}

echo "\n✨ Done! Total new fields inserted: $totalInserted\n";
