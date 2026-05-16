@extends('backEnd.ultraSuperAdmin.layouts.master')
@section('title', $group->name)

@section('content')
<div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
    <a href="{{ route('ultrasuperadmin.school-groups.index') }}" class="usa-btn usa-btn-outline usa-btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Groups
    </a>
    <a href="{{ route('ultrasuperadmin.school-groups.edit', $group->id) }}" class="usa-btn usa-btn-primary usa-btn-sm">
        <i class="fas fa-edit"></i> Edit Group
    </a>
</div>

<!-- Group Info -->
<div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 20px;">
    <div class="usa-card" style="flex: 2; min-width: 350px;">
        <div class="usa-card-title" style="margin-bottom: 16px;">
            <i class="fas fa-layer-group" style="color: var(--usa-primary-light); margin-right: 8px;"></i>
            {{ $group->name }}
            <span class="usa-badge {{ $group->active_status ? 'usa-badge-success' : 'usa-badge-danger' }}" style="margin-left: 8px;">
                {{ $group->active_status ? 'Active' : 'Inactive' }}
            </span>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
            <div><span style="color: var(--usa-text-muted); font-size: 12px;">Code</span><br><span class="usa-badge usa-badge-primary">{{ $group->code }}</span></div>
            <div><span style="color: var(--usa-text-muted); font-size: 12px;">Plan</span><br><strong>{{ ucfirst($group->subscription_plan) }}</strong></div>
            <div><span style="color: var(--usa-text-muted); font-size: 12px;">Schools</span><br><strong>{{ $group->schools->count() }} / {{ $group->max_schools }}</strong></div>
            <div><span style="color: var(--usa-text-muted); font-size: 12px;">Max Students/School</span><br><strong>{{ $group->max_students_per_school }}</strong></div>
            <div><span style="color: var(--usa-text-muted); font-size: 12px;">License Key</span><br><code style="font-size: 11px; color: var(--usa-text-secondary);">{{ $group->license_key ?? 'N/A' }}</code></div>
            <div><span style="color: var(--usa-text-muted); font-size: 12px;">Subscription</span><br>
                @if($group->subscription_end)
                    {{ optional($group->subscription_start)->format('M d, Y') }} — {{ $group->subscription_end->format('M d, Y') }}
                @else
                    <span class="usa-badge usa-badge-success">Unlimited</span>
                @endif
            </div>
        </div>
        @if($group->description)
            <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid var(--usa-border); font-size: 13px; color: var(--usa-text-secondary);">{{ $group->description }}</div>
        @endif
    </div>

    <div class="usa-card" style="flex: 1; min-width: 250px;">
        <div class="usa-card-title" style="margin-bottom: 16px;">
            <i class="fas fa-file-invoice" style="color: var(--usa-secondary); margin-right: 8px;"></i>
            Billing Info
        </div>
        <div style="font-size: 13px; display: flex; flex-direction: column; gap: 10px;">
            <div><span style="color: var(--usa-text-muted);">Contact:</span> {{ $group->billing_contact_name ?? '—' }}</div>
            <div><span style="color: var(--usa-text-muted);">Email:</span> {{ $group->billing_contact_email ?? '—' }}</div>
            <div><span style="color: var(--usa-text-muted);">Phone:</span> {{ $group->billing_phone ?? '—' }}</div>
            <div><span style="color: var(--usa-text-muted);">Address:</span> {{ $group->billing_address ?? '—' }}</div>
        </div>
    </div>
</div>

<!-- Schools in Group -->
<div class="usa-card" style="margin-bottom: 20px;">
    <div class="usa-card-header">
        <div class="usa-card-title">
            <i class="fas fa-school" style="color: var(--usa-secondary); margin-right: 8px;"></i>
            Schools in this Group ({{ $group->schools->count() }})
        </div>
    </div>

    @if($group->schools->count() > 0)
    <table class="usa-table">
        <thead>
            <tr><th>ID</th><th>School Name</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($group->schools as $school)
            <tr>
                <td>{{ $school->id }}</td>
                <td style="color: var(--usa-text-primary); font-weight: 600;">{{ $school->school_name }}</td>
                <td><span class="usa-badge {{ $school->active_status ? 'usa-badge-success' : 'usa-badge-danger' }}">{{ $school->active_status ? 'Active' : 'Inactive' }}</span></td>
                <td>
                    <form method="POST" action="{{ route('ultrasuperadmin.school-groups.remove-school', $group->id) }}" style="display:inline;" onsubmit="return confirm('Remove this school from the group?');">
                        @csrf
                        <input type="hidden" name="school_id" value="{{ $school->id }}">
                        <button type="submit" class="usa-btn usa-btn-danger usa-btn-sm"><i class="fas fa-unlink"></i> Remove</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 30px; color: var(--usa-text-muted);">No schools assigned to this group yet.</div>
    @endif
</div>

<!-- Assign School -->
@if(isset($availableSchools) && $availableSchools->count() > 0)
<div class="usa-card">
    <div class="usa-card-title" style="margin-bottom: 16px;">
        <i class="fas fa-plus-circle" style="color: var(--usa-success); margin-right: 8px;"></i>
        Assign School to Group
    </div>
    <form method="POST" action="{{ route('ultrasuperadmin.school-groups.assign-school', $group->id) }}" style="display: flex; gap: 12px; align-items: flex-end;">
        @csrf
        <div class="usa-form-group" style="flex: 1; margin-bottom: 0;">
            <label class="usa-form-label">Select School</label>
            <select name="school_id" class="usa-form-control" required>
                <option value="">Choose a school...</option>
                @foreach($availableSchools as $school)
                    <option value="{{ $school->id }}">{{ $school->school_name }} (ID: {{ $school->id }})</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="usa-btn usa-btn-success"><i class="fas fa-link"></i> Assign</button>
    </form>
</div>
@endif

<!-- Features -->
@if($group->features->count() > 0)
<div class="usa-card" style="margin-top: 20px;">
    <div class="usa-card-title" style="margin-bottom: 16px;">
        <i class="fas fa-toggle-on" style="color: var(--usa-primary-light); margin-right: 8px;"></i>
        Enabled Features ({{ $group->features->where('is_enabled', true)->count() }})
    </div>
    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
        @foreach($group->features as $feature)
        <span class="usa-badge {{ $feature->is_enabled ? 'usa-badge-success' : 'usa-badge-danger' }}">
            <i class="fas {{ $feature->is_enabled ? 'fa-check' : 'fa-times' }}" style="margin-right: 4px;"></i>
            {{ $feature->feature_name }}
        </span>
        @endforeach
    </div>
</div>
@endif
@endsection
