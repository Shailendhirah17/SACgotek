@extends('backEnd.ultraSuperAdmin.layouts.master')
@section('title', 'Platform Broadcasts')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="usa-card">
            <div class="usa-card-title">
                <i class="fas fa-bullhorn" style="color: var(--usa-primary); margin-right: 8px;"></i>
                New Broadcast
            </div>
            <form action="{{ route('ultrasuperadmin.communication.broadcast.store') }}" method="POST">
                @csrf
                <div class="form-group" style="margin-top: 15px;">
                    <label>Title</label>
                    <input type="text" name="title" class="usa-input" required>
                </div>
                <div class="form-group" style="margin-top: 15px;">
                    <label>Message</label>
                    <textarea name="message" class="usa-input" rows="4" required></textarea>
                </div>
                <div class="form-group" style="margin-top: 15px;">
                    <label>Priority</label>
                    <select name="priority" class="usa-input" required>
                        <option value="info">Info (Standard)</option>
                        <option value="warning">Warning (Important)</option>
                        <option value="critical">Critical (Urgent)</option>
                    </select>
                </div>
                <div class="form-group" style="margin-top: 15px;">
                    <label>Target Group (Optional)</label>
                    <select name="target_school_group_id" class="usa-input">
                        <option value="">All School Groups</option>
                        @foreach($schoolGroups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-top: 15px; display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="is_published" id="is_published" checked>
                    <label for="is_published" style="margin: 0;">Publish Immediately</label>
                </div>
                <button type="submit" class="usa-btn usa-btn-primary" style="margin-top: 20px; width: 100%; justify-content: center;">
                    <i class="fas fa-paper-plane"></i> Send Broadcast
                </button>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="usa-card">
            <div class="usa-card-title">
                <i class="fas fa-list" style="color: var(--usa-secondary); margin-right: 8px;"></i>
                Recent Broadcasts
            </div>
            @if($announcements->count() > 0)
                <div style="margin-top: 15px;">
                    @foreach($announcements as $announcement)
                        <div style="padding: 15px; border: 1px solid var(--usa-border); border-radius: 8px; margin-bottom: 10px; background: rgba(255,255,255,0.02);">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div>
                                    <h5 style="margin: 0; color: var(--usa-text-primary);">
                                        @if($announcement->priority == 'critical')
                                            <i class="fas fa-exclamation-triangle" style="color: var(--usa-danger); margin-right: 5px;"></i>
                                        @elseif($announcement->priority == 'warning')
                                            <i class="fas fa-exclamation-circle" style="color: var(--usa-warning); margin-right: 5px;"></i>
                                        @else
                                            <i class="fas fa-info-circle" style="color: var(--usa-info); margin-right: 5px;"></i>
                                        @endif
                                        {{ $announcement->title }}
                                    </h5>
                                    <div style="font-size: 12px; color: var(--usa-text-muted); margin-top: 5px;">
                                        Target: {{ $announcement->targetGroup ? $announcement->targetGroup->name : 'All Platform' }} | 
                                        Date: {{ $announcement->created_at->format('M d, Y h:i A') }}
                                    </div>
                                </div>
                                <form action="{{ route('ultrasuperadmin.communication.broadcast.destroy', $announcement->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this broadcast?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="usa-btn" style="background: transparent; color: var(--usa-danger); padding: 5px;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                            <p style="margin-top: 10px; margin-bottom: 0; color: var(--usa-text-secondary); white-space: pre-wrap;">{{ $announcement->message }}</p>
                        </div>
                    @endforeach
                    <div style="margin-top: 20px;">
                        {{ $announcements->links() }}
                    </div>
                </div>
            @else
                <div style="text-align: center; padding: 40px; color: var(--usa-text-muted);">
                    <i class="fas fa-satellite-dish" style="font-size: 32px; margin-bottom: 12px; display: block;"></i>
                    <p>No broadcasts sent yet</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
