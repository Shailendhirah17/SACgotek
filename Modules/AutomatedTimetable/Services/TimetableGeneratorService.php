<?php

namespace Modules\AutomatedTimetable\Services;

use Illuminate\Support\Facades\DB;
use Exception;

class TimetableGeneratorService
{
    protected $school_id;
    protected $academic_id;

    public function __construct()
    {
        $this->school_id = auth()->check() ? auth()->user()->school_id : 1;
        $this->academic_id = auth()->check() ? auth()->user()->academic_id : 1;
    }

    public function generate($class_id, $section_id)
    {
        DB::beginTransaction();
        try {
            // 1. Clear existing routine
            DB::table('sm_class_routine_updates')
                ->where('class_id', $class_id)
                ->where('section_id', $section_id)
                ->where('school_id', $this->school_id)
                ->delete();

            // 2. Fetch assigned subjects
            $assignedSubjects = DB::table('sm_assign_subjects')
                ->where('class_id', $class_id)
                ->where('section_id', $section_id)
                ->where('school_id', $this->school_id)
                ->whereNotNull('teacher_id')
                ->get();

            if ($assignedSubjects->isEmpty()) { throw new Exception("No assigned subjects."); }

            // 3. Fetch Dynamic Rules
            $dynamicRules = DB::table('timetable_rules')
                ->where('class_id', $class_id)
                ->where('section_id', $section_id)
                ->where('school_id', $this->school_id)
                ->get()->keyBy('subject_id');

            // 4. Build Pool
            $pool = [];
            foreach ($assignedSubjects as $subj) {
                $required = $dynamicRules[$subj->subject_id]->required_periods ?? 6; 
                for ($i = 0; $i < $required; $i++) {
                    $pool[] = [
                        'subject_id' => $subj->subject_id,
                        'teacher_id' => $subj->teacher_id
                    ];
                }
            }

            // 5. Fetch Slots & Days
            $classTimes = DB::table('sm_class_times')->where('is_break', 0)->where('type', 'class')->where('school_id', $this->school_id)->orderBy('start_time')->get();
            $weekDays = DB::table('sm_weekends')->where('is_weekend', 0)->where('school_id', $this->school_id)->orderBy('order')->pluck('id')->toArray();
            if (empty($weekDays)) { $weekDays = [3, 4, 5, 6, 7]; }
            $lunchBreakId = DB::table('sm_class_times')->where('is_break', 1)->where('school_id', $this->school_id)->value('id') ?? 3;

            // 6. Constraint Engine
            $schedule = [];
            shuffle($pool);

            foreach ($weekDays as $day) {
                $dailySubjectCounts = [];
                foreach ($classTimes as $time) {
                    if (empty($pool)) break 2;
                    $slotted = false;
                    foreach ($pool as $idx => $item) {
                        
                        // FETCH SPECIFIC RULE FOR THIS SUBJECT
                        $rule = $dynamicRules[$item['subject_id']] ?? null;
                        
                        // RULE 1: Max periods per day (User Configurable)
                        $maxDay = $rule->max_periods_per_day ?? 2;
                        if (($dailySubjectCounts[$item['subject_id']] ?? 0) >= $maxDay) continue;

                        // RULE 2: Morning only (User Configurable)
                        $isAfterLunch = $time->id > $lunchBreakId;
                        if ($isAfterLunch && ($rule->is_morning_only ?? 0)) continue;

                        $isTeacherBusy = false; 

                        if (!$isTeacherBusy) {
                            $schedule[] = [
                                'day' => $day,
                                'class_period_id' => $time->id,
                                'start_time' => $time->start_time,
                                'end_time' => $time->end_time,
                                'subject_id' => $item['subject_id'],
                                'teacher_id' => $item['teacher_id'],
                                'class_id' => $class_id,
                                'section_id' => $section_id,
                                'room_id' => 1,
                                'is_break' => 0,
                                'school_id' => $this->school_id,
                                'academic_id' => $this->academic_id,
                                'created_at' => now(), 'updated_at' => now()
                            ];

                            $dailySubjectCounts[$item['subject_id']] = ($dailySubjectCounts[$item['subject_id']] ?? 0) + 1;
                            DB::table('sm_class_routine_updates')->insert(end($schedule));
                            unset($pool[$idx]);
                            $pool = array_values($pool);
                            $slotted = true;
                            break;
                        }
                    }
                }
            }

            DB::commit();
            return ['status' => true, 'message' => 'Routine generated based on your custom rules!'];

        } catch (Exception $e) { DB::rollBack(); return ['status' => false, 'message' => $e->getMessage()]; }
    }
}
