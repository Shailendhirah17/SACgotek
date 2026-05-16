<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmVendorPayment extends Model
{
    protected $table = 'sm_vendor_payments';
    protected $guarded = [];

    public function scopeSchool($q) {
        return $q->where('school_id', auth()->user()->school_id ?? 1);
    }
    public function vendor() {
        return $this->belongsTo(SmVendor::class, 'vendor_id');
    }
    public function purchaseOrder() {
        return $this->belongsTo(SmPurchaseOrder::class, 'purchase_order_id');
    }
}
