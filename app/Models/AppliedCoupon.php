<?php

namespace App\Models;

use App\SmSchool;
use Illuminate\Database\Eloquent\Model;

class AppliedCoupon extends Model
{
    protected $table = 'sm_applied_coupons';
    protected $guarded = ['id'];

    public function school()
    {
        return $this->belongsTo(SmSchool::class, 'school_id');
    }

    public function coupon()
    {
        return $this->belongsTo(SubscriptionCoupon::class, 'coupon_id');
    }
}
