@extends('backEnd.superAdmin.layouts.master')
@section('title', 'Support Tickets')

@section('content')
<div class="usa-card">
    <div class="usa-card-header">
        <span class="usa-card-title">SaaS Support Helpdesk</span>
    </div>
    
    <div style="margin-bottom: 20px; display: flex; gap: 10px;">
        <form action="{{ route('superadmin.tickets.index') }}" method="GET" style="display: flex; gap: 10px; flex-wrap: wrap;">
            <select name="status" class="usa-form-control" style="width: 150px;">
                <option value="">All Statuses</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="answered" {{ request('status') == 'answered' ? 'selected' : '' }}>Answered</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
            <select name="priority" class="usa-form-control" style="width: 150px;">
                <option value="">All Priorities</option>
                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
            </select>
            <button type="submit" class="usa-btn usa-btn-primary">Filter</button>
        </form>
    </div>

    <div class="usa-table-responsive">
        <table class="usa-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tenant School</th>
                    <th>Subject</th>
                    <th>Category</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Last Updated</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                <tr>
                    <td>#{{ $ticket->id }}</td>
                    <td>{{ $ticket->school->school_name ?? 'Unknown' }}</td>
                    <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <strong>{{ $ticket->subject }}</strong>
                    </td>
                    <td>{{ $ticket->category->name ?? 'General' }}</td>
                    <td>
                        @if($ticket->priority == 'urgent' || $ticket->priority == 'high')
                            <span class="usa-badge" style="background: var(--usa-danger);">{{ ucfirst($ticket->priority) }}</span>
                        @else
                            <span class="usa-badge" style="background: var(--usa-info);">{{ ucfirst($ticket->priority) }}</span>
                        @endif
                    </td>
                    <td>
                        @if($ticket->status == 'open')
                            <span class="usa-badge" style="background: var(--usa-warning);">Open</span>
                        @elseif($ticket->status == 'answered')
                            <span class="usa-badge" style="background: var(--usa-success);">Answered</span>
                        @elseif($ticket->status == 'closed')
                            <span class="usa-badge" style="background: var(--usa-text-muted);">Closed</span>
                        @else
                            <span class="usa-badge">{{ ucfirst($ticket->status) }}</span>
                        @endif
                    </td>
                    <td>{{ $ticket->updated_at->diffForHumans() }}</td>
                    <td>
                        <a href="{{ route('superadmin.tickets.show', $ticket->id) }}" class="usa-btn usa-btn-outline usa-btn-sm">View Ticket</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="usa-empty-state">
                        <i class="fas fa-ticket-alt"></i>
                        <p>No support tickets found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 20px;">
        {{ $tickets->links() }}
    </div>
</div>
@endsection
