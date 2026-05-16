<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmHostelAllocation extends Model
{
    protected $table = 'sm_hostel_allocations';
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
    public function room() {
        return $this->belongsTo(SmHostelRoom::class, 'room_id');
    }
}
