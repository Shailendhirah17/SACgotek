<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmVendorDocument extends Model
{
    protected $table = 'sm_vendor_documents';
    protected $guarded = ['id'];

    public function vendor() { return $this->belongsTo(SmVendor::class, 'vendor_id'); }
    public function verifier() { return $this->belongsTo(\App\User::class, 'verified_by'); }
}
