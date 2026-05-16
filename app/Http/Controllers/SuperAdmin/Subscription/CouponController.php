<?php

namespace App\Http\Controllers\SuperAdmin\Subscription;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionCoupon;
use App\Models\SuperAdminAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = SubscriptionCoupon::orderBy('created_at', 'desc')->paginate(10);
        return view('backEnd.superAdmin.subscriptions.coupons', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:sm_subscription_coupons,code',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:fixed,percentage',
            'usage_limit' => 'nullable|integer|min:0',
            'expired_at' => 'nullable|date|after:today',
        ]);

        $coupon = SubscriptionCoupon::create([
            'code' => strtoupper($request->code),
            'amount' => $request->amount,
            'type' => $request->type,
            'usage_limit' => $request->usage_limit ?: 0,
            'expired_at' => $request->expired_at,
            'active_status' => 1,
        ]);

        SuperAdminAuditLog::log(
            Auth::guard('superadmin')->id(),
            'coupon_created',
            'SubscriptionCoupon',
            $coupon->id,
            "Created subscription coupon: {$coupon->code} ({$coupon->amount} {$coupon->type})"
        );

        return back()->with('message-success', 'Coupon created successfully.');
    }

    public function destroy($id)
    {
        $coupon = SubscriptionCoupon::findOrFail($id);
        $code = $coupon->code;
        $coupon->delete();

        SuperAdminAuditLog::log(
            Auth::guard('superadmin')->id(),
            'coupon_deleted',
            'SubscriptionCoupon',
            $id,
            "Deleted subscription coupon: {$code}"
        );

        return back()->with('message-success', 'Coupon deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $coupon = SubscriptionCoupon::findOrFail($id);
        $coupon->active_status = $coupon->active_status == 1 ? 0 : 1;
        $coupon->save();

        return back()->with('message-success', 'Coupon status updated.');
    }
}
