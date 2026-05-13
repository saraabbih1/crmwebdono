<!doctype html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'CRM')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --crm-bg: #f6f8fb;
            --crm-panel: #ffffff;
            --crm-line: #e6eaf0;
            --crm-text: #111827;
            --crm-muted: #64748b;
            --crm-primary: #2563eb;
            --crm-primary-dark: #1d4ed8;
            --crm-sidebar: #101828;
            --crm-sidebar-soft: rgba(255,255,255,.08);
            --crm-shadow: 0 12px 32px rgba(15, 23, 42, .07);
        }

        [data-bs-theme="dark"] {
            --crm-bg: #0b1120;
            --crm-panel: #111827;
            --crm-line: #263244;
            --crm-text: #f8fafc;
            --crm-muted: #94a3b8;
            --crm-sidebar: #020617;
            --crm-shadow: 0 14px 34px rgba(0, 0, 0, .28);
        }

        body {
            background: linear-gradient(180deg, rgba(37,99,235,.08), transparent 260px), var(--crm-bg);
            color: var(--crm-text);
            font-feature-settings: "cv02", "cv03", "cv04";
        }

        .app-shell { min-height: 100vh; }
        .sidebar {
            width: 272px;
            background: var(--crm-sidebar);
            border-right: 1px solid rgba(255,255,255,.08);
        }
        .brand-mark {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #3b82f6, #10b981);
            color: #fff;
            font-weight: 800;
        }
        .sidebar .nav-link {
            color: #cbd5e1;
            border-radius: 8px;
            padding: .75rem .85rem;
            font-weight: 600;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: var(--crm-sidebar-soft);
        }
        .main-wrap {
            max-width: 1480px;
            margin: 0 auto;
        }
        .page-kicker {
            color: var(--crm-muted);
            font-size: .95rem;
        }
        .content-card {
            border: 1px solid var(--crm-line);
            border-radius: 8px;
            background: var(--crm-panel);
            box-shadow: var(--crm-shadow);
        }
        .soft-card {
            border: 1px solid var(--crm-line);
            border-radius: 8px;
            background: var(--crm-panel);
        }
        .table {
            --bs-table-bg: transparent;
            --bs-table-hover-bg: rgba(37, 99, 235, .04);
        }
        .table td, .table th { vertical-align: middle; }
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
        .form-label {
            color: var(--crm-muted);
            font-size: .82rem;
            font-weight: 700;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border-color: var(--crm-line);
            min-height: 42px;
        }
        .form-control:focus, .form-select:focus {
            border-color: rgba(37,99,235,.55);
            box-shadow: 0 0 0 .2rem rgba(37,99,235,.12);
        }
        .btn { border-radius: 8px; font-weight: 700; }
        .btn-primary {
            background: var(--crm-primary);
            border-color: var(--crm-primary);
            box-shadow: 0 8px 18px rgba(37,99,235,.18);
        }
        .btn-primary:hover {
            background: var(--crm-primary-dark);
            border-color: var(--crm-primary-dark);
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
        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 8px;
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
        @media (max-width: 991.98px) {
            .sidebar { width: 100%; }
            .app-shell { flex-direction: column; }
        }
    </style>
</head>
<body>
<div class="d-flex app-shell">
    <aside class="sidebar p-3">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 text-white text-decoration-none fw-bold">
                <span class="brand-mark">{{ strtoupper(substr(app(\App\Services\SettingsService::class)->get('company_name', 'CRM'), 0, 1)) }}</span>
                <span>{{ app(\App\Services\SettingsService::class)->get('company_name', 'CRM') }}</span>
            </a>
            <button type="button" class="btn btn-sm btn-outline-light" id="themeToggle">Dark</button>
        </div>
        <nav class="nav flex-column gap-1">
            <a class="nav-link @if(request()->routeIs('dashboard')) active @endif" href="{{ route('dashboard') }}">Dashboard</a>
            <a class="nav-link @if(request()->routeIs('clients.*')) active @endif" href="{{ route('clients.index') }}">Clients</a>
            <a class="nav-link @if(request()->routeIs('subscriptions.*')) active @endif" href="{{ route('subscriptions.index') }}">Subscriptions</a>
            @if(auth()->user()?->isAdmin())
                <a class="nav-link @if(request()->routeIs('notifications.*')) active @endif" href="{{ route('notifications.index') }}">Notifications</a>
                <a class="nav-link @if(request()->routeIs('settings.*')) active @endif" href="{{ route('settings.edit') }}">Settings</a>
            @endif
        </nav>
        <div class="mt-4 pt-4 border-top border-secondary">
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
                <div>@yield('page-actions')</div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Please fix the highlighted fields.</strong>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
@stack('scripts')
<script>
    const html = document.documentElement;
    const toggle = document.getElementById('themeToggle');
    const savedTheme = localStorage.getItem('crm-theme') || 'light';
    html.setAttribute('data-bs-theme', savedTheme);
    toggle.textContent = savedTheme === 'dark' ? 'Light' : 'Dark';
    toggle.addEventListener('click', () => {
        const nextTheme = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-bs-theme', nextTheme);
        localStorage.setItem('crm-theme', nextTheme);
        toggle.textContent = nextTheme === 'dark' ? 'Light' : 'Dark';
    });
</script>
</body>
</html>
