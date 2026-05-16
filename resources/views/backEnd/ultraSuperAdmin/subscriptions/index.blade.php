@extends('backEnd.ultraSuperAdmin.layouts.master')
@section('title', 'Subscription Management')

@section('content')
<div style="margin-bottom: 24px;">
    <h4 style="font-size: 20px; font-weight: 700;">Subscription Management</h4>
    <p style="font-size: 13px; color: var(--usa-text-muted); margin-top: 4px;">Manage subscriptions at the school group level</p>
</div>

<!-- Stats -->
<div style="display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 24px;">
    <div class="usa-stat-card" style="flex: 1; min-width: 160px;">
        <div class="usa-stat-value" style="font-size: 24px;">{{ $totalGroups }}</div>
        <div class="usa-stat-label">Total Groups</div>
    </div>
    <div class="usa-stat-card" style="flex: 1; min-width: 160px;">
        <div class="usa-stat-value" style="font-size: 24px; color: var(--usa-success);">{{ $activeCount }}</div>
        <div class="usa-stat-label">Active</div>
    </div>
    <div class="usa-stat-card" style="flex: 1; min-width: 160px;">
        <div class="usa-stat-value" style="font-size: 24px; color: var(--usa-warning);">{{ $expiringCount }}</div>
        <div class="usa-stat-label">Expiring Soon</div>
    </div>
    <div class="usa-stat-card" style="flex: 1; min-width: 160px;">
        <div class="usa-stat-value" style="font-size: 24px; color: var(--usa-danger);">{{ $expiredCount }}</div>
        <div class="usa-stat-label">Expired</div>
    </div>
</div>

<!-- Filters -->
<div class="usa-card" style="margin-bottom: 20px; padding: 16px 20px;">
    <form method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <select name="plan" class="usa-form-control" style="max-width: 180px;">
            <option value="">All Plans</option>
            @foreach(['standard', 'professional', 'enterprise', 'custom'] as $plan)
            <option value="{{ $plan }}" {{ request('plan') === $plan ? 'selected' : '' }}>{{ ucfirst($plan) }}</option>
            @endforeach
        </select>
        <select name="status" class="usa-form-control" style="max-width: 180px;">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
            <option value="expiring" {{ request('status') === 'expiring' ? 'selected' : '' }}>Expiring Soon</option>
        </select>
        <button type="submit" class="usa-btn usa-btn-outline usa-btn-sm"><i class="fas fa-filter"></i> Filter</button>
    </form>
</div>

<!-- Groups -->
@foreach($groups as $group)
<div class="usa-card" style="margin-bottom: 16px;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
        <div>
            <h5 style="font-size: 16px; font-weight: 700; margin-bottom: 6px;">{{ $group->name }} <span class="usa-badge usa-badge-primary" style="font-size: 10px;">{{ $group->code }}</span></h5>
            <div style="display: flex; gap: 12px; font-size: 12px; color: var(--usa-text-muted);">
                <span><i class="fas fa-school" style="margin-right: 4px;"></i>{{ $group->schools_count }} schools</span>
                <span><i class="fas fa-gem" style="margin-right: 4px;"></i>{{ ucfirst($group->subscription_plan) }}</span>
                <span>
                    @if($group->subscription_end)
                        @if($group->subscription_end->isPast())
                            <span style="color: var(--usa-danger);"><i class="fas fa-times-circle"></i> Expired {{ $group->subscription_end->diffForHumans() }}</span>
                        @else
                            <span style="color: var(--usa-success);"><i class="fas fa-check-circle"></i> Expires {{ $group->subscription_end->format('M d, Y') }}</span>
                        @endif
                    @else
                        <span style="color: var(--usa-success);"><i class="fas fa-infinity"></i> Unlimited</span>
                    @endif
                </span>
            </div>
        </div>
        <div>
            <form method="POST" action="{{ route('ultrasuperadmin.subscriptions.update', $group->id) }}" style="display: flex; gap: 8px; align-items: flex-end; flex-wrap: wrap;">
                @csrf @method('PUT')
                <div><label style="font-size: 10px; color: var(--usa-text-muted);">Plan</label><select name="subscription_plan" class="usa-form-control" style="min-width: 130px;">
                    @foreach(['standard', 'professional', 'enterprise', 'custom'] as $plan)
                    <option value="{{ $plan }}" {{ $group->subscription_plan === $plan ? 'selected' : '' }}>{{ ucfirst($plan) }}</option>
                    @endforeach
                </select></div>
                <div><label style="font-size: 10px; color: var(--usa-text-muted);">Start</label><input type="date" name="subscription_start" class="usa-form-control" value="{{ optional($group->subscription_start)->format('Y-m-d') }}" style="min-width: 140px;"></div>
                <div><label style="font-size: 10px; color: var(--usa-text-muted);">End</label><input type="date" name="subscription_end" class="usa-form-control" value="{{ optional($group->subscription_end)->format('Y-m-d') }}" style="min-width: 140px;"></div>
                <div><label style="font-size: 10px; color: var(--usa-text-muted);">Schools</label><input type="number" name="max_schools" class="usa-form-control" value="{{ $group->max_schools }}" min="1" style="width: 80px;"></div>
                <div><label style="font-size: 10px; color: var(--usa-text-muted);">Students</label><input type="number" name="max_students_per_school" class="usa-form-control" value="{{ $group->max_students_per_school }}" min="1" style="width: 80px;"></div>
                <button type="submit" class="usa-btn usa-btn-primary usa-btn-sm"><i class="fas fa-save"></i> Save</button>
            </form>
        </div>
    </div>
</div>
@endforeach

@if($groups->hasPages())
<div class="usa-pagination">{{ $groups->appends(request()->query())->links() }}</div>
@endif
@endsection
