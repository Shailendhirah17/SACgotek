@extends('backEnd.ultraSuperAdmin.layouts.master')
@section('title', 'Analytics')

@section('content')
<div style="margin-bottom: 24px;">
    <h4 style="font-size: 20px; font-weight: 700;">Cross-Organization Analytics</h4>
    <p style="font-size: 13px; color: var(--usa-text-muted); margin-top: 4px;">Insights across all school groups and organizations</p>
</div>

<!-- Plan Distribution -->
@if(isset($planDistribution) && $planDistribution->count() > 0)
<div class="usa-card" style="margin-bottom: 20px;">
    <div class="usa-card-title" style="margin-bottom: 16px;">
        <i class="fas fa-chart-pie" style="color: var(--usa-secondary); margin-right: 8px;"></i>
        Subscription Plan Distribution
    </div>
    <div style="display: flex; gap: 16px; flex-wrap: wrap;">
        @php $colors = ['standard' => '#818cf8', 'professional' => '#fbbf24', 'enterprise' => '#34d399', 'custom' => '#f87171']; @endphp
        @foreach($planDistribution as $plan)
        <div style="flex: 1; min-width: 140px; text-align: center; padding: 24px 16px; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid var(--usa-border);">
            <div style="font-size: 36px; font-weight: 800; color: {{ $colors[$plan->subscription_plan] ?? 'var(--usa-primary-light)' }};">{{ $plan->total }}</div>
            <div style="font-size: 13px; color: var(--usa-text-secondary); text-transform: capitalize; margin-top: 6px;">{{ $plan->subscription_plan }}</div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Group Stats -->
@if(isset($groupStats) && $groupStats->count() > 0)
<div class="usa-card" style="margin-bottom: 20px;">
    <div class="usa-card-title" style="margin-bottom: 16px;">
        <i class="fas fa-layer-group" style="color: var(--usa-primary-light); margin-right: 8px;"></i>
        School Groups Overview
    </div>
    <table class="usa-table">
        <thead>
            <tr><th>Group</th><th>Schools</th><th>Plan</th><th>Subscription</th></tr>
        </thead>
        <tbody>
            @foreach($groupStats as $stat)
            <tr>
                <td style="color: var(--usa-text-primary); font-weight: 600;">{{ $stat['name'] }}</td>
                <td>{{ $stat['schools_count'] }}</td>
                <td><span class="usa-badge usa-badge-info">{{ ucfirst($stat['plan']) }}</span></td>
                <td>
                    @php $s = $stat['subscription_status']; @endphp
                    <span class="usa-badge {{ $s === 'Active' || $s === 'Unlimited' ? 'usa-badge-success' : ($s === 'Expiring Soon' ? 'usa-badge-warning' : 'usa-badge-danger') }}">{{ $s }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<!-- Students Per Group -->
@if(isset($studentsPerGroup) && count($studentsPerGroup) > 0)
<div class="usa-card" style="margin-bottom: 20px;">
    <div class="usa-card-title" style="margin-bottom: 16px;">
        <i class="fas fa-user-graduate" style="color: var(--usa-success); margin-right: 8px;"></i>
        Students Per Group
    </div>
    <div style="display: flex; gap: 16px; flex-wrap: wrap;">
        @foreach($studentsPerGroup as $item)
        <div style="flex: 1; min-width: 140px; text-align: center; padding: 20px; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid var(--usa-border);">
            <div style="font-size: 28px; font-weight: 800; color: var(--usa-success);">{{ number_format($item->total_students) }}</div>
            <div style="font-size: 12px; color: var(--usa-text-secondary); margin-top: 4px;">{{ $item->name }}</div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Top Schools -->
@if(isset($topSchools) && count($topSchools) > 0)
<div class="usa-card">
    <div class="usa-card-title" style="margin-bottom: 16px;">
        <i class="fas fa-trophy" style="color: var(--usa-warning); margin-right: 8px;"></i>
        Top 10 Schools by Student Count
    </div>
    <table class="usa-table">
        <thead><tr><th>#</th><th>School Name</th><th>Students</th></tr></thead>
        <tbody>
            @foreach($topSchools as $i => $school)
            <tr>
                <td style="font-weight: 700; color: {{ $i < 3 ? 'var(--usa-warning)' : 'var(--usa-text-muted)' }};">{{ $i + 1 }}</td>
                <td style="color: var(--usa-text-primary); font-weight: 600;">{{ $school->school_name }}</td>
                <td>{{ number_format($school->student_count) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection
