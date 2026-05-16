<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="SuperAdmin Panel - {{ \App\Models\SuperAdminSetting::get('platform_name', 'TISEDU') }}">
    <title>SuperAdmin Login - {{ \App\Models\SuperAdminSetting::get('platform_name', 'TISEDU') }}</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('public/backEnd/login')}}/css/4_3_1_bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('public/backEnd/login')}}/css/themify-icons.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
            position: relative;
            overflow: hidden;
        }

        /* Animated background particles */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background:
                radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 48, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(76, 190, 243, 0.1) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
            z-index: 0;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(5deg); }
            66% { transform: translate(-20px, 20px) rotate(-3deg); }
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-header .logo-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .login-header .logo-icon .ti-shield {
            font-size: 28px;
            color: #fff;
        }

        .login-header h4 {
            color: #fff;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .login-header p {
            color: rgba(255, 255, 255, 0.5);
            font-size: 14px;
            font-weight: 300;
        }

        .alert-msg {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 13px;
            font-weight: 500;
        }

        .alert-msg.success {
            background: rgba(76, 175, 80, 0.15);
            border: 1px solid rgba(76, 175, 80, 0.3);
            color: #81c784;
        }

        .alert-msg.danger {
            background: rgba(244, 67, 54, 0.15);
            border: 1px solid rgba(244, 67, 54, 0.3);
            color: #ef9a9a;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            color: rgba(255, 255, 255, 0.7);
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 8px;
            letter-spacing: 0.3px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper input {
            width: 100%;
            padding: 14px 16px 14px 44px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            color: #fff;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            outline: none;
        }

        .input-wrapper input::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .input-wrapper input:focus {
            border-color: #667eea;
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }

        .input-wrapper .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.4);
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .input-wrapper input:focus + .input-icon,
        .input-wrapper input:focus ~ .input-icon {
            color: #667eea;
        }

        .error-text {
            color: #ef9a9a;
            font-size: 12px;
            margin-top: 6px;
            padding-left: 4px;
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #667eea;
            cursor: pointer;
        }

        .remember-me label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 13px;
            cursor: pointer;
            margin: 0;
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .login-btn .ti-lock {
            margin-right: 8px;
        }

        .footer-text {
            text-align: center;
            margin-top: 32px;
            color: rgba(255, 255, 255, 0.3);
            font-size: 12px;
        }

        .security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 24px;
            padding: 10px;
            background: rgba(76, 175, 80, 0.08);
            border: 1px solid rgba(76, 175, 80, 0.15);
            border-radius: 10px;
        }

        .security-badge .ti-lock {
            color: #81c784;
            font-size: 12px;
        }

        .security-badge span {
            color: rgba(255, 255, 255, 0.4);
            font-size: 11px;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="logo-icon">
                <span class="ti-shield"></span>
            </div>
            <h4>Super Admin</h4>
            <p>Platform Administration Panel</p>
        </div>

        @if(session('message-success'))
            <div class="alert-msg success">
                {{ session('message-success') }}
            </div>
        @endif

        @if(session('message-danger'))
            <div class="alert-msg danger">
                {{ session('message-danger') }}
            </div>
        @endif

        <form method="POST" action="{{ route('superadmin.login.submit') }}">
            @csrf

            <div class="form-group">
                <label for="username">Username or Email</label>
                <div class="input-wrapper">
                    <input type="text"
                           id="username"
                           name="username"
                           placeholder="Enter username or email"
                           value="{{ old('username') }}"
                           required
                           autofocus>
                    <span class="input-icon ti-user"></span>
                </div>
                @error('username')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password"
                           id="password"
                           name="password"
                           placeholder="Enter password"
                           required>
                    <span class="input-icon ti-key"></span>
                </div>
                @error('password')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-options">
                <div class="remember-me">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember me</label>
                </div>
            </div>

            <button type="submit" class="login-btn">
                <span class="ti-lock"></span>
                Sign In
            </button>

            <div class="security-badge">
                <span class="ti-lock"></span>
                <span>256-bit SSL Encrypted Connection</span>
            </div>
        </form>
    </div>

    <div class="footer-text">
        &copy; {{ date('Y') }} {{ \App\Models\SuperAdminSetting::get('platform_name', 'TISEDU') }} • SuperAdmin Panel
    </div>
</div>

<!-- Scripts -->
<script src="{{asset('public/backEnd/login')}}/js/jquery-3.3.1.slim.min.js"></script>
<script src="{{asset('public/backEnd/login')}}/js/bootstrap.min.js"></script>

</body>
</html>
