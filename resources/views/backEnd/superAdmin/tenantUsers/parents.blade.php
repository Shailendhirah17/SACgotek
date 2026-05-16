@extends('backEnd.superAdmin.layouts.master')
@section('title', 'Global SaaS Parents')

@section('content')
<div class="usa-card">
    <div class="usa-card-header">
        <span class="usa-card-title">Master Roster: Parents</span>
        <a href="{{ route('superadmin-dashboard') }}" class="usa-btn usa-btn-outline usa-btn-sm"><i class="fas fa-arrow-left"></i> Dashboard</a>
    </div>

    <div style="margin-bottom: 20px;">
        <form action="{{ route('superadmin.tenant.parents') }}" method="GET" style="display: flex; gap: 10px; flex-wrap: wrap; background: rgba(255,255,255,0.03); padding: 15px; border-radius: 8px;">
            <input type="text" name="search" class="usa-form-control" placeholder="Name/Email..." value="{{ request('search') }}" style="max-width: 150px;">
            
            <input type="text" name="occupation" class="usa-form-control" placeholder="Occupation..." value="{{ request('occupation') }}" style="max-width: 150px;">

            <select name="school_id" class="usa-form-control" style="max-width: 200px;">
                <option value="">All Schools</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>{{ $school->school_name }}</option>
                @endforeach
            </select>
            
            <select name="status" class="usa-form-control" style="max-width: 150px;">
                <option value="">Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            
            <button type="submit" class="usa-btn usa-btn-primary">Filter</button>
            @if(request('search') || request('school_id') || request('status') || request('occupation'))
                <a href="{{ route('superadmin.tenant.parents') }}" class="usa-btn usa-btn-outline">Clear</a>
            @endif
        </form>
    </div>

    <div class="usa-table-responsive">
        <table class="usa-table">
            <thead>
                <tr>
                    <th>School (Tenant)</th>
                    <th>Father's Name</th>
                    <th>Mother's Name</th>
                    <th>Guardian Email</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($parents as $parent)
                <tr>
                    <td style="color: var(--usa-text-primary); font-weight: 500;">{{ $parent->school->school_name ?? 'N/A' }}</td>
                    <td>{{ $parent->fathers_name ?: 'N/A' }}</td>
                    <td>{{ $parent->mothers_name ?: 'N/A' }}</td>
                    <td>{{ $parent->guardians_email ?: $parent->guardians_mobile }}</td>
                    <td>
                        @if($parent->active_status)
                            <span class="usa-badge usa-badge-success">Active</span>
                        @else
                            <span class="usa-badge usa-badge-danger">Inactive</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="usa-empty-state">No parents found across the SaaS network.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 20px;">
        {{ $parents->links() }}
    </div>
</div>
@endsection
