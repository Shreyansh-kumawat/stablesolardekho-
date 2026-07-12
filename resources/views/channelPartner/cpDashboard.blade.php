@extends('layouts.adminLayout')

@section('title', 'CP Dashboard')

@section('css')
<link href="/assets/css/fa-all.min.css" rel="stylesheet">
<style>
    .cpd-wrap { max-width: 1200px; margin: 0 auto; padding: 1.5rem 1rem; }

    .cpd-header { background: linear-gradient(135deg, #4A90E2 0%, #357abd 100%); padding: 1.5rem; border-radius: 12px; color: #fff; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
    .cpd-header-left h1 { font-size: 1.3rem; font-weight: 800; margin: 0 0 .3rem; }
    .cpd-header-left p { font-size: .85rem; opacity: .9; margin: 0; }
    .cpd-header-right { display: flex; gap: .5rem; flex-wrap: wrap; }
    .cpd-header-tag { padding: 5px 14px; border-radius: 20px; font-size: .75rem; font-weight: 700; background: rgba(255,255,255,.2); color: #fff; display: inline-flex; align-items: center; gap: 5px; }

    .cpd-profile { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.25rem; margin-bottom: 1.25rem; display: flex; gap: 1.5rem; flex-wrap: wrap; }
    .cpd-profile-info { flex: 1; min-width: 200px; }
    .cpd-profile-info h3 { font-size: 1rem; font-weight: 700; color: #1f2937; margin: 0 0 .5rem; }
    .cpd-profile-row { display: flex; gap: 1.5rem; flex-wrap: wrap; }
    .cpd-profile-item { font-size: .82rem; color: #6b7280; }
    .cpd-profile-item i { color: #4A90E2; margin-right: 4px; width: 14px; }
    .cpd-profile-item span { color: #374151; font-weight: 500; }

    .cpd-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .cpd-stat { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.25rem; position: relative; overflow: hidden; transition: transform .15s, box-shadow .15s; }
    .cpd-stat:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.08); }
    .cpd-stat::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 3px; }
    .cpd-stat.orders::before { background: linear-gradient(90deg, #4A90E2, #357abd); }
    .cpd-stat.pending::before { background: linear-gradient(90deg, #f59e0b, #d97706); }
    .cpd-stat.completed::before { background: linear-gradient(90deg, #10b981, #059669); }
    .cpd-stat.spending::before { background: linear-gradient(90deg, #ef4444, #dc2626); }
    .cpd-stat.balance::before { background: linear-gradient(90deg, #8b5cf6, #7c3aed); }
    .cpd-stat.inventory::before { background: linear-gradient(90deg, #06b6d4, #0891b2); }

    .cpd-stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; margin-bottom: .75rem; }
    .cpd-stat.orders .cpd-stat-icon { background: #e3f2fd; color: #1565c0; }
    .cpd-stat.pending .cpd-stat-icon { background: #fff3e0; color: #e65100; }
    .cpd-stat.completed .cpd-stat-icon { background: #d1fae5; color: #065f46; }
    .cpd-stat.spending .cpd-stat-icon { background: #fee2e2; color: #991b1b; }
    .cpd-stat.balance .cpd-stat-icon { background: #ede9fe; color: #6d28d9; }
    .cpd-stat.inventory .cpd-stat-icon { background: #cffafe; color: #0e7490; }

    .cpd-stat-number { font-size: 1.75rem; font-weight: 800; color: #1f2937; margin: 0; }
    .cpd-stat-label { font-size: .78rem; color: #6b7280; font-weight: 500; margin: .2rem 0 0; }

    .cpd-sections { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
    @media(max-width: 768px) { .cpd-sections { grid-template-columns: 1fr; } }

    .cpd-section { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.25rem; }
    .cpd-section h3 { font-size: .95rem; font-weight: 700; color: #1f2937; margin: 0 0 1rem; display: flex; align-items: center; gap: .5rem; }
    .cpd-section h3 i { color: #4A90E2; }

    .cpd-list-item { display: flex; align-items: center; gap: .75rem; padding: .65rem .5rem; border-bottom: 1px solid #f3f4f6; transition: background .1s; }
    .cpd-list-item:last-child { border-bottom: none; }
    .cpd-list-item:hover { background: #f9fafb; }

    .cpd-list-icon { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .85rem; flex-shrink: 0; }
    .cpd-list-icon.order { background: #e3f2fd; color: #1565c0; }
    .cpd-list-icon.credit { background: #d1fae5; color: #065f46; }
    .cpd-list-icon.debit { background: #fee2e2; color: #991b1b; }

    .cpd-list-text { flex: 1; }
    .cpd-list-text p { margin: 0; font-size: .82rem; color: #374151; font-weight: 500; }
    .cpd-list-text small { color: #9ca3af; font-size: .72rem; }

    .cpd-list-amount { font-size: .85rem; font-weight: 700; }
    .cpd-list-amount.credit { color: #065f46; }
    .cpd-list-amount.debit { color: #991b1b; }

    .cpd-empty { text-align: center; padding: 2rem 1rem; color: #9ca3af; font-size: .85rem; }
    .cpd-empty i { font-size: 1.5rem; display: block; margin-bottom: .5rem; opacity: .5; }

    .cpd-quick-links { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: .75rem; margin-top: 1.25rem; }
    .cpd-quick-link { display: flex; align-items: center; gap: .5rem; padding: .75rem 1rem; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; text-decoration: none; color: #374151; font-size: .82rem; font-weight: 600; transition: all .15s; }
    .cpd-quick-link:hover { border-color: #4A90E2; color: #4A90E2; transform: translateY(-1px); box-shadow: 0 2px 8px rgba(0,0,0,.06); }
    .cpd-quick-link i { color: #4A90E2; width: 18px; text-align: center; }
</style>
@endsection

@section('content')
<div class="cpd-wrap">
    {{-- Header --}}
    <div class="cpd-header">
        <div class="cpd-header-left">
            <h1><i class="fas fa-chart-line"></i> Partner Dashboard</h1>
            <p>Welcome back, {{ $cp->contact_person ?? $cp->cp_name }}!</p>
        </div>
        <div class="cpd-header-right">
            <span class="cpd-header-tag"><i class="fas fa-tag"></i> Partner</span>
            <span class="cpd-header-tag"><i class="fas fa-circle" style="font-size:.5rem;color:{{ $cp->is_active ? '#10b981' : '#ef4444' }};"></i> {{ $cp->is_active ? 'Active' : 'Inactive' }}</span>
        </div>
    </div>

    {{-- Profile Card --}}
    <div class="cpd-profile">
        <div class="cpd-profile-info">
            <h3>{{ $cp->cp_name }}</h3>
            <div class="cpd-profile-row">
                <div class="cpd-profile-item"><i class="fas fa-user"></i> <span>{{ $cp->contact_person }}</span></div>
                <div class="cpd-profile-item"><i class="fas fa-envelope"></i> <span>{{ $cp->email }}</span></div>
                <div class="cpd-profile-item"><i class="fas fa-phone"></i> <span>{{ $cp->phone_number }}</span></div>
                <div class="cpd-profile-item"><i class="fas fa-map-marker-alt"></i> <span>{{ $cp->city }}, {{ $cp->state }}</span></div>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="cpd-stats">
        <div class="cpd-stat orders">
            <div class="cpd-stat-icon"><i class="fas fa-shopping-cart"></i></div>
            <p class="cpd-stat-number">{{ $totalOrders }}</p>
            <p class="cpd-stat-label">Total Orders</p>
        </div>
        <div class="cpd-stat pending">
            <div class="cpd-stat-icon"><i class="fas fa-clock"></i></div>
            <p class="cpd-stat-number">{{ $pendingOrders }}</p>
            <p class="cpd-stat-label">Pending Orders</p>
        </div>
        <div class="cpd-stat completed">
            <div class="cpd-stat-icon"><i class="fas fa-check-circle"></i></div>
            <p class="cpd-stat-number">{{ $completedOrders }}</p>
            <p class="cpd-stat-label">Completed Orders</p>
        </div>
        <div class="cpd-stat spending">
            <div class="cpd-stat-icon"><i class="fas fa-rupee-sign"></i></div>
            <p class="cpd-stat-number">{{ number_format($totalSpending, 0) }}</p>
            <p class="cpd-stat-label">Total Spending</p>
        </div>
        <div class="cpd-stat balance">
            <div class="cpd-stat-icon"><i class="fas fa-wallet"></i></div>
            <p class="cpd-stat-number">@php try { echo $cp->wallet ? number_format($cp->wallet->balance, 2) : '0.00'; } catch (\Exception $e) { echo '0.00'; } @endphp</p>
            <p class="cpd-stat-label">Wallet Balance</p>
        </div>
        <div class="cpd-stat inventory">
            <div class="cpd-stat-icon"><i class="fas fa-boxes"></i></div>
            <p class="cpd-stat-number">{{ $inventoryCount }}</p>
            <p class="cpd-stat-label">Inventory Items</p>
        </div>
    </div>

    {{-- Recent Orders & Transactions --}}
    <div class="cpd-sections">
        <div class="cpd-section">
            <h3><i class="fas fa-shopping-bag"></i> Recent Orders</h3>
            @if($recentOrders->count())
                @foreach($recentOrders as $order)
                <div class="cpd-list-item">
                    <div class="cpd-list-icon order"><i class="fas fa-file-invoice"></i></div>
                    <div class="cpd-list-text">
                        <p>Order #{{ $order->id }}</p>
                        <small>{{ $order->created_at->format('d M Y, h:i A') }}</small>
                    </div>
                    <span style="padding:3px 8px;border-radius:12px;font-size:.7rem;font-weight:700;
                        {{ $order->status === 'completed' ? 'background:#d1fae5;color:#065f46;' : ($order->status === 'pending' ? 'background:#fef3c7;color:#92400e;' : 'background:#e3f2fd;color:#1565c0;') }}">
                        {{ ucfirst($order->status ?? 'pending') }}
                    </span>
                </div>
                @endforeach
            @else
                <div class="cpd-empty"><i class="fas fa-inbox"></i> No orders yet</div>
            @endif
        </div>

        <div class="cpd-section">
            <h3><i class="fas fa-exchange-alt"></i> Recent Transactions</h3>
            @if($recentTransactions->count())
                @foreach($recentTransactions as $txn)
                <div class="cpd-list-item">
                    <div class="cpd-list-icon {{ $txn->type === 'credit' ? 'credit' : 'debit' }}">
                        <i class="fas {{ $txn->type === 'credit' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                    </div>
                    <div class="cpd-list-text">
                        <p>{{ $txn->description ?? ucfirst($txn->type) }}</p>
                        <small>{{ $txn->created_at->format('d M Y, h:i A') }}</small>
                    </div>
                    <span class="cpd-list-amount {{ $txn->type === 'credit' ? 'credit' : 'debit' }}">
                        {{ $txn->type === 'credit' ? '+' : '-' }}{{ number_format($txn->amount, 2) }}
                    </span>
                </div>
                @endforeach
            @else
                <div class="cpd-empty"><i class="fas fa-inbox"></i> No transactions yet</div>
            @endif
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="cpd-quick-links">
        @php $perms = []; try { $perms = auth()->user()->cp_permissions ?? []; if (is_string($perms)) $perms = json_decode($perms, true) ?? []; } catch (\Exception $e) { $perms = []; } @endphp
        @if(!empty($perms))
            @if(in_array('new_request', $perms ?? []))
                <a href="{{ route('newOrderCp') }}" class="cpd-quick-link"><i class="fas fa-plus-circle"></i> New Order</a>
            @endif
            @if(in_array('view_requests', $perms ?? []))
                <a href="{{ route('orderReportCp') }}" class="cpd-quick-link"><i class="fas fa-list"></i> Order Report</a>
            @endif
            @if(in_array('product_pricing', $perms ?? []))
                <a href="{{ route('productPricing') }}" class="cpd-quick-link"><i class="fas fa-tags"></i> Product Pricing</a>
            @endif
            @if(in_array('view_inventory', $perms ?? []))
                <a href="{{ route('cpInventory') }}" class="cpd-quick-link"><i class="fas fa-warehouse"></i> Inventory</a>
            @endif
        @endif
    </div>
</div>
@endsection
