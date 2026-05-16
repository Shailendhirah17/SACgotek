@extends('backEnd.superAdmin.layouts.master')
@section('title', 'Backups')

@section('content')
<div class="usa-card">
    <div class="usa-card-header">
        <span class="usa-card-title">Database Backup Management</span>
        <form action="{{ route('superadmin.backup.create') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="usa-btn usa-btn-primary" onclick="this.disabled=true; this.innerHTML='<i class=\'fas fa-spinner fa-spin\'></i> Creating...'; this.form.submit();">
                <i class="fas fa-plus"></i> Create Backup
            </button>
        </form>
    </div>

    @if(count($backups) > 0)
        <table class="usa-table">
            <thead>
                <tr>
                    <th>Filename</th>
                    <th>Size</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($backups as $backup)
                    <tr>
                        <td style="font-weight: 500; font-family: monospace; font-size: 12px;">
                            <i class="fas fa-file-archive" style="color: var(--usa-primary); margin-right: 6px;"></i>
                            {{ $backup['name'] }}
                        </td>
                        <td>{{ $backup['size'] }}</td>
                        <td>{{ $backup['date'] }}</td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <a href="{{ route('superadmin.backup.download', $backup['name']) }}" class="usa-btn usa-btn-outline usa-btn-sm">
                                    <i class="fas fa-download"></i>
                                </a>
                                <form action="{{ route('superadmin.backup.destroy', $backup['name']) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this backup?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="usa-btn usa-btn-danger usa-btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 60px 0; color: var(--usa-text-muted);">
            <i class="fas fa-database" style="font-size: 40px; margin-bottom: 16px; display: block;"></i>
            <p style="font-size: 14px;">No backups found. Create your first backup.</p>
        </div>
    @endif
</div>
@endsection
