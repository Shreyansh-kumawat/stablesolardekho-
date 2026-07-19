@extends('layouts.public')

@section('css')
<style>
    .cp-container {
        max-width: 820px;
        margin: 32px auto 64px;
        padding: 0 16px;
    }
    .cp-card {
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 14px;
        padding: 28px 24px;
        box-shadow: 0 10px 22px rgba(2,6,23,.25);
        backdrop-filter: blur(10px);
    }
    .cp-header {
        text-align: center;
        margin-bottom: 24px;
    }
    .cp-icon {
        width: 56px; height: 56px;
        background: linear-gradient(135deg, #f97316, #ea580c);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 14px;
    }
    .cp-icon svg { width: 28px; height: 28px; color: #fff; }
    .cp-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: #f1f5f9;
        margin-bottom: 6px;
    }
    .cp-sub {
        color: #94a3b8;
        font-size: .88rem;
        line-height: 1.5;
    }

    /* About Section */
    .cp-about {
        margin-bottom: 28px;
        padding-bottom: 24px;
        border-bottom: 1px solid rgba(255,255,255,0.08);
    }
    .cp-about-title {
        font-size: 1rem;
        font-weight: 700;
        color: #e2e8f0;
        margin-bottom: 10px;
    }
    .cp-about-text {
        color: #94a3b8;
        font-size: .88rem;
        line-height: 1.7;
        margin-bottom: 12px;
    }
    .cp-features {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 16px;
    }
    @media(max-width: 560px) {
        .cp-features { grid-template-columns: 1fr; }
    }
    .cp-feature {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 10px 12px;
        background: rgba(249,115,22,0.06);
        border: 1px solid rgba(249,115,22,0.12);
        border-radius: 10px;
    }
    .cp-feature-icon {
        width: 32px; height: 32px;
        background: rgba(249,115,22,0.15);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .cp-feature-icon svg { width: 16px; height: 16px; color: #f97316; }
    .cp-feature-text { font-size: .82rem; color: #cbd5e1; line-height: 1.4; }
    .cp-feature-text strong { color: #f1f5f9; font-weight: 700; display: block; margin-bottom: 2px; }

    /* Login CTA */
    .cp-login-cta {
        text-align: center;
        padding: 28px 20px;
        background: rgba(249,115,22,0.06);
        border: 1px dashed rgba(249,115,22,0.25);
        border-radius: 12px;
        margin-top: 8px;
    }
    .cp-login-cta-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: #f1f5f9;
        margin-bottom: 8px;
    }
    .cp-login-cta-sub {
        color: #94a3b8;
        font-size: .85rem;
        margin-bottom: 18px;
    }
    .cp-login-btns {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }
    .cp-btn-login {
        padding: 12px 32px;
        border-radius: 10px;
        border: none;
        font-weight: 800;
        font-size: .95rem;
        color: #fff;
        background: linear-gradient(135deg, #f97316, #ea580c);
        box-shadow: 0 8px 24px rgba(249,115,22,.3);
        cursor: pointer;
        transition: transform 0.15s, box-shadow 0.15s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .cp-btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(249,115,22,.4);
        color: #fff;
    }
    .cp-btn-signup {
        padding: 12px 32px;
        border-radius: 10px;
        border: 1.5px solid rgba(255,255,255,0.2);
        font-weight: 700;
        font-size: .95rem;
        color: #e2e8f0;
        background: rgba(255,255,255,0.06);
        cursor: pointer;
        transition: all 0.15s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .cp-btn-signup:hover {
        background: rgba(255,255,255,0.12);
        border-color: rgba(255,255,255,0.3);
        color: #fff;
    }

    /* Form */
    .cp-grid {
        display: grid;
        gap: 14px;
        grid-template-columns: 1fr 1fr;
    }
    @media(max-width: 560px) {
        .cp-grid { grid-template-columns: 1fr; }
        .cp-card { padding: 20px 16px; }
    }
    .cp-full { grid-column: 1 / -1; }
    .cp-label {
        font-size: .78rem;
        color: #94a3b8;
        margin-bottom: 5px;
        display: block;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .cp-input {
        width: 100%;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1.5px solid rgba(255,255,255,0.12);
        background: rgba(255,255,255,0.04);
        color: #e2e8f0;
        font-size: .9rem;
        transition: border-color 0.2s;
    }
    .cp-input:focus {
        outline: none;
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.15);
    }
    .cp-input::placeholder { color: #475569; }
    .cp-textarea {
        resize: vertical;
        min-height: 80px;
    }
    .cp-btn {
        width: 100%;
        padding: 13px 14px;
        border-radius: 10px;
        border: none;
        font-weight: 800;
        font-size: .95rem;
        color: #fff;
        background: linear-gradient(135deg, #f97316, #ea580c);
        box-shadow: 0 8px 24px rgba(249,115,22,.3);
        cursor: pointer;
        transition: transform 0.15s, box-shadow 0.15s;
        margin-top: 6px;
    }
    .cp-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(249,115,22,.4);
    }
    .cp-form-section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #e2e8f0;
        margin-bottom: 14px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(255,255,255,0.08);
    }
    .error-text { color: #f87171; font-size: .78rem; margin-top: 4px; }

    /* Custom dropdown */
    .cp-dd { position: relative; }
    .cp-dd-trigger {
        width: 100%; padding: 10px 14px;
        border-radius: 10px;
        border: 1.5px solid rgba(255,255,255,0.12);
        background: rgba(255,255,255,0.04);
        color: #e2e8f0; font-size: .9rem;
        display: flex; align-items: center; justify-content: space-between;
        cursor: pointer; user-select: none;
        transition: border-color 0.2s;
    }
    .cp-dd-trigger:hover { border-color: rgba(255,255,255,0.25); }
    .cp-dd-trigger.is-open {
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.15);
    }
    .cp-dd-trigger .cp-dd-placeholder { color: #475569; }
    .cp-dd-arrow {
        width: 16px; height: 16px; flex-shrink: 0;
        color: #64748b; transition: transform 0.2s;
    }
    .cp-dd-trigger.is-open .cp-dd-arrow { transform: rotate(180deg); color: #f97316; }
    .cp-dd-panel {
        display: none; position: absolute; left: 0; right: 0; top: 100%;
        margin-top: 4px; z-index: 50;
        background: #1e293b; border: 1.5px solid rgba(249,115,22,0.3);
        border-radius: 10px; overflow: hidden;
        box-shadow: 0 12px 36px rgba(0,0,0,0.5);
        max-height: 220px;
    }
    .cp-dd-panel.is-open { display: block; }
    .cp-dd-search {
        width: 100%; padding: 9px 12px;
        border: none; border-bottom: 1px solid rgba(255,255,255,0.08);
        background: transparent; color: #e2e8f0;
        font-size: .85rem; outline: none;
    }
    .cp-dd-search::placeholder { color: #475569; }
    .cp-dd-list { overflow-y: auto; max-height: 176px; }
    .cp-dd-opt {
        padding: 9px 12px; font-size: .86rem;
        color: #94a3b8; cursor: pointer;
        transition: background 0.12s, color 0.12s;
    }
    .cp-dd-opt:hover { background: rgba(249,115,22,0.1); color: #f97316; }
    .cp-dd-opt.active { background: rgba(249,115,22,0.15); color: #f97316; font-weight: 700; }
    .cp-dd-empty {
        padding: 14px 12px; text-align: center;
        color: #475569; font-size: .84rem;
    }
</style>
@endsection

@section('content')
<div class="cp-container">
    <div class="cp-card">
        {{-- Header --}}
        <div class="cp-header">
            <div class="cp-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                </svg>
            </div>
            <div class="cp-title">Become a Channel Partner</div>
            <div class="cp-sub">Join our network and grow your solar business with Stable Solar Dekho</div>
        </div>

        {{-- About CP Program --}}
        <div class="cp-about">
            <div class="cp-about-title">What is a Channel Partner?</div>
            <div class="cp-about-text">
                Stable Solar Dekho's Channel Partner program is designed for solar dealers, installers, and distributors. As a partner, you get access to exclusive dealer pricing, dedicated inventory management, a business dashboard, and priority support.
            </div>
            <div class="cp-features">
                <div class="cp-feature">
                    <div class="cp-feature-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                    </div>
                    <div class="cp-feature-text">
                        <strong>Exclusive Dealer Pricing</strong>
                        Special wholesale rates on all solar products
                    </div>
                </div>
                <div class="cp-feature">
                    <div class="cp-feature-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                    </div>
                    <div class="cp-feature-text">
                        <strong>Inventory Management</strong>
                        Dedicated stock tracking & transfer system
                    </div>
                </div>
                <div class="cp-feature">
                    <div class="cp-feature-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                    </div>
                    <div class="cp-feature-text">
                        <strong>Business Dashboard</strong>
                        Track orders, wallet & performance
                    </div>
                </div>
                <div class="cp-feature">
                    <div class="cp-feature-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                    </div>
                    <div class="cp-feature-text">
                        <strong>Priority Support</strong>
                        Dedicated account manager & training
                    </div>
                </div>
            </div>
        </div>

        @auth
            @if(isset($cpStatus) && $cpStatus === 'partner')
                <div style="text-align:center;padding:24px 20px;background:rgba(16,185,129,0.1);border:1.5px solid rgba(16,185,129,0.3);border-radius:12px;margin-bottom:20px;">
                    <div style="width:48px;height:48px;background:rgba(16,185,129,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                        <svg width="24" height="24" fill="none" stroke="#10b981" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div style="font-size:1.1rem;font-weight:800;color:#10b981;margin-bottom:6px;">You are a Channel Partner now!</div>
                    <div style="color:#94a3b8;font-size:.85rem;">You are already a Channel Partner. Manage your business from your dashboard.</div>
                    <a href="{{ route('cpDashboard') }}" style="display:inline-flex;align-items:center;gap:6px;margin-top:14px;padding:10px 24px;background:linear-gradient(135deg,#10b981,#059669);color:#fff;border-radius:10px;font-weight:700;font-size:.9rem;text-decoration:none;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                        Go to Dashboard
                    </a>
                </div>
            @elseif(isset($cpStatus) && $cpStatus === 'pending')
                <div style="text-align:center;padding:24px 20px;background:rgba(251,191,36,0.1);border:1.5px solid rgba(251,191,36,0.3);border-radius:12px;margin-bottom:20px;">
                    <div style="width:48px;height:48px;background:rgba(251,191,36,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                        <svg width="24" height="24" fill="none" stroke="#fbbf24" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div style="font-size:1.1rem;font-weight:800;color:#fbbf24;margin-bottom:6px;">Verification in Progress</div>
                    <div style="color:#94a3b8;font-size:.85rem;">Your application has been submitted successfully. Our team will verify it shortly. Please wait for the confirmation.</div>
                </div>
            @elseif(isset($cpStatus) && $cpStatus === 'approved')
                <div style="text-align:center;padding:24px 20px;background:rgba(16,185,129,0.1);border:1.5px solid rgba(16,185,129,0.3);border-radius:12px;margin-bottom:20px;">
                    <div style="width:48px;height:48px;background:rgba(16,185,129,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                        <svg width="24" height="24" fill="none" stroke="#10b981" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div style="font-size:1.1rem;font-weight:800;color:#10b981;margin-bottom:6px;">Application Approved!</div>
                    <div style="color:#94a3b8;font-size:.85rem;">Your application has been approved. You are now a Channel Partner!</div>
                    <a href="{{ route('cpDashboard') }}" style="display:inline-flex;align-items:center;gap:6px;margin-top:14px;padding:10px 24px;background:linear-gradient(135deg,#10b981,#059669);color:#fff;border-radius:10px;font-weight:700;font-size:.9rem;text-decoration:none;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                        Go to Dashboard
                    </a>
                </div>
            @else
            {{-- No application yet — Show Form --}}
            <div class="cp-form-section-title">Fill Your Application</div>
            <form method="POST" action="{{ route('QueryCpInterest') }}">
                @csrf
                <div class="cp-grid">
                    <div>
                        <label class="cp-label" for="companyName">Company Name</label>
                        <input class="cp-input" id="companyName" name="companyName" type="text" required placeholder="Your company name" value="{{ old('companyName') }}">
                        @error('companyName') <div class="error-text">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="cp-label" for="contactPerson">Contact Person</label>
                        <input class="cp-input" id="contactPerson" name="contactPerson" type="text" required placeholder="Full name" value="{{ old('contactPerson', auth()->user()->name) }}">
                        @error('contactPerson') <div class="error-text">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="cp-label" for="email">Email <span style="font-size:.7rem;color:#64748b;">(verified)</span></label>
                        <input class="cp-input" id="email" name="email" type="email" required value="{{ auth()->user()->email }}" readonly style="opacity:0.6;cursor:not-allowed;">
                    </div>
                    <div>
                        <label class="cp-label" for="mobile">Mobile</label>
                        <input class="cp-input" id="mobile" name="mobile" type="text" pattern="[6-9]\d{9}" maxlength="10" required placeholder="10-digit number" value="{{ old('mobile', auth()->user()->mobile_number) }}">
                        @error('mobile') <div class="error-text">{{ $message }}</div> @enderror
                    </div>
                    @php $userState = old('state', auth()->user()->state); $userCity = old('city', auth()->user()->city); @endphp
                    <div>
                        <label class="cp-label">State</label>
                        <input type="hidden" name="state" id="stateHidden" value="{{ $userState }}" required>
                        <div class="cp-dd" id="stateDd">
                            <div class="cp-dd-trigger" id="stateTrigger">
                                <span id="stateLabel">{{ $userState ?: '' }}</span>
                                <svg class="cp-dd-arrow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                            <div class="cp-dd-panel" id="statePanel">
                                <input type="text" class="cp-dd-search" id="stateSearch" placeholder="Search state...">
                                <div class="cp-dd-list" id="stateList">
                                    @foreach($states as $s)
                                    <div class="cp-dd-opt {{ $userState == $s ? 'active' : '' }}" data-val="{{ $s }}">{{ $s }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="cp-label">City</label>
                        <input type="hidden" name="city" id="cityHidden" value="{{ $userCity }}" required>
                        <div class="cp-dd" id="cityDd">
                            <div class="cp-dd-trigger" id="cityTrigger">
                                <span id="cityLabel">{{ $userCity ?: '' }}</span>
                                <svg class="cp-dd-arrow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                            <div class="cp-dd-panel" id="cityPanel">
                                <input type="text" class="cp-dd-search" id="citySearch" placeholder="Search city...">
                                <div class="cp-dd-list" id="cityList">
                                    @foreach($cities as $c)
                                    <div class="cp-dd-opt {{ $userCity == $c ? 'active' : '' }}" data-val="{{ $c }}">{{ $c }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="cp-label" for="pin_code">Pin Code</label>
                        <input class="cp-input" id="pin_code" name="pin_code" type="text" pattern="\d{6}" maxlength="6" required placeholder="6-digit pin code" value="{{ old('pin_code') }}">
                        @error('pin_code') <div class="error-text">{{ $message }}</div> @enderror
                    </div>
                    <div class="cp-full">
                        <label class="cp-label" for="message">Message (Optional)</label>
                        <textarea class="cp-input cp-textarea" id="message" name="message" placeholder="Tell us about your business...">{{ old('message') }}</textarea>
                    </div>
                </div>

                <div style="margin-top: 16px;">
                    <button class="cp-btn" type="submit">Submit Application</button>
                </div>
            </form>
            @endif
        @else
            {{-- Not logged in — Show Login/Signup CTA --}}
            <div class="cp-login-cta">
                <div class="cp-login-cta-title">Login to Become a Channel Partner</div>
                <div class="cp-login-cta-sub">Please login to your account to fill the application form. If you don't have an account, sign up first.</div>
                <div class="cp-login-btns">
                    <a href="{{ route('login') }}?redirect={{ urlencode(route('CpInterest')) }}" class="cp-btn-login">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="cp-btn-signup">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
                        Sign Up
                    </a>
                </div>
            </div>
        @endauth
    </div>
</div>
@endsection

@section('js')
<script>
    @if(Session::has('success'))
        toastr.success({!! json_encode(Session::get('success')) !!}, 'Success');
    @endif
    @if(Session::has('error'))
        toastr.error({!! json_encode(Session::get('error')) !!}, 'Error');
    @endif

    (function() {
        function initDd(triggerId, panelId, searchId, listId, hiddenId, labelId) {
            var trigger = document.getElementById(triggerId);
            var panel = document.getElementById(panelId);
            var search = document.getElementById(searchId);
            var list = document.getElementById(listId);
            var hidden = document.getElementById(hiddenId);
            var label = document.getElementById(labelId);
            if (!trigger || !panel) return;

            var placeholder = 'Select ' + (triggerId === 'stateTrigger' ? 'state' : 'city') + '...';
            if (!hidden.value) {
                label.textContent = placeholder;
                label.classList.add('cp-dd-placeholder');
            }

            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                var open = panel.classList.contains('is-open');
                closeAll();
                if (!open) {
                    panel.classList.add('is-open');
                    trigger.classList.add('is-open');
                    search.value = '';
                    filterOpts(list, '');
                    search.focus();
                }
            });

            search.addEventListener('input', function() {
                filterOpts(list, this.value);
            });
            search.addEventListener('click', function(e) { e.stopPropagation(); });

            list.addEventListener('click', function(e) {
                var opt = e.target.closest('.cp-dd-opt');
                if (!opt) return;
                var val = opt.dataset.val;
                hidden.value = val;
                label.textContent = val;
                label.classList.remove('cp-dd-placeholder');
                list.querySelectorAll('.cp-dd-opt').forEach(function(o) {
                    o.classList.toggle('active', o.dataset.val === val);
                });
                panel.classList.remove('is-open');
                trigger.classList.remove('is-open');
            });
        }

        function filterOpts(list, q) {
            q = q.toLowerCase();
            var found = 0;
            list.querySelectorAll('.cp-dd-opt').forEach(function(o) {
                var match = o.textContent.toLowerCase().indexOf(q) > -1;
                o.style.display = match ? '' : 'none';
                if (match) found++;
            });
            var empty = list.querySelector('.cp-dd-empty');
            if (!found) {
                if (!empty) {
                    empty = document.createElement('div');
                    empty.className = 'cp-dd-empty';
                    empty.textContent = 'No results found';
                    list.appendChild(empty);
                }
                empty.style.display = '';
            } else if (empty) {
                empty.style.display = 'none';
            }
        }

        function closeAll() {
            document.querySelectorAll('.cp-dd-panel').forEach(function(p) { p.classList.remove('is-open'); });
            document.querySelectorAll('.cp-dd-trigger').forEach(function(t) { t.classList.remove('is-open'); });
        }

        document.addEventListener('click', closeAll);

        initDd('stateTrigger', 'statePanel', 'stateSearch', 'stateList', 'stateHidden', 'stateLabel');
        initDd('cityTrigger', 'cityPanel', 'citySearch', 'cityList', 'cityHidden', 'cityLabel');
    })();
</script>
@endsection
