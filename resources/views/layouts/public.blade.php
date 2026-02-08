<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Solar Panel Installation') }}</title>
    <!-- jQuery FIRST -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Solar Energy Theme CSS -->
    <link href="{{ asset('css/themes/solar-energy.css') }}" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

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
            height: 46px;
            width: auto;
            max-width: 180px;
            object-fit: contain;
            filter: drop-shadow(0 6px 18px rgba(0, 0, 0, .25));
        }

        /* Navigation */
        .solar-nav {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.75), rgba(2, 6, 23, 0.65));
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 10px 30px rgba(2, 6, 23, 0.4);
            position: relative;
        }

        .nav-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 72px;
            gap: 1.5rem;
            overflow: visible;
        }

        .desktop-menu {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            flex-grow: 1;
            justify-content: center;
        }

        .nav-link {
            color: var(--text);
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.55rem 1rem;
            border-radius: 999px;
            transition: all 0.25s ease;
            position: relative;
            white-space: nowrap;
            border: 1px solid transparent;
        }

        .nav-link:hover {
            color: var(--white);
            background: var(--glass);
            border-color: var(--border);
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.25);
        }

        .nav-link.active {
            color: var(--white);
            background: linear-gradient(135deg, rgba(96, 165, 250, 0.25), rgba(167, 139, 250, 0.25));
            border-color: rgba(96, 165, 250, 0.35);
            box-shadow: 0 6px 18px rgba(96, 165, 250, 0.2);
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

        .mobile-menu {
            display: none;
            padding: 10px 12px 16px;
            border-top: 1px solid var(--border);
            background: rgba(2, 6, 23, 0.75);
            backdrop-filter: blur(10px);
        }

        .mobile-menu a {
            display: block;
            padding: 10px 12px;
            margin: 6px 0;
            border-radius: 10px;
            color: var(--text);
            font-size: .9rem;
            border: 1px solid transparent;
        }

        .mobile-menu a:hover {
            background: var(--glass);
            border-color: var(--border);
        }

        .mobile-menu.show {
            display: block;
        }

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
                            Dashboard
                        </a>
                        <a href="{{ route('CpInterest') }}"
                            class="nav-link {{ request()->routeIs('CpInterest') ? 'active' : '' }}">
                            Enroll For Channel Partner
                        </a>
                        <a href="{{ route('installationPartner') }}"
                            class="nav-link {{ request()->routeIs('installationPartner') ? 'active' : '' }}">
                            Enroll For Installation Partner
                        </a>
                        <a href="{{ route('ourTeam') }}"
                            class="nav-link {{ request()->routeIs('ourTeam') ? 'active' : '' }}">
                            Our Team
                        </a>
                        <a href="{{ route('allInstallationPhotos') }}"
                            class="nav-link {{ request()->routeIs('allInstallationPhotos') ? 'active' : '' }}">
                            Installation Photos
                        </a>
                        <a href="{{ route('contactUs') }}"
                            class="nav-link {{ request()->routeIs('contactUs') ? 'active' : '' }}">
                            Contact
                        </a>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button class="mobile-menu-btn" type="button" id="mobile-menu-toggle" aria-expanded="false"
                        aria-controls="mobile-menu">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#e2e8f0" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="mobile-menu">
                <a href="{{ route('dashBoardFunction') }}"
                    class="{{ request()->routeIs('dashBoardFunction') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('CpInterest') }}"
                    class="{{ request()->routeIs('CpInterest') ? 'active' : '' }}">Enroll For Channel Partner</a>
                <a href="{{ route('installationPartner') }}"
                    class="{{ request()->routeIs('installationPartner') ? 'active' : '' }}">Enroll For Installation
                    Partner</a>
                <a href="{{ route('ourTeam') }}" class="{{ request()->routeIs('ourTeam') ? 'active' : '' }}">Our
                    Team</a>
                <a href="{{ route('allInstallationPhotos') }}"
                    class="{{ request()->routeIs('allInstallationPhotos') ? 'active' : '' }}">Installation Photos</a>
                <a href="{{ route('contactUs') }}"
                    class="{{ request()->routeIs('contactUs') ? 'active' : '' }}">Contact</a>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>


    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        // Auth modal: safe initialization and helper functions
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('auth-modal');
            const closeBtn = document.getElementById('auth-modal-close');
            const titleEl = document.getElementById('auth-modal-title');
            const emailInput = document.getElementById('auth-email');

            function openAuthModal(mode = 'login') {
                if (!modal) return;
                modal.classList.remove('hidden');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                if (titleEl) titleEl.textContent = mode === 'register' ? 'Register' : 'Login';
                setTimeout(() => emailInput && emailInput.focus(), 50);
            }

            function closeAuthModal() {
                if (!modal) return;
                modal.classList.add('hidden');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }

            // Expose globally for inline onclick
            window.openAuthModal = openAuthModal;
            window.closeAuthModal = closeAuthModal;

            if (closeBtn) closeBtn.addEventListener('click', closeAuthModal);

            if (modal) {
                // click outside content closes modal
                modal.addEventListener('click', function (e) {
                    if (e.target === modal) closeAuthModal();
                });
            }

            // close on Escape
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeAuthModal();
            });
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

    <!-- Mobile menu toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('mobile-menu-toggle');
            const menu = document.getElementById('mobile-menu');
            if (!btn || !menu) return;

            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const isOpen = menu.classList.toggle('show');
                btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });
        });
    </script>

    @yield('js')
</body>

</html>