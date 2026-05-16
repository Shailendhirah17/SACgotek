@extends('backEnd.superAdmin.layouts.master')
@section('title', 'School Report')

@section('content')
<div class="usa-card">
    <div class="usa-card-header">
        <span class="usa-card-title">School Report</span>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('superadmin.reports.schools.export') }}" class="usa-btn usa-btn-outline usa-btn-sm"><i class="fas fa-download"></i> Export CSV</a>
            <a href="{{ route('superadmin.reports.index') }}" class="usa-btn usa-btn-outline usa-btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>
    <table class="usa-table">
        <thead>
            <tr>
                <th>#</th>
                <th>School</th>
                <th>Email</th>
                <th>Students</th>
                <th>Staff</th>
                <th>Parents</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schools as $school)
                <tr>
                    <td>{{ $school->id }}</td>
                    <td style="color: var(--usa-text-primary); font-weight: 500;">{{ $school->school_name }}</td>
                    <td>{{ $school->email }}</td>
                    <td>{{ number_format($school->student_count) }}</td>
                    <td>{{ number_format($school->staff_count) }}</td>
                    <td>{{ number_format($school->parent_count) }}</td>
                    <td>
                        @if($school->active_status)
                            <span class="usa-badge usa-badge-success">Active</span>
                        @else
                            <span class="usa-badge usa-badge-danger">Inactive</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
