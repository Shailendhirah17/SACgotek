<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmHostelMeal extends Model
{
    protected $table = 'sm_hostel_meals';
    protected $guarded = [];

    public function scopeSchool($q) {
        return $q->where('school_id', auth()->user()->school_id ?? 1);
    }
    public function hostel() {
        return $this->belongsTo(SmHostel::class, 'hostel_id');
    }
}
