@extends('backEnd.superAdmin.layouts.master')
@section('title', 'Admin Users')

@section('content')
<div class="usa-card">
    <div class="usa-card-header">
        <span class="usa-card-title">SuperAdmin Users</span>
        <a href="{{ route('superadmin.users.create') }}" class="usa-btn usa-btn-primary"><i class="fas fa-plus"></i> Add User</a>
    </div>
    <table class="usa-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Last Login</th>
                <th>Actions</th>

            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>

                    <td style="color: var(--usa-text-primary); font-weight: 500;">{{ $user->full_name }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="usa-badge usa-badge-info">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span></td>
                    <td>
                        @if($user->active_status)
                            <span class="usa-badge usa-badge-success">Active</span>
                        @else
                            <span class="usa-badge usa-badge-danger">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <a href="{{ route('superadmin.users.edit', $user->id) }}" class="usa-btn usa-btn-outline usa-btn-sm"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('superadmin.users.toggle-status', $user->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="usa-btn usa-btn-sm {{ $user->active_status ? 'usa-btn-danger' : 'usa-btn-success' }}">
                                    <i class="fas {{ $user->active_status ? 'fa-ban' : 'fa-check' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="usa-btn usa-btn-danger usa-btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align: center; color: var(--usa-text-muted); padding: 40px;">No users found</td></tr>
            @endforelse
        </tbody>
    </table>
    @if(method_exists($users, 'links'))
        <div class="usa-pagination">{{ $users->links() }}</div>
    @endif
</div>
@endsection
