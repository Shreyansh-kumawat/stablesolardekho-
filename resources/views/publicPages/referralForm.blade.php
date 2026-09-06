@extends('layouts.public')

@section('title', 'Solar Referral - ' . $referrer->name)

@section('content')
<style>
.ref-grid{display:grid;grid-template-columns:1fr 1fr;gap:36px;align-items:start;}
.ref-strip-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;}
@media(max-width:768px){
    .ref-grid{grid-template-columns:1fr;}
    .ref-strip-grid{grid-template-columns:1fr 1fr;}
}
</style>
<div style="background:#fff;min-height:100vh;">

    {{-- Top: Heading centered --}}
    <div style="padding:48px 0 0;">
        <div style="max-width:1100px;margin:0 auto;padding:0 20px;text-align:center;">
            <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(249,115,22,0.1);border:1px solid rgba(249,115,22,0.25);border-radius:20px;padding:6px 18px;margin-bottom:14px;">
                <svg width="16" height="16" fill="none" stroke="#f97316" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/></svg>
                <span style="color:#f97316;font-size:.82rem;font-weight:600;">Referred by {{ $referrer->name }}</span>
            </div>
            <p style="color:#f97316;font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.12em;margin:0 0 8px;">Grand Combo Offer &middot; Total 6 Parts</p>
            <h1 style="color:#1e293b;font-size:2rem;font-weight:900;margin:0 0 10px;line-height:1.2;">Sales Bhi &middot; Seva Bhi &middot; Safar Bhi</h1>
            <p style="color:#64748b;font-size:0.92rem;margin:0 auto 36px;max-width:640px;">Complete your billing target and win a FREE Goa or Thailand-Pattaya trip. Offer valid 7 September - 30 September 2026. Register below to join.</p>
        </div>

        {{-- Main grid --}}
        <div style="max-width:1100px;margin:0 auto;padding:0 20px;">
            @if(session('success'))
            <div style="background:#f0fdf4;border:1px solid #86efac;border-radius:12px;padding:16px 20px;margin-bottom:24px;color:#166534;font-size:.9rem;text-align:center;">
                {{ session('success') }}
            </div>
            @endif

            <div class="ref-grid">
                {{-- Left: Image + Trust Points --}}
                <div>
                    <img src="{{ asset('stable/images/grand-combo-offer.jpg') }}?v=1" alt="Grand Combo Offer - Goa & Thailand Trip" style="width:100%;border-radius:14px;box-shadow:0 8px 30px rgba(0,0,0,0.12);margin-bottom:24px;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
                        @foreach([
                            ['icon'=>'M12 21c-4.97 0-9-4.03-9-9 0-3.87 2.44-7.16 5.86-8.42C10.11 4.5 12 6 12 6s1.89-1.5 3.14-2.42C18.56 4.84 21 8.13 21 12c0 4.97-4.03 9-9 9z','title'=>'Goa Trip FREE','desc'=>'On billing of Rs 30 Lakh (GST included) - 4 Days / 3 Nights'],
                            ['icon'=>'M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z','title'=>'Thailand-Pattaya FREE','desc'=>'On billing of Rs 70 Lakh (GST included) - 3 Nights / 4 Days'],
                            ['icon'=>'M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z','title'=>'1% Extra Discount','desc'=>'Buy the full combo material together and save even more'],
                            ['icon'=>'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5','title'=>'Offer Period','desc'=>'Valid from 7 September to 30 September 2026 only']
                        ] as $tp)
                        <div style="background:#f8fafc;border-radius:12px;padding:16px;border:1px solid #e2e8f0;">
                            <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#fff7ed,#fed7aa);display:flex;align-items:center;justify-content:center;margin-bottom:10px;">
                                <svg width="18" height="18" fill="none" stroke="#ea580c" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $tp['icon'] }}"/></svg>
                            </div>
                            <p style="font-weight:700;color:#1e293b;font-size:0.82rem;margin:0 0 3px;">{{ $tp['title'] }}</p>
                            <p style="color:#64748b;font-size:0.72rem;line-height:1.4;margin:0;">{{ $tp['desc'] }}</p>
                        </div>
                        @endforeach
                    </div>
                    <div style="background:#fffbeb;border:1px solid #fcd34d;border-radius:12px;padding:12px 14px;font-size:.78rem;color:#92400e;line-height:1.5;margin-bottom:24px;">
                        <strong>Note:</strong> Both trips cannot be availed together. Whoever completes the target will get one trip only. GST included &middot; Material in 3 parts allowed &middot; Transport extra.
                    </div>
                </div>

                {{-- Right: Form --}}
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:18px;padding:28px;box-shadow:0 4px 24px rgba(0,0,0,0.06);">
                    <h3 style="font-size:1.1rem;font-weight:800;color:#1e293b;margin:0 0 4px;">Register for Grand Combo Offer</h3>
                    <p style="font-size:0.78rem;color:#64748b;margin:0 0 20px;">Get a FREE Goa or Thailand-Pattaya trip on your solar order</p>

                    <form action="{{ route('referral.submit', $referralCode->code) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div style="margin-bottom:14px;">
                            <label style="display:block;color:#374151;font-size:0.75rem;font-weight:600;margin-bottom:5px;">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Your full name"
                                   style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:0.88rem;color:#1e293b;background:#fff;box-sizing:border-box;outline:none;transition:border-color .15s;"
                                   onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#e2e8f0'">
                            @error('name')<p style="color:#ef4444;font-size:.78rem;margin:4px 0 0;">{{ $message }}</p>@enderror
                        </div>

                        <div style="margin-bottom:14px;">
                            <label style="display:block;color:#374151;font-size:0.75rem;font-weight:600;margin-bottom:5px;">Mobile Number *</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="10 digit mobile number"
                                   style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:0.88rem;color:#1e293b;background:#fff;box-sizing:border-box;outline:none;transition:border-color .15s;"
                                   onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#e2e8f0'">
                            @error('phone')<p style="color:#ef4444;font-size:.78rem;margin:4px 0 0;">{{ $message }}</p>@enderror
                        </div>

                        <div style="margin-bottom:14px;">
                            <label style="display:block;color:#374151;font-size:0.75rem;font-weight:600;margin-bottom:5px;">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="your@email.com"
                                   style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:0.88rem;color:#1e293b;background:#fff;box-sizing:border-box;outline:none;transition:border-color .15s;"
                                   onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#e2e8f0'">
                        </div>

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
                            <div>
                                <label style="display:block;color:#374151;font-size:0.75rem;font-weight:600;margin-bottom:5px;">City</label>
                                <input type="text" name="city" value="{{ old('city') }}" placeholder="City"
                                       style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:0.88rem;color:#1e293b;background:#fff;box-sizing:border-box;outline:none;transition:border-color .15s;"
                                       onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#e2e8f0'">
                            </div>
                            <div>
                                <label style="display:block;color:#374151;font-size:0.75rem;font-weight:600;margin-bottom:5px;">State</label>
                                <input type="text" name="state" value="{{ old('state') }}" placeholder="State"
                                       style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:0.88rem;color:#1e293b;background:#fff;box-sizing:border-box;outline:none;transition:border-color .15s;"
                                       onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#e2e8f0'">
                            </div>
                        </div>

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
                            <div>
                                <label style="display:block;color:#374151;font-size:0.75rem;font-weight:600;margin-bottom:5px;">Pin Code</label>
                                <input type="text" name="pin_code" value="{{ old('pin_code') }}" placeholder="Pin code"
                                       style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:0.88rem;color:#1e293b;background:#fff;box-sizing:border-box;outline:none;transition:border-color .15s;"
                                       onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#e2e8f0'">
                            </div>
                            <div>
                                <label style="display:block;color:#374151;font-size:0.75rem;font-weight:600;margin-bottom:5px;">Monthly Bill</label>
                                <input type="text" name="monthly_bill" value="{{ old('monthly_bill') }}" placeholder="e.g. 3000"
                                       style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:0.88rem;color:#1e293b;background:#fff;box-sizing:border-box;outline:none;transition:border-color .15s;"
                                       onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#e2e8f0'">
                            </div>
                        </div>

                        <div style="margin-bottom:14px;">
                            <label style="display:block;color:#374151;font-size:0.75rem;font-weight:600;margin-bottom:5px;">Address</label>
                            <textarea name="address" rows="2" placeholder="Full address"
                                      style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:0.88rem;color:#1e293b;background:#fff;box-sizing:border-box;outline:none;resize:vertical;transition:border-color .15s;"
                                      onfocus="this.style.borderColor='#f97316'" onblur="this.style.borderColor='#e2e8f0'">{{ old('address') }}</textarea>
                        </div>

                        <div style="margin-bottom:18px;">
                            <label style="display:block;color:#374151;font-size:0.75rem;font-weight:600;margin-bottom:5px;">Interested System Size</label>
                            <select name="system_size"
                                    style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:0.88rem;color:#1e293b;background:#fff;box-sizing:border-box;outline:none;transition:border-color .15s;"
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

                        <button type="submit"
                                style="width:100%;padding:14px;background:linear-gradient(135deg,#f97316,#ea580c);color:#fff;font-weight:800;font-size:0.95rem;border:none;border-radius:10px;cursor:pointer;transition:opacity 0.2s;box-shadow:0 4px 14px rgba(249,115,22,0.35);"
                                onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                            Submit Details
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ───────────────── COMBO OFFER DETAILS ───────────────── --}}
    <div style="background:#f8fafc;padding:56px 0;margin-top:48px;">
        <div style="max-width:1100px;margin:0 auto;padding:0 20px;">
            <div style="text-align:center;margin-bottom:36px;">
                <p style="color:#f97316;font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.12em;margin:0 0 8px;">Complete Combo Details</p>
                <h2 style="color:#1e293b;font-size:1.75rem;font-weight:900;margin:0 0 10px;line-height:1.2;">Two Amazing Trips &middot; One Grand Offer</h2>
                <p style="color:#64748b;font-size:0.92rem;margin:0 auto;max-width:640px;">Full breakdown of material included in each combo. Both trips cannot be availed together - whoever completes the target gets one trip.</p>
            </div>

            {{-- Highlight Cards --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:40px;" class="combo-highlight-grid">
                <div style="background:linear-gradient(135deg,#fff7ed,#ffedd5);border:2px solid #fdba74;border-radius:16px;padding:24px;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                        <span style="font-size:1.6rem;">🇮🇳</span>
                        <h3 style="font-size:1.15rem;font-weight:900;color:#7c2d12;margin:0;">GOA TRIP</h3>
                    </div>
                    <p style="font-size:1.4rem;font-weight:900;color:#c2410c;margin:0 0 6px;">Rs 30 Lakh <span style="font-size:.78rem;font-weight:600;color:#7c2d12;">Billing (GST included)</span></p>
                    <p style="color:#7c2d12;font-size:.88rem;font-weight:600;margin:0;">4 Days / 3 Nights &middot; FREE</p>
                </div>
                <div style="background:linear-gradient(135deg,#eff6ff,#dbeafe);border:2px solid #93c5fd;border-radius:16px;padding:24px;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                        <span style="font-size:1.6rem;">🇹🇭</span>
                        <h3 style="font-size:1.15rem;font-weight:900;color:#1e3a8a;margin:0;">THAILAND-PATTAYA TRIP</h3>
                    </div>
                    <p style="font-size:1.4rem;font-weight:900;color:#1d4ed8;margin:0 0 6px;">Rs 70 Lakh <span style="font-size:.78rem;font-weight:600;color:#1e3a8a;">Billing (GST included)</span></p>
                    <p style="color:#1e3a8a;font-size:.88rem;font-weight:600;margin:0;">3 Nights / 4 Days &middot; FREE</p>
                </div>
            </div>

            {{-- Common Benefits Strip --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:20px 24px;margin-bottom:40px;">
                <p style="font-weight:800;color:#1e293b;font-size:.95rem;margin:0 0 12px;">🎁 Same Benefits on All 6 Parts</p>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;">
                    @foreach([
                        'GST Included',
                        'Material in 3 Parts or Together',
                        '1% Extra Discount on Full Combo',
                        'Transport Extra',
                        'Offer: 7 Sep - 30 Sep 2026',
                        'Register at stablesolardekho.com'
                    ] as $benefit)
                    <div style="display:flex;align-items:center;gap:8px;color:#374151;font-size:.82rem;">
                        <span style="width:18px;height:18px;border-radius:50%;background:#dcfce7;color:#166534;display:inline-flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:900;flex-shrink:0;">✓</span>
                        <span>{{ $benefit }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            @php
                $combos = [
                    [
                        'flag' => '🇮🇳',
                        'title' => 'GOA COMBO',
                        'target' => 'Rs 30 Lakh',
                        'accent' => '#ea580c',
                        'bg' => '#fff7ed',
                        'parts' => [
                            'PART 1' => [
                                ['Adani 550W DCR','Adani',60],
                                ['Adani 620W DCR','Adani',36],
                                ['Adani 630W NDCR','Adani',36],
                                ['Waaree 610W DCR','Waaree',36],
                                ['Waaree 615W NDCR','Waaree',36],
                                ['Polycab 5KW','Polycab',3],
                                ['Polycab 3.6KW','Polycab',3],
                                ['ACDB/DCDB 1PH','Havells + Sibass',5],
                                ['Earthing Rod','Copper Bonded',15],
                                ['LA Set','Base Plasting',5],
                                ['L&T Meter','L&T',5],
                            ],
                            'PART 2' => [
                                ['Adani 550W DCR','Adani',72],
                                ['Adani 620W DCR','Adani',60],
                                ['Adani 630W NDCR','Adani',72],
                                ['Polycab 5KW','Polycab',4],
                                ['Polycab 3.6KW','Polycab',4],
                                ['ACDB/DCDB 1PH','Havells + Sibass',4],
                            ],
                            'PART 3' => [
                                ['Waaree 610W DCR','Waaree',90],
                                ['Waaree 615W NDCR','Waaree',120],
                                ['Polycab 5KW','Polycab',5],
                                ['Polycab 3.6KW','Polycab',5],
                                ['ACDB/DCDB 1PH','Havells + Sibass',10],
                                ['Earthing Rod','Copper Bonded',15],
                                ['LA Set','Base Plasting',5],
                                ['L&T Meter','L&T',5],
                            ],
                        ],
                    ],
                    [
                        'flag' => '🇹🇭',
                        'title' => 'THAILAND-PATTAYA COMBO',
                        'target' => 'Rs 70 Lakh',
                        'accent' => '#1d4ed8',
                        'bg' => '#eff6ff',
                        'parts' => [
                            'PART 1' => [
                                ['Adani 550W DCR','Adani',108],
                                ['Adani 620W DCR','Adani',72],
                                ['Adani 630W NDCR','Adani',108],
                                ['Waaree 610W DCR','Waaree',93],
                                ['Waaree 615W NDCR','Waaree',93],
                                ['Polycab 5KW','Polycab',10],
                                ['Polycab 3.6KW','Polycab',10],
                                ['ACDB/DCDB 1PH','Havells + Sibass',5],
                                ['Earthing Rod','Copper Bonded',30],
                                ['LA Set','Base Plasting',15],
                                ['L&T Meter','L&T',15],
                            ],
                            'PART 2' => [
                                ['Adani 550W DCR','Adani',180],
                                ['Adani 620W DCR','Adani',144],
                                ['Adani 630W NDCR','Adani',180],
                                ['ACDB/DCDB 1PH','Havells + Sibass',5],
                                ['ACDB/DCDB 3PH','Havells + Sibass',5],
                                ['L&T Meter','L&T',5],
                            ],
                            'PART 3' => [
                                ['Waaree 610W DCR','Waaree',217],
                                ['Waaree 615W NDCR','Waaree',217],
                                ['Polycab 5KW','Polycab',15],
                                ['Polycab 3.6KW','Polycab',15],
                                ['Mictotek 3KW','Mictotek',15],
                                ['ACDB/DCDB 1PH','Havells + Sibass',25],
                                ['Earthing Rod','Copper Bonded',30],
                                ['LA Set','Base Plasting',15],
                                ['L&T Meter','L&T',15],
                                ['ACDB/DCDB 3PH','Havells + Sibass',15],
                            ],
                        ],
                    ],
                ];
            @endphp

            @foreach($combos as $combo)
            <div style="margin-bottom:40px;">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;padding-bottom:12px;border-bottom:2px solid {{ $combo['accent'] }};">
                    <span style="font-size:1.8rem;">{{ $combo['flag'] }}</span>
                    <div>
                        <h3 style="font-size:1.3rem;font-weight:900;color:#1e293b;margin:0;">{{ $combo['title'] }}</h3>
                        <p style="font-size:.82rem;color:#64748b;margin:2px 0 0;">Target Billing: <strong style="color:{{ $combo['accent'] }};">{{ $combo['target'] }}</strong> (GST included) &middot; 3 Parts</p>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;" class="combo-parts-grid">
                    @foreach($combo['parts'] as $partName => $items)
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                        <div style="background:{{ $combo['bg'] }};padding:10px 14px;font-weight:800;color:{{ $combo['accent'] }};font-size:.88rem;border-bottom:1px solid #e2e8f0;">{{ $partName }}</div>
                        <div style="padding:8px 0;">
                            <div style="display:grid;grid-template-columns:1.4fr 1fr 40px;gap:8px;padding:6px 14px;font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:#64748b;font-weight:700;border-bottom:1px solid #f1f5f9;">
                                <span>Item</span>
                                <span>Make</span>
                                <span style="text-align:right;">Qty</span>
                            </div>
                            @foreach($items as $row)
                            <div style="display:grid;grid-template-columns:1.4fr 1fr 40px;gap:8px;padding:8px 14px;font-size:.78rem;color:#374151;border-bottom:1px solid #f8fafc;">
                                <span style="font-weight:600;color:#1e293b;">{{ $row[0] }}</span>
                                <span style="color:#64748b;">{{ $row[1] }}</span>
                                <span style="text-align:right;font-weight:700;color:{{ $combo['accent'] }};">{{ $row[2] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            {{-- Note --}}
            <div style="background:#fffbeb;border:1px solid #fcd34d;border-radius:12px;padding:14px 18px;margin-bottom:24px;">
                <p style="margin:0;color:#92400e;font-size:.82rem;line-height:1.5;">
                    <strong>Note:</strong> All panels may be provided in 540-560W or 600-630W models based on availability.
                </p>
            </div>

            {{-- Contact --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:24px;text-align:center;">
                <p style="font-size:.78rem;color:#64748b;text-transform:uppercase;letter-spacing:.1em;font-weight:700;margin:0 0 10px;">📞 Contact</p>
                <p style="font-size:1.05rem;font-weight:800;color:#1e293b;margin:0 0 6px;">Helpline: <a href="tel:7014920144" style="color:#ea580c;text-decoration:none;">7014920144</a></p>
                <p style="font-size:.88rem;color:#64748b;margin:0 0 14px;">Website: <a href="https://stablesolardekho.com" style="color:#1d4ed8;text-decoration:none;">stablesolardekho.com</a></p>
                <p style="font-style:italic;color:#f97316;font-weight:700;font-size:.95rem;margin:0;">Mehnat Aapki &middot; Safar Hamara</p>
            </div>
        </div>
    </div>

    <style>
    @media (max-width: 900px) {
        .combo-parts-grid { grid-template-columns: 1fr !important; }
        .combo-highlight-grid { grid-template-columns: 1fr !important; }
    }
    </style>

    {{-- Trust Strip --}}
    <div style="padding:40px 0 56px;">
        <div style="max-width:1100px;margin:0 auto;padding:0 20px;">
            <div class="ref-strip-grid">
                @foreach([
                    ['num'=>'₹1000Cr+','label'=>'Savings Across India'],
                    ['num'=>'10+','label'=>'Years of Experience'],
                    ['num'=>'50,000+','label'=>'Homes Solarised'],
                    ['num'=>'4.8 ★','label'=>'Google Rating (8000+ Reviews)']
                ] as $stat)
                <div style="padding:16px;background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;text-align:center;">
                    <p style="font-size:1.35rem;font-weight:900;color:#1e293b;margin:0 0 2px;">{{ $stat['num'] }}</p>
                    <p style="font-size:0.72rem;color:#64748b;margin:0;font-weight:500;">{{ $stat['label'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <p style="text-align:center;color:#94a3b8;font-size:.78rem;padding-bottom:32px;">Powered by Stable Solar Energy</p>
</div>
@endsection
