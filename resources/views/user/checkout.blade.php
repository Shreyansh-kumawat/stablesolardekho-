@extends('layouts.public')

@section('css')
<style>
:root{--bg:#0b1117;--card:#131929;--border:rgba(255,255,255,0.07);--text:#e2e8f0;--muted:#64748b;--orange:#f97316;}

.co-page{min-height:80vh;position:relative;overflow:hidden;}
.co-page::before{content:'';position:absolute;top:-100px;left:50%;transform:translateX(-50%);width:600px;height:400px;background:radial-gradient(ellipse,rgba(249,115,22,0.06) 0%,transparent 70%);pointer-events:none;}

.co-wrap{max-width:1020px;margin:0 auto;padding:40px 16px 64px;position:relative;z-index:1;}

.co-header{margin-bottom:1.8rem;}
.co-header h1{color:#fff;font-size:1.6rem;font-weight:900;margin:0 0 4px;letter-spacing:-0.3px;}
.co-header p{color:var(--muted);font-size:0.85rem;margin:0;}

.co-steps{display:flex;align-items:center;gap:0;margin-bottom:2rem;}
.co-step{display:flex;align-items:center;gap:8px;padding:8px 16px;border-radius:10px;position:relative;}
.co-step.active{background:rgba(249,115,22,0.08);}
.co-step-num{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:800;flex-shrink:0;border:2px solid rgba(255,255,255,0.1);color:var(--muted);background:transparent;}
.co-step.active .co-step-num{border-color:var(--orange);color:#fff;background:var(--orange);box-shadow:0 2px 10px rgba(249,115,22,0.3);}
.co-step.done .co-step-num{border-color:#22c55e;color:#fff;background:#22c55e;}
.co-step-text{font-size:0.8rem;color:var(--muted);font-weight:600;}
.co-step.active .co-step-text{color:var(--text);}
.co-step-line{width:40px;height:2px;background:rgba(255,255,255,0.08);flex-shrink:0;}

.co-layout{display:flex;gap:1.8rem;align-items:flex-start;}
.co-form-col{flex:1;min-width:0;}
.co-summary-col{width:340px;flex-shrink:0;}

.co-card{background:var(--card);border:1px solid var(--border);border-radius:16px;margin-bottom:1rem;overflow:hidden;transition:border-color 0.3s;}
.co-card:hover{border-color:rgba(255,255,255,0.12);}
.co-card-header{padding:1.1rem 1.4rem;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:12px;background:rgba(255,255,255,0.015);}
.co-card-icon{width:38px;height:38px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.co-card-header h3{color:var(--text);font-weight:700;font-size:0.92rem;margin:0;}
.co-card-body{padding:1.3rem 1.4rem;}

.co-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}
.co-full{grid-column:1/-1;}

.co-field{position:relative;}
.co-label{display:block;color:#94a3b8;font-size:0.75rem;font-weight:700;margin-bottom:6px;letter-spacing:0.05em;text-transform:uppercase;}
.co-input-wrap{position:relative;}
.co-input-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#475569;pointer-events:none;display:flex;align-items:center;}
textarea ~ .co-input-icon,.co-input-icon.top{top:14px;transform:none;}
.co-input{width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);border-radius:10px;padding:11px 14px 11px 40px;color:var(--text);font-size:0.88rem;box-sizing:border-box;outline:none;transition:all 0.2s;}
.co-input.no-icon{padding-left:14px;}
.co-input:focus{border-color:var(--orange);box-shadow:0 0 0 3px rgba(249,115,22,0.08);background:rgba(255,255,255,0.06);}
.co-input::placeholder{color:#3e4a5c;}
textarea.co-input{resize:vertical;min-height:56px;padding-top:11px;}

.co-btn{width:100%;background:linear-gradient(135deg,#f97316,#ea580c);color:#fff;font-weight:800;padding:15px;border-radius:14px;border:none;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;gap:8px;transition:all 0.2s;position:relative;overflow:hidden;margin-top:0.4rem;}
.co-btn::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,transparent 40%,rgba(255,255,255,0.15) 50%,transparent 60%);transform:translateX(-100%);transition:transform 0.5s;}
.co-btn:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(249,115,22,0.35);}
.co-btn:hover::before{transform:translateX(100%);}
.co-btn:active{transform:translateY(0);}

.co-secure{display:flex;align-items:center;gap:6px;justify-content:center;margin-top:14px;}
.co-secure span{color:#475569;font-size:0.75rem;font-weight:500;}

.co-pay-info{display:flex;align-items:flex-start;gap:10px;background:rgba(249,115,22,0.05);border:1px solid rgba(249,115,22,0.12);border-radius:12px;padding:14px 16px;}
.co-pay-info p{color:#d4a574;font-size:0.82rem;margin:0;line-height:1.5;}
.co-pay-info svg{flex-shrink:0;margin-top:1px;}

/* Summary card */
.co-sum{position:sticky;top:90px;background:var(--card);border:1px solid var(--border);border-radius:16px;overflow:hidden;}
.co-sum-header{padding:1.1rem 1.4rem;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px;background:rgba(255,255,255,0.015);}
.co-sum-header h3{color:var(--text);font-weight:700;font-size:0.92rem;margin:0;}
.co-sum-body{padding:1.3rem 1.4rem;}

.co-sum-item{display:flex;gap:12px;align-items:center;padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.04);}
.co-sum-item:last-child{border-bottom:none;}
.co-sum-item-img{width:52px;height:52px;border-radius:8px;overflow:hidden;flex-shrink:0;background:rgba(249,115,22,0.04);border:1px solid var(--border);}
.co-sum-item-img img{width:100%;height:100%;object-fit:cover;display:block;}
.co-sum-item-info{flex:1;min-width:0;}
.co-sum-item-name{color:#fff;font-weight:700;font-size:0.82rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin:0 0 2px;}
.co-sum-item-meta{color:var(--muted);font-size:0.72rem;}
.co-sum-item-price{color:var(--orange);font-weight:800;font-size:0.85rem;flex-shrink:0;}

.co-sum-row{display:flex;justify-content:space-between;align-items:center;padding:9px 0;}
.co-sum-row+.co-sum-row{border-top:1px solid rgba(255,255,255,0.04);}
.co-sum-label{color:#94a3b8;font-size:0.82rem;}
.co-sum-val{color:var(--text);font-weight:600;font-size:0.85rem;}

.co-sum-total{display:flex;justify-content:space-between;align-items:center;padding:14px 0 0;margin-top:8px;border-top:2px solid rgba(249,115,22,0.2);}
.co-sum-total span:first-child{color:#fff;font-weight:800;font-size:0.95rem;}
.co-sum-total span:last-child{color:var(--orange);font-weight:900;font-size:1.3rem;letter-spacing:-0.5px;}

.co-sum-footer{padding:1rem 1.4rem;background:rgba(255,255,255,0.02);border-top:1px solid var(--border);}
.co-sum-trust{display:flex;align-items:center;gap:8px;}
.co-sum-trust svg{flex-shrink:0;}
.co-sum-trust p{color:var(--muted);font-size:0.75rem;margin:0;line-height:1.4;}

/* Dropdown */
.cdd-wrap{position:relative;width:100%;}
.cdd-trigger{display:flex;align-items:center;justify-content:space-between;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);border-radius:10px;padding:11px 14px;color:#94a3b8;font-size:0.88rem;cursor:pointer;user-select:none;transition:all 0.2s;}
.cdd-trigger:hover{border-color:rgba(255,255,255,0.2);}
.cdd-wrap.open .cdd-trigger{border-color:var(--orange);box-shadow:0 0 0 3px rgba(249,115,22,0.08);background:rgba(255,255,255,0.06);border-bottom-left-radius:0;border-bottom-right-radius:0;}
.cdd-trigger .cdd-label{flex:1;}
.cdd-trigger .cdd-label.selected{color:var(--text);}
.cdd-arrow{color:#475569;flex-shrink:0;transition:transform 0.2s;}
.cdd-wrap.open .cdd-arrow{transform:rotate(180deg);color:var(--orange);}
.cdd-list{display:none;position:absolute;top:100%;left:0;right:0;z-index:999;background:#151d2e;border:1px solid var(--orange);border-top:none;border-radius:0 0 10px 10px;overflow:hidden;box-shadow:0 12px 32px rgba(0,0,0,0.5);}
.cdd-wrap.open .cdd-list{display:block;}
.cdd-search-wrap{padding:8px;border-bottom:1px solid rgba(255,255,255,0.06);}
.cdd-search{width:100%;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:7px;padding:8px 10px;color:var(--text);font-size:0.82rem;box-sizing:border-box;outline:none;}
.cdd-search:focus{border-color:var(--orange);}
.cdd-search::placeholder{color:#475569;}
.cdd-options{max-height:200px;overflow-y:auto;overscroll-behavior:contain;}
.cdd-options::-webkit-scrollbar{width:4px;}
.cdd-options::-webkit-scrollbar-track{background:transparent;}
.cdd-options::-webkit-scrollbar-thumb{background:var(--orange);border-radius:4px;}
.cdd-opt{padding:9px 14px;color:#cbd5e1;font-size:0.84rem;cursor:pointer;transition:all 0.15s;}
.cdd-opt:hover{background:rgba(249,115,22,0.1);color:var(--orange);}
.cdd-opt.active{background:rgba(249,115,22,0.15);color:var(--orange);font-weight:600;}
.cdd-divider{padding:6px 14px;color:#475569;font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;border-top:1px solid rgba(255,255,255,0.05);border-bottom:1px solid rgba(255,255,255,0.05);background:rgba(255,255,255,0.02);}

@media(max-width:750px){
    .co-layout{flex-direction:column-reverse;}
    .co-summary-col{width:100%;}
    .co-sum{position:static;}
    .co-grid{grid-template-columns:1fr;}
    .co-full{grid-column:1/-1;}
    .co-steps{flex-wrap:wrap;gap:4px;}
    .co-step-line{width:20px;}
    .co-step{padding:6px 10px;}
}
</style>
@endsection

@section('content')
@php
    $total = 0;
    $totalQty = 0;
    foreach ($items as $item) {
        $total += ($item['product']->current_sale_price ?? 0) * $item['quantity'];
        $totalQty += $item['quantity'];
    }
@endphp
<div class="co-page">
<div class="co-wrap">

    <div class="co-header">
        <h1>Checkout</h1>
        <p>You're almost there! Complete your details to place the order.</p>
    </div>

    {{-- Steps --}}
    <div class="co-steps">
        <div class="co-step done">
            <div class="co-step-num">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
            </div>
            <span class="co-step-text">Cart</span>
        </div>
        <div class="co-step-line"></div>
        <div class="co-step active">
            <div class="co-step-num">2</div>
            <span class="co-step-text">Details</span>
        </div>
        <div class="co-step-line"></div>
        <div class="co-step">
            <div class="co-step-num">3</div>
            <span class="co-step-text">Payment</span>
        </div>
        <div class="co-step-line"></div>
        <div class="co-step">
            <div class="co-step-num">4</div>
            <span class="co-step-text">Done</span>
        </div>
    </div>

    <div class="co-layout">

        {{-- Form --}}
        <div class="co-form-col">
            <form action="{{ route('order.place') }}" method="POST" id="checkout-form">
                @csrf
                <input type="hidden" name="mode" value="{{ $mode }}">
                @if($mode === 'buy_now')
                <input type="hidden" name="product_id" value="{{ $items[0]['product']->id }}">
                <input type="hidden" name="qty" value="{{ $items[0]['quantity'] }}">
                @endif
                <input type="hidden" name="payment_method" value="online">

                {{-- Contact --}}
                <div class="co-card">
                    <div class="co-card-header">
                        <div class="co-card-icon" style="background:linear-gradient(135deg,rgba(249,115,22,0.15),rgba(249,115,22,0.05));">
                            <svg width="20" height="20" fill="none" stroke="#f97316" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        </div>
                        <h3>Contact Details</h3>
                    </div>
                    <div class="co-card-body">
                        <div class="co-grid">
                            <div class="co-field">
                                <label class="co-label">Full Name *</label>
                                <div class="co-input-wrap">
                                    <span class="co-input-icon"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg></span>
                                    <input type="text" name="name" value="{{ Auth::user()->name }}" required class="co-input">
                                </div>
                            </div>
                            <div class="co-field">
                                <label class="co-label">Phone *</label>
                                <div class="co-input-wrap">
                                    <span class="co-input-icon"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg></span>
                                    <input type="text" name="phone" value="{{ Auth::user()->mobile_number }}" required class="co-input">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Delivery Address --}}
                <div class="co-card">
                    <div class="co-card-header">
                        <div class="co-card-icon" style="background:linear-gradient(135deg,rgba(34,211,238,0.15),rgba(34,211,238,0.05));">
                            <svg width="20" height="20" fill="none" stroke="#22d3ee" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                        </div>
                        <h3>Delivery Address</h3>
                    </div>
                    <div class="co-card-body">
                        <div class="co-grid">
                            <div class="co-field co-full">
                                <label class="co-label">Address *</label>
                                <div class="co-input-wrap">
                                    <span class="co-input-icon top"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg></span>
                                    <textarea name="address" required rows="2" placeholder="House no., street, locality" class="co-input">{{ Auth::user()->address }}</textarea>
                                </div>
                            </div>
                            <div class="co-field">
                                <label class="co-label">State *</label>
                                <input type="hidden" name="state" id="stateHidden" value="{{ Auth::user()->state }}" required>
                                <div class="cdd-wrap" id="stateDrop">
                                    <div class="cdd-trigger" onclick="toggleDrop('stateDrop')">
                                        <span class="cdd-label{{ Auth::user()->state ? ' selected' : '' }}" id="stateLabel">{{ Auth::user()->state ?: 'Select State' }}</span>
                                        <svg class="cdd-arrow" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                    <div class="cdd-list" id="stateList">
                                        <div class="cdd-search-wrap">
                                            <input class="cdd-search" type="text" placeholder="Search state..." oninput="filterDrop('stateOptions',this.value)">
                                        </div>
                                        <div class="cdd-options" id="stateOptions">
                                            @foreach(['Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Goa','Gujarat','Haryana','Himachal Pradesh','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur','Meghalaya','Mizoram','Nagaland','Odisha','Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana','Tripura','Uttar Pradesh','Uttarakhand','West Bengal'] as $st)
                                            <div class="cdd-opt{{ Auth::user()->state === $st ? ' active' : '' }}" onclick="pickState('{{ $st }}')">{{ $st }}</div>
                                            @endforeach
                                            <div class="cdd-divider">Union Territories</div>
                                            @foreach(['Andaman and Nicobar Islands','Chandigarh','Dadra and Nagar Haveli and Daman and Diu','Delhi','Jammu and Kashmir','Ladakh','Lakshadweep','Puducherry'] as $ut)
                                            <div class="cdd-opt{{ Auth::user()->state === $ut ? ' active' : '' }}" onclick="pickState('{{ $ut }}')">{{ $ut }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="co-field">
                                <label class="co-label">District</label>
                                <div class="co-input-wrap">
                                    <span class="co-input-icon"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z"/></svg></span>
                                    <input type="text" name="district" value="{{ Auth::user()->district }}" placeholder="Enter district" class="co-input">
                                </div>
                            </div>
                            <div class="co-field">
                                <label class="co-label">City *</label>
                                <div class="co-input-wrap">
                                    <span class="co-input-icon"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg></span>
                                    <input type="text" name="city" value="{{ Auth::user()->city }}" required placeholder="Enter city" class="co-input">
                                </div>
                            </div>
                            <div class="co-field">
                                <label class="co-label">Pincode *</label>
                                <div class="co-input-wrap">
                                    <span class="co-input-icon"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 9v.906a2.25 2.25 0 01-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 001.183 1.981l6.478 3.488m8.839 2.51l-4.66-2.51m0 0l-1.023-.55a2.25 2.25 0 00-2.134 0l-1.022.55m0 0l-4.661 2.51m16.5 1.615a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V8.844a2.25 2.25 0 011.183-1.981l7.5-4.039a2.25 2.25 0 012.134 0l7.5 4.039a2.25 2.25 0 011.183 1.98V19.5z"/></svg></span>
                                    <input type="text" name="pincode" value="{{ Auth::user()->pincode }}" required maxlength="6" placeholder="6-digit pincode" class="co-input">
                                </div>
                            </div>
                            <div class="co-field co-full">
                                <label class="co-label">Notes (optional)</label>
                                <div class="co-input-wrap">
                                    <span class="co-input-icon top"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 011.037-.443 48.282 48.282 0 005.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg></span>
                                    <textarea name="notes" rows="2" placeholder="Any delivery instructions..." class="co-input"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment --}}
                <div class="co-card">
                    <div class="co-card-header">
                        <div class="co-card-icon" style="background:linear-gradient(135deg,rgba(34,197,94,0.15),rgba(34,197,94,0.05));">
                            <svg width="20" height="20" fill="none" stroke="#22c55e" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                        </div>
                        <h3>Payment Method</h3>
                    </div>
                    <div class="co-card-body">
                        <div class="co-pay-info">
                            <svg width="20" height="20" fill="none" stroke="#f97316" stroke-width="1.8" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z"/></svg>
                            <p>Bank Transfer (NEFT / RTGS / IMPS) -- After placing the order, you'll see the bank details to complete your payment and upload a screenshot.</p>
                        </div>
                    </div>
                </div>

                <button type="submit" class="co-btn">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                    Place Order & Pay
                </button>

                <div class="co-secure">
                    <svg width="14" height="14" fill="none" stroke="#475569" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                    <span>100% Secure Checkout</span>
                </div>
            </form>
        </div>

        {{-- Summary --}}
        <div class="co-summary-col">
            <div class="co-sum">
                <div class="co-sum-header">
                    <svg width="18" height="18" fill="none" stroke="var(--orange)" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                    <h3>Order Summary ({{ count($items) }} item{{ count($items) > 1 ? 's' : '' }})</h3>
                </div>
                <div class="co-sum-body">
                    @foreach($items as $item)
                    <div class="co-sum-item">
                        <div class="co-sum-item-img">
                            @if($item['product']->image)
                                <img src="{{ Storage::url($item['product']->image) }}" alt="{{ $item['product']->item_name }}">
                            @else
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                    <svg width="20" height="20" fill="none" stroke="rgba(249,115,22,0.25)" stroke-width="1.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="co-sum-item-info">
                            <p class="co-sum-item-name">{{ $item['product']->item_name }}</p>
                            <p class="co-sum-item-meta">Qty: {{ $item['quantity'] }}{{ $item['product']->current_sale_price ? ' x ₹'.number_format($item['product']->current_sale_price, 0) : '' }}</p>
                        </div>
                        @if($item['product']->current_sale_price)
                        <span class="co-sum-item-price">₹{{ number_format($item['product']->current_sale_price * $item['quantity'], 0) }}</span>
                        @endif
                    </div>
                    @endforeach

                    <div style="margin-top:12px;">
                        <div class="co-sum-row">
                            <span class="co-sum-label">Subtotal ({{ $totalQty }} items)</span>
                            <span class="co-sum-val">@if($total > 0) ₹{{ number_format($total, 0) }} @else -- @endif</span>
                        </div>
                        <div class="co-sum-row">
                            <span class="co-sum-label">Shipping</span>
                            <span class="co-sum-val" style="color:#22c55e;font-weight:700;">FREE</span>
                        </div>
                        @if($total > 0)
                        <div class="co-sum-total">
                            <span>Total</span>
                            <span>₹{{ number_format($total, 0) }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="co-sum-footer">
                    <div class="co-sum-trust">
                        <svg width="16" height="16" fill="none" stroke="#22c55e" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                        <p>Free delivery across India. Your address is safe with us.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
@endsection

@section('js')
<script>
function toggleDrop(id){var w=document.getElementById(id);var o=w.classList.contains('open');document.querySelectorAll('.cdd-wrap.open').forEach(function(e){e.classList.remove('open')});if(!o){w.classList.add('open');var s=w.querySelector('.cdd-search');if(s){s.value='';filterDrop(w.querySelector('.cdd-options').id,'');s.focus();}}}
function filterDrop(id,q){var l=q.toLowerCase();document.querySelectorAll('#'+id+' .cdd-opt').forEach(function(e){e.style.display=e.textContent.toLowerCase().includes(l)?'':'none';});}
function pickState(v){document.getElementById('stateHidden').value=v;document.getElementById('stateLabel').textContent=v;document.getElementById('stateLabel').classList.add('selected');document.querySelectorAll('#stateOptions .cdd-opt').forEach(function(e){e.classList.toggle('active',e.textContent.trim()===v);});document.getElementById('stateDrop').classList.remove('open');}
document.addEventListener('click',function(e){if(!e.target.closest('.cdd-wrap'))document.querySelectorAll('.cdd-wrap.open').forEach(function(el){el.classList.remove('open');});});
document.querySelectorAll('.cdd-options').forEach(function(el){el.addEventListener('wheel',function(e){var t=el.scrollTop===0&&e.deltaY<0;var b=(el.scrollTop+el.clientHeight>=el.scrollHeight)&&e.deltaY>0;if(t||b)e.preventDefault();e.stopPropagation();},{passive:false});});
document.getElementById('checkout-form').addEventListener('submit',function(e){if(!document.getElementById('stateHidden').value){e.preventDefault();var t=document.getElementById('stateDrop').querySelector('.cdd-trigger');t.style.borderColor='#ef4444';t.style.boxShadow='0 0 0 3px rgba(239,68,68,0.15)';document.getElementById('stateDrop').scrollIntoView({behavior:'smooth',block:'center'});}});
var ss=document.getElementById('stateHidden').value;if(ss){document.querySelectorAll('#stateOptions .cdd-opt').forEach(function(el){if(el.textContent.trim()===ss)el.classList.add('active');});}
</script>
@endsection
