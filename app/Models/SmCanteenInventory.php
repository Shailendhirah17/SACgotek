<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmCanteenInventory extends Model
{
    protected $table = 'sm_canteen_inventory';
    protected $guarded = ['id'];

    public function item() { return $this->belongsTo(SmCanteenItem::class, 'item_id'); }
}
