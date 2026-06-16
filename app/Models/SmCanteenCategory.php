<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmCanteenCategory extends Model
{
    protected $table = 'sm_canteen_categories';
    protected $guarded = ['id'];

    public function items() { return $this->hasMany(SmCanteenItem::class, 'category_id'); }
}
