@extends('backEnd.superAdmin.layouts.master')
@section('title', 'My Profile')

@section('content')
<div class="row">
    <!-- Profile Info -->
    <div class="col-md-8">
        <div class="usa-card" style="margin-bottom: 20px;">
            <div class="usa-card-header">
                <span class="usa-card-title">Profile Information</span>
            </div>
            <form action="{{ route('superadmin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="usa-form-group">
                            <label class="usa-form-label">Full Name *</label>
                            <input type="text" name="full_name" class="usa-form-control" value="{{ $superAdmin->full_name }}" required>
                            @error('full_name') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="usa-form-group">
                            <label class="usa-form-label">Email *</label>
                            <input type="email" name="email" class="usa-form-control" value="{{ $superAdmin->email }}" required>
                            @error('email') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="usa-form-group">
                            <label class="usa-form-label">Phone Number</label>
                            <input type="text" name="phone_number" class="usa-form-control" value="{{ $superAdmin->phone_number }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="usa-form-group">
                            <label class="usa-form-label">Avatar</label>
                            <input type="file" name="avatar" class="usa-form-control" accept="image/*">
                        </div>
                    </div>
                </div>
                <button type="submit" class="usa-btn usa-btn-primary"><i class="fas fa-save"></i> Update Profile</button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="usa-card" style="margin-bottom: 20px;">
            <div class="usa-card-header">
                <span class="usa-card-title">Change Password</span>
            </div>
            <form action="{{ route('superadmin.profile.change-password') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="usa-form-group">
                            <label class="usa-form-label">Current Password *</label>
                            <input type="password" name="current_password" class="usa-form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="usa-form-group">
                            <label class="usa-form-label">New Password *</label>
                            <input type="password" name="new_password" class="usa-form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="usa-form-group">
                            <label class="usa-form-label">Confirm Password *</label>
                            <input type="password" name="new_password_confirmation" class="usa-form-control" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="usa-btn usa-btn-danger"><i class="fas fa-key"></i> Change Password</button>
            </form>
        </div>
    </div>

    <!-- Side Panel -->
    <div class="col-md-4">
        <div class="usa-card" style="margin-bottom: 20px; text-align: center;">
            <div class="usa-user-avatar" style="width: 80px; height: 80px; font-size: 28px; margin: 0 auto 16px;">
                {{ strtoupper(substr($superAdmin->full_name, 0, 2)) }}
            </div>
            <h5 style="font-weight: 700; margin-bottom: 4px;">{{ $superAdmin->full_name }}</h5>
            <p style="color: var(--usa-text-muted); font-size: 13px; margin-bottom: 12px;">{{ $superAdmin->email }}</p>
            <span class="usa-badge usa-badge-info">{{ ucfirst(str_replace('_', ' ', $superAdmin->role)) }}</span>

            <div style="margin-top: 20px; text-align: left;">
                <div class="usa-health-row"><span class="usa-health-key">Username</span><span class="usa-health-val">{{ $superAdmin->username }}</span></div>
                <div class="usa-health-row"><span class="usa-health-key">Phone</span><span class="usa-health-val">{{ $superAdmin->phone_number ?? 'N/A' }}</span></div>
                <div class="usa-health-row"><span class="usa-health-key">Last Login</span><span class="usa-health-val">{{ $superAdmin->last_login_at ? $superAdmin->last_login_at->diffForHumans() : 'N/A' }}</span></div>
                <div class="usa-health-row"><span class="usa-health-key">Last IP</span><span class="usa-health-val" style="font-family: monospace; font-size: 12px;">{{ $superAdmin->last_login_ip ?? 'N/A' }}</span></div>
                <div class="usa-health-row"><span class="usa-health-key">Created</span><span class="usa-health-val">{{ $superAdmin->created_at ? $superAdmin->created_at->format('M d, Y') : 'N/A' }}</span></div>
            </div>
        </div>

        <div class="usa-card">
            <div class="usa-card-header">
                <span class="usa-card-title">Recent Activity</span>
            </div>
            @forelse($recentActivity as $activity)
                <div style="padding: 8px 0; border-bottom: 1px solid var(--usa-border); font-size: 12px;">
                    <div style="color: var(--usa-text-secondary);">{{ $activity->description ?? $activity->action }}</div>
                    <div style="color: var(--usa-text-muted); margin-top: 2px;">{{ $activity->created_at->diffForHumans() }}</div>
                </div>
            @empty
                <p style="color: var(--usa-text-muted); font-size: 13px;">No recent activity</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
