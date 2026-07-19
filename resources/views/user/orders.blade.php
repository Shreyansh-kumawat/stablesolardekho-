@extends('layouts.public')

@section('css')
<style>
    :root {--bg:#0b1117;--card:#131929;--card2:#1a2236;--border:rgba(255,255,255,0.07);--text:#e2e8f0;--muted:#64748b;--orange:#f97316;}
    .orders-list{display:flex;flex-direction:column;gap:12px;padding:0 0 24px;}
    .order-card{background:var(--card);border:1px solid var(--border);border-radius:14px;padding:18px 20px;display:flex;align-items:center;justify-content:space-between;gap:16px;text-decoration:none;transition:border-color 0.2s,transform 0.15s;}
    .order-card:hover{border-color:rgba(249,115,22,0.25);transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,0.3);}
    .order-card-left{display:flex;align-items:center;gap:14px;min-width:0;}
    .order-icon-wrap{width:44px;height:44px;border-radius:10px;flex-shrink:0;background:rgba(249,115,22,0.08);display:flex;align-items:center;justify-content:center;}
    .order-number{color:var(--text);font-weight:700;font-size:0.92rem;margin:0 0 3px;}
    .order-meta{color:var(--muted);font-size:0.78rem;margin:0;}
    .order-card-right{text-align:right;flex-shrink:0;}
    .order-amount{color:var(--orange);font-weight:900;font-size:1.05rem;margin:0 0 5px;}
    .order-status-badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:0.68rem;font-weight:800;text-transform:uppercase;letter-spacing:0.04em;}
    .order-arrow{color:var(--muted);transition:color 0.2s,transform 0.2s;flex-shrink:0;}
    .order-card:hover .order-arrow{color:var(--orange);transform:translateX(3px);}
    .orders-empty{text-align:center;padding:5rem 1rem;background:var(--card);border:1px solid var(--border);border-radius:18px;}
    .orders-empty-icon{width:72px;height:72px;border-radius:50%;margin:0 auto 20px;background:rgba(249,115,22,0.08);display:flex;align-items:center;justify-content:center;}
    .orders-empty h2{color:#fff;font-weight:700;font-size:1.15rem;margin:0 0 6px;}
    .orders-empty p{color:var(--muted);font-size:0.88rem;margin:0 0 24px;}
    .orders-empty-btn{background:linear-gradient(135deg,#f97316,#ea580c);color:#fff;font-weight:800;padding:12px 28px;border-radius:10px;text-decoration:none;font-size:0.9rem;display:inline-block;}
    @media(max-width:600px){
        .order-card{flex-wrap:wrap;padding:14px 16px;}
        .order-card-right{width:100%;text-align:left;display:flex;align-items:center;justify-content:space-between;padding-top:10px;border-top:1px solid var(--border);}
        .order-arrow{display:none;}
    }
</style>
@endsection

@section('content')
<div class="ud-layout">
    @include('user.partials.sidebar', ['activePage' => 'orders'])
    <div class="ud-main">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px;">
            <div>
                <h1 style="color:#fff;font-size:1.4rem;font-weight:800;margin:0 0 4px;display:flex;align-items:center;gap:10px;">
                    My Orders
                    @if($orders->count())<span style="background:rgba(249,115,22,0.12);color:#f97316;font-size:.72rem;font-weight:800;padding:3px 10px;border-radius:20px;">{{ $orders->count() }}</span>@endif
                </h1>
                <p style="color:#64748b;font-size:.85rem;margin:0;">Track and manage your purchases</p>
            </div>
            <a href="{{ route('shop') }}" style="background:#1a2236;border:1px solid rgba(255,255,255,0.07);color:#e2e8f0;font-size:.82rem;font-weight:600;padding:8px 16px;border-radius:8px;text-decoration:none;">Browse Shop</a>
        </div>

        @if($orders->count())
        <div class="orders-list">
            @foreach($orders as $order)
            <a href="{{ route('user.order.detail', $order->id) }}" class="order-card">
                <div class="order-card-left">
                    <div class="order-icon-wrap">
                        <svg width="20" height="20" fill="none" stroke="var(--orange)" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
                    </div>
                    <div>
                        <p class="order-number">{{ $order->order_number }}</p>
                        <p class="order-meta">{{ $order->items->count() }} item{{ $order->items->count() != 1 ? 's' : '' }} · {{ $order->created_at->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="order-card-right">
                    <p class="order-amount">₹{{ number_format($order->total_amount, 0) }}</p>
                    <span class="order-status-badge" style="background:{{ $order->getStatusBadgeColor() }}18;color:{{ $order->getStatusBadgeColor() }};border:1px solid {{ $order->getStatusBadgeColor() }}40;">{{ $order->status }}</span>
                </div>
                <div class="order-arrow">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="orders-empty">
            <div class="orders-empty-icon">
                <svg width="36" height="36" fill="none" stroke="var(--orange)" stroke-width="1.3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
            </div>
            <h2>No orders yet</h2>
            <p>Your order history will appear here</p>
            <a href="{{ route('shop') }}" class="orders-empty-btn">Start Shopping</a>
        </div>
        @endif
    </div>
</div>
@endsection
