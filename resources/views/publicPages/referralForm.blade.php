@extends('layouts.public')

@section('title', 'Solar Referral - ' . $referrer->name)

@section('content')
<div style="min-height:100vh;background:#fff;">

    {{-- Hero banner with Independence Day image --}}
    <div style="background:linear-gradient(135deg,#1e293b 0%,#0f172a 100%);padding:48px 0 0;text-align:center;">
        <div style="max-width:600px;margin:0 auto;padding:0 16px;">
            <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(249,115,22,0.12);border:1px solid rgba(249,115,22,0.25);border-radius:20px;padding:6px 16px;margin-bottom:16px;">
                <svg width="16" height="16" fill="none" stroke="#f97316" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/></svg>
                <span style="color:#f97316;font-size:.82rem;font-weight:600;">Referred by {{ $referrer->name }}</span>
            </div>
            <h1 style="color:#fff;font-size:1.85rem;font-weight:900;margin:0 0 10px;line-height:1.2;">Get a Free Solar Quote</h1>
            <p style="color:#94a3b8;font-size:.9rem;margin:0 0 28px;">Fill in your details and our solar expert will contact you with the best solar solution.</p>
        </div>
        <div style="max-width:520px;margin:0 auto;padding:0 16px;">
            <img src="{{ asset('stable/images/indipendence.jpeg') }}" alt="Independence Day Offer" style="width:100%;border-radius:14px 14px 0 0;display:block;">
        </div>
    </div>

    {{-- Form Section --}}
    <div style="max-width:520px;margin:0 auto;padding:0 16px;">
        {{-- Success --}}
        @if(session('success'))
        <div style="background:#f0fdf4;border:1px solid #86efac;border-radius:12px;padding:16px 20px;margin-top:24px;color:#166534;font-size:.9rem;text-align:center;">
            {{ session('success') }}
        </div>
        @endif

        {{-- Form Card --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:28px 24px;margin-top:24px;box-shadow:0 4px 24px rgba(0,0,0,0.06);">
            <h3 style="font-size:1.05rem;font-weight:800;color:#1e293b;margin:0 0 4px;">Book a FREE Consultation</h3>
            <p style="font-size:.78rem;color:#64748b;margin:0 0 20px;">And save up to ₹78,000 with subsidy</p>

            <form action="{{ route('referral.submit', $referralCode->code) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div style="margin-bottom:14px;">
                    <label style="display:block;color:#374151;font-size:.75rem;font-weight:600;margin-bottom:5px;">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Your full name"
                           style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:.88rem;color:#1e293b;background:#fff;box-sizing:border-box;outline:none;transition:border-color .15s;"
                           onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#e2e8f0'">
                    @error('name')<p style="color:#ef4444;font-size:.78rem;margin:4px 0 0;">{{ $message }}</p>@enderror
                </div>

                <div style="margin-bottom:14px;">
                    <label style="display:block;color:#374151;font-size:.75rem;font-weight:600;margin-bottom:5px;">Mobile Number *</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="10 digit mobile number"
                           style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:.88rem;color:#1e293b;background:#fff;box-sizing:border-box;outline:none;transition:border-color .15s;"
                           onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#e2e8f0'">
                    @error('phone')<p style="color:#ef4444;font-size:.78rem;margin:4px 0 0;">{{ $message }}</p>@enderror
                </div>

                <div style="margin-bottom:14px;">
                    <label style="display:block;color:#374151;font-size:.75rem;font-weight:600;margin-bottom:5px;">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="your@email.com"
                           style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:.88rem;color:#1e293b;background:#fff;box-sizing:border-box;outline:none;transition:border-color .15s;"
                           onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#e2e8f0'">
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
                    <div>
                        <label style="display:block;color:#374151;font-size:.75rem;font-weight:600;margin-bottom:5px;">City</label>
                        <input type="text" name="city" value="{{ old('city') }}" placeholder="City"
                               style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:.88rem;color:#1e293b;background:#fff;box-sizing:border-box;outline:none;transition:border-color .15s;"
                               onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    <div>
                        <label style="display:block;color:#374151;font-size:.75rem;font-weight:600;margin-bottom:5px;">State</label>
                        <input type="text" name="state" value="{{ old('state') }}" placeholder="State"
                               style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:.88rem;color:#1e293b;background:#fff;box-sizing:border-box;outline:none;transition:border-color .15s;"
                               onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
                    <div>
                        <label style="display:block;color:#374151;font-size:.75rem;font-weight:600;margin-bottom:5px;">Pin Code</label>
                        <input type="text" name="pin_code" value="{{ old('pin_code') }}" placeholder="Pin code"
                               style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:.88rem;color:#1e293b;background:#fff;box-sizing:border-box;outline:none;transition:border-color .15s;"
                               onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    <div>
                        <label style="display:block;color:#374151;font-size:.75rem;font-weight:600;margin-bottom:5px;">Monthly Bill</label>
                        <input type="text" name="monthly_bill" value="{{ old('monthly_bill') }}" placeholder="e.g. 3000"
                               style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:.88rem;color:#1e293b;background:#fff;box-sizing:border-box;outline:none;transition:border-color .15s;"
                               onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>

                <div style="margin-bottom:14px;">
                    <label style="display:block;color:#374151;font-size:.75rem;font-weight:600;margin-bottom:5px;">Address</label>
                    <textarea name="address" rows="2" placeholder="Full address"
                              style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:.88rem;color:#1e293b;background:#fff;box-sizing:border-box;outline:none;resize:vertical;transition:border-color .15s;"
                              onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#e2e8f0'">{{ old('address') }}</textarea>
                </div>

                <div style="margin-bottom:18px;">
                    <label style="display:block;color:#374151;font-size:.75rem;font-weight:600;margin-bottom:5px;">Interested System Size</label>
                    <select name="system_size"
                            style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:.88rem;color:#1e293b;background:#fff;box-sizing:border-box;outline:none;transition:border-color .15s;"
                            onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#e2e8f0'">
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

                {{-- Independence Day Offer --}}
                <div style="background:linear-gradient(135deg,#fff7ed 0%,#fed7aa 100%);border:1px solid #fdba74;border-radius:10px;padding:14px;margin-bottom:16px;">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                        <span style="font-size:1.1rem;">🇮🇳</span>
                        <span style="font-weight:800;color:#c2410c;font-size:.82rem;">15 August Special Offer!</span>
                    </div>
                    <p style="font-size:.76rem;color:#7c2d12;line-height:1.5;margin:0 0 10px;">Take a selfie with the Tiranga on your rooftop and get <b>5% or ₹5,000 discount</b> on solar installation!</p>
                    <label style="display:block;color:#9a3412;font-size:.72rem;font-weight:600;margin-bottom:5px;">Upload Your Tiranga Selfie (Optional)</label>
                    <input type="file" name="selfie_image" id="refSelfieInput" accept="image/*" style="display:none;">
                    <div onclick="document.getElementById('refSelfieInput').click()" style="cursor:pointer;border:2px dashed #fdba74;border-radius:10px;padding:16px;text-align:center;background:#fffbf5;transition:border-color .15s;" onmouseover="this.style.borderColor='#ea580c'" onmouseout="this.style.borderColor='#fdba74'">
                        <svg width="28" height="28" fill="none" stroke="#ea580c" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:6px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"/></svg>
                        <p id="refSelfieFileName" style="font-size:.78rem;color:#9a3412;margin:0;font-weight:600;">Click to upload selfie</p>
                    </div>
                    <script>document.getElementById('refSelfieInput').addEventListener('change',function(){document.getElementById('refSelfieFileName').textContent=this.files[0]?this.files[0].name:'Click to upload selfie';});</script>
                </div>

                <button type="submit"
                        style="width:100%;padding:14px;background:linear-gradient(135deg,#f97316,#ea580c);color:#fff;font-size:1rem;font-weight:700;border:none;border-radius:10px;cursor:pointer;letter-spacing:.3px;box-shadow:0 4px 14px rgba(249,115,22,0.35);transition:opacity .15s;"
                        onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                    Submit Details
                </button>
            </form>
        </div>

        {{-- Trust points --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:20px;padding-bottom:40px;">
            @foreach([
                ['icon'=>'M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z','title'=>'Guaranteed Savings'],
                ['icon'=>'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z','title'=>'Subsidy Assistance'],
                ['icon'=>'M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z','title'=>'No Hidden Charges'],
                ['icon'=>'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z','title'=>'Certified Team']
            ] as $tp)
            <div style="background:#f8fafc;border-radius:10px;padding:14px;border:1px solid #e2e8f0;display:flex;align-items:center;gap:10px;">
                <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#fff7ed,#fed7aa);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="16" height="16" fill="none" stroke="#ea580c" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $tp['icon'] }}"/></svg>
                </div>
                <span style="font-weight:700;color:#1e293b;font-size:.78rem;">{{ $tp['title'] }}</span>
            </div>
            @endforeach
        </div>

        <p style="text-align:center;color:#94a3b8;font-size:.78rem;padding-bottom:32px;">
            Powered by Stable Solar Energy
        </p>
    </div>
</div>
@endsection
