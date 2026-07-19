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

{{-- Product Pricing --}}
@if(auth()->user()->hasCpPermission('product_pricing'))
<a href="{{ route('productPricing') }}"
    class="w-full flex items-center gap-3 px-3 py-2 rounded-md transition-colors hover:bg-slate-800 {{ request()->routeIs('productPricing') ? 'bg-slate-700 text-white' : '' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
    </svg>
    <span>Product Pricing</span>
</a>
@endif

{{-- Inventory --}}
@if(auth()->user()->hasCpPermission('view_inventory'))
<button type="button" data-toggle="submenu-cpInventory"
    class="w-full flex items-center justify-between px-3 py-2 rounded-md transition-colors hover:bg-slate-800 {{ request()->routeIs('cpInventory','transferInventoryCp','invTxnsCp') ? 'bg-slate-700 text-white' : '' }}"
    data-submenu="cpInventory">
    <span class="flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
        </svg>
        <span>Inventory</span>
    </span>
    <svg class="w-4 h-4 transition-transform {{ request()->routeIs('cpInventory','transferInventoryCp','invTxnsCp') ? 'rotate-180' : '' }}"
        data-arrow="submenu-cpInventory" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />
    </svg>
</button>
<ul id="submenu-cpInventory"
    class="ml-8 mt-1 space-y-1 {{ request()->routeIs('cpInventory','transferInventoryCp','invTxnsCp') ? '' : 'hidden' }}">
    <li>
        <a href="{{ route('cpInventory') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('cpInventory') ? 'bg-slate-700 text-white' : 'hover:bg-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
            </svg>
            <span>My Stock</span>
        </a>
    </li>
    @if(auth()->user()->hasCpPermission('transfer_inventory'))
    <li>
        <a href="{{ route('transferInventoryCp') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('transferInventoryCp') ? 'bg-slate-700 text-white' : 'hover:bg-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
            </svg>
            <span>Transfer</span>
        </a>
    </li>
    @endif
    @if(auth()->user()->hasCpPermission('inventory_transactions'))
    <li>
        <a href="{{ route('invTxnsCp') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('invTxnsCp') ? 'bg-slate-700 text-white' : 'hover:bg-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
            <span>Transactions</span>
        </a>
    </li>
    @endif
</ul>
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

{{-- My Profile --}}
<a href="{{ route('cpProfile') }}"
    class="w-full flex items-center gap-3 px-3 py-2 rounded-md transition-colors hover:bg-slate-800 {{ request()->routeIs('cpProfile') ? 'bg-slate-700 text-white' : '' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
    </svg>
    <span>My Profile</span>
</a>
