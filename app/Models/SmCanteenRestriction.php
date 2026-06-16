<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmCanteenRestriction extends Model
{
    protected $table = 'sm_canteen_restrictions';
    protected $guarded = ['id'];

    public function student() { return $this->belongsTo(\App\SmStudent::class, 'student_id'); }
    public function category() { return $this->belongsTo(SmCanteenCategory::class, 'category_id'); }
    public function item() { return $this->belongsTo(SmCanteenItem::class, 'item_id'); }
    public function setter() { return $this->belongsTo(\App\User::class, 'set_by'); }
}
