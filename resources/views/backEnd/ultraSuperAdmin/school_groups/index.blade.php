@extends('backEnd.ultraSuperAdmin.layouts.master')
@section('title', 'School Groups')

@section('content')
<div class="usa-card-header" style="margin-bottom: 20px;">
    <div>
        <h4 style="font-size: 20px; font-weight: 700;">School Groups</h4>
        <p style="font-size: 13px; color: var(--usa-text-muted); margin-top: 4px;">Manage organizational groups that contain schools</p>
    </div>
    <a href="{{ route('ultrasuperadmin.school-groups.create') }}" class="usa-btn usa-btn-primary">
        <i class="fas fa-plus"></i> Create Group
    </a>
</div>

<!-- Filters -->
<div class="usa-card" style="margin-bottom: 20px; padding: 16px 20px;">
    <form method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <input type="text" name="search" class="usa-form-control" placeholder="Search groups..." value="{{ request('search') }}" style="max-width: 280px;">
        <select name="status" class="usa-form-control" style="max-width: 150px;">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <select name="plan" class="usa-form-control" style="max-width: 180px;">
            <option value="">All Plans</option>
            <option value="standard" {{ request('plan') === 'standard' ? 'selected' : '' }}>Standard</option>
            <option value="professional" {{ request('plan') === 'professional' ? 'selected' : '' }}>Professional</option>
            <option value="enterprise" {{ request('plan') === 'enterprise' ? 'selected' : '' }}>Enterprise</option>
            <option value="custom" {{ request('plan') === 'custom' ? 'selected' : '' }}>Custom</option>
        </select>
        <button type="submit" class="usa-btn usa-btn-outline usa-btn-sm"><i class="fas fa-search"></i> Filter</button>
        <a href="{{ route('ultrasuperadmin.school-groups.index') }}" class="usa-btn usa-btn-outline usa-btn-sm"><i class="fas fa-times"></i> Reset</a>
    </form>
</div>

<!-- Groups Table -->
<div class="usa-card">
    <table class="usa-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Code</th>
                <th>Schools</th>
                <th>Plan</th>
                <th>Subscription</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schoolGroups as $group)
            <tr>
                <td>{{ $group->id }}</td>
                <td style="color: var(--usa-text-primary); font-weight: 600;">{{ $group->name }}</td>
                <td><span class="usa-badge usa-badge-primary">{{ $group->code }}</span></td>
                <td>{{ $group->schools_count }} / {{ $group->max_schools }}</td>
                <td><span class="usa-badge usa-badge-info">{{ ucfirst($group->subscription_plan) }}</span></td>
                <td>
                    @if($group->subscription_end)
                        @if($group->subscription_end->isPast())
                            <span class="usa-badge usa-badge-danger">Expired</span>
                        @elseif($group->subscription_end->diffInDays(now()) <= 30)
                            <span class="usa-badge usa-badge-warning">{{ $group->subscription_end->diffInDays(now()) }}d left</span>
                        @else
                            <span class="usa-badge usa-badge-success">{{ $group->subscription_end->format('M d, Y') }}</span>
                        @endif
                    @else
                        <span class="usa-badge usa-badge-success">Unlimited</span>
                    @endif
                </td>
                <td>
                    <span class="usa-badge {{ $group->active_status ? 'usa-badge-success' : 'usa-badge-danger' }}">
                        {{ $group->active_status ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <div style="display: flex; gap: 6px;">
                        <a href="{{ route('ultrasuperadmin.school-groups.show', $group->id) }}" class="usa-btn usa-btn-outline usa-btn-sm" title="View"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('ultrasuperadmin.school-groups.edit', $group->id) }}" class="usa-btn usa-btn-outline usa-btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{ route('ultrasuperadmin.school-groups.toggle-status') }}" style="display:inline;">
                            @csrf
                            <input type="hidden" name="id" value="{{ $group->id }}">
                            <button type="submit" class="usa-btn usa-btn-sm {{ $group->active_status ? 'usa-btn-warning' : 'usa-btn-success' }}" title="{{ $group->active_status ? 'Deactivate' : 'Activate' }}">
                                <i class="fas {{ $group->active_status ? 'fa-pause' : 'fa-play' }}"></i>
                            </button>
                        </form>
                        @if($group->code !== 'DEFAULT')
                        <form method="POST" action="{{ route('ultrasuperadmin.school-groups.destroy', $group->id) }}" style="display:inline;" onsubmit="return confirm('Delete this group?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="usa-btn usa-btn-danger usa-btn-sm" title="Delete"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px; color: var(--usa-text-muted);">
                    No school groups found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($schoolGroups->hasPages())
    <div class="usa-pagination">
        {{ $schoolGroups->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
