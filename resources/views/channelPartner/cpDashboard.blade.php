@extends('layouts.adminLayout')

@section('title', 'CP Dashboard')

@section('css')
<style>
    .cpd-wrap { max-width: 1200px; margin: 0 auto; padding: 1.5rem 1rem; }

    .cpd-header { background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%); padding: 1.5rem; border-radius: 12px; color: #fff; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
    .cpd-header-left h1 { font-size: 1.3rem; font-weight: 800; margin: 0 0 .3rem; display: flex; align-items: center; gap: .5rem; }
    .cpd-header-left p { font-size: .85rem; opacity: .9; margin: 0; }
    .cpd-header-right { display: flex; gap: .5rem; flex-wrap: wrap; }
    .cpd-header-tag { padding: 5px 14px; border-radius: 20px; font-size: .75rem; font-weight: 700; background: rgba(255,255,255,.2); color: #fff; display: inline-flex; align-items: center; gap: 5px; }

    .cpd-profile { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.25rem; margin-bottom: 1.25rem; }
    .cpd-profile h3 { font-size: 1rem; font-weight: 700; color: #1f2937; margin: 0 0 .5rem; }
    .cpd-profile-row { display: flex; gap: 1.5rem; flex-wrap: wrap; }
    .cpd-profile-item { font-size: .82rem; color: #6b7280; display: flex; align-items: center; gap: 5px; }
    .cpd-profile-item svg { color: #2563eb; flex-shrink: 0; }
    .cpd-profile-item span { color: #374151; font-weight: 500; }

    .cpd-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .cpd-stat { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.25rem; position: relative; overflow: hidden; transition: transform .15s, box-shadow .15s; }
    .cpd-stat:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.08); }
    .cpd-stat::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 3px; }
    .cpd-stat.s-blue::before { background: #2563eb; }
    .cpd-stat.s-amber::before { background: #d97706; }
    .cpd-stat.s-green::before { background: #16a34a; }
    .cpd-stat.s-red::before { background: #dc2626; }

    .cpd-stat-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: .75rem; }
    .cpd-stat.s-blue .cpd-stat-icon { background: #dbeafe; color: #1d4ed8; }
    .cpd-stat.s-amber .cpd-stat-icon { background: #fef3c7; color: #b45309; }
    .cpd-stat.s-green .cpd-stat-icon { background: #dcfce7; color: #15803d; }
    .cpd-stat.s-red .cpd-stat-icon { background: #fee2e2; color: #b91c1c; }

    .cpd-stat-number { font-size: 1.75rem; font-weight: 800; color: #1f2937; margin: 0; }
    .cpd-stat-label { font-size: .78rem; color: #6b7280; font-weight: 500; margin: .2rem 0 0; }

    .cpd-sections { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem; }
    @media(max-width: 768px) { .cpd-sections { grid-template-columns: 1fr; } }

    .cpd-section { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.25rem; }
    .cpd-section h3 { font-size: .95rem; font-weight: 700; color: #1f2937; margin: 0 0 1rem; display: flex; align-items: center; gap: .5rem; }
    .cpd-section h3 svg { color: #2563eb; }

    .cpd-list-item { display: flex; align-items: center; gap: .75rem; padding: .65rem .5rem; border-bottom: 1px solid #f3f4f6; transition: background .1s; }
    .cpd-list-item:last-child { border-bottom: none; }
    .cpd-list-item:hover { background: #f9fafb; }

    .cpd-list-icon { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .cpd-list-icon.order { background: #dbeafe; color: #1d4ed8; }
    .cpd-list-icon.approved { background: #dcfce7; color: #15803d; }
    .cpd-list-icon.rejected { background: #fee2e2; color: #b91c1c; }

    .cpd-list-text { flex: 1; }
    .cpd-list-text p { margin: 0; font-size: .82rem; color: #374151; font-weight: 500; }
    .cpd-list-text small { color: #9ca3af; font-size: .72rem; }

    .cpd-status-pill { padding: 3px 10px; border-radius: 12px; font-size: .68rem; font-weight: 700; }
    .cpd-status-pending { background: #fef3c7; color: #92400e; }
    .cpd-status-completed { background: #dcfce7; color: #166534; }
    .cpd-status-cancelled { background: #fee2e2; color: #991b1b; }

    .cpd-empty { text-align: center; padding: 2rem 1rem; color: #9ca3af; font-size: .85rem; }
    .cpd-empty svg { margin: 0 auto .5rem; opacity: .5; }

    .cpd-quick-links { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: .75rem; }
    .cpd-quick-link { display: flex; align-items: center; gap: .5rem; padding: .75rem 1rem; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; text-decoration: none; color: #374151; font-size: .82rem; font-weight: 600; transition: all .15s; }
    .cpd-quick-link:hover { border-color: #2563eb; color: #2563eb; transform: translateY(-1px); box-shadow: 0 2px 8px rgba(0,0,0,.06); }
    .cpd-quick-link svg { color: #2563eb; flex-shrink: 0; }

    .cpd-notif { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: .75rem 1rem; margin-bottom: .5rem; display: flex; align-items: center; gap: .75rem; }
    .cpd-notif-icon { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .cpd-notif-icon.n-green { background: #dcfce7; color: #15803d; }
    .cpd-notif-icon.n-red { background: #fee2e2; color: #b91c1c; }
    .cpd-notif-icon.n-amber { background: #fef3c7; color: #b45309; }
    .cpd-notif-text { flex: 1; font-size: .8rem; color: #1e40af; }
    .cpd-notif-text strong { font-weight: 600; }
    .cpd-notif-time { font-size: .68rem; color: #93c5fd; white-space: nowrap; }
</style>
@endsection

@section('content')
<div class="cpd-wrap">
    {{-- Header --}}
    <div class="cpd-header">
        <div class="cpd-header-left">
            <h1>
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                Partner Dashboard
            </h1>
            <p>Welcome back, {{ $cp->contact_person ?? $cp->cp_name }}!</p>
        </div>
        <div class="cpd-header-right">
            <span class="cpd-header-tag">Partner</span>
            <span class="cpd-header-tag">
                <span style="width:6px;height:6px;border-radius:50%;background:{{ $cp->is_active ? '#4ade80' : '#f87171' }};"></span>
                {{ $cp->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </div>

    {{-- Notifications --}}
    @php
        $recentUpdates = \App\Models\CpOrder::where('cp_id', $cp->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->where('updated_at', '>=', now()->subDays(7))
            ->orderBy('updated_at', 'desc')
            ->take(3)
            ->get();
    @endphp
    @if($recentUpdates->count())
        @foreach($recentUpdates as $upd)
        <div class="cpd-notif">
            <div class="cpd-notif-icon {{ $upd->status === 'completed' ? 'n-green' : 'n-red' }}">
                @if($upd->status === 'completed')
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                @else
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                @endif
            </div>
            <div class="cpd-notif-text">
                Order <strong>#{{ $upd->order_id }}</strong> was <strong>{{ $upd->status === 'completed' ? 'approved' : 'cancelled' }}</strong>
            </div>
            <div class="cpd-notif-time">{{ $upd->updated_at->diffForHumans() }}</div>
        </div>
        @endforeach
    @endif

    {{-- Profile Card --}}
    <div class="cpd-profile">
        <h3>{{ $cp->cp_name }}</h3>
        <div class="cpd-profile-row">
            <div class="cpd-profile-item">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                <span>{{ $cp->contact_person }}</span>
            </div>
            <div class="cpd-profile-item">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                <span>{{ $cp->email }}</span>
            </div>
            <div class="cpd-profile-item">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                <span>{{ $cp->phone_number }}</span>
            </div>
            <div class="cpd-profile-item">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                <span>{{ $cp->city }}, {{ $cp->state }}</span>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="cpd-stats">
        <div class="cpd-stat s-blue">
            <div class="cpd-stat-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
            </div>
            <p class="cpd-stat-number">{{ $totalOrders }}</p>
            <p class="cpd-stat-label">Total Orders</p>
        </div>
        <div class="cpd-stat s-amber">
            <div class="cpd-stat-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="cpd-stat-number">{{ $pendingOrders }}</p>
            <p class="cpd-stat-label">Pending Orders</p>
        </div>
        <div class="cpd-stat s-green">
            <div class="cpd-stat-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="cpd-stat-number">{{ $completedOrders }}</p>
            <p class="cpd-stat-label">Completed Orders</p>
        </div>
        <div class="cpd-stat s-red">
            <div class="cpd-stat-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
            </div>
            <p class="cpd-stat-number">{{ number_format($totalSpending, 0) }}</p>
            <p class="cpd-stat-label">Total Spending (&#8377;)</p>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="cpd-section" style="margin-bottom:1.25rem;">
        <h3>
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
            Recent Orders
        </h3>
        @if($recentOrders->count())
            @foreach($recentOrders as $order)
            <div class="cpd-list-item">
                <div class="cpd-list-icon order">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <div class="cpd-list-text">
                    <p>Order #{{ $order->order_id }}</p>
                    <small>{{ $order->created_at->format('d M Y, h:i A') }}</small>
                </div>
                @if($order->grand_total)
                    <span style="font-size:.82rem;font-weight:700;color:#1f2937;margin-right:.5rem;">&#8377;{{ number_format($order->grand_total, 0) }}</span>
                @endif
                <span class="cpd-status-pill cpd-status-{{ $order->status ?? 'pending' }}">
                    {{ ucfirst($order->status ?? 'pending') }}
                </span>
            </div>
            @endforeach
        @else
            <div class="cpd-empty">
                <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859m-17.399 0V5.625A2.25 2.25 0 015.625 3.375h12.75a2.25 2.25 0 012.25 2.25v7.875m-17.25 0v4.875a2.25 2.25 0 002.25 2.25h12.75a2.25 2.25 0 002.25-2.25V13.5"/></svg>
                No orders yet
            </div>
        @endif
    </div>

    {{-- Quick Links --}}
    <div class="cpd-quick-links">
        @php $perms = []; try { $perms = auth()->user()->cp_permissions ?? []; if (is_string($perms)) $perms = json_decode($perms, true) ?? []; } catch (\Exception $e) { $perms = []; } @endphp
        @if(in_array('new_request', $perms))
            <a href="{{ route('newOrderCp') }}" class="cpd-quick-link">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                New Order
            </a>
        @endif
        @if(in_array('view_requests', $perms))
            <a href="{{ route('orderReportCp') }}" class="cpd-quick-link">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                My Orders
            </a>
        @endif
        @if(in_array('product_pricing', $perms))
            <a href="{{ route('productPricing') }}" class="cpd-quick-link">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                Product Pricing
            </a>
        @endif
        <a href="{{ url('/shop') }}" class="cpd-quick-link">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 2.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"/></svg>
            Browse Shop
        </a>
        <a href="{{ route('cpProfile') }}" class="cpd-quick-link">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
            My Profile
        </a>
    </div>
</div>
@endsection
