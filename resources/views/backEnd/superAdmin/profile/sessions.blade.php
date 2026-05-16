@extends('backEnd.superAdmin.layouts.master')
@section('title', 'Active Sessions')

@section('content')
<div class="usa-card" style="margin-bottom: 20px;">
    <div class="usa-card-header">
        <span class="usa-card-title">Login History</span>
    </div>
    <table class="usa-table">
        <thead>
            <tr>
                <th>Action</th>
                <th>Description</th>
                <th>IP Address</th>
                <th>User Agent</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loginHistory as $log)
                <tr>
                    <td>
                        <span class="usa-badge {{ $log->action === 'login' ? 'usa-badge-success' : 'usa-badge-info' }}">
                            {{ ucfirst($log->action) }}
                        </span>
                    </td>
                    <td>{{ $log->description }}</td>
                    <td style="font-family: monospace; font-size: 12px;">{{ $log->ip_address ?? '-' }}</td>
                    <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 11px;">{{ $log->user_agent ?? '-' }}</td>
                    <td style="white-space: nowrap;">{{ $log->created_at->format('M d, H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align: center; color: var(--usa-text-muted); padding: 40px;">No login history found</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($sessions->count() > 0)
<div class="usa-card">
    <div class="usa-card-header">
        <span class="usa-card-title">Active Database Sessions</span>
    </div>
    <table class="usa-table">
        <thead>
            <tr>
                <th>Session ID</th>
                <th>IP Address</th>
                <th>Last Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sessions as $session)
                <tr>
                    <td style="font-family: monospace; font-size: 11px;">{{ substr($session->id, 0, 20) }}...</td>
                    <td style="font-family: monospace;">{{ $session->ip_address ?? 'N/A' }}</td>
                    <td>{{ $session->last_activity_formatted }}</td>
                    <td>
                        <form action="{{ route('superadmin.profile.terminate-session') }}" method="POST" style="display: inline;">
                            @csrf
                            <input type="hidden" name="session_id" value="{{ $session->id }}">
                            <button type="submit" class="usa-btn usa-btn-danger usa-btn-sm" onclick="return confirm('Terminate this session?')">
                                <i class="fas fa-times"></i> Terminate
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection
