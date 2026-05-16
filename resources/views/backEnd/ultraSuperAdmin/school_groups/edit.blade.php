@extends('backEnd.ultraSuperAdmin.layouts.master')
@section('title', 'Edit School Group')

@section('content')
<div style="max-width: 800px;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('ultrasuperadmin.school-groups.index') }}" class="usa-btn usa-btn-outline usa-btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Groups
        </a>
    </div>

    <div class="usa-card">
        <div class="usa-card-title" style="margin-bottom: 24px;">
            <i class="fas fa-edit" style="color: var(--usa-primary-light); margin-right: 8px;"></i>
            Edit: {{ $group->name }}
        </div>

        <form method="POST" action="{{ route('ultrasuperadmin.school-groups.update', $group->id) }}">
            @csrf @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="usa-form-group">
                    <label class="usa-form-label">Group Name *</label>
                    <input type="text" name="name" class="usa-form-control" value="{{ old('name', $group->name) }}" required>
                    @error('name') <div style="color: var(--usa-danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
                </div>
                <div class="usa-form-group">
                    <label class="usa-form-label">Group Code *</label>
                    <input type="text" name="code" class="usa-form-control" value="{{ old('code', $group->code) }}" required style="text-transform: uppercase;" {{ $group->code === 'DEFAULT' ? 'readonly' : '' }}>
                    @error('code') <div style="color: var(--usa-danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="usa-form-group">
                <label class="usa-form-label">Description</label>
                <textarea name="description" class="usa-form-control">{{ old('description', $group->description) }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                <div class="usa-form-group">
                    <label class="usa-form-label">Subscription Plan *</label>
                    <select name="subscription_plan" class="usa-form-control" required>
                        @foreach(['standard', 'professional', 'enterprise', 'custom'] as $plan)
                        <option value="{{ $plan }}" {{ old('subscription_plan', $group->subscription_plan) === $plan ? 'selected' : '' }}>{{ ucfirst($plan) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="usa-form-group">
                    <label class="usa-form-label">Max Schools *</label>
                    <input type="number" name="max_schools" class="usa-form-control" value="{{ old('max_schools', $group->max_schools) }}" min="1" required>
                </div>
                <div class="usa-form-group">
                    <label class="usa-form-label">Max Students/School *</label>
                    <input type="number" name="max_students_per_school" class="usa-form-control" value="{{ old('max_students_per_school', $group->max_students_per_school) }}" min="1" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="usa-form-group">
                    <label class="usa-form-label">Subscription Start</label>
                    <input type="date" name="subscription_start" class="usa-form-control" value="{{ old('subscription_start', optional($group->subscription_start)->format('Y-m-d')) }}">
                </div>
                <div class="usa-form-group">
                    <label class="usa-form-label">Subscription End</label>
                    <input type="date" name="subscription_end" class="usa-form-control" value="{{ old('subscription_end', optional($group->subscription_end)->format('Y-m-d')) }}">
                </div>
            </div>

            <div style="border-top: 1px solid var(--usa-border); margin-top: 8px; padding-top: 20px;">
                <div class="usa-card-title" style="margin-bottom: 16px; font-size: 14px;">
                    <i class="fas fa-file-invoice" style="color: var(--usa-secondary); margin-right: 8px;"></i> Billing Information
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="usa-form-group">
                        <label class="usa-form-label">Contact Name</label>
                        <input type="text" name="billing_contact_name" class="usa-form-control" value="{{ old('billing_contact_name', $group->billing_contact_name) }}">
                    </div>
                    <div class="usa-form-group">
                        <label class="usa-form-label">Contact Email</label>
                        <input type="email" name="billing_contact_email" class="usa-form-control" value="{{ old('billing_contact_email', $group->billing_contact_email) }}">
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px;">
                    <div class="usa-form-group">
                        <label class="usa-form-label">Billing Address</label>
                        <textarea name="billing_address" class="usa-form-control">{{ old('billing_address', $group->billing_address) }}</textarea>
                    </div>
                    <div class="usa-form-group">
                        <label class="usa-form-label">Phone</label>
                        <input type="text" name="billing_phone" class="usa-form-control" value="{{ old('billing_phone', $group->billing_phone) }}">
                    </div>
                </div>
            </div>

            <div style="margin-top: 24px; display: flex; gap: 12px;">
                <button type="submit" class="usa-btn usa-btn-primary"><i class="fas fa-save"></i> Update School Group</button>
                <a href="{{ route('ultrasuperadmin.school-groups.index') }}" class="usa-btn usa-btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
