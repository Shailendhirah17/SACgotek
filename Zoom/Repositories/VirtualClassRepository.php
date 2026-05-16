<?php

namespace Modules\Zoom\Repositories;

use App\SmStaff;
use App\SmStudent;
use App\SmGeneralSettings;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use MacsiDigital\Zoom\Facades\Zoom;
use Illuminate\Support\Facades\Auth;
use Modules\Zoom\Entities\ZoomSetting;
use Modules\Zoom\Entities\VirtualClass;
use Modules\Zoom\Repositories\Interfaces\VirtualClassRepositoryInterface;

class VirtualClassRepository implements VirtualClassRepositoryInterface
{
    public function classStore($request)
    {
        $schoolUser = SmStaff::where('user_id', $request->teacher_ids)->first();
        $userMail = $schoolUser ? $schoolUser->email : null;

        $str_days_id = null;
        if (!empty($request->days)) {
            $str_days_id = implode(',', $request->days);
        }

        $profile = Zoom::user()->where('status', 'active')->setPaginate(false)->setPerPage(300)->get()->toArray()['data'][0];
        $start_date = Carbon::parse($request->date)->format('Y-m-d') . ' ' . date("H:i:s", strtotime($request->time));
        $GSetting = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();

        $meeting = Zoom::meeting()->make([
            "topic" => $request->topic,
            "type" => $request->is_recurring == 1 ? 8 : 2,
            "duration" => $request->durration,
            "timezone" => $GSetting->timeZone->time_zone,
            "password" => $request->password,
            "start_time" => new Carbon($start_date),
        ]);

        $meeting->settings()->make([
            'join_before_host'  => $request->join_before_host == 1,
            'host_video'        => $request->host_video == 1,
            'participant_video' => $request->participant_video == 1,
            'mute_upon_entry'   => $request->mute_upon_entry == 1,
            'waiting_room'      => $request->waiting_room == 1,
            'audio'             => $request->audio,
            'auto_recording'    => $request->auto_recording ?? 'none',
            'approval_type'     => $request->approval_type,
            'alternative_hosts' => $userMail,
        ]);

        if ($request->is_recurring == 1) {
            $end_date = Carbon::parse($request->recurring_end_date)->endOfDay();
            $meeting->recurrence()->make([
                'type' => $request->recurring_type,
                'repeat_interval' => $request->recurring_repect_day,
                'weekly_days' => $request->recurring_type == 2 ? $str_days_id : null,
                'end_date_time' => $end_date
            ]);
        }

        $meeting_details = Zoom::user()->find($profile['id'])->meetings()->save($meeting);

        DB::beginTransaction();
        try {
            $system_meeting = VirtualClass::create([
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'topic' => $request->topic,
                'description' => $request->description,
                'date_of_meeting' => $request->date,
                'time_of_meeting' => $request->time,
                'meeting_duration' => $request->durration,
                'time_before_start' => $request->time_before_start,
                'host_video' => $request->host_video,
                'participant_video' => $request->participant_video,
                'join_before_host' => $request->join_before_host,
                'mute_upon_entry' => $request->mute_upon_entry,
                'waiting_room' => $request->waiting_room,
                'audio' => $request->audio,
                'auto_recording' => $request->auto_recording ?? 'none',
                'approval_type' => $request->approval_type,
                'is_recurring' => $request->is_recurring,
                'recurring_type' => $request->is_recurring == 1 ? $request->recurring_type : null,
                'recurring_repect_day' => $request->is_recurring == 1 ? $request->recurring_repect_day : null,
                'weekly_days' => $request->recurring_type == 2 ? $str_days_id : null,
                'recurring_end_date' => $request->is_recurring == 1 ? $request->recurring_end_date : null,
                'meeting_id' => (string)$meeting_details->id,
                'password' => $meeting_details->password,
                'start_time' => Carbon::parse($start_date)->toDateTimeString(),
                'end_time' => Carbon::parse($start_date)->addMinute($request->durration)->toDateTimeString(),
                'created_by' => Auth::user()->id,
                'school_id' => Auth::user()->school_id,
            ]);

            $system_meeting->teachers()->attach($request->teacher_ids ?: Auth::user()->id);
            DB::commit();
            return $system_meeting;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function classUpdate($request, $id) { /* Implementation similar to store */ }
    public function classDelete($id) { /* Implementation */ }
    public function classShow($id) { return VirtualClass::findOrFail($id); }
}
