@extends('backEnd.superAdmin.layouts.master')
@section('title', 'Settings')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="usa-card" style="margin-bottom: 20px;">
            <div class="usa-card-header">
                <span class="usa-card-title">System Settings</span>
            </div>
            <form action="{{ route('superadmin.settings.update') }}" method="POST">
                @csrf
                <div class="usa-form-group">
                    <label class="usa-form-label">Platform Name</label>
                    <input type="text" name="settings[platform_name]" class="usa-form-control" value="{{ \App\Models\SuperAdminSetting::get('platform_name', 'TISEDU') }}">
                </div>
                <div class="usa-form-group">
                    <label class="usa-form-label">Support Email</label>
                    <input type="email" name="settings[support_email]" class="usa-form-control" value="{{ \App\Models\SuperAdminSetting::get('support_email', '') }}">
                </div>
                <div class="usa-form-group">
                    <label class="usa-form-label">Max Schools Allowed</label>
                    <input type="number" name="settings[max_schools]" class="usa-form-control" value="{{ \App\Models\SuperAdminSetting::get('max_schools', '0') }}">
                    <small style="color: var(--usa-text-muted);">0 = unlimited</small>
                </div>
                <button type="submit" class="usa-btn usa-btn-primary"><i class="fas fa-save"></i> Save Settings</button>
            </form>
        </div>

        <div class="usa-card" style="margin-bottom: 20px;">
            <div class="usa-card-header">
                <span class="usa-card-title"><i class="fab fa-stripe" style="color: #635bff; margin-right: 8px;"></i>Global Payment Gateways (Stripe)</span>
            </div>
            <form action="{{ route('superadmin.settings.update') }}" method="POST">
                @csrf
                <div class="usa-form-group">
                    <label class="usa-form-label">Stripe Public Key</label>
                    <input type="text" name="settings[stripe_key]" class="usa-form-control" value="{{ \App\Models\SuperAdminSetting::get('stripe_key', '') }}" placeholder="pk_test_...">
                </div>
                <div class="usa-form-group">
                    <label class="usa-form-label">Stripe Secret Key</label>
                    <input type="password" name="settings[stripe_secret]" class="usa-form-control" value="{{ \App\Models\SuperAdminSetting::get('stripe_secret', '') }}" placeholder="sk_test_...">
                </div>
                <div class="usa-form-group" style="display: flex; align-items: center; gap: 10px; margin-top: 15px;">
                    <input type="hidden" name="settings[stripe_active]" value="0">
                    <input type="checkbox" name="settings[stripe_active]" value="1" id="stripe_active" {{ \App\Models\SuperAdminSetting::get('stripe_active', '0') == '1' ? 'checked' : '' }} style="width: 18px; height: 18px;">
                    <label for="stripe_active" style="margin: 0; font-weight: 500;">Enable Stripe for SaaS Subscriptions</label>
                </div>
                <button type="submit" class="usa-btn usa-btn-primary" style="margin-top: 15px;"><i class="fas fa-save"></i> Save Gateways</button>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="usa-card" style="margin-bottom: 20px;">
            <div class="usa-card-header">
                <span class="usa-card-title">Quick Actions</span>
            </div>
            <form action="{{ route('superadmin.settings.clear-cache') }}" method="POST" style="margin-bottom: 12px;">
                @csrf
                <button type="submit" class="usa-btn usa-btn-outline" style="width: 100%;"><i class="fas fa-broom"></i> Clear All Caches</button>
            </form>
            <form action="{{ route('superadmin.settings.toggle-maintenance') }}" method="POST">
                @csrf
                <button type="submit" class="usa-btn usa-btn-danger" style="width: 100%;"><i class="fas fa-tools"></i> Toggle Maintenance Mode</button>
            </form>
        </div>
    </div>
</div>
@endsection
