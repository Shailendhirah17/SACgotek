@extends('backEnd.superAdmin.layouts.master')
@section('title', 'School Administrators')

@section('content')
<div class="usa-card">
    <div class="usa-card-header">
        <span class="usa-card-title">School Administrators (Principals)</span>
        <a href="{{ route('superadmin.school-admins.create') }}" class="usa-btn usa-btn-primary"><i class="fas fa-plus"></i> Add Principal</a>
    </div>
    
    <div style="padding: 15px 20px; background: rgba(0,0,0,0.1); border-bottom: 1px solid rgba(255,255,255,0.05);">
        <p style="color: var(--usa-text-muted); font-size: 13px; margin: 0;">
            <i class="fas fa-info-circle" style="margin-right: 5px;"></i> 
            These users are the <strong>School Principals</strong>. They manage individual schools and log in directly at <code>/login</code>.
        </p>
    </div>

    <table class="usa-table">
        <thead>
            <tr>
                <th>#</th>
                <th>NAME</th>
                <th>USERNAME</th>
                <th>EMAIL</th>
                <th>SCHOOL</th>
                <th>STATUS</th>
                <th>ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                    <td style="color: var(--usa-text-primary); font-weight: 500;">{{ $user->full_name }}</td>
                    <td><code>{{ $user->username }}</code></td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span style="display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fas fa-school" style="color: var(--usa-primary); font-size: 12px;"></i>
                            {{ $user->school->school_name ?? 'N/A' }}
                        </span>
                    </td>
                    <td>
                        @if($user->active_status)
                            <span class="usa-badge usa-badge-success">Active</span>
                        @else
                            <span class="usa-badge usa-badge-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <a href="{{ route('superadmin.school-admins.edit', $user->id) }}" class="usa-btn usa-btn-outline usa-btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                            
                            <form action="{{ route('superadmin.impersonate.start', $user->school_id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="usa-btn usa-btn-primary usa-btn-sm" title="Login as this Principal">
                                    <i class="fas fa-sign-in-alt"></i> Login
                                </button>
                            </form>

                            <form action="{{ route('superadmin.school-admins.destroy', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this principal account?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="usa-btn usa-btn-danger usa-btn-sm" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align: center; color: var(--usa-text-muted); padding: 40px;">No school administrators found.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if(method_exists($users, 'links'))
        <div class="usa-pagination">{{ $users->links() }}</div>
    @endif
</div>
@endsection
