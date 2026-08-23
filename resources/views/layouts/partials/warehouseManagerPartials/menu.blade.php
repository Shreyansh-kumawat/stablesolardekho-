<a href="{{ route('wh.manager.dashboard') }}"
    class="flex items-center gap-2.5 {{ request()->routeIs('wh.manager.dashboard') ? 'active' : '' }}">
    <svg class="flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="width:18px;height:18px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.125 1.125 0 011.592 0L21.75 12M4.5 9.75V21h4.5v-4.5h4.5V21h4.5V9.75" />
    </svg>
    <span class="text-xs font-semibold">Warehouse Dashboard</span>
</a>

<a href="{{ route('wh.manager.inventory') }}"
    class="flex items-center gap-2.5 {{ request()->routeIs('wh.manager.inventory') ? 'active' : '' }}">
    <svg class="flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="width:18px;height:18px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
    </svg>
    <span class="text-xs font-semibold">My Inventory</span>
</a>

<a href="{{ route('wh.manager.transactions') }}"
    class="flex items-center gap-2.5 {{ request()->routeIs('wh.manager.transactions') ? 'active' : '' }}">
    <svg class="flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="width:18px;height:18px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
    </svg>
    <span class="text-xs font-semibold">Transactions</span>
</a>

<a href="{{ route('wh.manager.transfer') }}"
    class="flex items-center gap-2.5 {{ request()->routeIs('wh.manager.transfer') ? 'active' : '' }}">
    <svg class="flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="width:18px;height:18px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
    </svg>
    <span class="text-xs font-semibold">Transfer to Warehouse</span>
</a>
