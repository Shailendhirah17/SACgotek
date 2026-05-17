@php
$gs = generalSetting();
@endphp
<!DOCTYPE html>
@php
App::setLocale(getUserLanguage());
$ttl_rtl = userRtlLtl();
@endphp
<html lang="{{ app()->getLocale() }}" @if (isset($ttl_rtl) && $ttl_rtl==1) dir="rtl" class="rtl" @endif>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset(generalSetting()->favicon) }}" type="image/png" />
    <title>SAC Portal | @lang('auth.login')</title>
    <meta name="_token" content="{!! csrf_token() !!}" />
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
    <link rel="stylesheet" href="{{ asset('public/backEnd/') }}/vendors/css/bootstrap.css" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/') }}/vendors/css/toastr.min.css" />
    
    <style>
        :root {
            --bg-dark: #0f172a;
            --bg-darker: #0b0f19;
            --card-glass: rgba(30, 41, 59, 0.7);
            --border-glass: rgba(255, 255, 255, 0.08);
            --accent-glow: #00f2fe;
            --accent-glow2: #4facfe;
            --text-primary: #ffffff;
            --text-secondary: #94a3b8;
        }

        * {
            font-family: 'Outfit', sans-serif;
            box-sizing: border-box;
        }

        body.login-screen-body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: radial-gradient(circle at top right, #1e1b4b 0%, var(--bg-darker) 60%), 
                        radial-gradient(circle at bottom left, #0f172a 0%, var(--bg-darker) 80%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
        }

        /* Tech Mesh Overlay */
        body.login-screen-body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 0);
            background-size: 24px 24px;
            pointer-events: none;
            z-index: 1;
        }

        .login-container {
            width: 100%;
            max-width: 520px;
            padding: 20px;
            z-index: 10;
        }

        .glass-card {
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            background-color: var(--card-glass);
            border: 1px solid var(--border-glass);
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5),
                        inset 0 1px 0 rgba(255, 255, 255, 0.1);
            padding: 40px;
            position: relative;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(
                transparent, 
                rgba(0, 242, 254, 0.15), 
                transparent 30%
            );
            animation: rotate-glow 12s linear infinite;
            pointer-events: none;
            z-index: 0;
        }

        @keyframes rotate-glow {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .card-content {
            position: relative;
            z-index: 2;
        }

        /* SVG SAC Logo Emblem */
        .logo-wrapper {
            margin-bottom: 25px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .sac-logo-svg {
            width: 85px;
            height: 85px;
            filter: drop-shadow(0 0 12px rgba(0, 242, 254, 0.4));
            animation: float-logo 4s ease-in-out infinite;
        }

        @keyframes float-logo {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-6px) rotate(3deg); }
        }

        .brand-title {
            font-size: 32px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--text-primary) 30%, var(--accent-glow) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .brand-subtitle {
            font-size: 13px;
            color: var(--text-secondary);
            margin: 0;
            font-weight: 500;
            letter-spacing: 4px;
            text-transform: uppercase;
        }

        .form-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 25px 0 15px;
            text-align: center;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 16px;
            transition: all 0.3s ease;
            z-index: 5;
        }

        .form-control-custom {
            width: 100%;
            background: rgba(15, 23, 42, 0.5) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
            border-radius: 14px;
            padding: 15px 15px 15px 48px;
            height: auto;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
            box-shadow: none !important;
        }

        .form-control-custom:focus {
            border-color: var(--accent-glow) !important;
            background: rgba(15, 23, 42, 0.7) !important;
            box-shadow: 0 0 20px rgba(0, 242, 254, 0.15) !important;
        }

        .form-control-custom:focus + .input-icon {
            color: var(--accent-glow);
            filter: drop-shadow(0 0 4px var(--accent-glow));
        }

        .options-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 13px;
        }

        .checkbox-custom {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: var(--text-secondary);
            user-select: none;
        }

        .checkbox-custom input {
            display: none;
        }

        .checkbox-indicator {
            width: 18px;
            height: 18px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            background: rgba(15, 23, 42, 0.3);
        }

        .checkbox-custom input:checked + .checkbox-indicator {
            background: var(--accent-glow);
            border-color: var(--accent-glow);
            color: #0b0f19;
        }

        .forget-link {
            color: var(--accent-glow);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .forget-link:hover {
            color: #ffffff;
            text-shadow: 0 0 8px var(--accent-glow);
        }

        .btn-submit-custom {
            width: 100%;
            background: linear-gradient(135deg, var(--accent-glow2) 0%, var(--accent-glow) 100%);
            border: none;
            border-radius: 14px;
            padding: 16px;
            color: #0b0f19;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 8px 24px rgba(0, 242, 254, 0.25);
        }

        .btn-submit-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(0, 242, 254, 0.4);
            filter: brightness(1.1);
        }

        .btn-submit-custom:active {
            transform: translateY(0);
        }

        /* Demo Auto-Login Section */
        .sync-section {
            margin-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding-top: 25px;
        }

        .sync-title {
            text-align: center;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--text-secondary);
            margin-bottom: 15px;
        }

        .grid-sync-buttons {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .btn-sync-role {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            color: var(--text-primary);
            padding: 10px 5px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            width: 100%;
        }

        .btn-sync-role:hover {
            background: rgba(0, 242, 254, 0.08);
            border-color: var(--accent-glow);
            color: var(--accent-glow);
            transform: translateY(-1px);
        }

        @media (max-width: 480px) {
            .glass-card {
                padding: 25px;
            }
            .grid-sync-buttons {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .copyright-text {
            text-align: center;
            margin-top: 25px;
            font-size: 12px;
            color: var(--text-secondary);
        }
    </style>
</head>

<body class="login-screen-body">

    <div class="login-container">
        <div class="glass-card">
            <div class="card-content">
                
                <!-- Logo & Brand Header -->
                <div class="logo-wrapper">
                    <svg class="sac-logo-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="sacGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#4facfe" />
                                <stop offset="100%" stop-color="#00f2fe" />
                            </linearGradient>
                            <filter id="sacGlow" x="-20%" y="-20%" width="140%" height="140%">
                                <feGaussianBlur stdDeviation="4" result="blur" />
                                <feComposite in="SourceGraphic" in2="blur" operator="over" />
                            </filter>
                        </defs>
                        <!-- Outer Academic Circular Crest -->
                        <circle cx="50" cy="50" r="44" fill="none" stroke="url(#sacGrad)" stroke-width="2" stroke-dasharray="6 3" stroke-opacity="0.6" filter="url(#sacGlow)" />
                        <circle cx="50" cy="50" r="40" fill="none" stroke="url(#sacGrad)" stroke-width="1" stroke-opacity="0.3" />
                        
                        <!-- Graduation Cap (Mortarboard) -->
                        <polygon points="50,16 84,26 50,36 16,26" fill="url(#sacGrad)" fill-opacity="0.15" stroke="url(#sacGrad)" stroke-width="3" stroke-linejoin="round" filter="url(#sacGlow)" />
                        <path d="M30,30 L30,44 C30,50 70,50 70,44 L70,30" fill="none" stroke="url(#sacGrad)" stroke-width="3" stroke-linecap="round" />
                        <path d="M50,26 L22,32 L20,46 C20,49 18,49 18,46 L18,34" fill="none" stroke="url(#sacGrad)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />

                        <!-- Open Book Motif representing Study & Wisdom -->
                        <path d="M50,76 C35,72 20,76 20,76 L20,54 C20,54 35,50 50,54 Z" fill="url(#sacGrad)" fill-opacity="0.1" stroke="url(#sacGrad)" stroke-width="3" stroke-linejoin="round" />
                        <path d="M50,76 C65,72 80,76 80,76 L80,54 C80,54 65,50 50,54 Z" fill="url(#sacGrad)" fill-opacity="0.1" stroke="url(#sacGrad)" stroke-width="3" stroke-linejoin="round" filter="url(#sacGlow)" />
                        <line x1="50" y1="54" x2="50" y2="76" stroke="url(#sacGrad)" stroke-width="2.5" stroke-linecap="round" />
                    </svg>
                    <h1 class="brand-title">SAC</h1>
                    <h2 class="brand-subtitle">Smart ERP Portal</h2>
                </div>

                <h3 class="form-title">@lang('auth.login_details')</h3>

                <!-- Laravel Alerts -->
                @if(session()->has('message-success') && session()->get('message-success') != '')
                    <div class="alert alert-success py-2 text-center" style="background: rgba(40, 167, 69, 0.15); border: 1px solid #28a745; color: #28a745; border-radius: 10px; font-size: 13px;">
                        {{ session()->get('message-success') }}
                    </div>
                @endif
                @if(session()->has('message-danger') && session()->get('message-danger') != '')
                    <div class="alert alert-danger py-2 text-center" style="background: rgba(220, 53, 69, 0.15); border: 1px solid #dc3545; color: #dc3545; border-radius: 10px; font-size: 13px;">
                        {{ session()->get('message-danger') }}
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <input type="hidden" name="username" id="username-hidden">

                    <!-- Email Field -->
                    <div class="input-group-custom">
                        <input class="form-control-custom{{ $errors->has('email') ? ' is-invalid' : '' }}"
                            type="text" name="email" id="email-address"
                            placeholder="@lang('auth.enter_email_address')" value="{{ old('email') }}" required />
                        <i class="fa-regular fa-envelope input-icon"></i>
                    </div>
                    @if ($errors->has('email'))
                        <span class="text-danger d-block mb-3" style="font-size: 12px; margin-top: -15px; font-weight: 500;">
                            {{ $errors->first('email') }}
                        </span>
                    @endif

                    <!-- Password Field -->
                    <div class="input-group-custom">
                        <input class="form-control-custom{{ $errors->has('password') ? ' is-invalid' : '' }}"
                            type="password" name="password" id="password"
                            placeholder="@lang('auth.enter_password')" required />
                        <i class="fa-solid fa-lock input-icon"></i>
                    </div>
                    @if ($errors->has('password'))
                        <span class="text-danger d-block mb-3" style="font-size: 12px; margin-top: -15px; font-weight: 500;">
                            {{ $errors->first('password') }}
                        </span>
                    @endif

                    <!-- Options Bar -->
                    <div class="options-bar">
                        <label class="checkbox-custom">
                            <input type="checkbox" name="remember" id="rememberMe" {{ old('remember') ? 'checked' : '' }}>
                            <span class="checkbox-indicator"><i class="fa-solid fa-check fa-xs"></i></span>
                            @lang('auth.remember_me')
                        </label>
                        <a href="{{ route('recoveryPassord') }}" class="forget-link">@lang('auth.forget_password')?</a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit-custom" id="btnsubmit">
                        <i class="fa-solid fa-unlock-keyhole"></i>
                        @lang('auth.login')
                    </button>
                </form>

                <!-- Auto-Login Demonstration Section -->
                @if (config('app.app_sync'))
                    <div class="sync-section">
                        <h4 class="sync-title">Auto-Login Selection Panel</h4>
                        <div class="grid-sync-buttons">
                            @foreach ($users as $user)
                                @if ($user)
                                    <form method="POST" action="{{ route('login') }}" style="margin: 0;">
                                        @csrf
                                        <input type="hidden" name="email" value="{{ $user[0]->email }}">
                                        <input type="hidden" name="auto_login" value="true">
                                        <button type="submit" class="btn-sync-role" title="{{ $user[0]->roles->name }}">
                                            {{ $user[0]->roles->name }}
                                        </button>
                                    </form>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>

        <div class="copyright-text">
            Copyright &copy; {{ date('Y') }} SAC. All rights reserved.
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('public/backEnd/') }}/vendors/js/jquery-3.2.1.min.js"></script>
    <script src="{{ asset('public/backEnd/') }}/vendors/js/popper.js"></script>
    <script src="{{ asset('public/backEnd/') }}/vendors/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ asset('public/backEnd/') }}/vendors/js/toastr.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            // Set initial state
            $("#username-hidden").val($("#email-address").val());
            
            // Listen to keypress
            $("#email-address").keyup(function () {
                $("#username-hidden").val($(this).val());
            });
        });
    </script>

    {!! Toastr::message() !!}

</body>

</html>