@extends('layouts.public')

@section('css')
<style>
    :root {
        --bg: #0b1117;
        --card: #131929;
        --card2: #1a2236;
        --border: rgba(255,255,255,0.07);
        --text: #e2e8f0;
        --muted: #64748b;
        --orange: #f97316;
    }
    body { background: var(--bg); }

    .od-wrap { max-width: 860px; margin: 0 auto; padding: 0 20px; }

    /* ── back link ── */
    .od-back {
        display: inline-flex; align-items: center; gap: 5px;
        color: var(--muted); font-size: 0.82rem; text-decoration: none;
        transition: color 0.2s; padding: 28px 0 0;
    }
    .od-back:hover { color: var(--orange); }

    /* ── header ── */
    .od-header {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 12px; padding: 16px 0 20px;
        border-bottom: 1px solid var(--border); margin-bottom: 20px;
    }
    .od-header h1 {
        color: #fff; font-size: 1.45rem; font-weight: 900; margin: 0 0 3px;
        display: flex; align-items: center; gap: 10px;
    }
    .od-header-meta { color: var(--muted); font-size: 0.82rem; margin: 0; }
    .od-status-badge {
        display: inline-block; padding: 6px 16px; border-radius: 999px;
        font-size: 0.78rem; font-weight: 700; text-transform: uppercase;
    }

    /* ── alert banners ── */
    .od-banner {
        border-radius: 14px; padding: 14px 18px; margin-bottom: 14px;
        display: flex; align-items: flex-start; gap: 12px;
    }
    .od-banner p { margin: 0; }
    .od-banner-title { font-weight: 700; font-size: 0.88rem; }
    .od-banner-desc { font-size: 0.78rem; color: var(--muted); margin-top: 3px !important; }
    .od-banner-link { font-weight: 700; font-size: 0.82rem; text-decoration: none; display: inline-block; margin-top: 6px !important; }

    /* ── status tracker ── */
    .od-tracker {
        background: var(--card); border: 1px solid var(--border);
        border-radius: 16px; padding: 20px; margin-bottom: 14px;
    }
    .od-tracker h3 {
        color: var(--text); font-weight: 700; font-size: 0.9rem; margin: 0 0 18px;
        display: flex; align-items: center; gap: 8px;
    }
    .od-steps { display: flex; align-items: flex-start; }
    .od-step { flex: 1; text-align: center; }
    .od-step-bar { display: flex; align-items: center; justify-content: center; margin-bottom: 8px; }
    .od-step-line { flex: 1; height: 3px; border-radius: 2px; }
    .od-step-dot {
        width: 28px; height: 28px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; transition: all 0.2s;
    }
    .od-step-label { font-size: 0.75rem; margin: 0; }

    /* ── items card ── */
    .od-card {
        background: var(--card); border: 1px solid var(--border);
        border-radius: 16px; padding: 18px 20px; margin-bottom: 14px;
    }
    .od-card h3 {
        color: var(--text); font-weight: 700; font-size: 0.9rem; margin: 0 0 14px;
        display: flex; align-items: center; gap: 8px;
    }
    .od-item {
        display: flex; align-items: center; gap: 14px;
        padding: 10px 0; border-bottom: 1px solid var(--border);
    }
    .od-item:last-of-type { border-bottom: none; }
    .od-item-img {
        width: 56px; height: 56px; border-radius: 10px; overflow: hidden;
        flex-shrink: 0; background: var(--card2);
    }
    .od-item-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .od-item-img-empty {
        width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
    }
    .od-item-info { flex: 1; min-width: 0; }
    .od-item-name { color: var(--text); font-weight: 600; font-size: 0.88rem; margin: 0 0 3px; }
    .od-item-qty { color: var(--muted); font-size: 0.78rem; margin: 0; }
    .od-item-total { color: var(--orange); font-weight: 800; font-size: 0.95rem; margin: 0; flex-shrink: 0; }
    .od-total-row {
        display: flex; justify-content: space-between; align-items: center;
        padding-top: 14px; margin-top: 4px; border-top: 1px solid var(--border);
    }
    .od-total-label { color: #fff; font-weight: 700; font-size: 0.92rem; }
    .od-total-val { color: var(--orange); font-weight: 900; font-size: 1.15rem; }

    /* ── info grid ── */
    .od-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 40px; }
    .od-info-title { color: var(--text); font-weight: 700; font-size: 0.9rem; margin: 0 0 10px; display: flex; align-items: center; gap: 8px; }
    .od-info-row { margin-bottom: 4px; }
    .od-info-label { color: var(--muted); font-size: 0.82rem; }
    .od-info-val { color: var(--text); font-weight: 600; font-size: 0.82rem; }
    .od-pay-status { font-weight: 700; font-size: 0.82rem; }

    /* ══ RESPONSIVE ══ */
    @media (max-width: 600px) {
        .od-header h1 { font-size: 1.2rem; }
        .od-info-grid { grid-template-columns: 1fr; }
        .od-item { flex-wrap: wrap; }
        .od-item-total { width: 100%; text-align: right; padding-top: 4px; }
        .od-steps { flex-direction: column; gap: 0; }
        .od-step { display: flex; align-items: center; gap: 12px; text-align: left; }
        .od-step-bar { flex-direction: column; margin-bottom: 0; width: 28px; flex-shrink: 0; }
        .od-step-line { width: 3px; height: 24px; flex: none; }
        .od-step-label { font-size: 0.8rem; padding: 8px 0; }
    }
</style>
@endsection

@section('content')
<div class="od-wrap">
    <a href="{{ route('user.orders') }}" class="od-back">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        Back to Orders
    </a>

    <div class="od-header">
        <div>
            <h1>
                <svg width="20" height="20" fill="none" stroke="var(--orange)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
                {{ $order->order_number }}
            </h1>
            <p class="od-header-meta">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
        </div>
        <span class="od-status-badge" style="background:{{ $order->getStatusBadgeColor() }}18;color:{{ $order->getStatusBadgeColor() }};border:1px solid {{ $order->getStatusBadgeColor() }}40;">
            {{ $order->status }}
        </span>
    </div>

    {{-- Payment Banners --}}
    @if($order->payment_status === 'verification_pending')
    <div class="od-banner" style="background:rgba(249,115,22,0.08);border:1px solid rgba(249,115,22,0.25);">
        <svg width="22" height="22" fill="none" stroke="#f97316" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <p class="od-banner-title" style="color:#f97316;">Payment Verification Pending</p>
            <p class="od-banner-desc">Your payment screenshot has been submitted. We will verify and confirm your order shortly.</p>
        </div>
    </div>
    @elseif($order->payment_status === 'pending')
    <div class="od-banner" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);">
        <svg width="22" height="22" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
        <div>
            <p class="od-banner-title" style="color:#ef4444;">Payment Not Submitted</p>
            <p class="od-banner-desc">Please complete your payment to process this order.</p>
            <a href="{{ route('user.order.payment', $order->id) }}" class="od-banner-link" style="color:var(--orange);">Complete Payment &#8594;</a>
        </div>
    </div>
    @elseif($order->payment_status === 'paid')
    <div class="od-banner" style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.25);">
        <svg width="22" height="22" fill="none" stroke="#22c55e" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <p class="od-banner-title" style="color:#22c55e;">Payment Verified & Order Confirmed</p>
        </div>
    </div>
    @elseif($order->payment_status === 'failed')
    <div class="od-banner" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);">
        <svg width="22" height="22" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <p class="od-banner-title" style="color:#ef4444;">Payment Rejected</p>
            <p class="od-banner-desc">Your payment could not be verified. Please try again.</p>
            <a href="{{ route('user.order.payment', $order->id) }}" class="od-banner-link" style="color:var(--orange);">Retry Payment &#8594;</a>
        </div>
    </div>
    @endif

    {{-- Order Status Tracker --}}
    @php
        $steps = ['pending'=>'Order Placed','confirmed'=>'Confirmed','shipped'=>'Shipped','delivered'=>'Delivered'];
        $stepKeys = array_keys($steps);
        $currentIndex = $order->status === 'cancelled' ? -1 : array_search($order->status, $stepKeys);
    @endphp
    @if($order->status !== 'cancelled')
    <div class="od-tracker">
        <h3>
            <svg width="18" height="18" fill="none" stroke="var(--orange)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
            Order Status
        </h3>
        <div class="od-steps">
            @foreach($steps as $key => $label)
            @php
                $i = array_search($key, $stepKeys);
                $isDone = $i < $currentIndex;
                $isCurrent = $i === $currentIndex;
                $active = $isDone || $isCurrent;
            @endphp
            <div class="od-step">
                <div class="od-step-bar">
                    @if(!$loop->first)
                    <div class="od-step-line" style="background:{{ $active ? '#f97316' : '#1e293b' }};"></div>
                    @else
                    <div style="flex:1;"></div>
                    @endif
                    <div class="od-step-dot" style="background:{{ $active ? '#f97316' : 'rgba(249,115,22,0.15)' }};border:2px solid {{ $active ? '#f97316' : '#334155' }};">
                        @if($isDone)
                        <svg width="14" height="14" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        @elseif($isCurrent)
                        <div style="width:8px;height:8px;border-radius:50%;background:#fff;"></div>
                        @endif
                    </div>
                    @if(!$loop->last)
                    <div class="od-step-line" style="background:{{ $isDone ? '#f97316' : '#1e293b' }};"></div>
                    @else
                    <div style="flex:1;"></div>
                    @endif
                </div>
                <p class="od-step-label" style="color:{{ $active ? '#e2e8f0' : '#475569' }};font-weight:{{ $isCurrent ? '700' : '500' }};">{{ $label }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="od-banner" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);">
        <svg width="22" height="22" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
        <div>
            <p class="od-banner-title" style="color:#ef4444;">Order Cancelled</p>
            <p class="od-banner-desc">This order has been cancelled.</p>
        </div>
    </div>
    @endif

    {{-- Items --}}
    <div class="od-card">
        <h3>
            <svg width="16" height="16" fill="none" stroke="var(--orange)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
            Items Ordered
        </h3>
        @foreach($order->items as $item)
        <div class="od-item">
            <div class="od-item-img">
                @if($item->product && $item->product->image)
                    <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product_name }}">
                @else
                    <div class="od-item-img-empty">
                        <svg width="24" height="24" fill="none" stroke="rgba(249,115,22,0.3)" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                    </div>
                @endif
            </div>
            <div class="od-item-info">
                <p class="od-item-name">{{ $item->product_name }}</p>
                <p class="od-item-qty">Qty: {{ $item->quantity }} x &#8377;{{ number_format($item->price, 0) }}</p>
            </div>
            <p class="od-item-total">&#8377;{{ number_format($item->subtotal, 0) }}</p>
        </div>
        @endforeach
        <div class="od-total-row">
            <span class="od-total-label">Total</span>
            <span class="od-total-val">&#8377;{{ number_format($order->total_amount, 0) }}</span>
        </div>
    </div>

    {{-- Delivery & Payment Info --}}
    <div class="od-info-grid">
        <div class="od-card">
            <h3 class="od-info-title">
                <svg width="16" height="16" fill="none" stroke="var(--orange)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                Delivery Address
            </h3>
            <p style="color:var(--text);font-weight:600;font-size:0.85rem;margin:0 0 3px;">{{ $order->name }}</p>
            <p style="color:var(--muted);font-size:0.82rem;margin:0 0 2px;">{{ $order->phone }}</p>
            <p style="color:var(--muted);font-size:0.82rem;margin:0 0 2px;">{{ $order->address }}</p>
            <p style="color:var(--muted);font-size:0.82rem;margin:0;">{{ $order->city }}@if($order->district), {{ $order->district }}@endif, {{ $order->state }} - {{ $order->pincode }}</p>
        </div>

        <div class="od-card">
            <h3 class="od-info-title">
                <svg width="16" height="16" fill="none" stroke="var(--orange)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                Payment
            </h3>
            <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                <span class="od-info-label">Method</span>
                <span class="od-info-val" style="text-transform:uppercase;">Online Transfer</span>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                <span class="od-info-label">Status</span>
                @php
                    $payColors = ['paid'=>'#10b981','verification_pending'=>'#f97316','pending'=>'#f59e0b','failed'=>'#ef4444'];
                    $payLabels = ['paid'=>'Verified','verification_pending'=>'Verification Pending','pending'=>'Not Paid','failed'=>'Rejected'];
                @endphp
                <span class="od-pay-status" style="color:{{ $payColors[$order->payment_status] ?? '#94a3b8' }};">
                    {{ $payLabels[$order->payment_status] ?? ucfirst($order->payment_status) }}
                </span>
            </div>
            @if($order->payment_reference)
            <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                <span class="od-info-label">Ref ID</span>
                <span class="od-info-val">{{ $order->payment_reference }}</span>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
