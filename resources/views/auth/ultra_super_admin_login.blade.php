<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ultra Super Admin Login - Technosprint</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #09090f;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Animated background */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(ellipse at 20% 50%, rgba(120, 50, 255, 0.08) 0%, transparent 50%),
                        radial-gradient(ellipse at 80% 20%, rgba(255, 165, 0, 0.06) 0%, transparent 50%),
                        radial-gradient(ellipse at 50% 80%, rgba(120, 50, 255, 0.04) 0%, transparent 50%);
            animation: bgPulse 15s ease-in-out infinite alternate;
            z-index: 0;
        }

        @keyframes bgPulse {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-2%, -2%) rotate(3deg); }
        }

        /* Floating particles */
        .particles {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: rgba(120, 50, 255, 0.3);
            border-radius: 50%;
            animation: float 20s infinite;
        }

        .particle:nth-child(2) { left: 15%; animation-delay: -3s; animation-duration: 25s; background: rgba(255, 165, 0, 0.2); }
        .particle:nth-child(3) { left: 30%; animation-delay: -7s; animation-duration: 22s; }
        .particle:nth-child(4) { left: 45%; animation-delay: -2s; animation-duration: 28s; background: rgba(255, 165, 0, 0.15); }
        .particle:nth-child(5) { left: 60%; animation-delay: -5s; animation-duration: 18s; }
        .particle:nth-child(6) { left: 75%; animation-delay: -9s; animation-duration: 24s; background: rgba(120, 50, 255, 0.2); }
        .particle:nth-child(7) { left: 85%; animation-delay: -4s; animation-duration: 20s; }
        .particle:nth-child(8) { left: 92%; animation-delay: -11s; animation-duration: 30s; background: rgba(255, 165, 0, 0.1); }

        @keyframes float {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100vh) scale(1.5); opacity: 0; }
        }

        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            padding: 20px;
        }

        /* Logo & branding */
        .login-brand {
            text-align: center;
            margin-bottom: 36px;
        }

        .brand-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #7832ff, #ff8800);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 32px rgba(120, 50, 255, 0.3),
                        0 0 80px rgba(120, 50, 255, 0.1);
            animation: glowPulse 3s ease-in-out infinite;
        }

        @keyframes glowPulse {
            0%, 100% { box-shadow: 0 8px 32px rgba(120, 50, 255, 0.3), 0 0 60px rgba(120, 50, 255, 0.1); }
            50% { box-shadow: 0 8px 48px rgba(120, 50, 255, 0.5), 0 0 100px rgba(120, 50, 255, 0.15); }
        }

        .brand-icon i {
            font-size: 30px;
            color: #fff;
        }

        .login-brand h2 {
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 4px;
        }

        .login-brand p {
            font-size: 13px;
            color: rgba(255,255,255,0.4);
            font-weight: 500;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 14px;
            background: linear-gradient(135deg, rgba(120, 50, 255, 0.15), rgba(255, 136, 0, 0.1));
            border: 1px solid rgba(120, 50, 255, 0.2);
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            color: #c9a0ff;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 12px;
        }

        /* Login card */
        .login-card {
            background: rgba(20, 20, 35, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
        }

        .login-card h3 {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 4px;
        }

        .login-card .subtitle {
            font-size: 13px;
            color: rgba(255,255,255,0.4);
            margin-bottom: 28px;
        }

        /* Form */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.25);
            font-size: 14px;
            transition: color 0.3s;
        }

        .form-control {
            width: 100%;
            padding: 13px 14px 13px 42px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            color: #fff;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #7832ff;
            box-shadow: 0 0 0 3px rgba(120, 50, 255, 0.15);
            background: rgba(255,255,255,0.06);
        }

        .form-control:focus + i,
        .form-control:focus ~ i {
            color: #7832ff;
        }

        .form-control::placeholder {
            color: rgba(255,255,255,0.2);
        }

        /* Remember me */
        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }

        .remember-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #7832ff;
        }

        .remember-row label {
            font-size: 13px;
            color: rgba(255,255,255,0.5);
            cursor: pointer;
        }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #7832ff, #b44aff);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,136,0,0.3), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(120, 50, 255, 0.4);
        }

        .btn-login:hover::before {
            opacity: 1;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Alert messages */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: rgba(52, 211, 153, 0.1);
            border: 1px solid rgba(52, 211, 153, 0.2);
            color: #34d399;
        }

        .alert-danger {
            background: rgba(248, 113, 113, 0.1);
            border: 1px solid rgba(248, 113, 113, 0.2);
            color: #f87171;
        }

        /* Validation errors */
        .validation-error {
            color: #f87171;
            font-size: 12px;
            margin-top: 6px;
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 24px;
        }

        .login-footer p {
            font-size: 11px;
            color: rgba(255,255,255,0.2);
        }

        .login-footer a {
            color: #7832ff;
            text-decoration: none;
        }

        @media (max-width: 480px) {
            .login-card { padding: 28px 24px; }
            .login-container { padding: 16px; }
        }
    </style>
</head>
<body>
    <!-- Floating particles -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="login-container">
        <!-- Branding -->
        <div class="login-brand">
            <div class="brand-icon">
                <i class="fas fa-crown"></i>
            </div>
            <h2>Ultra Super Admin</h2>
            <p>Master Control Layer</p>
            <div class="brand-badge">
                <i class="fas fa-lock" style="font-size: 8px;"></i>
                Technosprint Info Solutions
            </div>
        </div>

        <!-- Login Card -->
        <div class="login-card">
            <h3>Welcome Back</h3>
            <p class="subtitle">Sign in to the master control panel</p>

            @if(session('message-success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('message-success') }}
                </div>
            @endif

            @if(session('message-danger'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('message-danger') }}
                </div>
            @endif

            <form method="POST" action="{{ route('ultrasuperadmin.login.submit') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Username or Email</label>
                    <div class="input-wrapper">
                        <input type="text" name="username" class="form-control" placeholder="Enter username or email" value="{{ old('username') }}" required autofocus>
                        <i class="fas fa-user"></i>
                    </div>
                    @error('username')
                        <div class="validation-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                        <i class="fas fa-lock"></i>
                    </div>
                    @error('password')
                        <div class="validation-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="remember-row">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember this session</label>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-arrow-right" style="margin-right: 8px;"></i>
                    Sign In to Master Panel
                </button>
            </form>
        </div>

        <div class="login-footer">
            <p>&copy; {{ date('Y') }} Technosprint Info Solutions. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
