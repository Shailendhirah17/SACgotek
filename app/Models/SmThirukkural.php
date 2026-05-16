<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmThirukkural extends Model
{
    protected $table = 'sm_thirukkurals';
    protected $guarded = [];

    public function scopeSchool($q) {
        return $q->where('school_id', auth()->user()->school_id ?? 1);
    }
}
