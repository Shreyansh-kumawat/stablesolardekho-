@extends('layouts.public')

@section('title', 'Solar Referral - ' . $referrer->name)

@section('content')
<div style="min-height:100vh;padding:60px 0;background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%);">
    <div style="max-width:560px;margin:0 auto;padding:0 16px;">

        {{-- Header --}}
        <div style="text-align:center;margin-bottom:32px;">
            <div style="display:inline-flex;align-items:center;justify-content:center;width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#f97316,#ef4444);margin-bottom:16px;">
                <svg width="32" height="32" fill="none" stroke="#fff" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/>
                </svg>
            </div>
            <h1 style="color:#fff;font-size:1.75rem;font-weight:800;margin:0 0 8px;">Get a Free Solar Quote</h1>
            <p style="color:#94a3b8;font-size:.95rem;margin:0;">
                Referred by <span style="color:#f97316;font-weight:600;">{{ $referrer->name }}</span>
            </p>
        </div>

        {{-- Success --}}
        @if(session('success'))
        <div style="background:#065f46;border:1px solid #10b981;border-radius:12px;padding:16px 20px;margin-bottom:24px;color:#d1fae5;font-size:.9rem;text-align:center;">
            {{ session('success') }}
        </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('referral.submit', $referralCode->code) }}" method="POST"
              style="background:#1e293b;border:1px solid #334155;border-radius:16px;padding:28px 24px;">
            @csrf

            <div style="margin-bottom:18px;">
                <label style="display:block;color:#cbd5e1;font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Full Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       style="width:100%;padding:12px 14px;background:#0f172a;border:1px solid #334155;border-radius:8px;color:#fff;font-size:.95rem;outline:none;"
                       placeholder="Your full name">
                @error('name')<p style="color:#ef4444;font-size:.8rem;margin:4px 0 0;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom:18px;">
                <label style="display:block;color:#cbd5e1;font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Mobile Number *</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" required
                       style="width:100%;padding:12px 14px;background:#0f172a;border:1px solid #334155;border-radius:8px;color:#fff;font-size:.95rem;outline:none;"
                       placeholder="10 digit mobile number">
                @error('phone')<p style="color:#ef4444;font-size:.8rem;margin:4px 0 0;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom:18px;">
                <label style="display:block;color:#cbd5e1;font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       style="width:100%;padding:12px 14px;background:#0f172a;border:1px solid #334155;border-radius:8px;color:#fff;font-size:.95rem;outline:none;"
                       placeholder="your@email.com">
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:18px;">
                <div>
                    <label style="display:block;color:#cbd5e1;font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">City</label>
                    <input type="text" name="city" value="{{ old('city') }}"
                           style="width:100%;padding:12px 14px;background:#0f172a;border:1px solid #334155;border-radius:8px;color:#fff;font-size:.95rem;outline:none;"
                           placeholder="City">
                </div>
                <div>
                    <label style="display:block;color:#cbd5e1;font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">State</label>
                    <input type="text" name="state" value="{{ old('state') }}"
                           style="width:100%;padding:12px 14px;background:#0f172a;border:1px solid #334155;border-radius:8px;color:#fff;font-size:.95rem;outline:none;"
                           placeholder="State">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:18px;">
                <div>
                    <label style="display:block;color:#cbd5e1;font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Pin Code</label>
                    <input type="text" name="pin_code" value="{{ old('pin_code') }}"
                           style="width:100%;padding:12px 14px;background:#0f172a;border:1px solid #334155;border-radius:8px;color:#fff;font-size:.95rem;outline:none;"
                           placeholder="Pin code">
                </div>
                <div>
                    <label style="display:block;color:#cbd5e1;font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Monthly Bill</label>
                    <input type="text" name="monthly_bill" value="{{ old('monthly_bill') }}"
                           style="width:100%;padding:12px 14px;background:#0f172a;border:1px solid #334155;border-radius:8px;color:#fff;font-size:.95rem;outline:none;"
                           placeholder="e.g. 3000">
                </div>
            </div>

            <div style="margin-bottom:18px;">
                <label style="display:block;color:#cbd5e1;font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Address</label>
                <textarea name="address" rows="2"
                          style="width:100%;padding:12px 14px;background:#0f172a;border:1px solid #334155;border-radius:8px;color:#fff;font-size:.95rem;outline:none;resize:vertical;"
                          placeholder="Full address">{{ old('address') }}</textarea>
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block;color:#cbd5e1;font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Interested System Size</label>
                <select name="system_size"
                        style="width:100%;padding:12px 14px;background:#0f172a;border:1px solid #334155;border-radius:8px;color:#fff;font-size:.95rem;outline:none;">
                    <option value="">Select (optional)</option>
                    <option value="1kW" {{ old('system_size')=='1kW'?'selected':'' }}>1 kW</option>
                    <option value="2kW" {{ old('system_size')=='2kW'?'selected':'' }}>2 kW</option>
                    <option value="3kW" {{ old('system_size')=='3kW'?'selected':'' }}>3 kW</option>
                    <option value="5kW" {{ old('system_size')=='5kW'?'selected':'' }}>5 kW</option>
                    <option value="8kW" {{ old('system_size')=='8kW'?'selected':'' }}>8 kW</option>
                    <option value="10kW" {{ old('system_size')=='10kW'?'selected':'' }}>10 kW</option>
                    <option value="10kW+" {{ old('system_size')=='10kW+'?'selected':'' }}>10 kW+</option>
                </select>
            </div>

            <button type="submit"
                    style="width:100%;padding:14px;background:linear-gradient(135deg,#f97316,#ef4444);color:#fff;font-size:1rem;font-weight:700;border:none;border-radius:10px;cursor:pointer;letter-spacing:.3px;">
                Submit Details
            </button>
        </form>

        <p style="text-align:center;color:#475569;font-size:.8rem;margin-top:20px;">
            Powered by Stable Solar Dekho
        </p>
    </div>
</div>
@endsection
