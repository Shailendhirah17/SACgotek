@extends('backEnd.superAdmin.layouts.master')

@section('title', 'Dashboard')

@section('content')
<!-- Stats Grid -->
<div class="usa-stats-grid">
    <a href="{{ route('superadmin.school-list') }}" class="usa-stat-card">
        <div class="usa-stat-icon" style="background: rgba(120,50,255,0.12); color: var(--usa-primary-light);">
            <i class="fas fa-school"></i>
        </div>
        <div class="usa-stat-value">{{ number_format($totalSchools) }}</div>
        <div class="usa-stat-label">Total Schools</div>
    </a>

    <a href="{{ route('superadmin.tenant.students') }}" class="usa-stat-card">
        <div class="usa-stat-icon" style="background: rgba(52,211,153,0.12); color: var(--usa-success);">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="usa-stat-value">{{ number_format($totalStudents) }}</div>
        <div class="usa-stat-label">Total Students</div>
    </a>

    <a href="{{ route('superadmin.tenant.staff') }}" class="usa-stat-card">
        <div class="usa-stat-icon" style="background: rgba(251,191,36,0.12); color: var(--usa-warning);">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <div class="usa-stat-value">{{ number_format($totalStaff) }}</div>
        <div class="usa-stat-label">Total Staff</div>
    </a>

    <a href="{{ route('superadmin.tenant.parents') }}" class="usa-stat-card">
        <div class="usa-stat-icon" style="background: rgba(248,113,113,0.12); color: var(--usa-danger);">
            <i class="fas fa-users"></i>
        </div>
        <div class="usa-stat-value">{{ number_format($totalParents) }}</div>
        <div class="usa-stat-label">Total Parents</div>
    </a>
</div>

<div class="usa-stats-grid">
    <a href="{{ route('superadmin.school-list', ['status' => 'active']) }}" class="usa-stat-card">
        <div class="usa-stat-icon" style="background: rgba(52,211,153,0.12); color: var(--usa-success);">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="usa-stat-value">{{ number_format($activeSchools) }}</div>
        <div class="usa-stat-label">Active Schools</div>
    </a>

    <a href="{{ route('superadmin.school-list', ['status' => 'inactive']) }}" class="usa-stat-card">
        <div class="usa-stat-icon" style="background: rgba(248,113,113,0.12); color: var(--usa-danger);">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="usa-stat-value">{{ number_format($inactiveSchools) }}</div>
        <div class="usa-stat-label">Inactive Schools</div>
    </a>

    <a href="{{ route('superadmin.subscriptions.index') }}" class="usa-stat-card">
        <div class="usa-stat-icon" style="background: rgba(129,140,248,0.12); color: var(--usa-info);">
            <i class="fas fa-gem"></i>
        </div>
        <div class="usa-stat-value">{{ number_format($activeSubscriptions) }}</div>
        <div class="usa-stat-label">Active Subscriptions</div>
    </a>

    <a href="{{ route('superadmin.reports.index') }}" class="usa-stat-card">
        <div class="usa-stat-icon" style="background: rgba(120,50,255,0.12); color: var(--usa-primary-light);">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="usa-stat-value">{{ number_format($totalRevenue, 2) }}</div>
        <div class="usa-stat-label">Total Revenue</div>
    </a>
</div>

<!-- Outstanding Dues Banner -->
<div class="usa-card" style="margin-bottom: 20px; border-left: 4px solid var(--usa-danger);">
    <div style="display: flex; align-items: center; gap: 16px;">
        <div class="usa-stat-icon" style="background: rgba(248,113,113,0.12); color: var(--usa-danger); margin-bottom: 0;">
            <i class="fas fa-file-invoice-dollar"></i>
        </div>
        <div>
            <div class="usa-stat-value" style="color: var(--usa-danger); font-size: 24px;">{{ number_format($pendingSubscriptionAmount, 2) }}</div>
            <div class="usa-stat-label">Outstanding Subscription Dues</div>
        </div>
    </div>
</div>

<!-- Dashboard Grid -->
<div class="usa-dash-grid">
    <!-- Recent Activities -->
    <div class="usa-card">
        <div class="usa-card-header">
            <span class="usa-card-title">
                <i class="fas fa-bolt" style="color: var(--usa-secondary); margin-right: 8px;"></i>
                Recent Activities
            </span>
            <a href="{{ route('superadmin.audit.index') }}" class="usa-btn usa-btn-outline usa-btn-sm">View All</a>
        </div>
        @forelse($recentActivities as $activity)
            <div class="usa-activity-item">
                <div class="usa-activity-dot {{ $activity->action ?? 'default' }}"></div>
                <div>
                    <div class="usa-activity-text">
                        {{ $activity->description ?? $activity->action }}
                    </div>
                    <div class="usa-activity-time">
                        {{ $activity->created_at->diffForHumans() }}
                        @if($activity->superAdmin)
                            • {{ $activity->superAdmin->username }}
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 40px 0; color: var(--usa-text-muted);">
                <i class="fas fa-inbox" style="font-size: 28px; margin-bottom: 12px; display: block;"></i>
                No recent activities
            </div>
        @endforelse
    </div>

    <!-- System Health -->
    <div class="usa-card">
        <div class="usa-card-header">
            <span class="usa-card-title">
                <i class="fas fa-heartbeat" style="color: var(--usa-success); margin-right: 8px;"></i>
                System Health
            </span>
            <span class="usa-badge usa-badge-success">Operational</span>
        </div>
        @foreach($systemHealth as $key => $value)
            <div class="usa-health-row">
                <span class="usa-health-key">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                <span class="usa-health-val">{{ $value }}</span>
            </div>
        @endforeach
    </div>

    <!-- Recent Schools -->
    <div class="usa-card" style="grid-column: span 2;">
        <div class="usa-card-header">
            <span class="usa-card-title">
                <i class="fas fa-school" style="color: var(--usa-primary-light); margin-right: 8px;"></i>
                Recent Schools
            </span>
            <a href="{{ route('superadmin.school-list') }}" class="usa-btn usa-btn-outline usa-btn-sm">View All</a>
        </div>
        <table class="usa-table">
            <thead>
                <tr>
                    <th>School Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentSchools as $school)
                    <tr>
                        <td style="color: var(--usa-text-primary); font-weight: 600;">{{ $school->school_name }}</td>
                        <td>{{ $school->email }}</td>
                        <td>
                            @if($school->active_status)
                                <span class="usa-badge usa-badge-success">Active</span>
                            @else
                                <span class="usa-badge usa-badge-danger">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $school->created_at ? $school->created_at->format('M d, Y') : 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--usa-text-muted);">No schools found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
