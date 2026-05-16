<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmBookBankIssue extends Model
{
    protected $table = 'sm_book_bank_issues';
    protected $guarded = [];

    public function scopeSchool($q) {
        return $q->where('school_id', auth()->user()->school_id ?? 1);
    }
    public function book() {
        return $this->belongsTo(SmBookBank::class, 'book_id');
    }
    public function student() {
        return $this->belongsTo(\App\SmStudent::class, 'student_id');
    }
}
