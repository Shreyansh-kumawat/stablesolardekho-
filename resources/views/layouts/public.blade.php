<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Solar Panel Installation') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('stable/images/logo.png') }}">
    <!-- jQuery FIRST -->
    <script src="/assets/js/jquery-3.7.1.min.js"></script>
    <!-- Solar Energy Theme CSS -->
    <link href="{{ asset('css/themes/solar-energy.css') }}" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="/assets/css/toastr.min.css">

    <style>
        /* New Modern Public Theme (Aurora/Glass) */
        :root {
            --bg: #0b1220;
            --bg-2: #0f172a;
            --card: rgba(255, 255, 255, 0.06);
            --glass: rgba(255, 255, 255, 0.08);
            --border: rgba(255, 255, 255, 0.12);
            --text: #e2e8f0;
            --muted: #94a3b8;
            --accent: #60a5fa;
            --accent-2: #a78bfa;
            --accent-3: #22d3ee;
            --white: #ffffff;
        }

        body {
            background: radial-gradient(1200px 600px at 10% -10%, rgba(96, 165, 250, 0.25), transparent 60%),
                radial-gradient(900px 500px at 100% 0%, rgba(167, 139, 250, 0.25), transparent 55%),
                linear-gradient(180deg, var(--bg), var(--bg-2));
            color: var(--text);
        }

        /* Logo */
        .logo-container {
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }

        .logo-img {
            height: 40px;
            width: auto;
            max-width: 160px;
            object-fit: contain;
        }

        /* Navigation */
        .solar-nav {
            background: rgba(8, 13, 26, 0.85);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .nav-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 68px;
            gap: 2rem;
        }

        .desktop-menu {
            display: flex;
            align-items: center;
            gap: 0.15rem;
            flex-grow: 1;
            justify-content: center;
        }

        .nav-link {
            color: #cbd5e1;
            font-weight: 500;
            font-size: 0.88rem;
            padding: 0.5rem 0.95rem;
            border-radius: 8px;
            transition: color 0.15s ease, background 0.15s ease;
            white-space: nowrap;
            position: relative;
        }

        .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.04);
        }

        .nav-link.active {
            color: #ffffff;
            font-weight: 600;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 50%;
            transform: translateX(-50%);
            width: 24px;
            height: 2px;
            background: #60a5fa;
            border-radius: 2px;
        }

        /* Login Button (kept but not shown in markup) */
        .login-btn {
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            color: #0b1220;
            font-weight: 800;
            padding: 0.65rem 1.6rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 8px 20px rgba(96, 165, 250, 0.35);
            white-space: nowrap;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 26px rgba(96, 165, 250, 0.45);
        }

        /* Dropdown */
        .user-dropdown {
            background: rgba(2, 6, 23, 0.92);
            border-radius: 14px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
            border: 1px solid var(--border);
            z-index: 99999;
            transform: translateY(8px);
            display: none;
        }

        .nav-container .group:hover .user-dropdown,
        .user-dropdown.show {
            display: block !important;
        }

        .user-dropdown a {
            color: var(--text);
            transition: all 0.2s ease;
        }

        .user-dropdown a:hover {
            background: rgba(255, 255, 255, 0.06);
            color: var(--accent-3);
        }

        /* Mobile */
        .mobile-menu-btn {
            display: none;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid var(--border);
            padding: 0.5rem;
            border-radius: 10px;
            cursor: pointer;
        }

        /* Mobile slide-in sidebar */
        .mobile-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(2px);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.28s ease, visibility 0.28s ease;
            z-index: 998;
        }
        .mobile-backdrop.show { opacity: 1; visibility: visible; }

        .mobile-menu {
            position: fixed;
            top: 0;
            right: 0;
            height: 100vh;
            width: min(78vw, 340px);
            background: linear-gradient(180deg, #0b1220 0%, #080d1a 100%);
            border-left: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: -20px 0 50px rgba(0, 0, 0, 0.5);
            transform: translateX(100%);
            transition: transform 0.32s cubic-bezier(0.32, 0.72, 0.28, 1);
            z-index: 999;
            display: flex;
            flex-direction: column;
            padding: 0;
            overflow-y: auto;
            overscroll-behavior: contain;
        }
        .mobile-menu.show { transform: translateX(0); }

        .mobile-menu-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            flex-shrink: 0;
        }
        .mobile-menu-header .brand {
            font-size: 0.82rem;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .mobile-close-btn {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #e2e8f0;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.15s;
        }
        .mobile-close-btn:hover { background: rgba(255, 255, 255, 0.12); }

        .mobile-actions {
            padding: 16px 20px;
            display: flex;
            gap: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            flex-shrink: 0;
        }
        .mobile-action-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e2e8f0;
            text-decoration: none;
            position: relative;
            transition: background 0.15s;
        }
        .mobile-action-icon:hover { background: rgba(255, 255, 255, 0.1); }
        .mobile-action-icon .badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #ef4444;
            color: #fff;
            font-size: 0.6rem;
            font-weight: 800;
            min-width: 16px;
            height: 16px;
            padding: 0 4px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .mobile-user {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 6px 12px 6px 6px;
            border-radius: 10px;
            text-decoration: none;
            overflow: hidden;
        }
        .mobile-user img { width: 30px; height: 30px; border-radius: 50%; flex-shrink: 0; }
        .mobile-user span { color: #e2e8f0; font-size: 0.85rem; font-weight: 600; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        .mobile-menu nav.mobile-links {
            padding: 12px 12px 24px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .mobile-menu nav.mobile-links a {
            display: flex;
            align-items: center;
            padding: 12px 14px;
            border-radius: 10px;
            color: #cbd5e1;
            font-size: 0.95rem;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.15s, color 0.15s;
        }
        .mobile-menu nav.mobile-links a:hover { background: rgba(255, 255, 255, 0.05); color: #fff; }
        .mobile-menu nav.mobile-links a.active {
            background: rgba(96, 165, 250, 0.12);
            color: #fff;
            font-weight: 600;
        }

        .hamburger-svg { transition: transform 0.3s ease; }
        .mobile-menu-btn.open .hamburger-svg { transform: rotate(90deg); }

        @media (max-width: 480px) {
            .modal-content {
                margin: 0 1rem;
                max-width: calc(100% - 2rem);
            }
        }

        @media (max-width: 1023px) {
            .desktop-menu {
                display: none;
            }

            .mobile-menu-btn {
                display: block;
            }
        }
    </style>

    <link rel="stylesheet" href="/assets/css/lenis.css">
    @yield('css')
</head>

<body class="font-sans antialiased bg-light-bg">
    <div class="min-h-screen">
        <!-- Navigation Bar -->
        <nav class="solar-nav sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="nav-container">
                    <!-- Logo -->
                    <div class="logo-container">
                        <a href="{{ route('dashBoardFunction') }}">
                            <img class="logo-img" src="{{ asset('stable/images/logo1.png') }}"
                                alt="Solar Panel Installation Logo">
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="desktop-menu">
                        <a href="{{ route('dashBoardFunction') }}"
                            class="nav-link {{ request()->routeIs('dashBoardFunction') ? 'active' : '' }}">
                            Home
                        </a>
                        <a href="{{ route('shop') }}"
                            class="nav-link {{ request()->routeIs('shop') ? 'active' : '' }}">
                            Shop
                        </a>
                        <a href="{{ route('about') }}"
                            class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                            About
                        </a>
                        <a href="{{ route('ourTeam') }}"
                            class="nav-link {{ request()->routeIs('ourTeam') ? 'active' : '' }}">
                            Team
                        </a>
                        <a href="{{ route('allInstallationPhotos') }}"
                            class="nav-link {{ request()->routeIs('allInstallationPhotos') ? 'active' : '' }}">
                            Gallery
                        </a>
                        <a href="{{ route('CpInterest') }}"
                            class="nav-link {{ request()->routeIs('CpInterest') ? 'active' : '' }}">
                            Channel Partner
                        </a>
                        <a href="{{ route('contactUs') }}"
                            class="nav-link {{ request()->routeIs('contactUs') ? 'active' : '' }}">
                            Contact
                        </a>
                    </div>

                    <!-- Right Side Actions -->
                    <div style="display:flex; align-items:center; gap:0.6rem; flex-shrink:0;">
                        @auth
                        {{-- Cart Icon --}}
                        @php $cartCount = count(session('cart', [])); @endphp
                        <a href="{{ route('cart.index') }}" style="position:relative; display:flex; align-items:center; justify-content:center; width:40px; height:40px; border-radius:50%; background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.12); text-decoration:none; transition:background 0.2s;"
                           onmouseover="this.style.background='rgba(255,255,255,0.15)'" onmouseout="this.style.background='rgba(255,255,255,0.08)'">
                            <svg width="18" height="18" fill="none" stroke="#e2e8f0" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                            @if($cartCount > 0)
                            <span style="position:absolute; top:-4px; right:-4px; background:#ef4444; color:#fff; font-size:0.62rem; font-weight:800; width:16px; height:16px; border-radius:50%; display:flex; align-items:center; justify-content:center;">{{ $cartCount }}</span>
                            @endif
                        </a>

                        {{-- User Dropdown --}}
                        <div style="position:relative;">
                            <a href="{{ Auth::user()->role_id == 1 || Auth::user()->role_id == 2 ? route('masterAdminDashboard') : (Auth::user()->role_id == 4 ? route('cpDashboard') : route('user.dashboard')) }}" id="user-menu-btn"
                                style="display:flex; align-items:center; gap:6px; background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.12); padding:6px 12px 6px 6px; border-radius:999px; cursor:pointer; transition:background 0.2s; text-decoration:none;"
                                onmouseover="this.style.background='rgba(255,255,255,0.14)'" onmouseout="this.style.background='rgba(255,255,255,0.08)'">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4A90E2&color=fff&size=32"
                                     style="width:28px; height:28px; border-radius:50%;">
                                <span style="color:#e2e8f0; font-size:0.82rem; font-weight:600; max-width:80px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ Auth::user()->name }}</span>
                            </a>

                        </div>
                        @else
                        <a href="{{ route('login') }}"
                            style="display:inline-flex;align-items:center;gap:6px;background:transparent;border:1.5px solid rgba(249,115,22,0.55);color:#f97316;font-weight:700;padding:7px 18px;border-radius:7px;font-size:0.84rem;text-decoration:none;white-space:nowrap;transition:all 0.2s;"
                            onmouseover="this.style.borderColor='#f97316';this.style.background='rgba(249,115,22,0.08)'"
                            onmouseout="this.style.borderColor='rgba(249,115,22,0.55)';this.style.background='transparent'">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/></svg>
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                            style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#f97316,#ea580c);color:#fff;font-weight:700;padding:7px 18px;border-radius:7px;font-size:0.84rem;text-decoration:none;white-space:nowrap;box-shadow:0 4px 14px rgba(249,115,22,0.35);transition:all 0.2s;"
                            onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 8px 20px rgba(249,115,22,0.45)'"
                            onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 14px rgba(249,115,22,0.35)'">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
                            Sign Up
                        </a>
                        @endauth
                    </div>

                    <!-- Mobile Menu Button -->
                    <button class="mobile-menu-btn" type="button" id="mobile-menu-toggle" aria-expanded="false"
                        aria-controls="mobile-menu" aria-label="Open menu">
                        <svg class="hamburger-svg" id="hamburger-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#e2e8f0" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Backdrop -->
            <div id="mobile-backdrop" class="mobile-backdrop"></div>

            <!-- Mobile Slide-in Sidebar -->
            <aside id="mobile-menu" class="mobile-menu" aria-hidden="true">
                <div class="mobile-menu-header">
                    <span class="brand">Menu</span>
                    <button type="button" class="mobile-close-btn" id="mobile-close-btn" aria-label="Close menu">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                @auth
                @php $cartCountM = count(session('cart', [])); @endphp
                <div class="mobile-actions">
                    <a href="{{ Auth::user()->role_id == 1 || Auth::user()->role_id == 2 ? route('masterAdminDashboard') : (Auth::user()->role_id == 4 ? route('cpDashboard') : route('user.dashboard')) }}" class="mobile-user">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4A90E2&color=fff&size=32" alt="">
                        <span>{{ Auth::user()->name }}</span>
                    </a>
                    <a href="{{ route('cart.index') }}" class="mobile-action-icon" aria-label="Cart">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                        @if($cartCountM > 0)<span class="badge">{{ $cartCountM }}</span>@endif
                    </a>
                </div>
                @else
                <div class="mobile-actions">
                    <a href="{{ route('login') }}" class="mobile-user" style="justify-content:center;">
                        <span style="color:#f97316; font-weight:700;">Login</span>
                    </a>
                    <a href="{{ route('register') }}" class="mobile-action-icon" aria-label="Sign Up" style="background:linear-gradient(135deg,#f97316,#ea580c); border:none; color:#fff;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
                    </a>
                </div>
                @endauth

                <nav class="mobile-links">
                    <a href="{{ route('dashBoardFunction') }}" class="{{ request()->routeIs('dashBoardFunction') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('shop') }}" class="{{ request()->routeIs('shop') ? 'active' : '' }}">Shop</a>
                    <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a>
                    <a href="{{ route('ourTeam') }}" class="{{ request()->routeIs('ourTeam') ? 'active' : '' }}">Team</a>
                    <a href="{{ route('allInstallationPhotos') }}" class="{{ request()->routeIs('allInstallationPhotos') ? 'active' : '' }}">Gallery</a>
                    <a href="{{ route('CpInterest') }}" class="{{ request()->routeIs('CpInterest') ? 'active' : '' }}">Channel Partner</a>
                    <a href="{{ route('contactUs') }}" class="{{ request()->routeIs('contactUs') ? 'active' : '' }}">Contact</a>
                    @auth
                    <a href="{{ route('user.orders') }}">My Orders</a>
                    <a href="{{ route('user.account') }}">Account</a>
                    @endauth
                </nav>
            </aside>
        </nav>

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        @include('layouts.partials.footer')
    </div>

    <!-- Scripts -->
    <script src="/assets/js/toastr.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="/assets/js/sweetalert2.all.min.js"></script>

    <style>
        /* SweetAlert2 custom styles */
        .swal2-popup.swal-success {
            border-left: 6px solid #10b981;
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        }

        .swal2-popup.swal-error {
            border-left: 6px solid #ef4444;
            background: linear-gradient(135deg, #fff1f2, #fee2e2);
        }

        .swal2-popup.swal-warning {
            border-left: 6px solid #f59e0b;
            background: linear-gradient(135deg, #fff7ed, #ffedd5);
        }

        .swal2-popup.swal-info {
            border-left: 6px solid #004e89;
            background: linear-gradient(135deg, #e8f1fb, #dbeafc);
        }

        .swal2-toast .swal2-title {
            font-weight: 700;
        }
    </style>

    <script>
        // Toastr Configuration (kept for backward compatibility)
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 5000
        };

        // SweetAlert2 helper
        const AlertStyles = {
            success: { icon: 'success', class: 'swal-success', confirmColor: '#10b981' },
            error: { icon: 'error', class: 'swal-error', confirmColor: '#ef4444' },
            warning: { icon: 'warning', class: 'swal-warning', confirmColor: '#f59e0b' },
            info: { icon: 'info', class: 'swal-info', confirmColor: '#004e89' }
        };

        function showAlert(type = 'info', title = '', text = '', { toast = false, timer = 3500 } = {}) {
            const style = AlertStyles[type] || AlertStyles.info;
            Swal.fire({
                title: title || undefined,
                text: text || undefined,
                icon: style.icon,
                toast,
                position: toast ? 'top-end' : 'center',
                showConfirmButton: !toast,
                confirmButtonColor: style.confirmColor,
                timer: toast ? timer : undefined,
                timerProgressBar: !!toast,
                customClass: { popup: style.class }
            });
        }

        function showConfirm({ title = 'Are you sure?', text = '', confirmText = 'Yes', cancelText = 'Cancel', confirmColor = '#ff6b35' } = {}) {
            return Swal.fire({
                title,
                text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#94a3b8',
                confirmButtonText: confirmText,
                cancelButtonText: cancelText
            });
        }

        // Session messages (Toastr kept; SweetAlert2 used as primary stylish alert)
        document.addEventListener('DOMContentLoaded', function () {
            @if (Session::has('success'))
                // preference to SweetAlert2 toast
                showAlert('success', 'Success', {!! json_encode(Session::get('success')) !!}, { toast: true, timer: 4000 });
            @endif

            @if (Session::has('error'))
                showAlert('error', 'Error', {!! json_encode(Session::get('error')) !!}, { toast: true, timer: 4000 });
            @endif

            @if (Session::has('warning'))
                showAlert('warning', 'Warning', {!! json_encode(Session::get('warning')) !!}, { toast: true, timer: 4000 });
            @endif

            @if (Session::has('info'))
                showAlert('info', 'Info', {!! json_encode(Session::get('info')) !!}, { toast: true, timer: 4000 });
            @endif
        });

        // close auth modal on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && typeof closeAuthModal === 'function') closeAuthModal();
        });

        // Dropdown click/tap support + close outside + keyboard
        (function () {
            const userButtons = document.querySelectorAll('.user-menu-button');

            userButtons.forEach(btn => {
                const menuId = btn.getAttribute('aria-controls');
                const menu = menuId ? document.getElementById(menuId) : null;

                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    // toggle
                    const isOpen = menu && menu.classList.contains('show');
                    // close any other open menus
                    document.querySelectorAll('.user-dropdown.show').forEach(m => {
                        if (m !== menu) {
                            m.classList.remove('show');
                            const b = document.querySelector('.user-menu-button[aria-controls="' + m.id + '"]');
                            if (b) b.setAttribute('aria-expanded', 'false');
                        }
                    });

                    if (!menu) return;
                    if (isOpen) {
                        menu.classList.remove('show');
                        btn.setAttribute('aria-expanded', 'false');
                    } else {
                        menu.classList.add('show');
                        btn.setAttribute('aria-expanded', 'true');
                    }
                });
            });

            // Close on outside click
            document.addEventListener('click', function (e) {
                document.querySelectorAll('.user-dropdown.show').forEach(m => {
                    if (!m.contains(e.target)) {
                        m.classList.remove('show');
                        const b = document.querySelector('.user-menu-button[aria-controls="' + m.id + '"]');
                        if (b) b.setAttribute('aria-expanded', 'false');
                    }
                });
            });

            // Close on Escape
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    document.querySelectorAll('.user-dropdown.show').forEach(m => {
                        m.classList.remove('show');
                        const b = document.querySelector('.user-menu-button[aria-controls="' + m.id + '"]');
                        if (b) b.setAttribute('aria-expanded', 'false');
                    });
                }
            });
        })();
    </script>

    <!-- Mobile slide-in menu -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('mobile-menu-toggle');
            const menu = document.getElementById('mobile-menu');
            const backdrop = document.getElementById('mobile-backdrop');
            const closeBtn = document.getElementById('mobile-close-btn');
            const hamburger = document.getElementById('hamburger-icon');
            if (!btn || !menu || !backdrop) return;

            const HAMBURGER_SVG = '<line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="18" x2="21" y2="18"></line>';
            const CLOSE_SVG = '<line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>';

            function openMenu() {
                menu.classList.add('show');
                backdrop.classList.add('show');
                btn.classList.add('open');
                btn.setAttribute('aria-expanded', 'true');
                btn.setAttribute('aria-label', 'Close menu');
                menu.setAttribute('aria-hidden', 'false');
                if (hamburger) hamburger.innerHTML = CLOSE_SVG;
                document.body.style.overflow = 'hidden';
            }
            function closeMenu() {
                menu.classList.remove('show');
                backdrop.classList.remove('show');
                btn.classList.remove('open');
                btn.setAttribute('aria-expanded', 'false');
                btn.setAttribute('aria-label', 'Open menu');
                menu.setAttribute('aria-hidden', 'true');
                if (hamburger) hamburger.innerHTML = HAMBURGER_SVG;
                document.body.style.overflow = '';
            }
            function toggleMenu() {
                if (menu.classList.contains('show')) closeMenu(); else openMenu();
            }

            btn.addEventListener('click', function (e) { e.preventDefault(); toggleMenu(); });
            if (closeBtn) closeBtn.addEventListener('click', closeMenu);
            backdrop.addEventListener('click', closeMenu);

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && menu.classList.contains('show')) closeMenu();
            });

            // Swipe left-to-right on sidebar to close
            let touchStartX = 0, touchStartY = 0, isSwiping = false;
            menu.addEventListener('touchstart', function (e) {
                touchStartX = e.touches[0].clientX;
                touchStartY = e.touches[0].clientY;
                isSwiping = true;
                menu.style.transition = 'none';
            }, { passive: true });

            menu.addEventListener('touchmove', function (e) {
                if (!isSwiping) return;
                const dx = e.touches[0].clientX - touchStartX;
                const dy = e.touches[0].clientY - touchStartY;
                if (Math.abs(dy) > Math.abs(dx)) return;
                if (dx > 0) {
                    menu.style.transform = 'translateX(' + dx + 'px)';
                    const opacity = Math.max(0, 1 - (dx / menu.offsetWidth));
                    backdrop.style.opacity = opacity;
                }
            }, { passive: true });

            menu.addEventListener('touchend', function (e) {
                if (!isSwiping) return;
                isSwiping = false;
                menu.style.transition = '';
                menu.style.transform = '';
                backdrop.style.opacity = '';
                const dx = e.changedTouches[0].clientX - touchStartX;
                if (dx > 60) closeMenu();
            });

            // Close menu when a link is tapped
            menu.querySelectorAll('a').forEach(function (a) {
                a.addEventListener('click', function () { setTimeout(closeMenu, 120); });
            });
        });

        function toggleUserMenu() {
            const dd = document.getElementById('user-dropdown');
            if (!dd) return;
            dd.style.display = dd.style.display === 'none' ? 'block' : 'none';
        }
        document.addEventListener('click', function(e) {
            const btn = document.getElementById('user-menu-btn');
            const dd = document.getElementById('user-dropdown');
            if (dd && btn && !btn.contains(e.target) && !dd.contains(e.target)) {
                dd.style.display = 'none';
            }
        });
    </script>

    @yield('js')

    <!-- Lenis Smooth Scroll -->
    <script src="/assets/js/lenis.min.js"></script>
    <script>
        const lenis = new Lenis({
            lerp: 0.08,
            smoothWheel: true,
            smoothTouch: false,
        });

        function raf(time) {
            lenis.raf(time);
            requestAnimationFrame(raf);
        }
        requestAnimationFrame(raf);
    </script>

    @guest
        @include('layouts.partials.modals.auth-modal')
    @endguest
</body>

</html>