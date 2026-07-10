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
    .cp-benefits {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 20px;
        padding-top: 18px;
        border-top: 1px solid rgba(255,255,255,0.08);
    }
    @media(max-width: 560px) {
        .cp-benefits { grid-template-columns: 1fr; }
    }
    .cp-benefit {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #94a3b8;
        font-size: .82rem;
    }
    .cp-benefit-icon {
        width: 28px; height: 28px;
        background: rgba(249,115,22,0.15);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .cp-benefit-icon svg { width: 14px; height: 14px; color: #f97316; }
    .error-text { color: #f87171; font-size: .78rem; margin-top: 4px; }
</style>
@endsection

@section('content')
<div class="cp-container">
    <div class="cp-card">
        <div class="cp-header">
            <div class="cp-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                </svg>
            </div>
            <div class="cp-title">Become a Channel Partner</div>
            <div class="cp-sub">Join our network and grow your solar business with Stable Solar Dekho</div>
        </div>

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
                    <input class="cp-input" id="contactPerson" name="contactPerson" type="text" required placeholder="Full name" value="{{ old('contactPerson') }}">
                    @error('contactPerson') <div class="error-text">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="cp-label" for="email">Email</label>
                    <input class="cp-input" id="email" name="email" type="email" required placeholder="name@email.com" value="{{ old('email') }}">
                    @error('email') <div class="error-text">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="cp-label" for="mobile">Mobile</label>
                    <input class="cp-input" id="mobile" name="mobile" type="text" pattern="[6-9]\d{9}" maxlength="10" required placeholder="10-digit number" value="{{ old('mobile') }}">
                    @error('mobile') <div class="error-text">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="cp-label" for="state">State</label>
                    <select class="cp-input" id="state" name="state" required>
                        <option value="" disabled {{ old('state') ? '' : 'selected' }}>Select state</option>
                        @foreach($states as $s)
                            <option value="{{ $s }}" {{ old('state') == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="cp-label" for="city">City</label>
                    <select class="cp-input" id="city" name="city" required>
                        <option value="" disabled {{ old('city') ? '' : 'selected' }}>Select city</option>
                        @foreach($cities as $c)
                            <option value="{{ $c }}" {{ old('city') == $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
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

        <div class="cp-benefits">
            <div class="cp-benefit">
                <div class="cp-benefit-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                </div>
                Exclusive dealer pricing
            </div>
            <div class="cp-benefit">
                <div class="cp-benefit-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                </div>
                Dedicated inventory access
            </div>
            <div class="cp-benefit">
                <div class="cp-benefit-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                </div>
                Priority support & training
            </div>
            <div class="cp-benefit">
                <div class="cp-benefit-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                </div>
                Business growth dashboard
            </div>
        </div>
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
</script>
@endsection
