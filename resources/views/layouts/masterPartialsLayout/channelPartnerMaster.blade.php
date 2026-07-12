{{-- CP Dashboard Link --}}
<a href="{{ route('cpDashboard') }}"
    class="w-full flex items-center gap-3 px-3 py-2 rounded-md transition-colors hover:bg-slate-800 {{ request()->routeIs('cpDashboard') ? 'bg-slate-700 text-white' : '' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5" />
    </svg>
    <span>Dashboard</span>
</a>

{{-- New Order --}}
@if(auth()->user()->hasCpPermission('new_request'))
<a href="{{ route('newOrderCp') }}"
    class="w-full flex items-center gap-3 px-3 py-2 rounded-md transition-colors hover:bg-slate-800 {{ request()->routeIs('newOrderCp') ? 'bg-slate-700 text-white' : '' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
    </svg>
    <span>New Order</span>
</a>
@endif

{{-- My Orders --}}
@if(auth()->user()->hasCpPermission('view_requests'))
<a href="{{ route('orderReportCp') }}"
    class="w-full flex items-center gap-3 px-3 py-2 rounded-md transition-colors hover:bg-slate-800 {{ request()->routeIs('orderReportCp','viewSingleOrderCp','cpOrderPayment') ? 'bg-slate-700 text-white' : '' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
    </svg>
    <span>My Orders</span>
</a>
@endif

{{-- Installations --}}
@if(auth()->user()->hasCpPermission('manual_installations'))
<button type="button" data-toggle="submenu-cpInstallation"
    class="w-full flex items-center justify-between px-3 py-2 rounded-md transition-colors hover:bg-slate-800 {{ request()->routeIs('newManualEntry','myManualEntries') ? 'bg-slate-700 text-white' : '' }}"
    data-submenu="cpInstallation">
    <span class="flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.657-5.657a8 8 0 1111.314 0l-5.657 5.657zm0 0L12 21" />
        </svg>
        <span>Installations</span>
    </span>
    <svg class="w-4 h-4 transition-transform {{ request()->routeIs('newManualEntry','myManualEntries') ? 'rotate-180' : '' }}"
        data-arrow="submenu-cpInstallation" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />
    </svg>
</button>
<ul id="submenu-cpInstallation"
    class="ml-8 mt-1 space-y-1 {{ request()->routeIs('newManualEntry','myManualEntries') ? '' : 'hidden' }}">
    <li>
        <a href="{{ route('newManualEntry') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('newManualEntry') ? 'bg-slate-700 text-white' : 'hover:bg-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>New Report</span>
        </a>
    </li>
    <li>
        <a href="{{ route('myManualEntries') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('myManualEntries') ? 'bg-slate-700 text-white' : 'hover:bg-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
            </svg>
            <span>My Reports</span>
        </a>
    </li>
</ul>
@endif
