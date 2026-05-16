<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmBookBank extends Model
{
    protected $table = 'sm_book_banks';
    protected $guarded = [];

    public function scopeSchool($q) {
        return $q->where('school_id', auth()->user()->school_id ?? 1);
    }
    public function issues() {
        return $this->hasMany(SmBookBankIssue::class, 'book_id');
    }
}
