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
        --orange2: #fb923c;
    }
    body { background: var(--bg); }

    .cart-wrap { max-width: 1080px; margin: 0 auto; padding: 0 20px; }

    /* ── hero ── */
    .cart-hero {
        padding: 36px 0 28px;
        border-bottom: 1px solid var(--border);
    }
    .cart-hero h1 {
        color: #fff; font-size: 1.6rem; font-weight: 900; margin: 0 0 4px;
        display: flex; align-items: center; gap: 10px;
    }
    .cart-hero p { color: var(--muted); font-size: 0.85rem; margin: 0; }
    .cart-badge {
        background: rgba(249,115,22,0.15); color: var(--orange);
        font-size: 0.72rem; font-weight: 800; padding: 3px 10px;
        border-radius: 20px; border: 1px solid rgba(249,115,22,0.25);
    }

    /* ── layout ── */
    .cart-body { display: flex; gap: 24px; padding: 28px 0 64px; align-items: flex-start; }
    .cart-items { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 14px; }
    .cart-summary { width: 320px; flex-shrink: 0; position: sticky; top: 88px; }

    /* ── item card ── */
    .cart-item {
        background: var(--card); border: 1px solid var(--border);
        border-radius: 14px; padding: 16px; display: flex; gap: 16px;
        align-items: center; transition: border-color 0.2s;
    }
    .cart-item:hover { border-color: rgba(249,115,22,0.2); }
    .cart-item-img {
        width: 88px; height: 88px; border-radius: 10px;
        overflow: hidden; flex-shrink: 0; background: var(--card2);
    }
    .cart-item-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .cart-item-img-empty {
        width: 100%; height: 100%; display: flex;
        align-items: center; justify-content: center;
    }
    .cart-item-info { flex: 1; min-width: 0; }
    .cart-item-name {
        color: var(--text); font-weight: 700; font-size: 0.92rem;
        margin: 0 0 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .cart-item-cat { color: var(--muted); font-size: 0.75rem; margin: 0 0 8px; }
    .cart-item-price { color: var(--orange); font-weight: 900; font-size: 1rem; margin: 0; }

    /* ── qty controls ── */
    .qty-wrap { display: flex; align-items: center; gap: 0; }
    .qty-btn {
        width: 30px; height: 30px; background: var(--card2);
        border: 1px solid var(--border); color: #fff; cursor: pointer;
        font-size: 1rem; display: flex; align-items: center; justify-content: center;
        transition: background 0.15s;
    }
    .qty-btn:first-child { border-radius: 8px 0 0 8px; }
    .qty-btn:last-child  { border-radius: 0 8px 8px 0; }
    .qty-btn:hover { background: rgba(249,115,22,0.15); border-color: rgba(249,115,22,0.3); }
    .qty-input {
        width: 40px; height: 30px; text-align: center;
        background: var(--card2); border: 1px solid var(--border);
        border-left: none; border-right: none;
        color: #fff; font-size: 0.85rem; font-weight: 700;
        outline: none; -moz-appearance: textfield;
    }
    .qty-input::-webkit-inner-spin-button { -webkit-appearance: none; }
    .qty-update {
        background: rgba(249,115,22,0.12); border: 1px solid rgba(249,115,22,0.25);
        color: var(--orange); font-size: 0.72rem; font-weight: 700;
        padding: 5px 10px; border-radius: 6px; cursor: pointer;
        margin-left: 8px; transition: background 0.15s;
    }
    .qty-update:hover { background: rgba(249,115,22,0.25); }

    /* ── actions column ── */
    .cart-item-actions { display: flex; flex-direction: column; align-items: flex-end; gap: 8px; flex-shrink: 0; }
    .cart-item-subtotal { color: var(--muted); font-size: 0.72rem; }
    .cart-item-subtotal-val { color: #fff; font-weight: 800; font-size: 1rem; }
    .cart-checkout-btn {
        background: var(--orange); color: #fff; font-weight: 700;
        padding: 7px 16px; border-radius: 8px; text-decoration: none;
        font-size: 0.78rem; white-space: nowrap; transition: background 0.2s;
        display: inline-flex; align-items: center; gap: 5px;
    }
    .cart-checkout-btn:hover { background: #ea580c; }
    .cart-remove-btn {
        background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2);
        color: #f87171; width: 30px; height: 30px; border-radius: 8px;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        transition: background 0.15s;
    }
    .cart-remove-btn:hover { background: rgba(239,68,68,0.18); }

    /* ── summary card ── */
    .summary-card {
        background: var(--card); border: 1px solid var(--border);
        border-radius: 16px; padding: 20px; overflow: hidden;
    }
    .summary-card h3 {
        color: #fff; font-weight: 800; font-size: 1rem; margin: 0 0 16px;
        display: flex; align-items: center; gap: 8px;
    }
    .summary-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px 0; border-bottom: 1px solid var(--border);
    }
    .summary-row:last-of-type { border-bottom: none; padding-top: 14px; }
    .summary-label { color: var(--muted); font-size: 0.85rem; }
    .summary-value { color: var(--text); font-weight: 600; font-size: 0.85rem; }
    .summary-total { color: var(--orange); font-weight: 900; font-size: 1.2rem; }
    .summary-total-label { color: #fff; font-weight: 700; font-size: 0.95rem; }
    .summary-cta {
        display: block; width: 100%; text-align: center; margin-top: 16px;
        background: linear-gradient(135deg, #f97316, #ea580c); color: #fff;
        font-weight: 800; padding: 13px; border-radius: 12px;
        text-decoration: none; font-size: 0.92rem;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 4px 16px rgba(249,115,22,0.3);
    }
    .summary-cta:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(249,115,22,0.4); }
    .summary-multi-note {
        text-align: center; background: rgba(249,115,22,0.06);
        border: 1px solid rgba(249,115,22,0.15); color: var(--muted);
        font-size: 0.8rem; padding: 12px; border-radius: 10px;
        margin-top: 16px; line-height: 1.5;
    }
    .summary-continue {
        display: block; text-align: center; color: var(--muted);
        font-size: 0.82rem; margin-top: 12px; text-decoration: none;
        transition: color 0.2s;
    }
    .summary-continue:hover { color: var(--orange); }
    .summary-trust {
        display: flex; align-items: center; justify-content: center; gap: 6px;
        margin-top: 16px; padding-top: 14px; border-top: 1px solid var(--border);
        color: var(--muted); font-size: 0.73rem;
    }

    /* ── empty ── */
    .cart-empty {
        text-align: center; padding: 5rem 1rem;
        background: var(--card); border: 1px solid var(--border);
        border-radius: 18px; margin: 40px 0 64px;
    }
    .cart-empty-icon {
        width: 72px; height: 72px; border-radius: 50%; margin: 0 auto 20px;
        background: rgba(249,115,22,0.08);
        display: flex; align-items: center; justify-content: center;
    }
    .cart-empty h2 { color: #fff; font-weight: 700; font-size: 1.15rem; margin: 0 0 6px; }
    .cart-empty p { color: var(--muted); font-size: 0.88rem; margin: 0 0 24px; }
    .cart-empty-btn {
        background: linear-gradient(135deg, #f97316, #ea580c); color: #fff;
        font-weight: 800; padding: 12px 28px; border-radius: 10px;
        text-decoration: none; font-size: 0.9rem; display: inline-block;
        box-shadow: 0 4px 16px rgba(249,115,22,0.3);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .cart-empty-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(249,115,22,0.4); }

    /* ══ RESPONSIVE ══ */
    @media (max-width: 768px) {
        .cart-body { flex-direction: column-reverse; }
        .cart-summary { width: 100%; position: static; }
        .cart-item { flex-wrap: wrap; }
        .cart-item-actions { flex-direction: row; width: 100%; justify-content: space-between; align-items: center; padding-top: 10px; border-top: 1px solid var(--border); margin-top: 4px; }
    }
    @media (max-width: 480px) {
        .cart-hero h1 { font-size: 1.3rem; }
        .cart-item-img { width: 68px; height: 68px; }
        .cart-item-name { font-size: 0.85rem; }
        .cart-item-price { font-size: 0.9rem; }
        .qty-btn { width: 26px; height: 26px; }
        .qty-input { width: 34px; height: 26px; }
    }
</style>
@endsection

@section('content')
<div class="cart-wrap">
    <div class="cart-hero">
        <h1>
            <svg width="22" height="22" fill="none" stroke="var(--orange)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
            Your Cart
            @if(count($products))
            <span class="cart-badge">{{ count($products) }} item{{ count($products) > 1 ? 's' : '' }}</span>
            @endif
        </h1>
        <p>Review your items before checkout</p>
    </div>

    @if(count($products))
    <div class="cart-body">
        <div class="cart-items">
            @php $total = 0; @endphp
            @foreach($products as $item)
            @php
                $subtotal = ($item['product']->current_sale_price ?? 0) * $item['quantity'];
                $total += $subtotal;
            @endphp
            <div class="cart-item">
                <div class="cart-item-img">
                    @if($item['product']->image)
                        <img src="{{ Storage::url($item['product']->image) }}" alt="{{ $item['product']->item_name }}">
                    @else
                        <div class="cart-item-img-empty">
                            <svg width="32" height="32" fill="none" stroke="rgba(249,115,22,0.3)" stroke-width="1.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                        </div>
                    @endif
                </div>

                <div class="cart-item-info">
                    <p class="cart-item-name">{{ $item['product']->item_name }}</p>
                    <p class="cart-item-cat">{{ $item['product']->category->category_name ?? '' }}</p>
                    <p class="cart-item-price">
                        @if($item['product']->current_sale_price)
                            &#8377;{{ number_format($item['product']->current_sale_price, 0) }}
                        @else
                            <span style="color:var(--muted);font-size:0.8rem;font-weight:500;">Price on request</span>
                        @endif
                    </p>
                </div>

                <form action="{{ route('cart.update') }}" method="POST" style="display:flex;align-items:center;">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                    <div class="qty-wrap">
                        <button type="button" class="qty-btn" onclick="var i=this.parentElement.querySelector('.qty-input');if(parseInt(i.value)>1)i.value=parseInt(i.value)-1;">-</button>
                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="qty-input">
                        <button type="button" class="qty-btn" onclick="var i=this.parentElement.querySelector('.qty-input');i.value=parseInt(i.value)+1;">+</button>
                    </div>
                    <button type="submit" class="qty-update">Update</button>
                </form>

                <div class="cart-item-actions">
                    <div>
                        <div class="cart-item-subtotal">Subtotal</div>
                        <div class="cart-item-subtotal-val">
                            @if($item['product']->current_sale_price) &#8377;{{ number_format($subtotal, 0) }} @else &mdash; @endif
                        </div>
                    </div>
                    <form action="{{ route('cart.remove') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                        <button type="submit" class="cart-remove-btn" title="Remove">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div class="cart-summary">
            <div class="summary-card">
                <h3>
                    <svg width="18" height="18" fill="none" stroke="var(--orange)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185zM9.75 9h.008v.008H9.75V9zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.008v.008H9.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                    Order Summary
                </h3>
                <div class="summary-row">
                    <span class="summary-label">Subtotal ({{ count($products) }} items)</span>
                    <span class="summary-value">@if($total > 0) &#8377;{{ number_format($total, 0) }} @else &mdash; @endif</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Shipping</span>
                    <span style="color:#22c55e;font-size:0.85rem;font-weight:600;">Free</span>
                </div>
                <div class="summary-row">
                    <span class="summary-total-label">Total</span>
                    <span class="summary-total">@if($total > 0) &#8377;{{ number_format($total, 0) }} @else &mdash; @endif</span>
                </div>

                <a href="{{ route('order.checkout') }}" class="summary-cta">
                    Proceed to Checkout
                </a>

                <a href="{{ route('shop') }}" class="summary-continue">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:3px;"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                    Continue Shopping
                </a>

                <div class="summary-trust">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                    Secure checkout &middot; Free shipping PAN India
                </div>
            </div>
        </div>
    </div>

    @else
    <div class="cart-empty">
        <div class="cart-empty-icon">
            <svg width="36" height="36" fill="none" stroke="var(--orange)" stroke-width="1.3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
        </div>
        <h2>Your cart is empty</h2>
        <p>Add some solar products to get started</p>
        <a href="{{ route('shop') }}" class="cart-empty-btn">Browse Products</a>
    </div>
    @endif
</div>
@endsection
