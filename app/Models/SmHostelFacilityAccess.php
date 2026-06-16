<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmHostelFacilityAccess extends Model
{
    protected $table = 'sm_hostel_facility_access';
    protected $guarded = ['id'];

    public function student() { return $this->belongsTo(\App\SmStudent::class, 'student_id'); }
    public function hostel() { return $this->belongsTo(SmHostel::class, 'hostel_id'); }
}
