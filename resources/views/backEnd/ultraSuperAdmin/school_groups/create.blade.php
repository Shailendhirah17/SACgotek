@extends('backEnd.ultraSuperAdmin.layouts.master')
@section('title', 'Create School Group')

@section('content')
<div style="max-width: 800px;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('ultrasuperadmin.school-groups.index') }}" class="usa-btn usa-btn-outline usa-btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Groups
        </a>
    </div>

    <div class="usa-card">
        <div class="usa-card-title" style="margin-bottom: 24px;">
            <i class="fas fa-layer-group" style="color: var(--usa-primary-light); margin-right: 8px;"></i>
            Create New School Group
        </div>

        <form method="POST" action="{{ route('ultrasuperadmin.school-groups.store') }}">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="usa-form-group">
                    <label class="usa-form-label">Group Name *</label>
                    <input type="text" name="name" class="usa-form-control" value="{{ old('name') }}" required placeholder="e.g., ABC Education Group">
                    @error('name') <div style="color: var(--usa-danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
                </div>

                <div class="usa-form-group">
                    <label class="usa-form-label">Group Code *</label>
                    <input type="text" name="code" class="usa-form-control" value="{{ old('code') }}" required placeholder="e.g., ABCEDU" style="text-transform: uppercase;">
                    @error('code') <div style="color: var(--usa-danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="usa-form-group">
                <label class="usa-form-label">Description</label>
                <textarea name="description" class="usa-form-control" placeholder="Describe this school group...">{{ old('description') }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                <div class="usa-form-group">
                    <label class="usa-form-label">Subscription Plan *</label>
                    <select name="subscription_plan" class="usa-form-control" required>
                        <option value="standard" {{ old('subscription_plan') === 'standard' ? 'selected' : '' }}>Standard</option>
                        <option value="professional" {{ old('subscription_plan') === 'professional' ? 'selected' : '' }}>Professional</option>
                        <option value="enterprise" {{ old('subscription_plan') === 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                        <option value="custom" {{ old('subscription_plan') === 'custom' ? 'selected' : '' }}>Custom</option>
                    </select>
                </div>

                <div class="usa-form-group">
                    <label class="usa-form-label">Max Schools *</label>
                    <input type="number" name="max_schools" class="usa-form-control" value="{{ old('max_schools', 5) }}" min="1" required>
                </div>

                <div class="usa-form-group">
                    <label class="usa-form-label">Max Students/School *</label>
                    <input type="number" name="max_students_per_school" class="usa-form-control" value="{{ old('max_students_per_school', 500) }}" min="1" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="usa-form-group">
                    <label class="usa-form-label">Subscription Start</label>
                    <input type="date" name="subscription_start" class="usa-form-control" value="{{ old('subscription_start', date('Y-m-d')) }}">
                </div>

                <div class="usa-form-group">
                    <label class="usa-form-label">Subscription End</label>
                    <input type="date" name="subscription_end" class="usa-form-control" value="{{ old('subscription_end') }}">
                </div>
            </div>

            <div style="border-top: 1px solid var(--usa-border); margin-top: 8px; padding-top: 20px;">
                <div class="usa-card-title" style="margin-bottom: 16px; font-size: 14px;">
                    <i class="fas fa-file-invoice" style="color: var(--usa-secondary); margin-right: 8px;"></i>
                    Billing Information
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="usa-form-group">
                        <label class="usa-form-label">Contact Name</label>
                        <input type="text" name="billing_contact_name" class="usa-form-control" value="{{ old('billing_contact_name') }}" placeholder="Billing contact name">
                    </div>
                    <div class="usa-form-group">
                        <label class="usa-form-label">Contact Email</label>
                        <input type="email" name="billing_contact_email" class="usa-form-control" value="{{ old('billing_contact_email') }}" placeholder="billing@example.com">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px;">
                    <div class="usa-form-group">
                        <label class="usa-form-label">Billing Address</label>
                        <textarea name="billing_address" class="usa-form-control" placeholder="Full billing address">{{ old('billing_address') }}</textarea>
                    </div>
                    <div class="usa-form-group">
                        <label class="usa-form-label">Phone</label>
                        <input type="text" name="billing_phone" class="usa-form-control" value="{{ old('billing_phone') }}" placeholder="+91 XXXXX XXXXX">
                    </div>
                </div>
            </div>

            <div style="margin-top: 24px; display: flex; gap: 12px;">
                <button type="submit" class="usa-btn usa-btn-primary">
                    <i class="fas fa-check"></i> Create School Group
                </button>
                <a href="{{ route('ultrasuperadmin.school-groups.index') }}" class="usa-btn usa-btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
