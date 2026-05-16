@extends('backEnd.superAdmin.layouts.master')
@section('title', 'Reports')

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
    <div class="usa-card" style="text-align: center;">
        <div style="font-size: 36px; margin-bottom: 16px;"><i class="fas fa-school" style="color: var(--usa-primary);"></i></div>
        <h5 style="font-weight: 700; margin-bottom: 8px;">School Report</h5>
        <p style="color: var(--usa-text-muted); font-size: 13px; margin-bottom: 20px;">Detailed overview of all schools with student and staff counts</p>
        <div style="display: flex; gap: 8px; justify-content: center;">
            <a href="{{ route('superadmin.reports.schools') }}" class="usa-btn usa-btn-primary"><i class="fas fa-eye"></i> View</a>
            <a href="{{ route('superadmin.reports.schools.export') }}" class="usa-btn usa-btn-outline"><i class="fas fa-download"></i> CSV</a>
        </div>
    </div>

    <div class="usa-card" style="text-align: center;">
        <div style="font-size: 36px; margin-bottom: 16px;"><i class="fas fa-chart-line" style="color: var(--usa-success);"></i></div>
        <h5 style="font-weight: 700; margin-bottom: 8px;">Analytics</h5>
        <p style="color: var(--usa-text-muted); font-size: 13px; margin-bottom: 20px;">Growth trends, enrollment data, and performance metrics</p>
        <a href="{{ route('superadmin.analytics.index') }}" class="usa-btn usa-btn-primary"><i class="fas fa-chart-bar"></i> View Analytics</a>
    </div>

    <div class="usa-card" style="text-align: center;">
        <div style="font-size: 36px; margin-bottom: 16px;"><i class="fas fa-history" style="color: var(--usa-warning);"></i></div>
        <h5 style="font-weight: 700; margin-bottom: 8px;">Audit Logs</h5>
        <p style="color: var(--usa-text-muted); font-size: 13px; margin-bottom: 20px;">Complete trail of all administrative actions and events</p>
        <a href="{{ route('superadmin.audit.index') }}" class="usa-btn usa-btn-primary"><i class="fas fa-list"></i> View Logs</a>
    </div>
</div>
@endsection
