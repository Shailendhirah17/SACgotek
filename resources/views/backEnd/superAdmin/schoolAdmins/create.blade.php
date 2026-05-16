@extends('backEnd.superAdmin.layouts.master')
@section('title', 'Add School Administrator')

@section('content')
<div class="usa-card" style="max-width: 800px;">
    <div class="usa-card-header">
        <span class="usa-card-title">Create Principal Account</span>
        <a href="{{ route('superadmin.school-admins.index') }}" class="usa-btn usa-btn-outline usa-btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    
    <div style="padding: 15px 20px; background: rgba(0,0,0,0.1); border-bottom: 1px solid rgba(255,255,255,0.05);">
        <p style="color: var(--usa-text-muted); font-size: 13px; margin: 0;">
            <i class="fas fa-info-circle" style="margin-right: 5px;"></i> 
            This account will be created in the <strong>School Layer</strong>. The person will log in at <code>{{ url('/login') }}</code> to manage their assigned school.
        </p>
    </div>

    <form action="{{ route('superadmin.school-admins.store') }}" method="POST">
        @csrf
        <div class="row" style="padding: 20px;">
            <div class="col-md-12">
                <div class="usa-form-group">
                    <label class="usa-form-label">Assign to School *</label>
                    <select name="school_id" class="usa-form-control" required>
                        <option value="">Select School...</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
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
                    <input type="text" name="full_name" class="usa-form-control" value="{{ old('full_name') }}" required placeholder="e.g. John Doe">
                    @error('full_name') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Username *</label>
                    <input type="text" name="username" class="usa-form-control" value="{{ old('username') }}" required placeholder="jane_admin">
                    @error('username') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="usa-form-group">
                    <label class="usa-form-label">Email Address *</label>
                    <input type="email" name="email" class="usa-form-control" value="{{ old('email') }}" required placeholder="admin@school.com">
                    @error('email') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
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

            <div class="col-md-12">
                <div class="usa-form-group">
                    <label class="usa-form-label">Phone Number</label>
                    <input type="text" name="phone" class="usa-form-control" value="{{ old('phone') }}">
                </div>
            </div>
        </div>

        <div style="padding: 20px; background: rgba(0,0,0,0.05); display: flex; gap: 12px; border-top: 1px solid rgba(255,255,255,0.05);">
            <button type="submit" class="usa-btn usa-btn-primary"><i class="fas fa-check"></i> Create Administrator</button>
            <a href="{{ route('superadmin.school-admins.index') }}" class="usa-btn usa-btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection
