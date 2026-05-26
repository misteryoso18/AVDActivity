@extends('format.layout')

@section('title', 'Login — EMS')

@section('hideChrome', '1')

@section('Content')
    @php($isLocked = $isLocked ?? false)
    @php($lockSecondsLeft = $lockSecondsLeft ?? null)

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&family=DM+Sans:wght@400;500;600&display=swap');

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        /* Reset body for login */
        body {
            display: block !important;
            background: #fff !important;
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
        }

        #ajaxPageContent {
            min-height: 100vh;
        }

        /* ── TWO-COLUMN WRAPPER ── */
        .login-wrapper {
            display: flex;
            flex-direction: row;
            min-height: 100vh;
            width: 100%;
        }

        /* ── LEFT PANEL ── */
        .login-left {
            width: 48%;
            min-height: 100vh;
            background: linear-gradient(145deg, #e65100 0%, #f57c00 50%, #ff9800 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 4rem 3.5rem;
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }

        .login-left .deco-circle-1 {
            position: absolute;
            top: -80px; right: -80px;
            width: 320px; height: 320px;
            border-radius: 50%;
            background: rgba(255,255,255,0.07);
            pointer-events: none;
        }
        .login-left .deco-circle-2 {
            position: absolute;
            bottom: -60px; left: -40px;
            width: 220px; height: 220px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            pointer-events: none;
        }
        .login-left .deco-circle-3 {
            position: absolute;
            bottom: 120px; right: -60px;
            width: 180px; height: 180px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.10);
            pointer-events: none;
        }
        .login-left .deco-dot {
            position: absolute;
            top: 120px; left: 50px;
            width: 8px; height: 8px;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            box-shadow: 0 0 0 14px rgba(255,255,255,0.07);
            pointer-events: none;
        }

        .login-left .brand {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 3.5rem;
            position: relative;
            z-index: 2;
        }
        .login-left .brand .logo-box {
            width: 52px; height: 52px;
            background: rgba(255,255,255,0.18);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; color: #fff;
        }
        .login-left .brand .logo-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.4rem;
            font-weight: 900;
            color: #fff;
        }
        .login-left .brand small {
            display: block;
            font-size: 0.72rem;
            color: rgba(255,255,255,0.65);
            font-weight: 500;
        }

        .login-left .hero-heading {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 2.6rem;
            font-weight: 900;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 1.1rem;
            position: relative;
            z-index: 2;
        }
        .login-left .hero-sub {
            font-size: 0.97rem;
            color: rgba(255,255,255,0.78);
            line-height: 1.6;
            max-width: 320px;
            position: relative;
            z-index: 2;
            margin-bottom: 2.5rem;
        }

        .feature-pills {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
            position: relative;
            z-index: 2;
        }
        .feature-pill {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            background: rgba(255,255,255,0.12);
            border-radius: 30px;
            padding: 0.5rem 1rem;
            color: #fff;
            font-size: 0.83rem;
            font-weight: 500;
            width: fit-content;
        }
        .feature-pill i { font-size: 0.78rem; opacity: 0.85; }

        /* ── RIGHT PANEL ── */
        .login-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem 3.5rem;
            background: #fff;
            position: relative;
        }
        .login-right::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 4px;
            background: linear-gradient(90deg, #f57c00, #ff9800);
        }

        .login-box {
            width: 100%;
            max-width: 400px;
        }

        .login-box .form-heading {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.85rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 0.4rem;
        }
        .login-box .form-sub {
            font-size: 0.88rem;
            color: #888;
            margin-bottom: 2rem;
        }

        .field-group { margin-bottom: 1.2rem; }
        .field-group label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #444;
            margin-bottom: 0.4rem;
        }
        .field-wrap { position: relative; }
        .field-icon {
            position: absolute;
            left: 14px; top: 50%; transform: translateY(-50%);
            color: #bbb;
            font-size: 0.85rem;
            pointer-events: none;
            transition: color 0.15s;
        }
        .field-wrap input {
            width: 100%;
            padding: 0.72rem 1rem 0.72rem 2.6rem;
            border: 1.5px solid #e8e0d8;
            border-radius: 10px;
            font-size: 0.9rem;
            font-family: 'DM Sans', sans-serif;
            color: #1a1a1a;
            background: #fdf9f6;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .field-wrap input:focus {
            border-color: #f57c00;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(245,124,0,0.12);
        }
        .field-wrap:focus-within .field-icon { color: #f57c00; }

        .eye-toggle {
            position: absolute;
            right: 13px; top: 50%; transform: translateY(-50%);
            background: none; border: none;
            color: #bbb; cursor: pointer; font-size: 0.85rem;
            padding: 0;
            transition: color 0.15s;
        }
        .eye-toggle:hover { color: #f57c00; }

        .field-error { font-size: 0.75rem; color: #e53935; margin-top: 0.3rem; }

        .btn-login {
            width: 100%;
            padding: 0.8rem;
            background: linear-gradient(135deg, #f57c00 0%, #e65100 100%);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 14px rgba(245,124,0,0.35);
            margin-top: 0.5rem;
        }
        .btn-login:hover {
            opacity: 0.92;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(245,124,0,0.4);
        }

        .alert-custom {
            padding: 0.75rem 1rem;
            border-radius: 9px;
            font-size: 0.84rem;
            font-weight: 500;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
        }
        .alert-success-c { background: #f0fdf4; border: 1px solid #86efac; color: #166534; }
        .alert-danger-c  { background: #fff5f5; border: 1px solid #fca5a5; color: #b91c1c; }
        .alert-custom button {
            margin-left: auto;
            background: none; border: none;
            color: inherit; cursor: pointer; opacity: 0.6; font-size: 0.9rem; padding: 0;
        }
        .alert-custom button:hover { opacity: 1; }

        /* lock */
        .lock-screen { text-align: center; padding: 1.5rem 0; }
        .lock-icon-wrap {
            width: 80px; height: 80px;
            background: linear-gradient(135deg, #fff0f0 0%, #ffe0e0 100%);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.25rem;
            font-size: 2rem; color: #e53935;
            box-shadow: 0 4px 16px rgba(229,57,53,0.18);
        }
        .lock-screen h4 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.15rem; font-weight: 800; color: #1a1a1a; margin-bottom: 0.5rem;
        }
        .lock-screen p { font-size: 0.87rem; color: #777; margin-bottom: 1.5rem; }
        .countdown-box {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            background: #fff5f5;
            border: 2px solid #fca5a5;
            border-radius: 14px;
            padding: 1rem 2.5rem;
        }
        #lockCountdown {
            font-family: 'Plus Jakarta Sans', monospace;
            font-size: 2.5rem; font-weight: 900; color: #e53935; letter-spacing: 0.3rem;
            animation: blink 1.2s ease-in-out infinite;
        }
        @keyframes blink { 0%,100% { opacity:1; } 50% { opacity:0.55; } }
        .countdown-box small { font-size: 0.72rem; color: #aaa; font-weight: 600; letter-spacing: 0.5px; margin-top: 0.25rem; }

        .login-footer-note { margin-top: 2rem; font-size: 0.75rem; color: #bbb; text-align: center; }

        @media (max-width: 768px) {
            .login-wrapper { flex-direction: column; }
            .login-left { width: 100%; min-height: auto; padding: 2.5rem 2rem; }
            .login-left .hero-heading { font-size: 1.85rem; }
            .login-right { padding: 2.5rem 1.5rem; }
        }
    </style>

    <div class="login-wrapper">

        {{-- LEFT --}}
        <div class="login-left">
            <div class="deco-circle-1"></div>
            <div class="deco-circle-2"></div>
            <div class="deco-circle-3"></div>
            <div class="deco-dot"></div>

            <div class="brand">
                <div class="logo-box"><i class="fas fa-graduation-cap"></i></div>
                <div>
                    <div class="logo-name">EMS</div>
                    <small>Educational Management System</small>
                </div>
            </div>

            <h1 class="hero-heading">Smart learning,<br>simplified.</h1>
            <p class="hero-sub">Manage students, degrees, and academic records all from one powerful platform.</p>

            <div class="feature-pills">
                <div class="feature-pill"><i class="fas fa-users"></i> Student &amp; Teacher Management</div>
                <div class="feature-pill"><i class="fas fa-graduation-cap"></i> Degree &amp; Course Tracking</div>
                <div class="feature-pill"><i class="fas fa-shield-alt"></i> Role-Based Access Control</div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="login-right">
            <div class="login-box">
                <h2 class="form-heading">Welcome back</h2>
                <p class="form-sub">Sign in to continue to your dashboard.</p>

                @if ($isLocked)
                    <div class="lock-screen">
                        <div class="lock-icon-wrap"><i class="fas fa-lock"></i></div>
                        <h4>Account Temporarily Locked</h4>
                        <p>Too many failed login attempts.<br>Please wait before trying again.</p>
                        <div class="countdown-box">
                            <span id="lockCountdown">5:00</span>
                            <small>MINUTES REMAINING</small>
                        </div>
                    </div>

                    <script>
                        (function() {
                            const lockSecondsLeft = {{ $lockSecondsLeft }};
                            const startTime = Date.now();
                            const el = document.getElementById('lockCountdown');
                            let last = lockSecondsLeft;
                            function tick() {
                                const elapsed = Math.floor((Date.now() - startTime) / 1000);
                                const remaining = Math.max(0, lockSecondsLeft - elapsed);
                                if (remaining <= 0) { location.reload(); return; }
                                if (Math.floor(remaining) !== last) {
                                    last = Math.floor(remaining);
                                    const m = Math.floor(remaining / 60);
                                    const s = remaining % 60;
                                    el.textContent = m + ':' + (s < 10 ? '0' : '') + s;
                                }
                            }
                            tick();
                            setInterval(tick, 100);
                        })();
                    </script>

                @else
                    @if (session('success'))
                        <div class="alert-custom alert-success-c">
                            <i class="fas fa-check-circle"></i>
                            <span>{{ session('success') }}</span>
                            <button onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert-custom alert-danger-c">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>
                                @foreach ($errors->all() as $error)
                                    {{ $error }}<br>
                                @endforeach
                            </span>
                            <button onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('authenticate') }}" novalidate autocomplete="off">
                        @csrf

                        <div class="field-group">
                            <label for="username">Username</label>
                            <div class="field-wrap">
                                <i class="fas fa-user field-icon"></i>
                                <input type="text" id="username" name="username"
                                    placeholder="Enter your username"
                                    autocomplete="off" autocapitalize="off"
                                    autocorrect="off" spellcheck="false" required>
                            </div>
                            @error('username')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="field-group">
                            <label for="password">Password</label>
                            <div class="field-wrap">
                                <i class="fas fa-lock field-icon"></i>
                                <input type="password" id="password" name="password"
                                    placeholder="Enter your password"
                                    autocomplete="new-password" required>
                                <button type="button" class="eye-toggle" id="eyeToggle">
                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn-login">
                            <i class="fas fa-sign-in-alt" style="margin-right:0.4rem;"></i>Sign In
                        </button>
                    </form>

                    <p class="login-footer-note">EMS &copy; {{ date('Y') }} &mdash; Educational Management System</p>

                    <script>
                        (function () {
                            const form = document.querySelector('form[action="{{ route('authenticate') }}"]');
                            const usernameEl = document.getElementById('username');
                            const passwordEl = document.getElementById('password');
                            const eyeToggle = document.getElementById('eyeToggle');
                            const eyeIcon = document.getElementById('eyeIcon');

                            if (eyeToggle) {
                                eyeToggle.addEventListener('click', function () {
                                    const isPwd = passwordEl.type === 'password';
                                    passwordEl.type = isPwd ? 'text' : 'password';
                                    eyeIcon.className = isPwd ? 'fas fa-eye-slash' : 'fas fa-eye';
                                });
                            }

                            const clearFields = () => {
                                if (form) form.reset();
                                if (usernameEl) usernameEl.value = '';
                                if (passwordEl) passwordEl.value = '';
                            };

                            window.addEventListener('pageshow', function (e) {
                                if (e.persisted) clearFields();
                            });

                            clearFields();
                        })();
                    </script>
                @endif
            </div>
        </div>

    </div>{{-- end .login-wrapper --}}
@endsection
