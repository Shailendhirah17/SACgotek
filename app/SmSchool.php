<?php

namespace App;

use App\Scopes\StatusAcademicSchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmSchool extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function subscription()
    {
        return $this->hasOne('Modules\Saas\Entities\SmSubscriptionPayment', 'school_id')->latest();
    }

    public function academicYears()
    {
        return $this->hasMany(SmAcademicYear::class, 'school_id', 'id');
    }

    public function sections()
    {
        return $this->hasMany(SmSection::class, 'school_id');
    }

    public function classes()
    {
        return $this->hasMany(SmClass::class, 'school_id');
    }

    public function classTimes()
    {
        return $this->hasMany(SmClassTime::class, 'school_id')->where('type', 'class');
    }

    public function weekends()
    {
        return $this->hasMany(SmWeekend::class, 'school_id')->where('active_status', 1);
    }

    public function routineUpdates()
    {
        return $this->hasMany(SmClassRoutineUpdate::class, 'school_id', 'id')->where('active_status', 1);
    }

    public function saasRoutineUpdates()
    {
        return $this->hasMany(SmClassRoutineUpdate::class, 'school_id', 'id')->withoutGlobalScope(StatusAcademicSchoolScope::class)->where('active_status', 1);
    }

    public function forumCategories()
    {
        return $this->belongsToMany('Modules\Forum\Entities\ForumCategory', 'forum_category_school', 'school_id', 'forum_category_id');
    }

    public function settings()
    {
        return $this->hasOne(SmGeneralSettings::class, 'school_id');
    }

    /**
     * Get the school group this school belongs to.
     * Part of the Ultra Super Admin hierarchy:
     * Ultra Super Admin → School Group → Schools
     */
    public function schoolGroup()
    {
        return $this->belongsTo(\App\Models\SchoolGroup::class, 'school_group_id');
    }
}
