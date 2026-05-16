<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmHostel extends Model
{
    protected $table = 'sm_hostels';
    protected $guarded = [];

    public function scopeSchool($q) {
        return $q->where('school_id', auth()->user()->school_id ?? 1);
    }
    public function rooms() {
        return $this->hasMany(SmHostelRoom::class, 'hostel_id');
    }
    public function allocations() {
        return $this->hasMany(SmHostelAllocation::class, 'hostel_id');
    }
}
