@extends('backEnd.ultraSuperAdmin.layouts.master')
@section('title', 'Feature Control')

@section('content')
<div style="margin-bottom: 24px;">
    <h4 style="font-size: 20px; font-weight: 700;">Feature Control</h4>
    <p style="font-size: 13px; color: var(--usa-text-muted); margin-top: 4px;">Enable or disable features for entire school groups</p>
</div>

@foreach($groups as $group)
<div class="usa-card" style="margin-bottom: 20px;">
    <div class="usa-card-header">
        <div class="usa-card-title">
            <i class="fas fa-layer-group" style="color: var(--usa-primary-light); margin-right: 8px;"></i>
            {{ $group->name }}
            <span class="usa-badge usa-badge-primary" style="margin-left: 8px;">{{ $group->code }}</span>
        </div>
        <div style="display: flex; gap: 8px;">
            <form method="POST" action="{{ route('ultrasuperadmin.features.enable-all') }}" style="display: inline;">
                @csrf <input type="hidden" name="group_id" value="{{ $group->id }}">
                <button type="submit" class="usa-btn usa-btn-success usa-btn-sm"><i class="fas fa-check-double"></i> Enable All</button>
            </form>
            <form method="POST" action="{{ route('ultrasuperadmin.features.disable-all') }}" style="display: inline;">
                @csrf <input type="hidden" name="group_id" value="{{ $group->id }}">
                <button type="submit" class="usa-btn usa-btn-danger usa-btn-sm"><i class="fas fa-times"></i> Disable All</button>
            </form>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 10px;">
        @foreach($availableFeatures as $key => $name)
        @php
            $feature = $group->features->where('feature_key', $key)->first();
            $isEnabled = $feature ? $feature->is_enabled : false;
        @endphp
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 14px; background: rgba(255,255,255,0.02); border: 1px solid var(--usa-border); border-radius: 10px; {{ $isEnabled ? 'border-color: rgba(52,211,153,0.2);' : '' }}">
            <div>
                <div style="font-size: 13px; font-weight: 600; color: var(--usa-text-primary);">{{ $name }}</div>
                <div style="font-size: 11px; color: var(--usa-text-muted);">{{ $key }}</div>
            </div>
            <form method="POST" action="{{ route('ultrasuperadmin.features.toggle') }}">
                @csrf
                <input type="hidden" name="group_id" value="{{ $group->id }}">
                <input type="hidden" name="feature_key" value="{{ $key }}">
                <button type="submit" class="usa-btn usa-btn-sm {{ $isEnabled ? 'usa-btn-success' : 'usa-btn-outline' }}" style="min-width: 60px;">
                    {{ $isEnabled ? 'ON' : 'OFF' }}
                </button>
            </form>
        </div>
        @endforeach
    </div>
</div>
@endforeach

@if($groups->count() === 0)
<div class="usa-card" style="text-align: center; padding: 60px;">
    <i class="fas fa-toggle-off" style="font-size: 48px; color: var(--usa-text-muted); margin-bottom: 16px;"></i>
    <p style="color: var(--usa-text-muted);">No active school groups found. Create a group first.</p>
    <a href="{{ route('ultrasuperadmin.school-groups.create') }}" class="usa-btn usa-btn-primary" style="margin-top: 12px;">
        <i class="fas fa-plus"></i> Create School Group
    </a>
</div>
@endif
@endsection
