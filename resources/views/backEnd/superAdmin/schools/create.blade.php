@extends('backEnd.superAdmin.layouts.master')
@section('title', 'Create School')

@section('content')
<div class="usa-card" style="max-width: 700px;">
    <div class="usa-card-header">
        <span class="usa-card-title">Create New School</span>
        <a href="{{ route('superadmin.school-list') }}" class="usa-btn usa-btn-outline usa-btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    <form action="{{ route('superadmin.school.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="usa-form-group">
                    <label class="usa-form-label">School Name *</label>
                    <input type="text" name="school_name" class="usa-form-control" value="{{ old('school_name') }}" required>
                    @error('school_name') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                </div>
            </div>

            <!-- Row 2: Email and Admin Password -->
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Email *</label>
                    <input type="email" name="email" class="usa-form-control" value="{{ old('email') }}" required>
                    @error('email') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Admin Password *</label>
                    <div style="position: relative;">
                        <input type="password" name="admin_password" id="admin_password" class="usa-form-control" required placeholder="Set a password">
                        <i class="fas fa-eye" id="togglePassword" style="position: absolute; right: 15px; top: 12px; cursor: pointer; color: var(--usa-text-muted);"></i>
                    </div>
                    @error('admin_password') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                </div>
            </div>

            <!-- Row 3: Primary and Secondary Phone -->
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Primary Contact Number</label>
                    <input type="text" name="primary_phone" class="usa-form-control" value="{{ old('primary_phone') }}">
                    @error('primary_phone') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Secondary Contact Number</label>
                    <input type="text" name="secondary_phone" class="usa-form-control" value="{{ old('secondary_phone') }}">
                    @error('secondary_phone') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                </div>
            </div>

            <script>
                document.getElementById('togglePassword').addEventListener('click', function (e) {
                    const password = document.getElementById('admin_password');
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    this.classList.toggle('fa-eye-slash');
                });
            </script>

            
            <div class="col-md-12">
                <hr style="border-color: rgba(255,255,255,0.05); margin: 20px 0;">
                <h6 style="color: var(--usa-text-muted); margin-bottom: 15px; font-weight: 500;">
                    <i class="fas fa-network-wired" style="margin-right: 8px;"></i> Routing Settings (SaaS)
                </h6>
            </div>
            
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">System Subdomain *</label>
                    <div style="display: flex; align-items: center; background: rgba(0,0,0,0.2); border-radius: 8px; border: 1px solid rgba(255,255,255,0.08); overflow: hidden;">
                        <input type="text" name="domain" class="usa-form-control" value="{{ old('domain') }}" required style="border: none; border-radius: 0; background: transparent; padding-right: 5px; text-align: right;" placeholder="schoolname">
                        <span style="color: var(--usa-text-muted); padding: 0 12px; font-size: 13px; font-family: monospace;">.{{ preg_replace('#^https?://#', '', rtrim(env('APP_URL', 'localhost'), '/')) }}</span>
                    </div>
                    @error('domain') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="usa-form-group">
                    <label class="usa-form-label">Custom Domain</label>
                    <input type="text" name="custom_domain" class="usa-form-control" value="{{ old('custom_domain') }}" placeholder="e.g., erp.myschool.com">
                    @error('custom_domain') <small style="color: var(--usa-danger);">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="usa-form-group">
                    <label class="usa-form-label">Address</label>
                    <textarea name="address" class="usa-form-control" rows="3">{{ old('address') }}</textarea>
                </div>
            </div>
        </div>
        <div style="display: flex; gap: 12px; margin-top: 12px;">
            <button type="submit" class="usa-btn usa-btn-primary"><i class="fas fa-save"></i> Create School</button>
            <a href="{{ route('superadmin.school-list') }}" class="usa-btn usa-btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection
