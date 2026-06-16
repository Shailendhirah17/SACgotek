<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmCanteenItem extends Model
{
    protected $table = 'sm_canteen_items';
    protected $guarded = ['id'];

    public function category() { return $this->belongsTo(SmCanteenCategory::class, 'category_id'); }
    public function inventory() { return $this->hasOne(SmCanteenInventory::class, 'item_id'); }
}
