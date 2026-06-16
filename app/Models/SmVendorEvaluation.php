<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmVendorEvaluation extends Model
{
    protected $table = 'sm_vendor_evaluations';
    protected $guarded = ['id'];

    public function vendor() { return $this->belongsTo(SmVendor::class, 'vendor_id'); }
    public function evaluator() { return $this->belongsTo(\App\User::class, 'evaluated_by'); }
}
