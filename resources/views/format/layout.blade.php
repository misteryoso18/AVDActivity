<!DOCTYPE html>
<html lang="en">
<head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student Management Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary:       #f57c00;
            --primary-dark:  #e65100;
            --primary-deep:  #bf360c;
            --primary-light: #ff9800;
            --primary-pale:  #fff3e0;
            --primary-glow:  rgba(245,124,0,0.18);
            --sidebar-w:     270px;
            --radius:        12px;
            --shadow-sm:     0 2px 8px rgba(0,0,0,0.07);
            --shadow-md:     0 6px 24px rgba(0,0,0,0.10);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            background: #fdf4ec;
            font-family: 'DM Sans', sans-serif;
            color: #1a1a1a;
        }

        /* ── SIDEBAR ── */
        .app-sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: linear-gradient(175deg, var(--primary-dark) 0%, var(--primary-deep) 100%);
            display: flex;
            flex-direction: column;
            z-index: 100;
            overflow: hidden;
        }

        /* top decorative blobs */
        .app-sidebar::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 180px; height: 180px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            pointer-events: none;
        }
        .app-sidebar::after {
            content: '';
            position: absolute;
            top: 60px; right: -30px;
            width: 100px; height: 100px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
            pointer-events: none;
        }

        /* brand */
        .sidebar-brand {
            padding: 1.6rem 1.4rem 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            position: relative;
        }
        .sidebar-brand .brand-icon {
            width: 44px; height: 44px;
            background: rgba(255,255,255,0.15);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem;
            color: #fff;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .sidebar-brand .brand-text h2 {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.05rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: 0.5px;
        }
        .sidebar-brand .brand-text small {
            font-size: 0.72rem;
            color: rgba(255,255,255,0.6);
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        /* nav section label */
        .nav-label {
            padding: 1.1rem 1.4rem 0.35rem;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.4);
        }

        /* nav links */
        .app-nav {
            flex: 1;
            overflow-y: auto;
            padding: 0.5rem 0.75rem;
            scrollbar-width: none;
        }
        .app-nav::-webkit-scrollbar { display: none; }

        .app-nav a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            color: rgba(255,255,255,0.82);
            border-radius: 9px;
            font-size: 0.88rem;
            font-weight: 500;
            margin-bottom: 0.2rem;
            text-decoration: none;
            transition: background 0.18s, color 0.18s, transform 0.15s;
            position: relative;
        }
        .app-nav a .nav-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: rgba(255,255,255,0.08);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.82rem;
            flex-shrink: 0;
            transition: background 0.18s;
        }
        .app-nav a:hover {
            background: rgba(255,255,255,0.10);
            color: #fff;
            transform: translateX(3px);
        }
        .app-nav a:hover .nav-icon { background: rgba(255,255,255,0.18); }
        .app-nav a.active {
            background: rgba(255,255,255,0.16);
            color: #fff;
            font-weight: 700;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }
        .app-nav a.active .nav-icon {
            background: rgba(255,255,255,0.25);
        }
        .app-nav a.active::before {
            content: '';
            position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 3px;
            background: #fff;
            border-radius: 0 3px 3px 0;
        }

        /* sidebar divider */
        .sidebar-divider {
            height: 1px;
            background: rgba(255,255,255,0.08);
            margin: 0.5rem 1rem;
        }

        /* user profile at bottom */
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-user {
            background: rgba(255,255,255,0.07);
            border-radius: 10px;
            padding: 0.85rem 1rem;
            margin-bottom: 0.6rem;
        }
        .sidebar-user .user-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.95rem;
            color: #fff;
            flex-shrink: 0;
        }
        .sidebar-user .user-info { flex: 1; min-width: 0; }
        .sidebar-user .user-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar-user .user-role {
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.55);
        }
        .sidebar-actions {
            display: flex;
            gap: 0.5rem;
        }
        .sidebar-actions a {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            padding: 0.5rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.15s, color 0.15s;
        }
        .sidebar-actions .btn-logout {
            background: rgba(255,255,255,0.12);
            color: #fff;
        }
        .sidebar-actions .btn-logout:hover { background: rgba(255,255,255,0.22); color: #fff; }
        .sidebar-actions .btn-pwd {
            background: rgba(255,255,255,0.07);
            color: rgba(255,255,255,0.7);
        }
        .sidebar-actions .btn-pwd:hover { background: rgba(255,255,255,0.14); color: #fff; }

        /* ── MAIN WRAPPER ── */
        .page-wrapper {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── TOP BAR ── */
        .topbar {
            height: 60px;
            background: #fff;
            border-bottom: 1px solid #f0e8e0;
            display: flex;
            align-items: center;
            padding: 0 1.75rem;
            gap: 1rem;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 1px 6px rgba(245,124,0,0.07);
        }
        .topbar-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            color: var(--primary-dark);
            flex: 1;
        }
        .topbar-badge {
            background: var(--primary-pale);
            color: var(--primary-dark);
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.2rem 0.65rem;
            border-radius: 20px;
            letter-spacing: 0.4px;
        }

        /* ── MAIN CONTENT ── */
        .main-content {
            flex: 1;
            padding: 1.75rem;
        }

        /* ── CARDS ── */
        .card {
            border: none;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            background: #fff;
            border-top: 3px solid var(--primary);
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
        .card-header {
            background: transparent;
            border-bottom: 1px solid #f5ece3;
            color: var(--primary-dark);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            padding: 1rem 1.25rem;
        }

        /* dashboard hero banner */
        .dash-hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-deep) 100%);
            border-radius: var(--radius);
            padding: 2rem 2.25rem;
            color: #fff;
            position: relative;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .dash-hero::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,0.07);
        }
        .dash-hero::after {
            content: '';
            position: absolute;
            bottom: -60px; right: 80px;
            width: 150px; height: 150px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }
        .dash-hero h1 { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.75rem; font-weight: 800; margin: 0; color: #fff; }
        .dash-hero p  { color: rgba(255,255,255,0.85); margin: 0.4rem 0 0; }

        /* general helpers */
        h1,h2,h3 { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--primary-dark); }
        a { color: var(--primary-dark); }
        .btn-primary { background: var(--primary); border-color: var(--primary); font-weight: 600; border-radius: 8px; }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-sm { border-radius: 7px; font-weight: 600; }

        /* ── RESPONSIVE ── */
        @media (max-width: 991px) {
            .app-sidebar { position: static; width: 100%; height: auto; }
            .page-wrapper { margin-left: 0; }
            .topbar { display: none; }
        }
    </style>
</head>
<body>
    @php($hideChrome = trim($__env->yieldContent('hideChrome')) !== '')
    @php($role = strtolower((string) session('role')))

    @if (!$hideChrome)
        <aside class="app-sidebar">
            <div class="sidebar-brand">
                <div class="brand-icon"><i class="fas fa-graduation-cap"></i></div>
                <div class="brand-text">
                    <h2>EMS</h2>
                    <small>Educational Management</small>
                </div>
            </div>

            <div class="nav-label">Navigation</div>
            <nav class="app-nav">
                @if ($role === 'admin')
                    <a href="{{ route('home') }}" data-ajax-link="true" data-target="#ajaxPageContent">
                        <span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span> Dashboard
                    </a>
                    <a href="{{ route('students.index') }}" data-ajax-link="true" data-target="#ajaxPageContent">
                        <span class="nav-icon"><i class="fas fa-users"></i></span> Students
                    </a>
                    <a href="{{ route('degrees.index') }}" data-ajax-link="true" data-target="#ajaxPageContent">
                        <span class="nav-icon"><i class="fas fa-graduation-cap"></i></span> Degrees
                    </a>
                    <a href="{{ route('logs') }}" data-ajax-link="true" data-target="#ajaxPageContent">
                        <span class="nav-icon"><i class="fas fa-file-alt"></i></span> System Logs
                    </a>
                @elseif ($role === 'teacher')
                    <a href="{{ route('teacher.dashboard') }}" data-ajax-link="true" data-target="#ajaxPageContent">
                        <span class="nav-icon"><i class="fas fa-chalkboard-teacher"></i></span> Dashboard
                    </a>
                @elseif ($role === 'student')
                    <a href="{{ route('student.dashboard') }}" data-ajax-link="true" data-target="#ajaxPageContent">
                        <span class="nav-icon"><i class="fas fa-user-graduate"></i></span> Dashboard
                    </a>
                @endif
            </nav>

            @if (session('user_id'))
                <div class="sidebar-footer">
                    <div class="sidebar-user d-flex align-items-center gap-2">
                        <div class="user-avatar"><i class="fas fa-user"></i></div>
                        <div class="user-info">
                            <div class="user-name">{{ session('username') ?? 'User' }}</div>
                            <div class="user-role">{{ session('role') ?? '' }}</div>
                        </div>
                    </div>
                    <div class="sidebar-actions">
                        <a href="{{ route('logout') }}" class="btn-logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                        <a href="{{ route('password.change') }}" class="btn-pwd">
                            <i class="fas fa-key"></i> Password
                        </a>
                    </div>
                </div>
            @endif
        </aside>

        <div class="page-wrapper">
            <div class="topbar">
                <div class="topbar-title">@yield('title', 'Dashboard')</div>
                @if(session('role'))
                    <span class="topbar-badge">{{ strtoupper(session('role')) }}</span>
                @endif
            </div>
            <main class="main-content">
                <div id="ajaxPageContent">
                    @yield('Content')
                </div>
            </main>
        </div>

    @else
        <div id="ajaxPageContent">
            @yield('Content')
        </div>
    @endif

    <script src="https://code.jquery.com/jquery-3.6.4.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/app.js') }}?v=generic-autoreload-v2-20260520"></script>
</body>
</html>
