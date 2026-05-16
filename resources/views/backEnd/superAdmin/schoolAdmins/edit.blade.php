@extends('backEnd.superAdmin.layouts.master')
@section('title', 'Edit School Administrator')

@section('content')
<div class="usa-card" style="max-width: 800px;">
    <div class="usa-card-header">
        <span class="usa-card-title">Edit Principal: {{ $user->full_name }}</span>
        <a href="{{ route('superadmin.school-admins.index') }}" class="usa-btn usa-btn-outline usa-btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
    </div>

    <form action="{{ route('superadmin.school-admins.update', $user->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="row" style="padding: 20px;">
            <div class="col-md-12">
                <div class="usa-form-group">
                    <label class="usa-form-label">Assign to School *</label>
                    <select name="school_id" class="usa-form-control" required>
                        <option value="">Select School...</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ old('school_id', $user->school_id) == $school->id ? 'selected' : '' }}>
                                {{ $school->school_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('school_id') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                </div>
            </div>

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

            <div class="col-md-12">
                <div class="usa-form-group">
                    <label class="usa-form-label">Email Address *</label>
                    <input type="email" name="email" class="usa-form-control" value="{{ old('email', $user->email) }}" required>
                    @error('email') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
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

            <div class="col-md-12">
                <div class="usa-form-group">
                    <label class="usa-form-label">Phone Number</label>
                    <input type="text" name="phone" class="usa-form-control" value="{{ old('phone', $user->phone) }}">
                </div>
            </div>
        </div>

        <div style="padding: 20px; background: rgba(0,0,0,0.05); display: flex; gap: 12px; border-top: 1px solid rgba(255,255,255,0.05);">
            <button type="submit" class="usa-btn usa-btn-primary"><i class="fas fa-save"></i> Save Changes</button>
            <a href="{{ route('superadmin.school-admins.index') }}" class="usa-btn usa-btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection
