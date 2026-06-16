<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmVendorAgreement extends Model
{
    protected $table = 'sm_vendor_agreements';
    protected $guarded = ['id'];

    public function vendor() { return $this->belongsTo(SmVendor::class, 'vendor_id'); }
}
