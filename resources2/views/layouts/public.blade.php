{{-- filepath: d:\My\rebatesoft\stable\resources\views\layouts\public.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Solar Dashboard') }}</title>
    <!-- jQuery FIRST -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        :root {
            --primary-orange: #ff6b35;
            --primary-blue: #004e89;
            --secondary-blue: #1a659e;
            --accent-yellow: #ffd23f;
            --light-bg: #f7f9fc;
            --dark-text: #1a1a2e;
            --light-text: #6c757d;
            --white: #ffffff;
        }

        /* Navigation Styles */
        .solar-nav {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            box-shadow: 0 4px 20px rgba(0, 78, 137, 0.15);
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            position: relative;
        }

        .nav-link:hover {
            color: var(--accent-yellow);
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .nav-link.active {
            color: var(--accent-yellow);
            background: rgba(255, 255, 255, 0.15);
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 40%;
            height: 3px;
            background: var(--accent-yellow);
            border-radius: 2px 2px 0 0;
        }

        .login-btn {
            background: linear-gradient(135deg, var(--primary-orange) 0%, #ff5722 100%);
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 10px;
            font-weight: 700;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
            border-color: var(--accent-yellow);
        }

        /* Mobile Menu */
        .mobile-menu-button {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .mobile-menu-button:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        #mobile-menu {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .mobile-nav-link {
            color: rgba(255, 255, 255, 0.9);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .mobile-nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--accent-yellow);
            transform: translateX(5px);
        }

        /* User Dropdown */
        .user-dropdown {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border: 2px solid var(--primary-orange);
        }

        .user-dropdown a {
            color: var(--dark-text);
            transition: all 0.3s ease;
        }

        .user-dropdown a:hover {
            background: linear-gradient(135deg, var(--primary-orange) 0%, #ff5722 100%);
            color: white;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 9998;
            display: none; /* Changed from flex to none */
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-overlay:not(.hidden) {
            display: flex; /* Show when not hidden */
        }

        .modal-container {
            background: white;
            border-radius: 24px;
            max-width: 450px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
            z-index: 9999;
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-30px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            padding: 2rem;
            border-radius: 24px 24px 0 0;
            text-align: center;
            position: relative;
        }

        .modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: white;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .modal-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: white;
            margin: 0;
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f8fafc;
        }

        .modal-input:focus {
            outline: none;
            border-color: var(--primary-orange);
            background-color: white;
            box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
        }

        .modal-submit-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary-orange) 0%, #ff5722 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .modal-submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
        }

        .modal-switch {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--light-text);
        }

        .modal-switch a {
            color: var(--primary-orange);
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .modal-switch a:hover {
            color: var(--secondary-blue);
        }

        /* Hide mobile menu by default */
        .mobile-menu {
            display: none;
        }

        @media (max-width: 1024px) {
            .mobile-menu {
                display: block;
            }

            .desktop-menu {
                display: none;
            }

            .modal-container {
                max-width: 95%;
            }
        }

        /* Logo Styles */
        .logo-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-img {
            height: 5rem;
            width: 5rem;
            /* Removed filter that was making logo invisible */
            transition: all 0.3s ease;
        }

        .logo-img:hover {
            transform: scale(1.05);
        }

        /* Notification Badge */
        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: var(--primary-orange);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }
    </style>

    @yield('css')
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation Bar -->
        <nav class="solar-nav sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('dashBoardFunction') }}" class="logo-container">
                            <img class="logo-img" src="{{ asset('stable/images/logo1.png') }}" alt="Solar Dashboard Logo">
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden lg:flex lg:items-center lg:space-x-2 desktop-menu">
                        <a href="{{ route('dashBoardFunction') }}"
                            class="nav-link {{ request()->routeIs('dashBoardFunction') ? 'active' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('CpInterest') }}"
                            class="nav-link {{ request()->routeIs('CpInterest') ? 'active' : '' }}">
                            Channel Partner
                        </a>
                        <a href="{{ route('installationPartner') }}"
                            class="nav-link {{ request()->routeIs('installationPartner') ? 'active' : '' }}">
                            Installation Partner
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

                    <!-- Right Side (Login/User) -->
                    <div class="flex items-center space-x-4">
                        @if (Auth::check())
                        <!-- Notifications -->
                        <button
                            class="relative p-2 text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition-all">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="notification-badge">3</span>
                        </button>

                        <!-- User Dropdown -->
                        <div class="relative group">
                            <button
                                class="flex items-center space-x-2 p-2 rounded-lg hover:bg-white hover:bg-opacity-10 transition-all">
                                <img class="h-9 w-9 rounded-full border-2 border-white"
                                    src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=ff6b35&color=fff"
                                    alt="{{ Auth::user()->name }}">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="absolute right-0 top-full mt-2 w-56 user-dropdown hidden group-hover:block">
                                <div class="py-2">
                                    <div class="px-4 py-3 border-b border-gray-100">
                                        <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                    <a href="#" class="block px-4 py-2.5 text-sm rounded-lg mx-2 mt-2">
                                        Your Profile
                                    </a>
                                    <a href="#" class="block px-4 py-2.5 text-sm rounded-lg mx-2">
                                        Settings
                                    </a>
                                    <a href="{{ route('logout') }}"
                                        class="block px-4 py-2.5 text-sm rounded-lg mx-2 mb-2"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Sign out
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                        @else
                        <button onclick="openAuthModal('login')" class="login-btn">
                            Login
                        </button>
                        @endif

                        <!-- Mobile Menu Button -->
                        <button type="button" onclick="toggleMobileMenu()"
                            class="mobile-menu mobile-menu-button lg:hidden">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div id="mobile-menu" class="hidden lg:hidden">
                <div class="px-4 pt-2 pb-4 space-y-1">
                    <a href="{{ route('dashBoardFunction') }}" class="mobile-nav-link block">Dashboard</a>
                    <a href="{{ route('CpInterest') }}" class="mobile-nav-link block">Channel Partner</a>
                    <a href="{{ route('installationPartner') }}" class="mobile-nav-link block">Installation Partner</a>
                    <a href="{{ route('ourTeam') }}" class="mobile-nav-link block">Our Team</a>
                    <a href="{{ route('allInstallationPhotos') }}" class="mobile-nav-link block">Installation Photos</a>
                    <a href="{{ route('contactUs') }}" class="mobile-nav-link block">Contact</a>
                </div>
            </div>
        </nav>

        <!-- Auth Modal -->
        <div id="auth-modal" class="modal-overlay hidden" onclick="closeModalOnOutsideClick(event)">
            <div class="modal-container" onclick="event.stopPropagation()">
                <div class="modal-header">
                    <button onclick="closeAuthModal()" class="modal-close">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <h2 class="modal-title" id="modal-title">Welcome Back</h2>
                </div>

                <div class="modal-body">
                    <!-- Login Form -->
                    <form id="login-form" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" required class="modal-input"
                                    placeholder="your.email@example.com">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                                <input type="password" name="password" required class="modal-input"
                                    placeholder="Enter your password">
                            </div>
                            <div class="flex items-center justify-between">
                                <label class="flex items-center">
                                    <input type="checkbox" name="remember"
                                        class="rounded border-gray-300 text-orange-500 focus:ring-orange-500">
                                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                                </label>
                                <a href="#" class="text-sm text-orange-500 hover:text-orange-600">Forgot password?</a>
                            </div>
                            <button type="submit" class="modal-submit-btn">Login</button>
                        </div>
                    </form>

                    <!-- Register Form -->
                    <form id="register-form" method="POST" action="{{ route('register') }}" class="hidden">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                                <input type="text" name="name" required class="modal-input" placeholder="John Doe">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" required class="modal-input"
                                    placeholder="your.email@example.com">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                                <input type="password" name="password" required class="modal-input"
                                    placeholder="Min. 8 characters">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                                <input type="password" name="password_confirmation" required class="modal-input"
                                    placeholder="Confirm password">
                            </div>
                            <button type="submit" class="modal-submit-btn">Register</button>
                        </div>
                    </form>

                    <div class="modal-switch">
                        <span id="switch-text">Don't have an account?</span>
                        <a href="#" onclick="switchAuthMode(event)">Register here</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }

        // Auth Modal Functions
        function openAuthModal(mode) {
            const modal = document.getElementById('auth-modal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent body scroll
            if (mode === 'register') {
                switchAuthMode(null);
            }
        }

        function closeAuthModal() {
            const modal = document.getElementById('auth-modal');
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Restore body scroll
        }

        function closeModalOnOutsideClick(event) {
            if (event.target.classList.contains('modal-overlay')) {
                closeAuthModal();
            }
        }

        function switchAuthMode(event) {
            if (event) event.preventDefault();

            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            const modalTitle = document.getElementById('modal-title');
            const switchText = document.getElementById('switch-text');

            if (loginForm.classList.contains('hidden')) {
                // Switch to login
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
                modalTitle.textContent = 'Welcome Back';
                switchText.textContent = "Don't have an account?";
                event.target.textContent = 'Register here';
            } else {
                // Switch to register
                loginForm.classList.add('hidden');
                registerForm.classList.remove('hidden');
                modalTitle.textContent = 'Create Account';
                switchText.textContent = 'Already have an account?';
                event.target.textContent = 'Login here';
            }
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeAuthModal();
            }
        });

        // Toastr Configuration
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 5000
        };

        // Session messages
        @if (Session::has('success'))
            toastr.success("{{ Session::get('success') }}", 'Success');
        @endif

        @if (Session::has('error'))
            toastr.error("{{ Session::get('error') }}", 'Error');
        @endif

        // Only open modal if there are actual errors and user is not authenticated
        @if ($errors->any() && !Auth::check())
            openAuthModal('login');
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}", 'Error');
            @endforeach
        @endif
    </script>

    @yield('js')

    <script>
        $(document).ready(function() {
            $('.select2-element').select2();
        });
    </script>
</body>

</html>
