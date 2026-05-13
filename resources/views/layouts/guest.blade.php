<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'CRM')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; background: linear-gradient(135deg, #0f172a, #334155); }
        .auth-card { max-width: 440px; border: 0; border-radius: .75rem; box-shadow: 0 24px 60px rgba(15,23,42,.25); }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center p-3">
    <main class="card auth-card w-100">
        <div class="card-body p-4 p-md-5">
            <div class="mb-4">
                <h1 class="h3 fw-bold mb-1">CRM System</h1>
                <div class="text-secondary">@yield('subtitle')</div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">Please check the form and try again.</div>
            @endif

            @yield('content')
        </div>
    </main>
</body>
</html>
