<nav class="usa-sidebar" id="usa-sidebar">
    <div class="usa-sidebar-header">
        <a href="{{ route('superadmin-dashboard') }}" style="text-decoration: none; display: flex; align-items: center; gap: 12px;">
            <div class="usa-logo-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="usa-logo-text">
                <h5>{{ \App\Models\SuperAdminSetting::get('platform_name', 'TISEDU') }}</h5>
                <span>Super Admin</span>
            </div>
        </a>
    </div>

    <div class="usa-nav">
        <!-- ================================ -->
        <!-- MAIN -->
        <!-- ================================ -->
        <div class="usa-nav-label">Main</div>

        <a href="{{ route('superadmin-dashboard') }}" class="usa-nav-item {{ request()->routeIs('superadmin-dashboard') ? 'active' : '' }}">
            <i class="fas fa-solar-panel"></i>
            <span>Dashboard</span>
        </a>

        <!-- ================================ -->
        <!-- MANAGEMENT -->
        <!-- ================================ -->
        <div class="usa-nav-label">Management</div>

        <a href="{{ route('superadmin.school-list') }}" class="usa-nav-item {{ request()->routeIs('superadmin.school*') ? 'active' : '' }}">
            <i class="fas fa-school"></i>
            <span>Schools</span>
        </a>

        <a href="{{ route('superadmin.users.index') }}" class="usa-nav-item {{ request()->routeIs('superadmin.users*') ? 'active' : '' }}">
            <i class="fas fa-users-cog"></i>
            <span>Admin Users</span>
        </a>

        <a href="{{ route('superadmin.school-admins.index') }}" class="usa-nav-item {{ request()->routeIs('superadmin.school-admins*') ? 'active' : '' }}">
            <i class="fas fa-user-tie"></i>
            <span>School Admins</span>
        </a>

        <a href="{{ route('superadmin.subscriptions.index') }}" class="usa-nav-item {{ request()->routeIs('superadmin.subscriptions.index') ? 'active' : '' }}">
            <i class="fas fa-gem"></i>
            <span>Subscriptions</span>
        </a>
        <div class="usa-nav-sub">
            <a href="{{ route('superadmin.subscriptions.coupons') }}" class="usa-nav-item {{ request()->routeIs('superadmin.subscriptions.coupons') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt"></i>
                <span>Coupons</span>
            </a>
        </div>

        <a href="{{ route('superadmin.modules.index') }}" class="usa-nav-item {{ request()->routeIs('superadmin.modules*') ? 'active' : '' }}">
            <i class="fas fa-puzzle-piece"></i>
            <span>Modules</span>
        </a>

        <a href="{{ route('superadmin.impersonate.index') }}" class="usa-nav-item {{ request()->routeIs('superadmin.impersonate*') ? 'active' : '' }}">
            <i class="fas fa-user-secret"></i>
            <span>Impersonate</span>
        </a>

        <!-- ================================ -->
        <!-- COMMUNICATION -->
        <!-- ================================ -->
        <div class="usa-nav-label">Communication</div>

        <a href="{{ route('superadmin.communicate.index') }}" class="usa-nav-item {{ request()->routeIs('superadmin.communicate*') ? 'active' : '' }}">
            <i class="fas fa-envelope"></i>
            <span>Communicate</span>
        </a>

        <a href="{{ route('superadmin.tickets.index') }}" class="usa-nav-item {{ request()->routeIs('superadmin.tickets*') ? 'active' : '' }}">
            <i class="fas fa-headset"></i>
            <span>Support Tickets</span>
        </a>

        <!-- ================================ -->
        <!-- INSIGHTS -->
        <!-- ================================ -->
        <div class="usa-nav-label">Insights</div>

        <a href="{{ route('superadmin.reports.index') }}" class="usa-nav-item {{ request()->routeIs('superadmin.reports*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i>
            <span>Reports</span>
        </a>

        <a href="{{ route('superadmin.analytics.index') }}" class="usa-nav-item {{ request()->routeIs('superadmin.analytics*') ? 'active' : '' }}">
            <i class="fas fa-chart-area"></i>
            <span>Analytics</span>
        </a>

        <a href="{{ route('superadmin.audit.index') }}" class="usa-nav-item {{ request()->routeIs('superadmin.audit*') ? 'active' : '' }}">
            <i class="fas fa-history"></i>
            <span>Audit Logs</span>
        </a>

        <!-- ================================ -->
        <!-- SYSTEM -->
        <!-- ================================ -->
        <div class="usa-nav-label">System</div>

        <a href="{{ route('superadmin.settings.index') }}" class="usa-nav-item {{ request()->routeIs('superadmin.settings*') ? 'active' : '' }}">
            <i class="fas fa-sliders-h"></i>
            <span>Settings</span>
        </a>

        <a href="{{ route('superadmin.backup.index') }}" class="usa-nav-item {{ request()->routeIs('superadmin.backup*') ? 'active' : '' }}">
            <i class="fas fa-database"></i>
            <span>Backups</span>
        </a>

        <a href="{{ route('superadmin.system-logs.index') }}" class="usa-nav-item {{ request()->routeIs('superadmin.system-logs*') ? 'active' : '' }}">
            <i class="fas fa-terminal"></i>
            <span>System Logs</span>
        </a>

        <!-- ================================ -->
        <!-- ACCOUNT -->
        <!-- ================================ -->
        <div class="usa-nav-label">Account</div>

        <a href="{{ route('superadmin.profile.index') }}" class="usa-nav-item {{ request()->routeIs('superadmin.profile.index') ? 'active' : '' }}">
            <i class="fas fa-user-circle"></i>
            <span>My Profile</span>
        </a>

        <a href="{{ route('superadmin.profile.sessions') }}" class="usa-nav-item {{ request()->routeIs('superadmin.profile.sessions') ? 'active' : '' }}">
            <i class="fas fa-desktop"></i>
            <span>Active Sessions</span>
        </a>
    </div>

    <div class="usa-sidebar-footer">
        @php $sa = Auth::guard('superadmin')->user(); @endphp
        <a href="{{ route('superadmin.profile.index') }}" style="text-decoration: none;">
            <div class="usa-user-info">
                <div class="usa-user-avatar">
                    {{ strtoupper(substr($sa->full_name ?? 'SA', 0, 2)) }}
                </div>
                <div class="usa-user-details">
                    <h6>{{ $sa->full_name ?? 'Super Admin' }}</h6>
                    <span>{{ ucfirst(str_replace('_', ' ', $sa->role ?? 'super_admin')) }}</span>
                </div>
            </div>
        </a>
    </div>
</nav>
