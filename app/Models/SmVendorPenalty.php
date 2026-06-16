<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmVendorPenalty extends Model
{
    protected $table = 'sm_vendor_penalties';
    protected $guarded = ['id'];

    public function vendor() { return $this->belongsTo(SmVendor::class, 'vendor_id'); }
    public function purchaseOrder() { return $this->belongsTo(SmPurchaseOrder::class, 'purchase_order_id'); }
}
