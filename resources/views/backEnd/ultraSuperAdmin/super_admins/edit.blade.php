@extends('backEnd.ultraSuperAdmin.layouts.master')
@section('title', 'Edit Super Admin')

@section('content')
<div style="max-width: 600px;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('ultrasuperadmin.super-admins.index') }}" class="usa-btn usa-btn-outline usa-btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    <div class="usa-card">
        <div class="usa-card-title" style="margin-bottom: 24px;"><i class="fas fa-user-edit" style="color: var(--usa-primary-light); margin-right: 8px;"></i>Edit: {{ $superAdmin->full_name }}</div>
        <form method="POST" action="{{ route('ultrasuperadmin.super-admins.update', $superAdmin->id) }}">
            @csrf @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="usa-form-group"><label class="usa-form-label">Full Name *</label><input type="text" name="full_name" class="usa-form-control" value="{{ old('full_name', $superAdmin->full_name) }}" required>@error('full_name')<div style="color:var(--usa-danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror</div>
                <div class="usa-form-group"><label class="usa-form-label">Username *</label><input type="text" name="username" class="usa-form-control" value="{{ old('username', $superAdmin->username) }}" required>@error('username')<div style="color:var(--usa-danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror</div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="usa-form-group"><label class="usa-form-label">Email *</label><input type="email" name="email" class="usa-form-control" value="{{ old('email', $superAdmin->email) }}" required>@error('email')<div style="color:var(--usa-danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror</div>
                <div class="usa-form-group"><label class="usa-form-label">Phone</label><input type="text" name="phone_number" class="usa-form-control" value="{{ old('phone_number', $superAdmin->phone_number) }}"></div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr; gap: 16px; margin-bottom: 16px;">
                <div class="usa-form-group">
                    <label class="usa-form-label">School Group (Assigned Tenant)</label>
                    <select name="school_group_id" class="usa-form-control">
                        <option value="">-- Master Tenant (Manage All Groups) --</option>
                        @foreach($schoolGroups as $group)
                            <option value="{{ $group->id }}" {{ old('school_group_id', $superAdmin->school_group_id) == $group->id ? 'selected' : '' }}>{{ $group->name }} ({{ $group->code }})</option>
                        @endforeach
                    </select>
                    @error('school_group_id')<div style="color:var(--usa-danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="usa-form-group"><label class="usa-form-label">New Password <small>(leave blank to keep)</small></label><input type="password" name="password" class="usa-form-control">@error('password')<div style="color:var(--usa-danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror</div>
                <div class="usa-form-group"><label class="usa-form-label">Confirm Password</label><input type="password" name="password_confirmation" class="usa-form-control"></div>
            </div>
            <div style="margin-top: 24px; display: flex; gap: 12px;">
                <button type="submit" class="usa-btn usa-btn-primary"><i class="fas fa-save"></i> Update Super Admin</button>
                <a href="{{ route('ultrasuperadmin.super-admins.index') }}" class="usa-btn usa-btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
