@extends('backEnd.superAdmin.layouts.master')
@section('title', 'Subscription Coupons')

@section('content')
<div class="usa-stats-grid" style="grid-template-columns: 1fr 2fr;">
    <!-- Create Coupon -->
    <div class="usa-card">
        <div class="usa-card-header">
            <span class="usa-card-title">Create New Coupon</span>
        </div>
        <div style="padding: 20px;">
            <form action="{{ route('superadmin.subscriptions.coupons.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 15px;">
                    <label class="usa-form-label">Coupon Code</label>
                    <input type="text" name="code" class="usa-form-control" placeholder="e.g. SAVE50" required style="text-transform: uppercase;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label class="usa-form-label">Discount Amount</label>
                    <input type="number" step="0.01" name="amount" class="usa-form-control" placeholder="Amount" required>
                </div>
                <div style="margin-bottom: 15px;">
                    <label class="usa-form-label">Discount Type</label>
                    <select name="type" class="usa-form-control">
                        <option value="fixed">Fixed Amount (₹)</option>
                        <option value="percentage">Percentage (%)</option>
                    </select>
                </div>
                <div style="margin-bottom: 15px;">
                    <label class="usa-form-label">Usage Limit (0 for Unlimited)</label>
                    <input type="number" name="usage_limit" class="usa-form-control" value="0">
                </div>
                <div style="margin-bottom: 20px;">
                    <label class="usa-form-label">Expiry Date</label>
                    <input type="date" name="expired_at" class="usa-form-control">
                </div>
                <button type="submit" class="usa-btn usa-btn-primary" style="width: 100%;">Create Coupon</button>
            </form>
        </div>
    </div>

    <!-- Coupon List -->
    <div class="usa-card">
        <div class="usa-card-header">
            <span class="usa-card-title">Active Coupons</span>
        </div>
        <div class="usa-table-responsive">
            <table class="usa-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Value</th>
                        <th>Usage</th>
                        <th>Expiry</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                    <tr>
                        <td><strong>{{ $coupon->code }}</strong></td>
                        <td>
                            @if($coupon->type == 'percentage')
                                {{ $coupon->amount }}% Off
                            @else
                                ₹{{ number_format($coupon->amount, 2) }} Off
                            @endif
                        </td>
                        <td>
                            @php $used = \DB::table('sm_applied_coupons')->where('coupon_id', $coupon->id)->count(); @endphp
                            {{ $used }} / {{ $coupon->usage_limit ?: '∞' }}
                        </td>
                        <td>
                            @if($coupon->expired_at)
                                <span class="{{ $coupon->expired_at->isPast() ? 'text-danger' : '' }}">
                                    {{ $coupon->expired_at->format('d M, Y') }}
                                </span>
                            @else
                                <span class="text-muted">No Expiry</span>
                            @endif
                        </td>
                        <td>
                            @if($coupon->active_status == 1)
                                <span class="usa-badge usa-badge-success">Active</span>
                            @else
                                <span class="usa-badge usa-badge-danger">Disabled</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <form action="{{ route('superadmin.subscriptions.coupons.toggle', $coupon->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="usa-btn usa-btn-sm {{ $coupon->active_status == 1 ? 'usa-btn-outline' : 'usa-btn-success' }}">
                                        {{ $coupon->active_status == 1 ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                                <form action="{{ route('superadmin.subscriptions.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Delete this coupon?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="usa-btn usa-btn-danger usa-btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="usa-empty-state">No coupons created yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding: 15px;">
            {{ $coupons->links() }}
        </div>
    </div>
</div>

<style>
    .usa-form-label { display: block; margin-bottom: 8px; font-size: 13px; color: var(--usa-text-muted); font-weight: 500; }
</style>
@endsection
