<?php

namespace Database\Seeders;

use App\User;
use App\SmClass;
use App\SmSchool;
use App\SmSection;
use App\SmStudent;
use App\SmSubject;
use App\SmAcademicYear;
use App\SmClassSection;
use App\SmStudentCategory;
use App\Models\SmStudentRegistrationField;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class MockupDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Log::info('🚀 Starting MockupDataSeeder execution...');

        // 1. Create Mock School (Tenant)
        $school = SmSchool::updateOrCreate(
            ['email' => 'school_owner@mock.edu'],
            [
                'school_name' => 'Mock Academic Foundation',
                'address' => '123 Tech Avenue, Innovation City',
                'phone' => '1234567890',
                'is_email_verified' => 1,
                'active_status' => 1,
            ]
        );
        $schoolId = $school->id;
        Log::info("✅ School Created: $school->school_name (ID: $schoolId)");

        // 2. Academic Year
        $academicYear = SmAcademicYear::updateOrCreate(
            ['school_id' => $schoolId, 'year' => date('Y')],
            [
                'title' => date('Y') . '-' . (date('Y') + 1),
                'starting_date' => date('Y') . '-01-01',
                'ending_date' => date('Y') . '-12-31',
                'active_status' => 1,
                'created_at' => now(),
            ]
        );
        $academicId = $academicYear->id;

        // 3. Super Admin for the School
        $adminUser = User::updateOrCreate(
            ['email' => 'school_owner@mock.edu'],
            [
                'full_name' => 'School Owner Admin',
                'username' => 'mock_admin',
                'password' => Hash::make('123456'),
                'role_id' => 1,
                'school_id' => $schoolId,
                'active_status' => 1,
            ]
        );

        // 4. Student Registration Fields (Crucial for UI)
        $request_fields = [
            'session', 'class', 'section', 'roll_number', 'admission_number', 'first_name', 'last_name', 'gender', 'date_of_birth',
            'blood_group', 'email_address', 'caste', 'phone_number', 'religion', 'admission_date', 'student_category_id',
            'student_group_id', 'height', 'weight', 'photo', 'fathers_name', 'fathers_occupation', 'fathers_phone',
            'fathers_photo', 'mothers_name', 'mothers_occupation', 'mothers_phone', 'mothers_photo', 'guardians_name',
            'guardians_email', 'guardians_photo', 'guardians_phone', 'guardians_occupation', 'guardians_address',
            'current_address', 'permanent_address', 'route', 'vehicle', 'dormitory_name', 'room_number',
            'national_id_number', 'local_id_number', 'bank_account_number', 'bank_name', 'previous_school_details',
            'additional_notes', 'ifsc_code', 'document_file_1', 'document_file_2', 'document_file_3', 'document_file_4', 'custom_field',
        ];

        foreach ($request_fields as $key => $value) {
            DB::table('sm_student_registration_fields')->updateOrInsert(
                ['field_name' => $value, 'school_id' => $schoolId],
                ['position' => $key + 1, 'label_name' => $value, 'type' => 1, 'is_show' => 1, 'active_status' => 1]
            );
        }

        $required_fields = ['session', 'class', 'section', 'first_name', 'last_name', 'gender', 'date_of_birth', 'relation', 'guardians_email', 'phone_number'];
        DB::table('sm_student_registration_fields')->where('school_id', $schoolId)->whereIn('field_name', $required_fields)->update(['is_required' => 1, 'is_system_required' => 1]);

        // 4b. Seed Currencies
        $currencies = [['Dollars', 'USD', '$'], ['Rupees', 'INR', '₹']];
        foreach ($currencies as $currency) {
            DB::table('sm_currencies')->updateOrInsert(
                ['school_id' => $schoolId, 'name' => $currency[0]],
                ['code' => $currency[1], 'symbol' => $currency[2], 'active_status' => 1]
            );
        }

        // 4c. Background Settings
        $bgSettings = [
            ['title' => 'Dashboard Background', 'image' => 'public/backEnd/img/body-bg.jpg'],
            ['title' => 'Login Background', 'image' => 'public/backEnd/img/login-bg.jpg']
        ];
        foreach ($bgSettings as $bg) {
            DB::table('sm_background_settings')->updateOrInsert(
                ['school_id' => $schoolId, 'title' => $bg['title']],
                ['type' => 'image', 'image' => $bg['image'], 'is_default' => 1]
            );
        }

        // 5. Academic Foundation: Sections & Classes
        $sections = ['A', 'B'];
        $sectionIds = [];
        foreach ($sections as $sName) {
            $section = SmSection::updateOrCreate(
                ['section_name' => $sName, 'school_id' => $schoolId],
                ['active_status' => 1, 'academic_id' => $academicId]
            );
            $sectionIds[] = $section->id;
        }

        $classes = ['Primary 1', 'Primary 2', 'Primary 3'];
        $classData = [];
        foreach ($classes as $cName) {
            $class = SmClass::updateOrCreate(
                ['class_name' => $cName, 'school_id' => $schoolId],
                ['active_status' => 1, 'academic_id' => $academicId]
            );
            
            foreach ($sectionIds as $sId) {
                SmClassSection::updateOrCreate(
                    ['class_id' => $class->id, 'section_id' => $sId, 'school_id' => $schoolId],
                    ['academic_id' => $academicId]
                );
            }
            $classData[] = $class;
        }

        // 6. Subjects
        $subjects = ['Mathematics', 'English', 'Science', 'History'];
        $subjectIds = [];
        foreach ($subjects as $sub) {
            $subject = SmSubject::updateOrCreate(
                ['subject_name' => $sub, 'school_id' => $schoolId],
                ['subject_type' => 'T', 'active_status' => 1]
            );
            $subjectIds[] = $subject->id;
        }

        // 7. Staff & Teachers
        $teacherNames = ['John Doe', 'Jane Smith', 'Robert Brown', 'Emily Davis', 'Michael Wilson'];
        foreach ($teacherNames as $index => $tName) {
            $tEmail = "teacher" . ($index + 1) . "@mock.edu";
            $user = User::updateOrCreate(
                ['email' => $tEmail],
                [
                    'full_name' => $tName,
                    'password' => Hash::make('123456'),
                    'role_id' => 4,
                    'school_id' => $schoolId,
                    'active_status' => 1,
                ]
            );

            DB::table('sm_staffs')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'full_name' => $tName,
                    'first_name' => explode(' ', $tName)[0],
                    'last_name' => explode(' ', $tName)[1] ?? '',
                    'email' => $tEmail,
                    'role_id' => 4,
                    'school_id' => $schoolId,
                    'staff_no' => 100 + $index,
                    'active_status' => 1,
                ]
            );
        }

        // 8. Students & Parents (Using our updated SmStudentsTableSeeder logic concept)
        Log::info("👨‍🎓 Generating students and parents...");
        $studentCount = 0;
        foreach ($classData as $class) {
            foreach ($sectionIds as $sectionId) {
                for ($i = 1; $i <= 5; $i++) {
                    $studentCount++;
                    $name = "Mock Student " . $studentCount;
                    $sEmail = "student" . $studentCount . "@mock.edu";
                    $pEmail = "parent" . $studentCount . "@mock.edu";

                    // Create Parent User
                    $parentUser = User::updateOrCreate(
                        ['email' => $pEmail],
                        [
                            'full_name' => "Parent of " . $name,
                            'password' => Hash::make('123456'),
                            'role_id' => 3,
                            'school_id' => $schoolId,
                        ]
                    );

                    $parentId = DB::table('sm_parents')->insertGetId([
                        'user_id' => $parentUser->id,
                        'fathers_name' => "Mr. Parent " . $studentCount,
                        'guardians_name' => "Mr. Parent " . $studentCount,
                        'school_id' => $schoolId,
                        'academic_id' => $academicId,
                        'created_at' => now(),
                    ]);

                    // Create Student User
                    $studentUser = User::updateOrCreate(
                        ['email' => $sEmail],
                        [
                            'full_name' => $name,
                            'password' => Hash::make('123456'),
                            'role_id' => 2,
                            'school_id' => $schoolId,
                        ]
                    );

                    SmStudent::updateOrCreate(
                        ['user_id' => $studentUser->id],
                        [
                            'full_name' => $name,
                            'first_name' => "Mock",
                            'last_name' => "Student " . $studentCount,
                            'email' => $sEmail,
                            'parent_id' => $parentId,
                            'role_id' => 2,
                            'school_id' => $schoolId,
                            'academic_id' => $academicId,
                            'class_id' => $class->id,
                            'section_id' => $sectionId,
                            'admission_no' => 202400 + $studentCount,
                            'roll_no' => $studentCount,
                            'active_status' => 1,
                        ]
                    );
                }
            }
        }

        Log::info("✨ MockupDataSeeder completed successfully. Total Students: $studentCount");
    }
}
