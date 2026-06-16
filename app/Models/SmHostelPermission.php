<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmHostelPermission extends Model
{
    protected $table = 'sm_hostel_permissions';
    protected $guarded = ['id'];

    public function student() { return $this->belongsTo(\App\SmStudent::class, 'student_id'); }
    public function hostel() { return $this->belongsTo(SmHostel::class, 'hostel_id'); }
    public function approver() { return $this->belongsTo(\App\User::class, 'approved_by'); }
}
