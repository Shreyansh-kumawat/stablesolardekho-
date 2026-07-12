{{-- CP Dashboard Link --}}
<a href="{{ route('cpDashboard') }}"
    class="w-full flex items-center gap-3 px-3 py-2 rounded-md transition-colors hover:bg-slate-800 {{ request()->routeIs('cpDashboard') ? 'bg-slate-700 text-white' : '' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
    </svg>
    <span>Dashboard</span>
</a>

{{-- Orders Section --}}
@if(auth()->user()->hasCpPermission('new_request') || auth()->user()->hasCpPermission('view_requests'))
<button type="button" data-toggle="submenu-cpOrders"
    class="w-full flex items-center justify-between px-3 py-2 rounded-md transition-colors hover:bg-slate-800 {{ request()->routeIs('newOrderCp','viewSingleOrderCp','orderReportCp') ? 'bg-slate-700 text-white' : '' }}"
    data-submenu="cpOrders">
    <span class="flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
        </svg>
        <span>Orders</span>
    </span>
    <svg class="w-4 h-4 transition-transform {{ request()->routeIs('newOrderCp','viewSingleOrderCp','orderReportCp') ? 'rotate-180' : '' }}"
        data-arrow="submenu-cpOrders" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />
    </svg>
</button>
<ul id="submenu-cpOrders"
    class="ml-8 mt-1 space-y-1 {{ request()->routeIs('newOrderCp','viewSingleOrderCp','orderReportCp') ? '' : 'hidden' }}">
    @if(auth()->user()->hasCpPermission('new_request'))
    <li>
        <a href="{{ route('newOrderCp') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('newOrderCp') ? 'bg-slate-700 text-white' : 'hover:bg-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>New Order</span>
        </a>
    </li>
    @endif
    @if(auth()->user()->hasCpPermission('view_requests'))
    <li>
        <a href="{{ route('orderReportCp') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('orderReportCp','viewSingleOrderCp') ? 'bg-slate-700 text-white' : 'hover:bg-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
            </svg>
            <span>My Orders</span>
        </a>
    </li>
    @endif
</ul>
@endif

{{-- Product Pricing --}}
@if(auth()->user()->hasCpPermission('product_pricing'))
<a href="{{ route('productPricing') }}"
    class="w-full flex items-center gap-3 px-3 py-2 rounded-md transition-colors hover:bg-slate-800 {{ request()->routeIs('productPricing') ? 'bg-slate-700 text-white' : '' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <span>Product Pricing</span>
</a>
@endif

{{-- Inventory --}}
@if(auth()->user()->hasCpPermission('view_inventory') || auth()->user()->hasCpPermission('transfer_inventory'))
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
    @if(auth()->user()->hasCpPermission('view_inventory'))
    <li>
        <a href="{{ route('cpInventory') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('cpInventory') ? 'bg-slate-700 text-white' : 'hover:bg-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
            </svg>
            <span>My Stock</span>
        </a>
    </li>
    @endif
    @if(auth()->user()->hasCpPermission('transfer_inventory'))
    <li>
        <a href="{{ route('transferInventoryCp') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('transferInventoryCp') ? 'bg-slate-700 text-white' : 'hover:bg-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
            </svg>
            <span>Transfer Stock</span>
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

{{-- Manual Installation --}}
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
