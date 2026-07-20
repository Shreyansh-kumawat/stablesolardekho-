@extends('layouts.adminLayout')

@section('title', 'Admin Dashboard')

@section('css')
<style>
    .dash-grid { display: grid; gap: 1rem; }
    .dash-grid-6 { grid-template-columns: repeat(2, 1fr); }
    .dash-grid-2 { grid-template-columns: 1fr; }
    .dash-grid-3 { grid-template-columns: 1fr; }

    @media (min-width: 640px) {
        .dash-grid-6 { grid-template-columns: repeat(3, 1fr); }
    }
    @media (min-width: 1024px) {
        .dash-grid-6 { grid-template-columns: repeat(4, 1fr); }
        .dash-grid-2 { grid-template-columns: 2fr 1fr; }
        .dash-grid-3 { grid-template-columns: 1fr 1fr; }
    }
    @media (min-width: 1280px) {
        .dash-grid-6 { grid-template-columns: repeat(6, 1fr); }
    }

    .stat-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.25rem;
        transition: transform .15s, box-shadow .15s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,.08);
    }
    .stat-icon {
        width: 40px; height: 40px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
    }
    .stat-label {
        font-size: .7rem; font-weight: 600; text-transform: uppercase;
        letter-spacing: .5px; color: #9ca3af;
    }
    .stat-value {
        font-size: 1.75rem; font-weight: 800; color: #1f2937; line-height: 1.2;
    }

    .stat-card.featured {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        border: none; color: #fff;
    }
    .stat-card.featured .stat-label { color: rgba(255,255,255,.7); }
    .stat-card.featured .stat-value { color: #fff; }

    .dash-panel {
        background: #fff; border: 1px solid #e5e7eb;
        border-radius: 12px; padding: 1.25rem;
    }
    .dash-panel h2 {
        font-size: .9rem; font-weight: 700; color: #1f2937;
        margin: 0 0 1rem; display: flex; align-items: center; justify-content: space-between;
    }
    .dash-panel h2 a {
        font-size: .75rem; color: #6366f1; text-decoration: none; font-weight: 600;
    }
    .dash-panel h2 a:hover { text-decoration: underline; }

    .order-row {
        display: flex; align-items: center; gap: .75rem;
        padding: .6rem 0; border-bottom: 1px solid #f3f4f6;
    }
    .order-row:last-child { border-bottom: none; }
    .order-avatar {
        width: 34px; height: 34px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: .75rem; font-weight: 700; flex-shrink: 0;
    }
    .order-name { font-size: .82rem; font-weight: 600; color: #1f2937; }
    .order-id { font-size: .7rem; color: #9ca3af; font-family: monospace; }
    .order-amount { font-size: .82rem; font-weight: 700; color: #1f2937; }

    .status-badge {
        display: inline-flex; padding: 2px 8px; border-radius: 12px;
        font-size: .65rem; font-weight: 600; text-transform: uppercase; letter-spacing: .3px;
    }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-confirmed { background: #dbeafe; color: #1e40af; }
    .status-shipped { background: #ede9fe; color: #5b21b6; }
    .status-delivered, .status-completed { background: #d1fae5; color: #065f46; }
    .status-cancelled { background: #fee2e2; color: #991b1b; }

    .product-row {
        display: flex; align-items: center; gap: .75rem;
        padding: .5rem 0; border-bottom: 1px solid #f3f4f6;
    }
    .product-row:last-child { border-bottom: none; }
    .product-rank {
        width: 24px; height: 24px; border-radius: 50%; background: #f3f4f6;
        display: flex; align-items: center; justify-content: center;
        font-size: .7rem; font-weight: 700; color: #6b7280; flex-shrink: 0;
    }
    .product-bar {
        height: 6px; background: #f3f4f6; border-radius: 3px; margin-top: 4px;
    }
    .product-bar-fill {
        height: 100%; border-radius: 3px; background: linear-gradient(90deg, #6366f1, #8b5cf6);
    }

    .quick-link {
        display: flex; align-items: center; gap: .75rem;
        padding: .75rem; border-radius: 8px; border: 1px solid #f3f4f6;
        text-decoration: none; color: #374151; transition: all .15s;
    }
    .quick-link:hover {
        background: #f9fafb; border-color: #e5e7eb;
        transform: translateX(4px);
    }
    .quick-link .ql-icon {
        width: 36px; height: 36px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .quick-link .ql-title { font-size: .82rem; font-weight: 600; }
    .quick-link .ql-desc { font-size: .7rem; color: #9ca3af; }

    .legend-item {
        display: flex; align-items: center; justify-content: space-between;
        font-size: .75rem; padding: .25rem 0;
    }
    .legend-dot {
        width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 6px;
    }

    .empty-state {
        text-align: center; padding: 2rem 1rem; color: #9ca3af;
    }
    .empty-state svg { width: 36px; height: 36px; margin: 0 auto .5rem; color: #d1d5db; }
    .empty-state p { font-size: .82rem; }
</style>
@endsection

@section('content')
<div style="padding: 1.5rem; max-width: 1400px; margin: 0 auto;">

    {{-- Header --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:.5rem;">
        <div>
            <h1 style="font-size:1.5rem; font-weight:800; color:#1f2937; margin:0;">Dashboard</h1>
            <p style="font-size:.82rem; color:#9ca3af; margin:.25rem 0 0;">Welcome back, {{ Auth::user()->name }}! Here's your business overview.</p>
        </div>
        <span style="font-size:.75rem; color:#9ca3af; background:#f9fafb; padding:.4rem .75rem; border-radius:8px; border:1px solid #e5e7eb;">
            {{ now()->format('d M Y, h:i A') }}
        </span>
    </div>

    {{-- Stat Cards --}}
    <div class="dash-grid dash-grid-6" style="margin-bottom:1.5rem;">
        {{-- Total Revenue --}}
        <div class="stat-card featured" style="grid-column: span 1;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.75rem;">
                <span class="stat-label">Total Revenue</span>
                <div class="stat-icon" style="background:rgba(255,255,255,.2);">
                    <svg style="width:20px;height:20px;color:#fff;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33"/>
                    </svg>
                </div>
            </div>
            <p class="stat-value">&#8377;{{ number_format($totalRevenue, 0) }}</p>
            <p style="font-size:.7rem; color:rgba(255,255,255,.6); margin-top:.25rem;">from paid orders</p>
        </div>

        {{-- Today's Orders --}}
        <div class="stat-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.75rem;">
                <span class="stat-label">Today's Orders</span>
                <div class="stat-icon" style="background:#fef3c7;">
                    <svg style="width:20px;height:20px;color:#f59e0b;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                    </svg>
                </div>
            </div>
            <p class="stat-value">{{ $ordersToday }}</p>
            <p style="font-size:.7rem; color:#9ca3af; margin-top:.25rem;">new orders</p>
        </div>

        {{-- Pending Orders --}}
        <div class="stat-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.75rem;">
                <span class="stat-label">Pending Orders</span>
                <div class="stat-icon" style="background:#fee2e2;">
                    <svg style="width:20px;height:20px;color:#ef4444;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="stat-value">{{ $pendingOrders }}</p>
            <p style="font-size:.7rem; color:#9ca3af; margin-top:.25rem;">need attention</p>
        </div>

        {{-- Total Customers --}}
        <div class="stat-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.75rem;">
                <span class="stat-label">Customers</span>
                <div class="stat-icon" style="background:#d1fae5;">
                    <svg style="width:20px;height:20px;color:#10b981;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                </div>
            </div>
            <p class="stat-value">{{ $totalCustomers }}</p>
            <p style="font-size:.7rem; color:#9ca3af; margin-top:.25rem;">registered users</p>
        </div>

        {{-- CP Partners --}}
        <div class="stat-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.75rem;">
                <span class="stat-label">CP Partners</span>
                <div class="stat-icon" style="background:#ede9fe;">
                    <svg style="width:20px;height:20px;color:#7c3aed;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                    </svg>
                </div>
            </div>
            <p class="stat-value">{{ $totalCPs }}</p>
            <p style="font-size:.7rem; color:#9ca3af; margin-top:.25rem;">active partners</p>
        </div>

        {{-- Products --}}
        <div class="stat-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.75rem;">
                <span class="stat-label">Products</span>
                <div class="stat-icon" style="background:#e0e7ff;">
                    <svg style="width:20px;height:20px;color:#4f46e5;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                    </svg>
                </div>
            </div>
            <p class="stat-value">{{ $totalProducts }}</p>
            <p style="font-size:.7rem; color:#9ca3af; margin-top:.25rem;">in catalogue</p>
        </div>
    </div>

    {{-- Alerts Row --}}
    @if($pendingOrders > 0 || $pendingCpOrders > 0 || $cpInterestCount > 0)
    <div style="display:flex; gap:.75rem; margin-bottom:1.5rem; flex-wrap:wrap;">
        @if($pendingOrders > 0)
        <a href="{{ route('customerOrders') }}" style="flex:1; min-width:200px; display:flex; align-items:center; gap:.75rem; padding:.75rem 1rem; background:#fef3c7; border:1px solid #fde68a; border-radius:10px; text-decoration:none; color:#92400e; transition:transform .15s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='none'">
            <svg style="width:20px;height:20px;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
            <span style="font-size:.82rem; font-weight:600;">{{ $pendingOrders }} pending customer order{{ $pendingOrders > 1 ? 's' : '' }}</span>
        </a>
        @endif
        @if($pendingCpOrders > 0)
        <a href="{{ route('pendingOrders') }}" style="flex:1; min-width:200px; display:flex; align-items:center; gap:.75rem; padding:.75rem 1rem; background:#ede9fe; border:1px solid #c4b5fd; border-radius:10px; text-decoration:none; color:#5b21b6; transition:transform .15s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='none'">
            <svg style="width:20px;height:20px;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
            <span style="font-size:.82rem; font-weight:600;">{{ $pendingCpOrders }} pending CP order{{ $pendingCpOrders > 1 ? 's' : '' }}</span>
        </a>
        @endif
        @if($cpInterestCount > 0)
        <a href="{{ route('cpInterestList') }}" style="flex:1; min-width:200px; display:flex; align-items:center; gap:.75rem; padding:.75rem 1rem; background:#dbeafe; border:1px solid #93c5fd; border-radius:10px; text-decoration:none; color:#1e40af; transition:transform .15s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='none'">
            <svg style="width:20px;height:20px;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
            <span style="font-size:.82rem; font-weight:600;">{{ $cpInterestCount }} new CP application{{ $cpInterestCount > 1 ? 's' : '' }}</span>
        </a>
        @endif
    </div>
    @endif

    {{-- Charts Row --}}
    <div class="dash-grid dash-grid-2" style="margin-bottom:1.5rem;">
        {{-- Monthly Revenue Chart --}}
        <div class="dash-panel">
            <h2>Monthly Revenue (Last 6 Months)</h2>
            <canvas id="revenueChart" height="100"></canvas>
        </div>

        {{-- Order Status Donut --}}
        <div class="dash-panel">
            <h2>Orders by Status</h2>
            <div style="max-width:200px; margin:0 auto;">
                <canvas id="statusChart"></canvas>
            </div>
            <div style="margin-top:1rem;">
                @php
                    $statusDots = ['pending'=>'#fbbf24','confirmed'=>'#60a5fa','shipped'=>'#a78bfa','delivered'=>'#34d399','cancelled'=>'#f87171'];
                @endphp
                @foreach($statusDots as $st => $dotColor)
                <div class="legend-item">
                    <div style="display:flex; align-items:center;">
                        <span class="legend-dot" style="background:{{ $dotColor }};"></span>
                        <span style="color:#6b7280; text-transform:capitalize;">{{ $st }}</span>
                    </div>
                    <span style="font-weight:700; color:#1f2937;">{{ $orderStatusCounts[$st] ?? 0 }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Bottom Row --}}
    <div class="dash-grid dash-grid-3" style="margin-bottom:1.5rem;">
        {{-- Top Selling Products --}}
        <div class="dash-panel">
            <h2>
                Top Selling Products
                <a href="{{ route('manageProducts') }}">View all</a>
            </h2>
            @if($topProducts->count())
                @foreach($topProducts as $i => $p)
                <div class="product-row">
                    <span class="product-rank">{{ $i+1 }}</span>
                    <div style="flex:1; min-width:0;">
                        <p style="font-size:.82rem; font-weight:600; color:#1f2937; margin:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $p->product_name }}</p>
                        <div class="product-bar">
                            @php $maxQty = $topProducts->max('total_qty'); @endphp
                            <div class="product-bar-fill" style="width: {{ $maxQty > 0 ? round($p->total_qty/$maxQty*100) : 0 }}%;"></div>
                        </div>
                    </div>
                    <div style="text-align:right; flex-shrink:0;">
                        <p style="font-size:.75rem; font-weight:700; color:#1f2937; margin:0;">{{ $p->total_qty }} units</p>
                        <p style="font-size:.7rem; color:#9ca3af; margin:0;">&#8377;{{ number_format($p->total_revenue, 0) }}</p>
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5"/>
                    </svg>
                    <p>No sales data yet</p>
                </div>
            @endif
        </div>

        {{-- Recent Customer Orders --}}
        <div class="dash-panel">
            <h2>
                Recent Customer Orders
                <a href="{{ route('customerOrders') }}">View all</a>
            </h2>
            @if($recentOrders->count())
                @foreach($recentOrders as $order)
                @php
                    $statusClass = 'status-' . $order->status;
                @endphp
                <div class="order-row">
                    <div class="order-avatar" style="background:#e0e7ff; color:#4f46e5;">
                        {{ strtoupper(substr($order->name, 0, 1)) }}
                    </div>
                    <div style="flex:1; min-width:0;">
                        <p class="order-name" style="margin:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $order->name }}</p>
                        <p class="order-id" style="margin:0;">{{ $order->order_number }}</p>
                    </div>
                    <div style="text-align:right; flex-shrink:0;">
                        <p class="order-amount" style="margin:0;">&#8377;{{ number_format($order->total_amount, 0) }}</p>
                        <span class="status-badge {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                    </div>
                    <a href="{{ route('viewCustomerOrder', $order->id) }}" style="color:#d1d5db; text-decoration:none; flex-shrink:0;">
                        <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                        </svg>
                    </a>
                </div>
                @endforeach
            @else
                <div class="empty-state">
                    <p>No customer orders yet</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Recent CP Orders --}}
    <div class="dash-grid" style="margin-bottom:1.5rem;">
        <div class="dash-panel">
            <h2>
                Recent CP Orders
                <a href="{{ route('pendingOrders') }}">View all</a>
            </h2>
            @if($recentCpOrders->count())
                @foreach($recentCpOrders as $cpOrder)
                @php
                    $cpStatusClass = 'status-' . $cpOrder->status;
                @endphp
                <div class="order-row">
                    <div class="order-avatar" style="background:#ede9fe; color:#7c3aed;">
                        {{ strtoupper(substr($cpOrder->channelPartner->cp_name ?? 'C', 0, 1)) }}
                    </div>
                    <div style="flex:1; min-width:0;">
                        <p class="order-name" style="margin:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $cpOrder->channelPartner->cp_name ?? 'N/A' }}</p>
                        <p class="order-id" style="margin:0;">{{ $cpOrder->order_id }}</p>
                    </div>
                    <div style="text-align:right; flex-shrink:0;">
                        <span class="status-badge {{ $cpStatusClass }}">{{ $cpOrder->status == 'completed' ? 'Approved' : ucfirst($cpOrder->status) }}</span>
                    </div>
                    <a href="{{ route('viewSingleOrder', $cpOrder->id) }}" style="color:#d1d5db; text-decoration:none; flex-shrink:0;">
                        <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                        </svg>
                    </a>
                </div>
                @endforeach
            @else
                <div class="empty-state">
                    <p>No CP orders yet</p>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

@section('js')
<script src="/assets/js/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: @json($monthlyRevenue->pluck('label')),
            datasets: [{
                label: 'Revenue',
                data: @json($monthlyRevenue->pluck('revenue')),
                backgroundColor: 'rgba(99, 102, 241, 0.12)',
                borderColor: 'rgba(99, 102, 241, 0.9)',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false },
                    ticks: {
                        font: { size: 11 },
                        callback: function(v) { return '₹' + (v >= 1000 ? (v/1000).toFixed(0)+'k' : v); }
                    }
                },
                x: {
                    ticks: { font: { size: 11 } },
                    grid: { display: false }
                }
            }
        }
    });

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pending','Confirmed','Shipped','Delivered','Cancelled'],
            datasets: [{
                data: [
                    {{ $orderStatusCounts['pending'] ?? 0 }},
                    {{ $orderStatusCounts['confirmed'] ?? 0 }},
                    {{ $orderStatusCounts['shipped'] ?? 0 }},
                    {{ $orderStatusCounts['delivered'] ?? 0 }},
                    {{ $orderStatusCounts['cancelled'] ?? 0 }}
                ],
                backgroundColor: ['#fbbf24','#60a5fa','#a78bfa','#34d399','#f87171'],
                borderWidth: 3,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            cutout: '72%',
            plugins: { legend: { display: false } }
        }
    });
</script>
@endsection
