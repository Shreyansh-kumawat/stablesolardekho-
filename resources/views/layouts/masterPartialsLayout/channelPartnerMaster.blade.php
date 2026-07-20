{{-- CP Dashboard Link --}}
<a href="{{ route('cpDashboard') }}"
    class="cp-nav-link {{ request()->routeIs('cpDashboard') ? 'cp-active' : '' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5" />
    </svg>
    <span>Dashboard</span>
</a>

{{-- New Order --}}
@if(auth()->user()->hasCpPermission('new_request'))
<a href="{{ route('newOrderCp') }}"
    class="cp-nav-link {{ request()->routeIs('newOrderCp') ? 'cp-active' : '' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
    </svg>
    <span>New Order</span>
</a>
@endif

{{-- My Orders --}}
@if(auth()->user()->hasCpPermission('view_requests'))
<a href="{{ route('orderReportCp') }}"
    class="cp-nav-link {{ request()->routeIs('orderReportCp','viewSingleOrderCp','cpOrderPayment') ? 'cp-active' : '' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
    </svg>
    <span>My Orders</span>
</a>
@endif

{{-- Installations --}}
@if(auth()->user()->hasCpPermission('manual_installations'))
<button type="button" data-toggle="submenu-cpInstallation"
    class="cp-nav-btn {{ request()->routeIs('newManualEntry','myManualEntries') ? 'cp-active' : '' }}"
    data-submenu="cpInstallation">
    <span style="display:flex;align-items:center;gap:0.75rem;">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.657-5.657a8 8 0 1111.314 0l-5.657 5.657zm0 0L12 21" />
        </svg>
        <span>Installations</span>
    </span>
    <svg class="w-4 h-4" style="transition:transform 0.2s;{{ request()->routeIs('newManualEntry','myManualEntries') ? 'transform:rotate(180deg);' : '' }}"
        data-arrow="submenu-cpInstallation" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />
    </svg>
</button>
<ul id="submenu-cpInstallation"
    style="margin-left:2rem;margin-top:0.25rem;{{ request()->routeIs('newManualEntry','myManualEntries') ? '' : 'display:none;' }}">
    <li>
        <a href="{{ route('newManualEntry') }}"
            class="cp-sub-link {{ request()->routeIs('newManualEntry') ? 'cp-active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>New Report</span>
        </a>
    </li>
    <li>
        <a href="{{ route('myManualEntries') }}"
            class="cp-sub-link {{ request()->routeIs('myManualEntries') ? 'cp-active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
            </svg>
            <span>My Reports</span>
        </a>
    </li>
</ul>
@endif

{{-- My Referrals --}}
<a href="{{ route('cpReferrals') }}"
    class="cp-nav-link {{ request()->routeIs('cpReferrals') ? 'cp-active' : '' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
    </svg>
    <span>My Referrals</span>
</a>

{{-- My Profile --}}
<a href="{{ route('cpProfile') }}"
    class="cp-nav-link {{ request()->routeIs('cpProfile') ? 'cp-active' : '' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
    </svg>
    <span>My Profile</span>
</a>
