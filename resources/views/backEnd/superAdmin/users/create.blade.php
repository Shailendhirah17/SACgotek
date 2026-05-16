@extends('backEnd.superAdmin.layouts.master')
@section('title', 'Create Admin User')

@section('content')
<div class="usa-card" style="max-width: 700px;">
    <div class="usa-card-header">
        <span class="usa-card-title">Create New SuperAdmin User</span>
        <a href="{{ route('superadmin.users.index') }}" class="usa-btn usa-btn-outline usa-btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    <form action="{{ route('superadmin.users.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Full Name *</label>
                    <input type="text" name="full_name" class="usa-form-control" value="{{ old('full_name') }}" required>
                    @error('full_name') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Username *</label>
                    <input type="text" name="username" class="usa-form-control" value="{{ old('username') }}" required>
                    @error('username') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Email *</label>
                    <input type="email" name="email" class="usa-form-control" value="{{ old('email') }}" required>
                    @error('email') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Phone Number</label>
                    <input type="text" name="phone_number" class="usa-form-control" value="{{ old('phone_number') }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Password *</label>
                    <input type="password" name="password" class="usa-form-control" required>
                    @error('password') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Confirm Password *</label>
                    <input type="password" name="password_confirmation" class="usa-form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Role</label>
                    <select name="role" class="usa-form-control">
                        <option value="admin_manager">Admin Manager</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Status</label>
                    <select name="active_status" class="usa-form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
        </div>
        <div style="display: flex; gap: 12px; margin-top: 12px;">
            <button type="submit" class="usa-btn usa-btn-primary"><i class="fas fa-save"></i> Create User</button>
            <a href="{{ route('superadmin.users.index') }}" class="usa-btn usa-btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection
