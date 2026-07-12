{{-- ===== ECOMMERCE SECTION ===== --}}
<button type="button" data-toggle="submenu-ecommerce"
    class="w-full flex items-center justify-between px-3 py-2 rounded-md transition-colors hover:bg-slate-800 {{ request()->routeIs('customerOrders','viewCustomerOrder','ecommerceCustomers','adminUserOrders','admin.banners','manageProducts','manageCategory','manageSecondaryAdmins','cpInterestList','cpList','addNewCp','edit_cp','cpDetail','pendingOrders','manageOrdersAdmin','viewSingleOrder') ? 'bg-slate-700 text-white' : '' }}"
    data-submenu="ecommerce">
    <span class="flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
        </svg>
        <span>Ecommerce</span>
        @if(isset($cpOrderBadge, $customerOrderBadge) && ($cpOrderBadge + $customerOrderBadge) > 0)
            <span style="background:#ef4444;color:#fff;font-size:.65rem;font-weight:700;min-width:18px;height:18px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;padding:0 5px;">{{ $cpOrderBadge + $customerOrderBadge }}</span>
        @endif
    </span>
    <svg class="w-4 h-4 transition-transform {{ request()->routeIs('customerOrders','viewCustomerOrder','ecommerceCustomers','adminUserOrders','admin.banners','manageProducts','manageCategory','manageSecondaryAdmins','cpInterestList','cpList','addNewCp','edit_cp','cpDetail','pendingOrders','manageOrdersAdmin','viewSingleOrder') ? 'rotate-180' : '' }}"
        data-arrow="submenu-ecommerce" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />
    </svg>
</button>

<ul id="submenu-ecommerce"
    class="ml-4 mt-1 space-y-0.5 {{ request()->routeIs('customerOrders','viewCustomerOrder','ecommerceCustomers','adminUserOrders','admin.banners','manageProducts','manageCategory','manageSecondaryAdmins','cpInterestList','cpList','addNewCp','edit_cp','cpDetail','pendingOrders','manageOrdersAdmin','viewSingleOrder') ? '' : 'hidden' }}">

    {{-- Home Banners --}}
    @if(Auth::user()->hasAdminPermission('banners'))
    <li>
        <a href="{{ route('admin.banners') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('admin.banners') ? 'ec-active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
            </svg>
            <span>Home Banners</span>
        </a>
    </li>
    @endif

    {{-- Manage Products --}}
    @if(Auth::user()->hasAdminPermission('products'))
    <li>
        <a href="{{ route('manageProducts') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('manageProducts') ? 'ec-active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
            </svg>
            <span>Products</span>
        </a>
    </li>
    @endif

    {{-- Categories --}}
    @if(Auth::user()->hasAdminPermission('categories'))
    <li>
        <a href="{{ route('manageCategory') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('manageCategory') ? 'ec-active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
            </svg>
            <span>Categories</span>
        </a>
    </li>
    @endif

    {{-- Orders --}}
    @if(Auth::user()->hasAdminPermission('orders'))
    <li>
        <a href="{{ route('customerOrders') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('customerOrders','viewCustomerOrder') ? 'ec-active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
            </svg>
            <span>Orders</span>
            @if(isset($customerOrderBadge) && $customerOrderBadge > 0)
                <span style="background:#ef4444;color:#fff;font-size:.65rem;font-weight:700;min-width:18px;height:18px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-left:auto;padding:0 5px;">{{ $customerOrderBadge }}</span>
            @endif
        </a>
    </li>
    @endif

    {{-- Customers --}}
    @if(Auth::user()->hasAdminPermission('users'))
    <li>
        <a href="{{ route('ecommerceCustomers') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('ecommerceCustomers','adminUserOrders') ? 'ec-active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
            <span>Users</span>
        </a>
    </li>
    @endif

    {{-- Secondary Admins (master_admin only) --}}
    @if(Auth::user()->role_id == 1)
    <li>
        <a href="{{ route('manageSecondaryAdmins') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('manageSecondaryAdmins') ? 'ec-active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
            </svg>
            <span>Secondary Admin</span>
        </a>
    </li>
    @endif

    {{-- CP Interest Requests --}}
    @if(Auth::user()->hasAdminPermission('cp_interest'))
    <li>
        <a href="{{ route('cpInterestList') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('cpInterestList') ? 'ec-active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
            </svg>
            <span>CP Interest Requests</span>
        </a>
    </li>
    @endif

    {{-- CP Partners --}}
    @if(Auth::user()->hasAdminPermission('cp_partners'))
    <li>
        <a href="{{ route('cpList') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('cpList','addNewCp','edit_cp','cpDetail') ? 'ec-active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
            <span>CP Partners</span>
        </a>
    </li>
    @endif

    {{-- CP Orders --}}
    @if(Auth::user()->hasAdminPermission('cp_orders'))
    <li>
        <a href="{{ route('pendingOrders') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('pendingOrders','manageOrdersAdmin','viewSingleOrder') ? 'ec-active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
            </svg>
            <span>CP Orders</span>
            @if(isset($cpOrderBadge) && $cpOrderBadge > 0)
                <span style="background:#ef4444;color:#fff;font-size:.65rem;font-weight:700;min-width:18px;height:18px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-left:auto;padding:0 5px;">{{ $cpOrderBadge }}</span>
            @endif
        </a>
    </li>
    @endif

    {{-- View Shop --}}
    <li>
        <a href="{{ route('shop') }}" target="_blank"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 2.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
            </svg>
            <span>View Shop</span>
            <svg class="w-3 h-3 ml-auto opacity-50" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
            </svg>
        </a>
    </li>
</ul>
