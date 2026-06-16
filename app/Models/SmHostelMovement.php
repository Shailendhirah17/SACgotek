<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmHostelMovement extends Model
{
    protected $table = 'sm_hostel_movements';
    protected $guarded = ['id'];

    public function student() { return $this->belongsTo(\App\SmStudent::class, 'student_id'); }
    public function hostel() { return $this->belongsTo(SmHostel::class, 'hostel_id'); }
    public function recorder() { return $this->belongsTo(\App\User::class, 'recorded_by'); }
}
