@extends('backEnd.superAdmin.layouts.master')
@section('title', 'Schools')

@section('content')
<div class="usa-card">
    <div class="usa-card-header">
        <span class="usa-card-title">School Management</span>
        <div style="display: flex; gap: 10px;">
            <form action="{{ route('superadmin.school-list') }}" method="GET" style="display: flex; gap: 8px;">
                <input type="text" name="search" class="usa-form-control" placeholder="Search schools..." value="{{ request('search') }}" style="width: 200px;">
                <select name="status" class="usa-form-control" style="width: 120px;" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <button type="submit" class="usa-btn usa-btn-outline usa-btn-sm"><i class="fas fa-search"></i></button>
            </form>
            <a href="{{ route('superadmin.school.create') }}" class="usa-btn usa-btn-primary"><i class="fas fa-plus"></i> Add School</a>
        </div>
    </div>
    <table class="usa-table">
        <thead>
            <tr>
                <th>#</th>
                <th>School Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Location</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schools as $school)
                <tr>
                    <td>{{ ($schools->currentPage() - 1) * $schools->perPage() + $loop->iteration }}</td>

                    <td style="color: var(--usa-text-primary); font-weight: 500;">{{ $school->school_name }}</td>
                    <td>{{ $school->email }}</td>
                    <td>{{ $school->phone ?? 'N/A' }}</td>
                    <td>{{ $school->city_name ? $school->city_name . ', ' : '' }}{{ $school->state_name ?? 'N/A' }}</td>
                    <td>
                        @if($school->active_status)
                            <span class="usa-badge usa-badge-success">Active</span>
                        @else
                            <span class="usa-badge usa-badge-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <form action="{{ route('superadmin.impersonate.start', $school->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="usa-btn usa-btn-primary usa-btn-sm" title="Login as School Admin">
                                    <i class="fas fa-sign-in-alt"></i> Login
                                </button>
                            </form>
                            <a href="{{ route('superadmin.school.show', $school->id) }}" class="usa-btn usa-btn-outline usa-btn-sm" title="View Details"><i class="fas fa-eye"></i> View</a>
                            <a href="{{ route('superadmin.school.edit', $school->id) }}" class="usa-btn usa-btn-outline usa-btn-sm" title="Edit"><i class="fas fa-edit"></i> Edit</a>
                            <form action="{{ route('superadmin.school.toggle-status') }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="id" value="{{ $school->id }}">
                                <button type="submit" class="usa-btn usa-btn-sm {{ $school->active_status ? 'usa-btn-danger' : 'usa-btn-success' }}" title="{{ $school->active_status ? 'Deactivate' : 'Activate' }}">

                                    <i class="fas {{ $school->active_status ? 'fa-ban' : 'fa-check' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('superadmin.school.destroy', $school->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this school?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="usa-btn usa-btn-danger usa-btn-sm" title="Delete"><i class="fas fa-trash"></i></button>

                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align: center; color: var(--usa-text-muted); padding: 40px;">No schools found</td></tr>
            @endforelse
        </tbody>
    </table>
    @if(method_exists($schools, 'links'))
        <div class="usa-pagination">{{ $schools->appends(request()->query())->links() }}</div>
    @endif
</div>
@endsection
