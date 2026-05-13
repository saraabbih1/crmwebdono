<!doctype html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'CRM')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        [data-bs-theme="dark"] body { background: #111827; }
        .app-shell { min-height: 100vh; }
        .sidebar { width: 260px; background: #0f172a; }
        .sidebar .nav-link { color: #cbd5e1; border-radius: .5rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,.12); }
        .content-card { border: 0; border-radius: .5rem; box-shadow: 0 8px 24px rgba(15,23,42,.06); transition: transform .15s ease, box-shadow .15s ease; }
        .content-card:hover { transform: translateY(-1px); box-shadow: 0 12px 30px rgba(15,23,42,.09); }
        .table td, .table th { vertical-align: middle; }
        @media (max-width: 991.98px) { .sidebar { width: 100%; } .app-shell { flex-direction: column; } }
    </style>
</head>
<body>
<div class="d-flex app-shell">
    <aside class="sidebar p-3">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <a href="{{ route('dashboard') }}" class="text-white text-decoration-none fw-bold fs-4">
                {{ app(\App\Services\SettingsService::class)->get('company_name', 'CRM') }}
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
            <div class="small text-secondary mb-2">{{ auth()->user()->name }} · {{ ucfirst(auth()->user()->role) }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-outline-light w-100">Logout</button>
            </form>
        </div>
    </aside>

    <main class="flex-grow-1 p-3 p-lg-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1">@yield('page-title', 'Dashboard')</h1>
                <div class="text-secondary">@yield('page-subtitle')</div>
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
