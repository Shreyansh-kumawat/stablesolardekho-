{{-- ===== WAREHOUSE SECTION ===== --}}
@if(Auth::user()->hasAdminPermission('warehouses'))
<div class="w-full flex items-center justify-between px-3 py-2 rounded-md mt-2">
    <span class="flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5M10.5 21H3m1.5 0h1.5m0 0V3.545M6 21V9m0 0h12M6 9l6-6 6 6" />
        </svg>
        <span class="text-xs font-semibold uppercase tracking-widest text-slate-500">Warehouse</span>
    </span>
</div>

<ul class="ml-4 mt-1 space-y-0.5">
    <li>
        <a href="{{ route('admin.warehouses.masterDashboard') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('admin.warehouses.masterDashboard') ? 'ec-active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5" />
            </svg>
            <span>Master Dashboard</span>
        </a>
    </li>

    <li>
        <a href="{{ route('admin.warehouses.index') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('admin.warehouses.index','admin.warehouses.create','admin.warehouses.edit') ? 'ec-active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5M10.5 21H3m1.5 0h1.5m0 0V3.545M6 21V9m0 0h12M6 9l6-6 6 6" />
            </svg>
            <span>All Warehouses</span>
        </a>
    </li>

    <li>
        <a href="{{ route('admin.warehouses.transfer') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('admin.warehouses.transfer') ? 'ec-active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
            </svg>
            <span>Main to Warehouse</span>
        </a>
    </li>

    <li>
        <a href="{{ route('admin.warehouses.w2wTransfer') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('admin.warehouses.w2wTransfer') ? 'ec-active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
            </svg>
            <span>W2W Transfer</span>
        </a>
    </li>

    @php
        $sidebarWarehouses = \App\Models\Warehouse::where('is_active', true)->orderBy('name')->get();
    @endphp

    @foreach($sidebarWarehouses as $wh)
    <li>
        <details class="group" {{ request()->route('id') == $wh->id && request()->routeIs('admin.warehouses.dashboard','admin.warehouses.inventory','admin.warehouses.transactions','admin.warehouses.profitLoss') ? 'open' : '' }}>
            <summary class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors cursor-pointer list-none" style="font-size:0.82rem;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span>{{ $wh->name }}</span>
            </summary>
            <ul class="ml-6 mt-0.5 space-y-0.5">
                <li>
                    <a href="{{ route('admin.warehouses.dashboard', $wh->id) }}"
                        class="flex items-center gap-2 px-2 py-1 rounded transition-colors text-xs {{ request()->routeIs('admin.warehouses.dashboard') && request()->route('id') == $wh->id ? 'ec-active' : '' }}">
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.warehouses.inventory', $wh->id) }}"
                        class="flex items-center gap-2 px-2 py-1 rounded transition-colors text-xs {{ request()->routeIs('admin.warehouses.inventory') && request()->route('id') == $wh->id ? 'ec-active' : '' }}">
                        <span>Inventory</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.warehouses.transactions', $wh->id) }}"
                        class="flex items-center gap-2 px-2 py-1 rounded transition-colors text-xs {{ request()->routeIs('admin.warehouses.transactions') && request()->route('id') == $wh->id ? 'ec-active' : '' }}">
                        <span>Transactions</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.warehouses.profitLoss', $wh->id) }}"
                        class="flex items-center gap-2 px-2 py-1 rounded transition-colors text-xs {{ request()->routeIs('admin.warehouses.profitLoss') && request()->route('id') == $wh->id ? 'ec-active' : '' }}">
                        <span>Profit / Loss</span>
                    </a>
                </li>
            </ul>
        </details>
    </li>
    @endforeach
</ul>
@endif
