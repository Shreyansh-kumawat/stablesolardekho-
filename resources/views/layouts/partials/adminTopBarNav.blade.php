 <header class="z-40 h-14 flex-shrink-0">
            <div class="h-full px-4 flex items-center justify-between">
                <button id="sidebarToggle"
                    class="md:hidden inline-flex items-center justify-center w-9 h-9 rounded-md header-btn">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 5.25h16.5M3.75 12h16.5M3.75 18.75h16.5" />
                    </svg>
                </button>

                <div class="flex-1 px-2">
                    <h1>@yield('page_title', 'Dashboard')</h1>
                </div>

                <div class="flex items-center gap-2">
                    <button class="relative inline-flex items-center justify-center w-9 h-9 rounded-full header-btn">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.25 17.25a2.25 2.25 0 11-4.5 0m9-4.5V9a6.75 6.75 0 10-13.5 0v3.75L3 15h18l-2.25-2.25z" />
                        </svg>
                    </button>

                    <div class="relative">
                        <button id="profileBtn"
                            class="inline-flex items-center gap-1.5 px-2 py-1.5 rounded-md header-btn">
                            <img src="{{ asset('stable/images/avatar.png') }}" alt="Avatar"
                                class="w-7 h-7 rounded-full object-cover">
                            <span class="hidden sm:inline text-xs font-semibold">{{ Auth::user()->name ?? 'User' }}</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />
                            </svg>
                        </button>
                        <div id="profileMenu"
                            class="absolute right-0 mt-1 w-40 border rounded-lg shadow-lg hidden z-50">
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center gap-2 px-3 py-2 text-xs font-semibold transition-colors border-b first:rounded-t-lg">
                                <i class="fa fa-lock" style="font-size: 11px;"></i>
                                Change Password
                            </a>
                            <form id="logoutForm" method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left flex items-center gap-2 px-3 py-2 text-xs font-semibold transition-colors last:rounded-b-lg">
                                    <i class="fa fa-sign-out-alt" style="font-size: 11px;"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>