<!DOCTYPE html>
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
                            @if(Auth::user()->role_id == 4)
                            <a href="{{ route('cpDashboard') }}" class="block px-4 py-2.5 text-sm rounded-lg mx-2 mt-2" style="color:#4A90E2;font-weight:600;">
                                <i class="fas fa-chart-line" style="margin-right:4px;"></i> CP Dashboard
                            </a>
                            @endif
                            <a href="#" class="block px-4 py-2.5 text-sm rounded-lg mx-2 {{ Auth::user()->role_id != 4 ? 'mt-2' : '' }}">
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