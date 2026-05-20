<nav class="usa-sidebar" id="usa-sidebar">
    <div class="usa-sidebar-header">
        <a href="{{ route('ultrasuperadmin-dashboard') }}" style="text-decoration: none; display: flex; align-items: center; gap: 12px;">
            <div class="usa-logo-icon">
                <i class="fas fa-crown"></i>
            </div>
            <div class="usa-logo-text">
                <h5>GOTEK</h5>
                <span>Master Control</span>
            </div>
        </a>
    </div>

    <div class="usa-nav">
        <!-- ================================ -->
        <!-- COMMAND CENTER -->
        <!-- ================================ -->
        <div class="usa-nav-label">Command Center</div>

        <a href="{{ route('ultrasuperadmin-dashboard') }}" class="usa-nav-item {{ request()->routeIs('ultrasuperadmin-dashboard') ? 'active' : '' }}">
            <i class="fas fa-solar-panel"></i>
            <span>Dashboard</span>
        </a>

        <!-- ================================ -->
        <!-- ORGANIZATION -->
        <!-- ================================ -->
        <div class="usa-nav-label">Organization</div>

        <a href="{{ route('ultrasuperadmin.school-groups.index') }}" class="usa-nav-item {{ request()->routeIs('ultrasuperadmin.school-groups*') ? 'active' : '' }}">
            <i class="fas fa-layer-group"></i>
            <span>School Groups</span>
        </a>

        <a href="{{ route('ultrasuperadmin.super-admins.index') }}" class="usa-nav-item {{ request()->routeIs('ultrasuperadmin.super-admins*') ? 'active' : '' }}">
            <i class="fas fa-user-shield"></i>
            <span>Super Admins</span>
        </a>

        <!-- ================================ -->
        <!-- CONTROL -->
        <!-- ================================ -->
        <div class="usa-nav-label">Control</div>

        <a href="{{ route('ultrasuperadmin.subscriptions.index') }}" class="usa-nav-item {{ request()->routeIs('ultrasuperadmin.subscriptions*') ? 'active' : '' }}">
            <i class="fas fa-gem"></i>
            <span>Subscriptions</span>
        </a>

        <a href="{{ route('ultrasuperadmin.features.index') }}" class="usa-nav-item {{ request()->routeIs('ultrasuperadmin.features*') ? 'active' : '' }}">
            <i class="fas fa-toggle-on"></i>
            <span>Feature Control</span>
        </a>

        <!-- ================================ -->
        <!-- INTELLIGENCE -->
        <!-- ================================ -->
        <div class="usa-nav-label">Intelligence</div>

        <a href="{{ route('ultrasuperadmin.analytics.index') }}" class="usa-nav-item {{ request()->routeIs('ultrasuperadmin.analytics*') ? 'active' : '' }}">
            <i class="fas fa-chart-area"></i>
            <span>Analytics</span>
        </a>

        <!-- ================================ -->
        <!-- PLATFORM -->
        <!-- ================================ -->
        <div class="usa-nav-label">Platform</div>

        <a href="{{ route('ultrasuperadmin.settings.index') }}" class="usa-nav-item {{ request()->routeIs('ultrasuperadmin.settings*') ? 'active' : '' }}">
            <i class="fas fa-sliders-h"></i>
            <span>Platform Settings</span>
        </a>
    </div>

    <div class="usa-sidebar-footer">
        @php $usa = Auth::guard('ultrasuperadmin')->user(); @endphp
        <div class="usa-user-info">
            <div class="usa-user-avatar">
                {{ strtoupper(substr($usa->full_name ?? 'GT', 0, 2)) }}
            </div>
            <div class="usa-user-details">
                <h6>{{ $usa->full_name ?? 'GOTEK' }}</h6>
                <span>Ultra Admin</span>
            </div>
        </div>
    </div>
</nav>
