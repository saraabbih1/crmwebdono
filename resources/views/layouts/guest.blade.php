<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'CRM')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @keyframes authIn {
            from { opacity: 0; transform: translateY(18px) scale(.985); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at 16% 12%, rgba(59,130,246,.38), transparent 24rem),
                radial-gradient(circle at 84% 24%, rgba(16,185,129,.26), transparent 26rem),
                linear-gradient(135deg, #07111f, #101828 52%, #0f172a);
            font-family: Inter, ui-sans-serif, system-ui, sans-serif;
        }
        .auth-card {
            max-width: 460px;
            border: 1px solid rgba(255,255,255,.18);
            border-radius: 24px;
            background: rgba(255,255,255,.92);
            box-shadow: 0 34px 90px rgba(2,6,23,.38);
            backdrop-filter: blur(18px);
            animation: authIn .45s ease both;
        }
        .brand-mark {
            width: 44px;
            height: 44px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            color: #fff;
            font-weight: 800;
            background: linear-gradient(135deg, #2563eb, #10b981);
            box-shadow: 0 18px 38px rgba(37,99,235,.26);
        }
        .form-label { color: #667085; font-size: .84rem; font-weight: 700; }
        .form-control { min-height: 46px; border-radius: 14px; border-color: #e5e7eb; }
        .form-control:focus { border-color: rgba(37,99,235,.55); box-shadow: 0 0 0 .2rem rgba(37,99,235,.12); }
        .btn { border-radius: 14px; font-weight: 800; min-height: 44px; transition: transform .16s ease, box-shadow .16s ease; }
        .btn:hover { transform: translateY(-1px); }
        .btn-primary { background: linear-gradient(135deg, #2563eb, #4f46e5); border-color: #2563eb; box-shadow: 0 14px 28px rgba(37,99,235,.24); }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center p-3">
    <main class="card auth-card w-100">
        <div class="card-body p-4 p-md-5">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="brand-mark">C</div>
                <div>
                    <h1 class="h3 fw-bold mb-1">CRM System</h1>
                    <div class="text-secondary">@yield('subtitle')</div>
                </div>
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
