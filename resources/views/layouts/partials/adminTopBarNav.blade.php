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
                            class="absolute right-0 mt-1 w-40 border rounded-lg shadow-lg hidden z-50" style="background:#fff;">
                            <a href="{{ route('user.dashboard') }}"
                                style="display:flex;align-items:center;gap:8px;padding:8px 12px;font-size:12px;font-weight:600;color:#374151;text-decoration:none;border-bottom:1px solid #f1f5f9;"
                                onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                My Account
                            </a>
                            <form id="logoutForm" method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    style="width:100%;text-align:left;display:flex;align-items:center;gap:8px;padding:8px 12px;font-size:12px;font-weight:600;color:#dc2626;border:none;background:none;cursor:pointer;"
                                    onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='transparent'">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>