<?php

namespace Modules\Jitsi\Entities;
use App\User;
use App\SmGeneralSettings;
use Carbon\Carbon;
use App\SmSection;
use App\SmClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Rennokki\QueryCache\Traits\QueryCacheable;
class JitsiMeeting extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'jitsi_meetings';
    public function participates()
    {
        return $this->belongsToMany(User::class, 'jitsi_meeting_users', 'meeting_id', 'user_id');
    }
    public function class()
    {
        return $this->hasOne(SmClass::class,'id','class_id')->withDefault();
    }


    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id')->withDefault();
    }
     public function getCurrentStatusAttribute()
    {
       
       

        $GSetting = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
         date_default_timezone_set($GSetting->timeZone->time_zone);
        $now = Carbon::now()->setTimezone($GSetting->timeZone->time_zone);
        if($this->time_start_before==null){
            $before_start=10;
        }else{
            $before_start=$this->time_start_before;
        }
        if($this->is_recurring == 1){
            if($now->between(Carbon::parse($this->start_time)->addMinute(-$before_start)->format('Y-m-d H:i:s'), Carbon::parse($this->recurring_end_date)->endOfDay()->format('Y-m-d H:i:s'))){
                return 'started';
            }
            if(!$now->gt(Carbon::parse($this->recurring_end_date)->addMinute($before_start))){
                return 'waiting';
            }
            return 'closed';
        }else{
            if($now->between(Carbon::parse($this->start_time)->addMinute(-$before_start)->format('Y-m-d H:i:s'), Carbon::parse($this->end_time)->format('Y-m-d H:i:s'))){
                return 'started';
            }

            if(!$now->gt(Carbon::parse($this->end_time)->addMinute($before_start))){
                return 'waiting';
            }
            return 'closed';
        }
    }
        public function getParticipatesNameAttribute()
    {
        return implode(', ', $this->participates->pluck('full_name')->toArray());
    }
}
