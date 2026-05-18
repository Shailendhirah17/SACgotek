<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmSportsSchedule extends Model
{
    use HasFactory;

    protected $table = 'sm_sports_schedules';

    protected $fillable = [
        'sport_name',
        'title',
        'session_date',
        'session_time',
        'venue',
        'school_id',
        'academic_id'
    ];
}
