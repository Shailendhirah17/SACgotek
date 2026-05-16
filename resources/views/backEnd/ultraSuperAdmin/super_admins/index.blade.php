@extends('backEnd.ultraSuperAdmin.layouts.master')
@section('title', 'Super Admin Management')

@section('content')
<div class="usa-card-header" style="margin-bottom: 20px;">
    <div>
        <h4 style="font-size: 20px; font-weight: 700;">Super Admin Management</h4>
        <p style="font-size: 13px; color: var(--usa-text-muted); margin-top: 4px;">Full control over all Super Admin users</p>
    </div>
    <a href="{{ route('ultrasuperadmin.super-admins.create') }}" class="usa-btn usa-btn-primary">
        <i class="fas fa-user-plus"></i> Add Super Admin
    </a>
</div>

<div class="usa-card" style="margin-bottom: 20px; padding: 16px 20px;">
    <form method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <input type="text" name="search" class="usa-form-control" placeholder="Search by name, email, username..." value="{{ request('search') }}" style="max-width: 300px;">
        <select name="status" class="usa-form-control" style="max-width: 150px;">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button type="submit" class="usa-btn usa-btn-outline usa-btn-sm"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<div class="usa-card">
    <table class="usa-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>School Group</th>
                <th>Username</th>
                <th>Email</th>
                <th>Last Login</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($superAdmins as $admin)
            <tr>
                <td>{{ $admin->id }}</td>
                <td style="color: var(--usa-text-primary); font-weight: 600;">{{ $admin->full_name }}</td>
                <td>
                    @if($admin->schoolGroup)
                        <span class="usa-badge" style="background: rgba(100, 108, 255, 0.1); color: var(--usa-primary-light); border: 1px solid rgba(100, 108, 255, 0.2);">
                            <i class="fas fa-building" style="margin-right: 4px;"></i> {{ $admin->schoolGroup->name }}
                        </span>
                    @else
                        <span class="usa-badge" style="background: rgba(255, 255, 255, 0.05); color: var(--usa-text-muted); border: 1px solid rgba(255, 255, 255, 0.1);">
                            <i class="fas fa-globe" style="margin-right: 4px;"></i> Global
                        </span>
                    @endif
                </td>
                <td>{{ $admin->username }}</td>
                <td>{{ $admin->email }}</td>
                <td style="font-size: 12px;">{{ $admin->last_login_at ? $admin->last_login_at->diffForHumans() : 'Never' }}</td>
                <td><span class="usa-badge {{ $admin->active_status ? 'usa-badge-success' : 'usa-badge-danger' }}">{{ $admin->active_status ? 'Active' : 'Inactive' }}</span></td>
                <td>
                    <div style="display: flex; gap: 6px;">
                        <a href="{{ route('ultrasuperadmin.super-admins.edit', $admin->id) }}" class="usa-btn usa-btn-outline usa-btn-sm"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{ route('ultrasuperadmin.super-admins.toggle-status', $admin->id) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="usa-btn usa-btn-sm {{ $admin->active_status ? 'usa-btn-warning' : 'usa-btn-success' }}">
                                <i class="fas {{ $admin->active_status ? 'fa-ban' : 'fa-check' }}"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('ultrasuperadmin.super-admins.destroy', $admin->id) }}" style="display:inline;" onsubmit="return confirm('Delete this Super Admin?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="usa-btn usa-btn-danger usa-btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align: center; padding: 40px; color: var(--usa-text-muted);">No Super Admins found.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($superAdmins->hasPages())
    <div class="usa-pagination">{{ $superAdmins->appends(request()->query())->links() }}</div>
    @endif
</div>
@endsection
