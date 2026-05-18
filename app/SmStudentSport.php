<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmStudentSport extends Model
{
    use HasFactory;

    protected $table = 'sm_student_sports';

    protected $fillable = [
        'student_id',
        'sport_name',
        'is_custom',
        'school_id',
        'academic_id'
    ];

    /**
     * Get the student associated with the sports selection.
     */
    public function student()
    {
        return $this->belongsTo('App\SmStudent', 'student_id');
    }
}
