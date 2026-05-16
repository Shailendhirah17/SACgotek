<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmHostelFee extends Model
{
    protected $table = 'sm_hostel_fees';
    protected $guarded = [];

    public function scopeSchool($q) {
        return $q->where('school_id', auth()->user()->school_id ?? 1);
    }
    public function student() {
        return $this->belongsTo(\App\SmStudent::class, 'student_id');
    }
    public function hostel() {
        return $this->belongsTo(SmHostel::class, 'hostel_id');
    }
}
