@extends('backEnd.superAdmin.layouts.master')
@section('title', 'Ticket #' . $ticket->id)

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Ticket #{{ $ticket->id }} - {{ $ticket->subject }}</h2>
    <a href="{{ route('superadmin.tickets.index') }}" class="usa-btn usa-btn-outline"><i class="fas fa-arrow-left"></i> Back to Tickets</a>
</div>

<div class="row">
    <!-- Ticket Conversation Column -->
    <div class="col-md-8">
        <div class="usa-card" style="margin-bottom: 20px; background: rgba(0,0,0,0.1); border-left: 3px solid var(--usa-primary);">
            <div style="display: flex; gap: 15px;">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--usa-primary); display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 18px;">
                    C
                </div>
                <div style="flex: 1;">
                    <h5 style="margin: 0; font-size: 16px;">Client / {{ $ticket->school->school_name ?? 'School Admin' }}</h5>
                    <small style="color: var(--usa-text-muted);">{{ $ticket->created_at->format('d M Y, h:i A') }}</small>
                    <hr style="border-color: rgba(255,255,255,0.05); margin: 10px 0;">
                    <div style="line-height: 1.6;">
                        {!! nl2br(e($ticket->description)) !!}
                    </div>
                </div>
            </div>
        </div>

        @foreach($ticket->replies as $reply)
        <div class="usa-card" style="margin-bottom: 15px; border-left: 3px solid {{ $reply->is_staff ? 'var(--usa-success)' : 'var(--usa-primary)' }};">
            <div style="display: flex; gap: 15px;">
                @if($reply->is_staff)
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--usa-success); display: flex; align-items: center; justify-content: center; font-weight: bold;">
                        <i class="fas fa-headset"></i>
                    </div>
                @else
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--usa-primary); display: flex; align-items: center; justify-content: center; font-weight: bold;">
                        C
                    </div>
                @endif
                <div style="flex: 1;">
                    <h5 style="margin: 0; font-size: 16px;">
                        @if($reply->is_staff)
                            {{ $reply->superAdmin->name ?? 'Staff' }} <span class="usa-badge" style="background: var(--usa-success); font-size: 10px; padding: 2px 6px;">Staff</span>
                        @else
                            Client / {{ $ticket->school->school_name ?? 'School Admin' }}
                        @endif
                    </h5>
                    <small style="color: var(--usa-text-muted);">{{ $reply->created_at->format('d M Y, h:i A') }}</small>
                    <hr style="border-color: rgba(255,255,255,0.05); margin: 10px 0;">
                    <div style="line-height: 1.6;">
                        {!! nl2br(e($reply->reply)) !!}
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        @if($ticket->status != 'closed')
        <div class="usa-card" style="margin-top: 30px;">
            <div class="usa-card-header">
                <span class="usa-card-title">Post a Reply</span>
            </div>
            <form action="{{ route('superadmin.tickets.reply', $ticket->id) }}" method="POST">
                @csrf
                <div class="usa-form-group">
                    <textarea name="reply" class="usa-form-control" rows="5" required placeholder="Type your response to the client here..."></textarea>
                </div>
                <button type="submit" class="usa-btn usa-btn-primary"><i class="fas fa-reply"></i> Send Reply</button>
            </form>
        </div>
        @endif
    </div>

    <!-- Ticket Sidebar Column -->
    <div class="col-md-4">
        <div class="usa-card" style="margin-bottom: 20px;">
            <div class="usa-card-header">
                <span class="usa-card-title">Ticket Information</span>
            </div>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div>
                    <small style="color: var(--usa-text-muted); display: block;">Status</small>
                    @if($ticket->status == 'open')
                        <span class="usa-badge" style="background: var(--usa-warning);">Open</span>
                    @elseif($ticket->status == 'answered')
                        <span class="usa-badge" style="background: var(--usa-success);">Answered</span>
                    @elseif($ticket->status == 'closed')
                        <span class="usa-badge" style="background: var(--usa-text-muted);">Closed</span>
                    @else
                        <span class="usa-badge">{{ ucfirst($ticket->status) }}</span>
                    @endif
                </div>
                
                <div>
                    <small style="color: var(--usa-text-muted); display: block;">Priority</small>
                    @if($ticket->priority == 'urgent' || $ticket->priority == 'high')
                        <span class="usa-badge" style="background: var(--usa-danger);">{{ ucfirst($ticket->priority) }}</span>
                    @else
                        <span class="usa-badge" style="background: var(--usa-info);">{{ ucfirst($ticket->priority) }}</span>
                    @endif
                </div>

                <div>
                    <small style="color: var(--usa-text-muted); display: block;">Category</small>
                    <strong>{{ $ticket->category->name ?? 'General' }}</strong>
                </div>

                <div>
                    <small style="color: var(--usa-text-muted); display: block;">Assigned To</small>
                    <strong>{{ $ticket->assignedTo->name ?? 'Unassigned' }}</strong>
                </div>

                <div>
                    <small style="color: var(--usa-text-muted); display: block;">Tenant School</small>
                    <strong>{{ $ticket->school->school_name ?? 'N/A' }}</strong>
                    @if(isset($ticket->school))
                        <div><small>{{ $ticket->school->email }}</small></div>
                    @endif
                </div>
            </div>
        </div>

        <div class="usa-card">
            <div class="usa-card-header">
                <span class="usa-card-title">Manage Ticket</span>
            </div>
            <form action="{{ route('superadmin.tickets.status', $ticket->id) }}" method="POST">
                @csrf
                <div class="usa-form-group">
                    <label class="usa-form-label">Change Status</label>
                    <select name="status" class="usa-form-control">
                        <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="answered" {{ $ticket->status == 'answered' ? 'selected' : '' }}>Answered</option>
                        <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <button type="submit" class="usa-btn usa-btn-outline" style="width: 100%;">Update Status</button>
            </form>
        </div>
    </div>
</div>
@endsection
