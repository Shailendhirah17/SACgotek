@extends('backEnd.superAdmin.layouts.master')
@section('title', 'School Details')

@section('content')
<div class="usa-card" style="max-width: 800px;">
    <div class="usa-card-header">
        <span class="usa-card-title">{{ $school->school_name }}</span>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('superadmin.school.edit', $school->id) }}" class="usa-btn usa-btn-outline usa-btn-sm"><i class="fas fa-edit"></i> Edit</a>
            <a href="{{ route('superadmin.school-list') }}" class="usa-btn usa-btn-outline usa-btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>

    <div class="row" style="margin-bottom: 24px;">
        <div class="col-md-3">
            <div class="usa-stat-card">
                <div class="usa-stat-icon students"><i class="fas fa-user-graduate"></i></div>
                <div>
                    <div class="usa-stat-value" style="font-size: 20px;">{{ $stats['students'] }}</div>
                    <div class="usa-stat-label">Students</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="usa-stat-card">
                <div class="usa-stat-icon staff"><i class="fas fa-chalkboard-teacher"></i></div>
                <div>
                    <div class="usa-stat-value" style="font-size: 20px;">{{ $stats['staff'] }}</div>
                    <div class="usa-stat-label">Staff</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="usa-stat-card">
                <div class="usa-stat-icon schools"><i class="fas fa-chalkboard"></i></div>
                <div>
                    <div class="usa-stat-value" style="font-size: 20px;">{{ $stats['classes'] }}</div>
                    <div class="usa-stat-label">Classes</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="usa-stat-card">
                <div class="usa-stat-icon revenue"><i class="fas fa-layer-group"></i></div>
                <div>
                    <div class="usa-stat-value" style="font-size: 20px;">{{ $stats['sections'] }}</div>
                    <div class="usa-stat-label">Sections</div>
                </div>
            </div>
        </div>
    </div>

    <div class="usa-health-row"><span class="usa-health-key">Email</span><span class="usa-health-val">{{ $school->email }}</span></div>
    <div class="usa-health-row"><span class="usa-health-key">Phone</span><span class="usa-health-val">{{ $school->phone ?? 'N/A' }}</span></div>
    <div class="usa-health-row"><span class="usa-health-key">Address</span><span class="usa-health-val">{{ $school->address ?? 'N/A' }}</span></div>
    <div class="usa-health-row"><span class="usa-health-key">Status</span><span class="usa-health-val">
        @if($school->active_status) <span class="usa-badge usa-badge-success">Active</span>
        @else <span class="usa-badge usa-badge-danger">Inactive</span> @endif
    </span></div>
    <div class="usa-health-row"><span class="usa-health-key">Created</span><span class="usa-health-val">{{ $school->created_at ? $school->created_at->format('M d, Y H:i') : 'N/A' }}</span></div>
</div>
@endsection
