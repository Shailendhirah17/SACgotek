<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmCanteenSupplier extends Model
{
    protected $table = 'sm_canteen_suppliers';
    protected $guarded = ['id'];

    public function vendor() { return $this->belongsTo(SmVendor::class, 'vendor_id'); }
}
