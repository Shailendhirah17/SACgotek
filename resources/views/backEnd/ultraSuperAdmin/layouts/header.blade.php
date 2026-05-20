<header class="usa-header">
    <div class="usa-header-left">
        <button class="usa-mobile-toggle">
            <i class="fas fa-bars"></i>
        </button>
        <h4>@yield('title', 'Dashboard')</h4>
    </div>

    <div class="usa-header-right">
        <div style="font-size: 12px; color: var(--usa-text-muted); display: flex; align-items: center; gap: 6px;">
            <i class="fas fa-crown" style="color: var(--usa-secondary); font-size: 10px;"></i>
            GOTEK Master Control
        </div>

        <button class="usa-header-btn usa-btn-outline" id="theme-toggle" type="button" title="Toggle Light/Dark Mode" style="border: none; font-size: 16px;">
            <i class="fas fa-sun" id="theme-icon"></i>
        </button>

        <!-- If they came from the web guard, we can show a Back to Home button -->
        <a href="{{ route('admin-dashboard') }}" class="usa-header-btn usa-btn-logout" style="text-decoration: none; padding: 6px 12px; display: inline-block;">
            <i class="fas fa-home" style="margin-right: 6px;"></i> Back to Home
        </a>
    </div>
</header>
