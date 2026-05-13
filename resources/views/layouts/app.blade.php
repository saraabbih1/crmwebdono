<!doctype html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'CRM')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --crm-bg: #f5f7fb;
            --crm-panel: rgba(255,255,255,.88);
            --crm-panel-solid: #ffffff;
            --crm-line: rgba(15,23,42,.09);
            --crm-text: #111827;
            --crm-muted: #667085;
            --crm-primary: #2563eb;
            --crm-primary-dark: #1d4ed8;
            --crm-sidebar: rgba(15,23,42,.94);
            --crm-sidebar-soft: rgba(255,255,255,.09);
            --crm-shadow: 0 18px 50px rgba(16, 24, 40, .08);
            --crm-shadow-hover: 0 24px 70px rgba(16, 24, 40, .14);
            --crm-radius: 22px;
        }

        [data-bs-theme="dark"] {
            --crm-bg: #070b14;
            --crm-panel: rgba(15,23,42,.82);
            --crm-panel-solid: #0f172a;
            --crm-line: rgba(148,163,184,.16);
            --crm-text: #f8fafc;
            --crm-muted: #94a3b8;
            --crm-sidebar: rgba(2,6,23,.96);
            --crm-shadow: 0 22px 70px rgba(0, 0, 0, .35);
            --crm-shadow-hover: 0 28px 90px rgba(0, 0, 0, .5);
        }

        body {
            background:
                radial-gradient(circle at 10% 0%, rgba(37,99,235,.16), transparent 34rem),
                radial-gradient(circle at 90% 12%, rgba(16,185,129,.12), transparent 28rem),
                linear-gradient(180deg, rgba(255,255,255,.46), transparent 18rem),
                var(--crm-bg);
            color: var(--crm-text);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-feature-settings: "cv02", "cv03", "cv04";
        }

        @keyframes pageIn {
            from { opacity: 0; transform: translateY(14px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes toastIn {
            from { opacity: 0; transform: translateY(-12px) scale(.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes shimmer {
            100% { transform: translateX(100%); }
        }

        .app-shell { min-height: 100vh; }
        .sidebar {
            width: 280px;
            background: var(--crm-sidebar);
            border-right: 1px solid rgba(255,255,255,.1);
            backdrop-filter: blur(18px);
            transition: width .25s ease, padding .25s ease;
            position: sticky;
            top: 0;
            height: 100vh;
            z-index: 10;
        }
        .sidebar.is-collapsed {
            width: 92px;
        }
        .sidebar.is-collapsed .nav-label,
        .sidebar.is-collapsed .brand-label,
        .sidebar.is-collapsed .user-panel {
            display: none;
        }
        .brand-mark {
            width: 40px;
            height: 40px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #3b82f6, #10b981);
            color: #fff;
            font-weight: 800;
            box-shadow: 0 16px 34px rgba(37,99,235,.26);
        }
        .sidebar .nav-link {
            color: #cbd5e1;
            border-radius: 16px;
            padding: .82rem .9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: .75rem;
            transition: background .18s ease, color .18s ease, transform .18s ease;
        }
        .nav-dot {
            width: 9px;
            height: 9px;
            border-radius: 999px;
            background: currentColor;
            opacity: .56;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: var(--crm-sidebar-soft);
            transform: translateX(2px);
        }
        .main-wrap {
            max-width: 1480px;
            margin: 0 auto;
            animation: pageIn .42s ease both;
        }
        .page-kicker {
            color: var(--crm-muted);
            font-size: .95rem;
        }
        .content-card {
            border: 1px solid var(--crm-line);
            border-radius: var(--crm-radius);
            background: var(--crm-panel);
            box-shadow: var(--crm-shadow);
            backdrop-filter: blur(16px);
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }
        .content-card:hover {
            transform: translateY(-3px) scale(1.005);
            box-shadow: var(--crm-shadow-hover);
            border-color: rgba(37,99,235,.18);
        }
        .soft-card {
            border: 1px solid var(--crm-line);
            border-radius: 18px;
            background: var(--crm-panel);
        }
        .ui-card {
            border: 1px solid var(--crm-line);
            border-radius: var(--crm-radius);
            background: var(--crm-panel);
            box-shadow: var(--crm-shadow);
            backdrop-filter: blur(16px);
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .ui-card:hover { transform: translateY(-3px); box-shadow: var(--crm-shadow-hover); }
        .table {
            --bs-table-bg: transparent;
            --bs-table-hover-bg: rgba(37, 99, 235, .055);
        }
        .table td, .table th { vertical-align: middle; }
        .table tbody tr { transition: background .18s ease, transform .18s ease; }
        .table tbody tr:hover { transform: translateX(2px); }
        .table thead th {
            color: var(--crm-muted);
            font-size: .76rem;
            letter-spacing: .04em;
            text-transform: uppercase;
            font-weight: 700;
            border-bottom-color: var(--crm-line);
            background: rgba(248,250,252,.7);
        }
        [data-bs-theme="dark"] .table thead th { background: rgba(15,23,42,.7); }
        .dropdown-menu {
            border-radius: 16px;
            border-color: var(--crm-line);
            box-shadow: var(--crm-shadow);
            padding: .45rem;
        }
        .dropdown-item { border-radius: 12px; font-weight: 600; }
        .form-label {
            color: var(--crm-muted);
            font-size: .82rem;
            font-weight: 700;
        }
        .form-control, .form-select {
            border-radius: 14px;
            border-color: var(--crm-line);
            min-height: 46px;
            background-color: var(--crm-panel-solid);
            transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: rgba(37,99,235,.55);
            box-shadow: 0 0 0 .2rem rgba(37,99,235,.12);
            transform: translateY(-1px);
        }
        .btn {
            border-radius: 14px;
            font-weight: 700;
            transition: transform .16s ease, box-shadow .16s ease, background .16s ease;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0) scale(.98); }
        .btn-primary {
            background: linear-gradient(135deg, var(--crm-primary), #4f46e5);
            border-color: var(--crm-primary);
            box-shadow: 0 12px 26px rgba(37,99,235,.23);
        }
        .btn-primary:hover {
            background: var(--crm-primary-dark);
            border-color: var(--crm-primary-dark);
        }
        .btn-ghost {
            background: transparent;
            border-color: transparent;
            color: var(--crm-muted);
        }
        .btn-ghost:hover {
            background: rgba(100,116,139,.11);
            color: var(--crm-text);
        }
        .stat-card .stat-label {
            color: var(--crm-muted);
            font-size: .82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .stat-card .stat-value {
            font-size: 2rem;
            line-height: 1;
            font-weight: 800;
        }
        .metric-icon {
            width: 44px;
            height: 44px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            color: #fff;
            font-weight: 800;
            background: linear-gradient(135deg, #2563eb, #14b8a6);
            box-shadow: 0 14px 28px rgba(37,99,235,.2);
        }
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: #eef2ff;
            color: #1e40af;
            font-weight: 800;
        }
        .action-group {
            display: inline-flex;
            gap: .35rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        .empty-state {
            padding: 3rem 1rem;
            color: var(--crm-muted);
            text-align: center;
        }
        .empty-illustration {
            width: 72px;
            height: 52px;
            border-radius: 22px;
            margin: 0 auto 1rem;
            background:
                linear-gradient(135deg, rgba(37,99,235,.16), rgba(16,185,129,.18)),
                var(--crm-panel-solid);
            border: 1px solid var(--crm-line);
        }
        .crm-toast {
            animation: toastIn .28s ease both;
            border-radius: 18px;
            border: 1px solid var(--crm-line);
            box-shadow: var(--crm-shadow);
            backdrop-filter: blur(16px);
        }
        .skeleton {
            position: relative;
            overflow: hidden;
            background: rgba(148,163,184,.18);
            border-radius: 14px;
        }
        .skeleton::after {
            content: "";
            position: absolute;
            inset: 0;
            transform: translateX(-100%);
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.38), transparent);
            animation: shimmer 1.4s infinite;
        }
        .quick-action {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: .95rem;
            border: 1px solid var(--crm-line);
            border-radius: 18px;
            color: var(--crm-text);
            text-decoration: none;
            transition: transform .18s ease, background .18s ease, border-color .18s ease;
        }
        .quick-action:hover {
            transform: translateY(-2px);
            background: rgba(37,99,235,.055);
            border-color: rgba(37,99,235,.24);
        }
        @media (max-width: 991.98px) {
            .sidebar { width: 100%; height: auto; position: static; }
            .sidebar.is-collapsed { width: 100%; }
            .sidebar.is-collapsed .nav-label,
            .sidebar.is-collapsed .brand-label,
            .sidebar.is-collapsed .user-panel { display: inline; }
            .app-shell { flex-direction: column; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>
<body>
<div class="d-flex app-shell">
    <aside class="sidebar p-3" id="appSidebar">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 text-white text-decoration-none fw-bold">
                <span class="brand-mark">{{ strtoupper(substr(app(\App\Services\SettingsService::class)->get('company_name', 'CRM'), 0, 1)) }}</span>
                <span class="brand-label">{{ app(\App\Services\SettingsService::class)->get('company_name', 'CRM') }}</span>
            </a>
            <button type="button" class="btn btn-sm btn-outline-light" id="sidebarToggle" aria-label="Toggle sidebar">...</button>
        </div>
        <nav class="nav flex-column gap-1">
            <a class="nav-link @if(request()->routeIs('dashboard')) active @endif" href="{{ route('dashboard') }}"><span class="nav-dot"></span><span class="nav-label">Dashboard</span></a>
            <a class="nav-link @if(request()->routeIs('clients.*')) active @endif" href="{{ route('clients.index') }}"><span class="nav-dot"></span><span class="nav-label">Clients</span></a>
            <a class="nav-link @if(request()->routeIs('subscriptions.*')) active @endif" href="{{ route('subscriptions.index') }}"><span class="nav-dot"></span><span class="nav-label">Subscriptions</span></a>
            @if(auth()->user()?->isAdmin())
                <a class="nav-link @if(request()->routeIs('notifications.*')) active @endif" href="{{ route('notifications.index') }}"><span class="nav-dot"></span><span class="nav-label">Notifications</span></a>
                <a class="nav-link @if(request()->routeIs('settings.*')) active @endif" href="{{ route('settings.edit') }}"><span class="nav-dot"></span><span class="nav-label">Settings</span></a>
            @endif
        </nav>
        <div class="user-panel mt-4 pt-4 border-top border-secondary">
            <div class="small text-secondary mb-2">{{ auth()->user()->name }} / {{ ucfirst(auth()->user()->role) }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-outline-light w-100">Logout</button>
            </form>
        </div>
    </aside>

    <main class="flex-grow-1 p-3 p-lg-4">
        <div class="main-wrap">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h1 class="h3 mb-1 fw-bold">@yield('page-title', 'Dashboard')</h1>
                    <div class="page-kicker">@yield('page-subtitle')</div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-secondary" id="themeToggle">Dark</button>
                    @yield('page-actions')
                </div>
            </div>

            @yield('content')
        </div>
    </main>
</div>

<div class="toast-container position-fixed top-0 end-0 p-3">
    @if(session('success'))
        <div class="toast show crm-toast text-bg-success" role="alert">
            <div class="toast-body fw-semibold">{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="toast show crm-toast text-bg-danger" role="alert">
            <div class="toast-body fw-semibold">{{ session('error') }}</div>
        </div>
    @endif
    @if($errors->any())
        <div class="toast show crm-toast text-bg-danger" role="alert">
            <div class="toast-body fw-semibold">Please fix the highlighted fields.</div>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
@stack('scripts')
<script>
    const html = document.documentElement;
    const toggle = document.getElementById('themeToggle');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('appSidebar');
    const savedTheme = localStorage.getItem('crm-theme') || 'light';
    const savedSidebar = localStorage.getItem('crm-sidebar') || 'expanded';
    html.setAttribute('data-bs-theme', savedTheme);
    toggle.textContent = savedTheme === 'dark' ? 'Light' : 'Dark';
    if (savedSidebar === 'collapsed') {
        sidebar.classList.add('is-collapsed');
    }
    toggle.addEventListener('click', () => {
        const nextTheme = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-bs-theme', nextTheme);
        localStorage.setItem('crm-theme', nextTheme);
        toggle.textContent = nextTheme === 'dark' ? 'Light' : 'Dark';
    });
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('is-collapsed');
        localStorage.setItem('crm-sidebar', sidebar.classList.contains('is-collapsed') ? 'collapsed' : 'expanded');
    });
    document.querySelectorAll('.toast.show').forEach((toast) => {
        setTimeout(() => toast.classList.remove('show'), 4200);
    });
</script>
</body>
</html>
