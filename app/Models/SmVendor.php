<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmVendor extends Model
{
    protected $table = 'sm_vendors';
    protected $guarded = [];

    public function scopeSchool($q) {
        return $q->where('school_id', auth()->user()->school_id ?? 1);
    }
    public function payments() {
        return $this->hasMany(SmVendorPayment::class, 'vendor_id');
    }
    public function purchaseOrders() {
        return $this->hasMany(SmPurchaseOrder::class, 'vendor_id');
    }
}
