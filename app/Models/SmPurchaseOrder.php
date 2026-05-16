<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmPurchaseOrder extends Model
{
    protected $table = 'sm_purchase_orders';
    protected $guarded = [];

    public function scopeSchool($q) {
        return $q->where('school_id', auth()->user()->school_id ?? 1);
    }
    public function vendor() {
        return $this->belongsTo(SmVendor::class, 'vendor_id');
    }
}
