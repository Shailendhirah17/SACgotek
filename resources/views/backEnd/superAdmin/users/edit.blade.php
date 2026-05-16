@extends('backEnd.superAdmin.layouts.master')
@section('title', 'Edit Admin User')

@section('content')
<div class="usa-card" style="max-width: 700px;">
    <div class="usa-card-header">
        <span class="usa-card-title">Edit User: {{ $user->full_name }}</span>
        <a href="{{ route('superadmin.users.index') }}" class="usa-btn usa-btn-outline usa-btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    <form action="{{ route('superadmin.users.update', $user->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Full Name *</label>
                    <input type="text" name="full_name" class="usa-form-control" value="{{ old('full_name', $user->full_name) }}" required>
                    @error('full_name') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Username *</label>
                    <input type="text" name="username" class="usa-form-control" value="{{ old('username', $user->username) }}" required>
                    @error('username') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Email *</label>
                    <input type="email" name="email" class="usa-form-control" value="{{ old('email', $user->email) }}" required>
                    @error('email') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Phone Number</label>
                    <input type="text" name="phone_number" class="usa-form-control" value="{{ old('phone_number', $user->phone_number) }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">New Password <small style="color: var(--usa-text-muted);">(leave blank to keep)</small></label>
                    <input type="password" name="password" class="usa-form-control">
                    @error('password') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="usa-form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Role</label>
                    <select name="role" class="usa-form-control">
                        <option value="admin_manager" {{ $user->role == 'admin_manager' ? 'selected' : '' }}>Admin Manager</option>
                        <option value="super_admin" {{ $user->role == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Status</label>
                    <select name="active_status" class="usa-form-control">
                        <option value="1" {{ $user->active_status ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$user->active_status ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
        </div>
        <div style="display: flex; gap: 12px; margin-top: 12px;">
            <button type="submit" class="usa-btn usa-btn-primary"><i class="fas fa-save"></i> Update User</button>
            <a href="{{ route('superadmin.users.index') }}" class="usa-btn usa-btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection
