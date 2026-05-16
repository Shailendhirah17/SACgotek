<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\SmSchool;

class SchoolHeartbeat extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'school_heartbeats';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id',
        'school_group_id',
        'last_activity_at',
        'daily_active_users',
        'system_load',
        'health_status',
        'churn_risk_score',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'last_activity_at' => 'datetime',
    ];

    /**
     * Get the school associated with this heartbeat record.
     */
    public function school()
    {
        return $this->belongsTo(SmSchool::class, 'school_id');
    }

    /**
     * Get the school group associated with this heartbeat record.
     */
    public function schoolGroup()
    {
        return $this->belongsTo(SchoolGroup::class, 'school_group_id');
    }
}
