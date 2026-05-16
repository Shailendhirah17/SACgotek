@extends('backEnd.superAdmin.layouts.master')
@section('title', 'Modules')

@section('content')
<div class="usa-card">
    <div class="usa-card-header">
        <span class="usa-card-title">Module Management</span>
        <span class="usa-badge usa-badge-info">{{ count($modules) }} Modules</span>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px;">
        @foreach($modules as $name => $status)
            <div style="background: var(--usa-bg-dark); border: 1px solid var(--usa-border); border-radius: 10px; padding: 16px; display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 10px; height: 10px; border-radius: 50%; background: {{ $status ? 'var(--usa-success)' : 'var(--usa-danger)' }};"></div>
                    <div>
                        <div style="font-size: 13px; font-weight: 600; color: var(--usa-text-primary);">{{ $name }}</div>
                        <div style="font-size: 11px; color: var(--usa-text-muted);">{{ $status ? 'Enabled' : 'Disabled' }}</div>
                    </div>
                </div>
                <form action="{{ route('superadmin.modules.toggle') }}" method="POST">
                    @csrf
                    <input type="hidden" name="module" value="{{ $name }}">
                    <button type="submit" class="usa-btn usa-btn-sm {{ $status ? 'usa-btn-danger' : 'usa-btn-success' }}">
                        {{ $status ? 'Disable' : 'Enable' }}
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection
