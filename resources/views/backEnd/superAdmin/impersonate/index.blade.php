@extends('backEnd.superAdmin.layouts.master')
@section('title', 'Impersonate School')

@section('content')
<div class="usa-card">
    <div class="usa-card-header">
        <span class="usa-card-title">School Impersonation</span>
        <span class="usa-badge usa-badge-warning"><i class="fas fa-exclamation-triangle"></i> Admin Access</span>
    </div>

    <div style="background: rgba(251,191,36,0.08); border: 1px solid rgba(251,191,36,0.2); border-radius: 10px; padding: 14px 18px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-exclamation-triangle" style="color: var(--usa-warning);"></i>
        <span style="font-size: 13px; color: var(--usa-text-secondary);">
            Impersonation allows you to log in as a school's admin. All actions performed will be under the school admin's identity. Use responsibly.
        </span>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 12px;">
        @foreach($schools as $school)
            <div style="background: var(--usa-bg-dark); border: 1px solid var(--usa-border); border-radius: 10px; padding: 16px; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 14px; font-weight: 600; color: var(--usa-text-primary);">{{ $school->school_name }}</div>
                    <div style="font-size: 12px; color: var(--usa-text-muted);">{{ $school->email }}</div>
                </div>
                <form action="{{ route('superadmin.impersonate.start', $school->id) }}" method="POST" onsubmit="return confirm('Impersonate as admin of {{ $school->school_name }}?')">
                    @csrf
                    <button type="submit" class="usa-btn usa-btn-outline usa-btn-sm">
                        <i class="fas fa-sign-in-alt"></i> Enter
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection
