<!doctype html>
<html lang="en">
<head>
    <script>
        // Prevent FOUC by setting theme early
        if (localStorage.getItem('usa-theme') === 'light' || (!('usa-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: light)').matches)) {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="SuperAdmin Panel - {{ \App\Models\SuperAdminSetting::get('platform_name', 'TISEDU') }}">
    <title>@yield('title', 'SuperAdmin Panel') - {{ \App\Models\SuperAdminSetting::get('platform_name', 'TISEDU') }}</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('public/backEnd/vendors/css/bootstrap.css')}}">
    <!-- Themify Icons -->
    <link rel="stylesheet" href="{{asset('public/backEnd/login')}}/css/themify-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --usa-primary: #7832ff;
            --usa-primary-light: #9b5fff;
            --usa-secondary: #ff8800;
            --usa-accent: #ffc107;
            --usa-bg-dark: #09090f;
            --usa-bg-card: #12121e;
            --usa-bg-sidebar: #0e0e1a;
            --usa-bg-hover: #1a1a2e;
            --usa-border: rgba(255,255,255,0.06);
            --usa-border-accent: rgba(120,50,255,0.2);
            --usa-text-primary: #e8e6f0;
            --usa-text-secondary: rgba(255,255,255,0.5);
            --usa-text-muted: rgba(255,255,255,0.25);
            --usa-success: #34d399;
            --usa-danger: #f87171;
            --usa-warning: #fbbf24;
            --usa-info: #818cf8;
            --usa-sidebar-width: 270px;
        }

        :root[data-theme="light"] {
            --usa-bg-dark: #fbfbfe;
            --usa-bg-card: #ffffff;
            --usa-bg-sidebar: #ffffff;
            --usa-bg-hover: #f1f1f8;
            --usa-border: rgba(0,0,0,0.08);
            --usa-border-accent: rgba(120,50,255,0.2);
            --usa-text-primary: #1e1e2d;
            --usa-text-secondary: #565674;
            --usa-text-muted: #9999b3;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--usa-bg-dark);
            color: var(--usa-text-primary);
            min-height: 100vh;
        }

        /* ========== SIDEBAR ========== */
        .usa-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: var(--usa-sidebar-width);
            background: var(--usa-bg-sidebar);
            border-right: 1px solid var(--usa-border);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }

        .usa-sidebar-header {
            padding: 20px 22px;
            border-bottom: 1px solid var(--usa-border);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .usa-logo-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--usa-primary), var(--usa-secondary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 15px rgba(120,50,255,0.3);
        }

        .usa-logo-icon i { color: #fff; font-size: 18px; }

        .usa-logo-text h5 {
            font-size: 14px;
            font-weight: 800;
            color: #fff;
            margin: 0;
            letter-spacing: -0.3px;
        }

        .usa-logo-text span {
            font-size: 10px;
            color: var(--usa-secondary);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 700;
        }

        .usa-nav {
            flex: 1;
            overflow-y: auto;
            padding: 14px 10px;
        }

        .usa-nav::-webkit-scrollbar { width: 3px; }
        .usa-nav::-webkit-scrollbar-track { background: transparent; }
        .usa-nav::-webkit-scrollbar-thumb { background: rgba(120,50,255,0.2); border-radius: 4px; }

        .usa-nav-label {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.8px;
            color: var(--usa-text-muted);
            padding: 18px 14px 8px;
        }

        .usa-nav-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 14px;
            border-radius: 10px;
            color: var(--usa-text-secondary);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 2px;
            transition: all 0.2s ease;
            position: relative;
        }

        .usa-nav-item:hover {
            background: var(--usa-bg-hover);
            color: var(--usa-text-primary);
            text-decoration: none;
        }

        .usa-nav-item.active {
            background: linear-gradient(135deg, rgba(120,50,255,0.15), rgba(255,136,0,0.08));
            color: var(--usa-primary-light);
            border: 1px solid var(--usa-border-accent);
        }

        .usa-nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 6px;
            bottom: 6px;
            width: 3px;
            background: linear-gradient(to bottom, var(--usa-primary), var(--usa-secondary));
            border-radius: 0 3px 3px 0;
        }

        .usa-nav-item i { width: 18px; text-align: center; font-size: 14px; }

        .usa-nav-sub {
            padding-left: 16px;
        }

        .usa-nav-sub .usa-nav-item {
            font-size: 12px;
            padding: 7px 14px;
            opacity: 0.85;
        }

        .usa-sidebar-footer {
            padding: 14px;
            border-top: 1px solid var(--usa-border);
        }

        .usa-user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px;
        }

        .usa-user-avatar {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--usa-primary), var(--usa-secondary));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .usa-user-details h6 { font-size: 13px; font-weight: 600; margin: 0; color: var(--usa-text-primary); }
        .usa-user-details span { font-size: 10px; color: var(--usa-secondary); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }

        /* ========== HEADER ========== */
        .usa-header {
            position: fixed;
            top: 0;
            left: var(--usa-sidebar-width);
            right: 0;
            height: 64px;
            background: var(--usa-bg-card);
            border-bottom: 1px solid var(--usa-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            z-index: 999;
        }

        .usa-header-left { display: flex; align-items: center; gap: 16px; }
        .usa-header-left h4 { font-size: 17px; font-weight: 700; margin: 0; }

        .usa-mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--usa-text-primary);
            font-size: 20px;
            cursor: pointer;
        }

        .usa-header-right { display: flex; align-items: center; gap: 14px; }

        .usa-header-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .usa-btn-logout {
            background: rgba(248,113,113,0.1);
            border: 1px solid rgba(248,113,113,0.2);
            color: var(--usa-danger);
        }

        .usa-btn-logout:hover {
            background: rgba(248,113,113,0.2);
            text-decoration: none;
            color: var(--usa-danger);
        }

        /* ========== MAIN CONTENT ========== */
        .usa-main {
            margin-left: var(--usa-sidebar-width);
            margin-top: 64px;
            padding: 28px;
            min-height: calc(100vh - 64px);
        }

        /* ========== ALERTS ========== */
        .usa-alert {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .usa-alert-success { background: rgba(52,211,153,0.1); border: 1px solid rgba(52,211,153,0.2); color: var(--usa-success); }
        .usa-alert-danger { background: rgba(248,113,113,0.1); border: 1px solid rgba(248,113,113,0.2); color: var(--usa-danger); }

        /* ========== CARDS ========== */
        .usa-card {
            background: var(--usa-bg-card);
            border: 1px solid var(--usa-border);
            border-radius: 14px;
            padding: 24px;
        }

        .usa-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .usa-card-title { font-size: 15px; font-weight: 700; color: var(--usa-text-primary); }

        /* ========== STAT CARDS ========== */
        .usa-stat-card {
            background: var(--usa-bg-card);
            border: 1px solid var(--usa-border);
            border-radius: 14px;
            padding: 22px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-decoration: none;
            display: block;
        }

        .usa-stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(to right, var(--usa-primary), var(--usa-secondary));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .usa-stat-card:hover::before { opacity: 1; }
        .usa-stat-card:hover { border-color: var(--usa-border-accent); transform: translateY(-2px); text-decoration: none; }

        .usa-stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 14px;
        }

        .usa-stat-value { font-size: 28px; font-weight: 800; margin-bottom: 4px; color: var(--usa-text-primary); }
        .usa-stat-label { font-size: 12px; color: var(--usa-text-secondary); font-weight: 500; }

        /* ========== TABLE ========== */
        .usa-table { width: 100%; border-collapse: collapse; }

        .usa-table thead th {
            padding: 12px 16px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--usa-text-muted);
            border-bottom: 1px solid var(--usa-border);
            text-align: left;
        }

        .usa-table tbody td {
            padding: 12px 16px;
            font-size: 13px;
            color: var(--usa-text-secondary);
            border-bottom: 1px solid var(--usa-border);
        }

        .usa-table tbody tr:hover { background: var(--usa-bg-hover); }

        /* ========== BADGES ========== */
        .usa-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }

        .usa-badge-success { background: rgba(52,211,153,0.15); color: var(--usa-success); }
        .usa-badge-danger { background: rgba(248,113,113,0.15); color: var(--usa-danger); }
        .usa-badge-warning { background: rgba(251,191,36,0.15); color: var(--usa-warning); }
        .usa-badge-info { background: rgba(129,140,248,0.15); color: var(--usa-info); }
        .usa-badge-primary { background: rgba(120,50,255,0.15); color: var(--usa-primary-light); }

        /* ========== BUTTONS ========== */
        .usa-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
        }

        .usa-btn-primary {
            background: linear-gradient(135deg, var(--usa-primary), var(--usa-primary-light));
            color: #fff;
        }

        .usa-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(120,50,255,0.3);
            text-decoration: none;
            color: #fff;
        }

        .usa-btn-outline {
            background: transparent;
            border: 1px solid var(--usa-border);
            color: var(--usa-text-secondary);
        }

        .usa-btn-outline:hover {
            background: var(--usa-bg-hover);
            color: var(--usa-text-primary);
            text-decoration: none;
        }

        .usa-btn-sm { padding: 6px 12px; font-size: 12px; }

        .usa-btn-danger {
            background: rgba(248,113,113,0.15);
            color: var(--usa-danger);
            border: 1px solid rgba(248,113,113,0.2);
        }

        .usa-btn-danger:hover { background: rgba(248,113,113,0.25); text-decoration: none; color: var(--usa-danger); }

        .usa-btn-success {
            background: rgba(52,211,153,0.15);
            color: var(--usa-success);
            border: 1px solid rgba(52,211,153,0.2);
        }

        .usa-btn-success:hover { background: rgba(52,211,153,0.25); text-decoration: none; color: var(--usa-success); }

        .usa-btn-warning {
            background: rgba(251,191,36,0.15);
            color: var(--usa-warning);
            border: 1px solid rgba(251,191,36,0.2);
        }

        /* ========== FORMS ========== */
        .usa-form-group { margin-bottom: 20px; }

        .usa-form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--usa-text-secondary);
            margin-bottom: 6px;
        }

        .usa-form-control {
            width: 100%;
            padding: 10px 14px;
            background: var(--usa-bg-dark);
            border: 1px solid var(--usa-border);
            border-radius: 10px;
            color: var(--usa-text-primary);
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: all 0.2s ease;
        }

        .usa-form-control:focus {
            border-color: var(--usa-primary);
            box-shadow: 0 0 0 3px rgba(120,50,255,0.15);
            background: var(--usa-bg-card);
        }

        .usa-form-control::placeholder { color: var(--usa-text-muted); }

        select.usa-form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 36px;
        }

        textarea.usa-form-control { resize: vertical; min-height: 80px; }

        /* ========== PAGINATION ========== */
        .usa-pagination { display: flex; gap: 4px; justify-content: center; margin-top: 20px; }

        .usa-pagination .page-link {
            padding: 6px 12px;
            background: var(--usa-bg-card);
            border: 1px solid var(--usa-border);
            border-radius: 6px;
            color: var(--usa-text-secondary);
            font-size: 13px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .usa-pagination .page-item.active .page-link {
            background: var(--usa-primary);
            border-color: var(--usa-primary);
            color: #fff;
        }

        /* ========== DASHBOARD SPECIFIC ========== */
        .usa-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 28px;
        }

        .usa-dash-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 991px) {
            .usa-dash-grid { grid-template-columns: 1fr; }
        }

        .usa-activity-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid var(--usa-border);
        }

        .usa-activity-item:last-child { border-bottom: none; }

        .usa-activity-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-top: 6px;
            flex-shrink: 0;
        }

        .usa-activity-dot.login { background: var(--usa-success); }
        .usa-activity-dot.created { background: var(--usa-info); }
        .usa-activity-dot.updated { background: var(--usa-warning); }
        .usa-activity-dot.deleted { background: var(--usa-danger); }
        .usa-activity-dot.default { background: var(--usa-text-muted); }

        .usa-activity-text {
            font-size: 13px;
            color: var(--usa-text-secondary);
            line-height: 1.5;
        }

        .usa-activity-time {
            font-size: 11px;
            color: var(--usa-text-muted);
        }

        .usa-health-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid var(--usa-border);
            font-size: 13px;
        }

        .usa-health-row:last-child { border-bottom: none; }

        .usa-health-key { color: var(--usa-text-muted); }
        .usa-health-val { color: var(--usa-text-primary); font-weight: 500; }

        /* ========== MODALS ========== */
        .usa-modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.7);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .usa-modal-content {
            background: var(--usa-bg-card);
            border-radius: 16px;
            border: 1px solid var(--usa-border);
            width: 90%;
            max-width: 600px;
        }

        .usa-modal-header {
            padding: 20px;
            border-bottom: 1px solid var(--usa-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .usa-modal-body { padding: 20px; }
        .usa-modal-footer { padding: 20px; border-top: 1px solid var(--usa-border); text-align: right; }

        .usa-modal-close {
            background: none;
            border: none;
            color: var(--usa-text-primary);
            font-size: 24px;
            cursor: pointer;
        }

        .usa-empty-state {
            text-align: center;
            padding: 40px 0;
            color: var(--usa-text-muted);
            font-size: 13px;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 991px) {
            .usa-sidebar { transform: translateX(-100%); }
            .usa-sidebar.show { transform: translateX(0); }
            .usa-header { left: 0; }
            .usa-main { margin-left: 0; }
            .usa-mobile-toggle { display: block; }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar -->
    @include('backEnd.superAdmin.layouts.sidebar')

    <!-- Header -->
    @include('backEnd.superAdmin.layouts.header')

    <!-- Main Content -->
    <main class="usa-main">
        @if(session('message-success'))
            <div class="usa-alert usa-alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('message-success') }}
            </div>
        @endif

        @if(session('message-danger'))
            <div class="usa-alert usa-alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('message-danger') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="{{asset('public/backEnd/vendors/js/vendor.bundle.base.js')}}"></script>
    <script>
        // Mobile sidebar toggle
        document.querySelector('.usa-mobile-toggle')?.addEventListener('click', function() {
            document.querySelector('.usa-sidebar').classList.toggle('show');
        });

        // Close sidebar on overlay click (mobile)
        document.addEventListener('click', function(e) {
            const sidebar = document.querySelector('.usa-sidebar');
            const toggle = document.querySelector('.usa-mobile-toggle');
            if (sidebar && !sidebar.contains(e.target) && !toggle?.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        });

        // Theme Toggle Logic
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');

        function updateThemeIcon() {
            if (document.documentElement.getAttribute('data-theme') === 'light') {
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            } else {
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            }
        }

        if (themeToggleBtn) {
            updateThemeIcon();
            themeToggleBtn.addEventListener('click', function() {
                const currentTheme = document.documentElement.getAttribute('data-theme');
                if (currentTheme === 'light') {
                    document.documentElement.removeAttribute('data-theme');
                    localStorage.setItem('usa-theme', 'dark');
                } else {
                    document.documentElement.setAttribute('data-theme', 'light');
                    localStorage.setItem('usa-theme', 'light');
                }
                updateThemeIcon();
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
