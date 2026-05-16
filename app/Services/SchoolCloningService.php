<?php

namespace App\Services;

use App\SmSchool;
use App\Models\SchoolGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchoolCloningService
{
    /**
     * Clone an existing school's core structure to a new branch for the same group.
     * 
     * @param int $sourceSchoolId
     * @param array $newSchoolData (name, email, domain, etc.)
     * @return SmSchool|bool
     */
    public function cloneSchool($sourceSchoolId, array $newSchoolData)
    {
        DB::beginTransaction();
        try {
            $sourceSchool = SmSchool::findOrFail($sourceSchoolId);

            // 1. Create the new school record based on the source attributes
            $newSchool = new SmSchool();
            $newSchool->school_name = $newSchoolData['school_name'] ?? $sourceSchool->school_name . ' - New Branch';
            $newSchool->email = $newSchoolData['email'] ?? 'admin_' . time() . '@' . ($newSchoolData['domain'] ?? 'example.com');
            $newSchool->school_code = $newSchoolData['school_code'] ?? 'SCH' . rand(1000, 9999);
            $newSchool->school_group_id = $sourceSchool->school_group_id;
            
            // Geographic data (optional, passed in)
            $newSchool->latitude = $newSchoolData['latitude'] ?? null;
            $newSchool->longitude = $newSchoolData['longitude'] ?? null;
            $newSchool->state_id = $newSchoolData['state_id'] ?? null;
            $newSchool->city_id = $newSchoolData['city_id'] ?? null;

            // Copy important non-unique settings
            $newSchool->phone = $newSchoolData['phone'] ?? $sourceSchool->phone;
            $newSchool->address = $newSchoolData['address'] ?? $sourceSchool->address;
            $newSchool->currency_id = $sourceSchool->currency_id;
            $newSchool->currency_symbol = $sourceSchool->currency_symbol;
            $newSchool->system_domain = $newSchoolData['domain'] ?? env('APP_URL');
            $newSchool->active_status = 1;
            
            // General setup defaults copied
            $newSchool->starting_date = now()->format('Y-m-d');
            $newSchool->is_email_verify = $sourceSchool->is_email_verify ?? 0;
            $newSchool->payment_gateway = $sourceSchool->payment_gateway;

            $newSchool->save();

            // 2. Clone essential related data (Academic Years, Roles, Settings)
            $this->cloneAcademicYears($sourceSchool->id, $newSchool->id);
            $this->cloneGeneralSettings($sourceSchool->id, $newSchool->id);
            // $this->cloneRoles($sourceSchool->id, $newSchool->id);

            DB::commit();
            return $newSchool;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Template/School Cloning Failed: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Recursively clone Academic Years and Terms.
     */
    private function cloneAcademicYears($sourceId, $targetId)
    {
        if (DB::getSchemaBuilder()->hasTable('sm_academic_years')) {
            $years = DB::table('sm_academic_years')->where('school_id', $sourceId)->get();
            $mappedYears = [];
            foreach ($years as $year) {
                $newYearId = DB::table('sm_academic_years')->insertGetId([
                    'year' => $year->year,
                    'title' => $year->title,
                    'starting_date' => $year->starting_date,
                    'ending_date' => $year->ending_date,
                    'school_id' => $targetId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $mappedYears[$year->id] = $newYearId;
            }
            
            // Map the active academic year to the newly created school record
            $sourceSchool = SmSchool::find($sourceId);
            if ($sourceSchool && isset($mappedYears[$sourceSchool->academic_id])) {
                SmSchool::where('id', $targetId)->update(['academic_id' => $mappedYears[$sourceSchool->academic_id]]);
            }
        }
    }

    /**
     * Clone General Settings.
     */
    private function cloneGeneralSettings($sourceId, $targetId)
    {
        if (DB::getSchemaBuilder()->hasTable('sm_general_settings')) {
            $settings = DB::table('sm_general_settings')->where('school_id', $sourceId)->first();
            if ($settings) {
                $newSettings = (array) $settings;
                unset($newSettings['id']);
                $newSettings['school_id'] = $targetId;
                // You might need to update the domain/url string inside the settings
                DB::table('sm_general_settings')->insert($newSettings);
            }
        }
    }
}
