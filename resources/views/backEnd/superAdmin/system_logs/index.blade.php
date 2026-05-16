@extends('backEnd.superAdmin.layouts.master')
@section('title', 'System Logs')

@section('styles')
<style>
    .log-viewer {
        background: #0d1117;
        border: 1px solid var(--usa-border);
        border-radius: 10px;
        padding: 16px;
        font-family: 'JetBrains Mono', 'Fira Code', 'Consolas', monospace;
        font-size: 11px;
        line-height: 1.6;
        color: #c9d1d9;
        max-height: 600px;
        overflow: auto;
        white-space: pre-wrap;
        word-break: break-all;
    }

    .log-viewer .log-error { color: #f87171; }
    .log-viewer .log-warning { color: #fbbf24; }
    .log-viewer .log-info { color: #60a5fa; }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="usa-card" style="margin-bottom: 20px;">
            <div class="usa-card-header">
                <span class="usa-card-title">Log Files</span>
            </div>
            @foreach($logFiles as $file)
                <a href="{{ route('superadmin.system-logs.index', ['file' => $file['name']]) }}"
                   style="display: flex; align-items: center; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--usa-border); text-decoration: none; {{ $selectedLog == $file['name'] ? 'color: var(--usa-primary);' : 'color: var(--usa-text-secondary);' }}">
                    <div>
                        <div style="font-size: 12px; font-weight: {{ $selectedLog == $file['name'] ? '600' : '400' }};">{{ $file['name'] }}</div>
                        <div style="font-size: 10px; color: var(--usa-text-muted);">{{ $file['size'] }}</div>
                    </div>
                    @if($selectedLog == $file['name'])
                        <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    <div class="col-md-9">
        <div class="usa-card">
            <div class="usa-card-header">
                <span class="usa-card-title">{{ $selectedLog }}</span>
                <div style="display: flex; gap: 8px;">
                    <a href="{{ route('superadmin.system-logs.download', $selectedLog) }}" class="usa-btn usa-btn-outline usa-btn-sm"><i class="fas fa-download"></i></a>
                    <form action="{{ route('superadmin.system-logs.clear') }}" method="POST" style="display: inline;" onsubmit="return confirm('Clear this log file?')">
                        @csrf
                        <input type="hidden" name="file" value="{{ $selectedLog }}">
                        <button type="submit" class="usa-btn usa-btn-danger usa-btn-sm"><i class="fas fa-trash"></i> Clear</button>
                    </form>
                </div>
            </div>
            <div class="log-viewer" id="logViewer">{!! nl2br(e($logContent)) !!}</div>
        </div>
    </div>
</div>

<script>
// Auto-scroll to bottom
document.getElementById('logViewer')?.scrollTo(0, document.getElementById('logViewer').scrollHeight);
</script>
@endsection
