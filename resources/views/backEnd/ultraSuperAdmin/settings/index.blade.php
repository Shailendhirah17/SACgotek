@extends('backEnd.ultraSuperAdmin.layouts.master')
@section('title', 'Platform Settings')

@section('content')
<div style="margin-bottom: 24px;">
    <h4 style="font-size: 20px; font-weight: 700;">Platform Settings</h4>
    <p style="font-size: 13px; color: var(--usa-text-muted); margin-top: 4px;">Global platform configuration managed by GOTEK</p>
</div>

<!-- System Configuration -->
<div class="usa-card" style="margin-bottom: 20px;">
    <div class="usa-card-title" style="margin-bottom: 20px;">
        <i class="fas fa-server" style="color: var(--usa-primary-light); margin-right: 8px;"></i>
        System Configuration
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 14px;">
        @foreach($settings as $key => $value)
        <div style="padding: 14px 16px; background: rgba(255,255,255,0.02); border: 1px solid var(--usa-border); border-radius: 10px;">
            <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--usa-text-muted); margin-bottom: 6px;">
                {{ str_replace('_', ' ', $key) }}
            </div>
            <div style="font-size: 14px; font-weight: 600; color: var(--usa-text-primary);">
                @if(is_bool($value))
                    <span class="usa-badge {{ $value ? 'usa-badge-success' : 'usa-badge-danger' }}">{{ $value ? 'Enabled' : 'Disabled' }}</span>
                @else
                    {{ $value ?? 'N/A' }}
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Actions -->
<div style="display: flex; gap: 20px; flex-wrap: wrap;">
    <!-- Cache Management -->
    <div class="usa-card" style="flex: 1; min-width: 300px;">
        <div class="usa-card-title" style="margin-bottom: 16px;">
            <i class="fas fa-broom" style="color: var(--usa-warning); margin-right: 8px;"></i>
            Cache Management
        </div>
        <p style="font-size: 13px; color: var(--usa-text-secondary); margin-bottom: 16px;">
            Clear all application caches including config, views, routes, and compiled files.
        </p>
        <form method="POST" action="{{ route('ultrasuperadmin.settings.clear-cache') }}">
            @csrf
            <button type="submit" class="usa-btn usa-btn-warning" onclick="return confirm('Clear all caches?');">
                <i class="fas fa-trash-alt"></i> Clear All Caches
            </button>
        </form>
    </div>

    <!-- Maintenance Mode -->
    <div class="usa-card" style="flex: 1; min-width: 300px;">
        <div class="usa-card-title" style="margin-bottom: 16px;">
            <i class="fas fa-hard-hat" style="color: var(--usa-danger); margin-right: 8px;"></i>
            Maintenance Mode
        </div>
        <p style="font-size: 13px; color: var(--usa-text-secondary); margin-bottom: 8px;">
            Current Status:
            @if(app()->isDownForMaintenance())
                <span class="usa-badge usa-badge-danger">MAINTENANCE</span>
            @else
                <span class="usa-badge usa-badge-success">LIVE</span>
            @endif
        </p>
        <p style="font-size: 12px; color: var(--usa-text-muted); margin-bottom: 16px;">
            Toggle maintenance mode for the entire platform. Your IP will be whitelisted.
        </p>
        <form method="POST" action="{{ route('ultrasuperadmin.settings.toggle-maintenance') }}">
            @csrf
            <button type="submit" class="usa-btn {{ app()->isDownForMaintenance() ? 'usa-btn-success' : 'usa-btn-danger' }}"
                    onclick="return confirm('{{ app()->isDownForMaintenance() ? 'Bring platform back online?' : 'Put platform in maintenance mode?' }}');">
                <i class="fas {{ app()->isDownForMaintenance() ? 'fa-play' : 'fa-pause' }}"></i>
                {{ app()->isDownForMaintenance() ? 'Go Live' : 'Enable Maintenance' }}
            </button>
        </form>
    </div>
</div>
@endsection
