<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmMedicalRecord extends Model
{
    protected $table = 'sm_medical_records';
    protected $guarded = [];

    public function scopeSchool($q) {
        return $q->where('school_id', auth()->user()->school_id ?? 1);
    }
    public function student() {
        return $this->belongsTo(\App\SmStudent::class, 'student_id');
    }
}
