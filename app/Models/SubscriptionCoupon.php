<?php

namespace App\Models;

use App\SmSchool;
use Illuminate\Database\Eloquent\Model;

class SubscriptionCoupon extends Model
{
    protected $table = 'sm_subscription_coupons';
    protected $guarded = ['id'];

    protected $casts = [
        'expired_at' => 'date',
        'active_status' => 'integer',
        'amount' => 'float',
    ];

    /**
     * Check if a coupon is valid.
     */
    public function isValid(): bool
    {
        if ($this->active_status != 1) {
            return false;
        }

        if ($this->expired_at && $this->expired_at->isPast()) {
            return false;
        }

        if ($this->usage_limit > 0) {
            $usedCount = \DB::table('sm_applied_coupons')->where('coupon_id', $this->id)->count();
            if ($usedCount >= $this->usage_limit) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate discount for a given amount.
     */
    public function calculateDiscount($totalAmount): float
    {
        if ($this->type == 'percentage') {
            return ($totalAmount * $this->amount) / 100;
        }

        return $this->amount;
    }
}
