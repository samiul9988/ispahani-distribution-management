<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --accent: #22d3ee;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', system-ui, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: radial-gradient(1200px 600px at 10% 10%, #312e81 0%, transparent 60%),
                        radial-gradient(1000px 600px at 90% 90%, #0e7490 0%, transparent 55%),
                        linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
            background-attachment: fixed;
            position: relative;
            overflow: hidden;
        }

        body::before,
        body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.35;
            z-index: 0;
        }

        body::before {
            width: 360px;
            height: 360px;
            background: var(--primary);
            top: -80px;
            left: -80px;
            animation: float1 9s ease-in-out infinite;
        }

        body::after {
            width: 420px;
            height: 420px;
            background: var(--accent);
            bottom: -100px;
            right: -100px;
            animation: float2 11s ease-in-out infinite;
        }

        @keyframes float1 {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(30px, 40px); }
        }

        @keyframes float2 {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-30px, -30px); }
        }

        .login-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 400px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 20px;
            padding: 40px 36px;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.45);
            color: #e2e8f0;
            animation: rise 0.5s ease-out;
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-badge {
            width: 56px;
            height: 56px;
            margin: 0 auto 18px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 22px;
            color: #fff;
            box-shadow: 0 8px 24px rgba(99, 102, 241, 0.45);
        }

        h1.title {
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            margin: 0 0 4px;
            color: #f8fafc;
        }

        p.subtitle {
            text-align: center;
            font-size: 13.5px;
            color: #94a3b8;
            margin: 0 0 28px;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #fecaca;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 13.5px;
            margin-bottom: 20px;
        }

        .alert-error div + div { margin-top: 4px; }

        .field { margin-bottom: 18px; }

        .field label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            letter-spacing: 0.02em;
            color: #cbd5e1;
            margin-bottom: 7px;
        }

        .field input[type="email"],
        .field input[type="password"] {
            width: 100%;
            padding: 11px 14px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: rgba(15, 23, 42, 0.45);
            color: #f1f5f9;
            font-size: 14.5px;
            font-family: inherit;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
        }

        .field input::placeholder { color: #64748b; }

        .field input:focus {
            border-color: var(--primary);
            background: rgba(15, 23, 42, 0.7);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            font-size: 13.5px;
            color: #cbd5e1;
        }

        .remember-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .remember-row label { cursor: pointer; }

        .btn-login {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            font-size: 14.5px;
            font-weight: 600;
            letter-spacing: 0.01em;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s, filter 0.15s;
            box-shadow: 0 10px 24px rgba(79, 70, 229, 0.4);
        }

        .btn-login:hover { filter: brightness(1.08); transform: translateY(-1px); }
        .btn-login:active { transform: translateY(0); }

        .footer-note {
            text-align: center;
            margin-top: 22px;
            font-size: 12px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo-badge">ID</div>
        <h1 class="title">Ispahani Distributor</h1>
        <p class="subtitle">M/S Tobarak Mollah Store</p>

        @if ($errors->any())
            <div class="alert-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf
            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="you@example.com" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
            <div class="remember-row">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Remember me</label>
            </div>
            <button type="submit" class="btn-login">Sign In</button>
        </form>

        <p class="footer-note">&copy; {{ date('Y') }} Ispahani Distributor. All rights reserved.</p>
    </div>
</body>
</html>
