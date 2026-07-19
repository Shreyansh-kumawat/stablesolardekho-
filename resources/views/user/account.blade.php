@extends('layouts.public')

@section('css')
<style>
:root{--bg:#0b1117;--card:#131929;--border:rgba(255,255,255,0.07);--text:#e2e8f0;--muted:#64748b;--orange:#f97316;}
.acc-card{background:var(--card);border:1px solid var(--border);border-radius:16px;margin-bottom:1rem;overflow:hidden;transition:border-color 0.3s;}
.acc-card:hover{border-color:rgba(255,255,255,0.12);}
.acc-card-header{padding:1.2rem 1.4rem;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:12px;background:rgba(255,255,255,0.015);}
.acc-card-icon{width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.acc-card-header h3{color:var(--text);font-weight:700;font-size:0.95rem;margin:0;}
.acc-card-header span{color:var(--muted);font-size:0.78rem;margin:0;display:block;margin-top:2px;}
.acc-card-body{padding:1.4rem;}
.acc-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}
.acc-full{grid-column:1/-1;}
.acc-field{position:relative;}
.acc-label{display:block;color:#94a3b8;font-size:0.75rem;font-weight:700;margin-bottom:6px;letter-spacing:0.05em;text-transform:uppercase;}
.acc-input-wrap{position:relative;}
.acc-input-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#475569;pointer-events:none;display:flex;align-items:center;}
textarea ~ .acc-input-icon, .acc-input-icon.top{top:14px;transform:none;}
.acc-input{width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);border-radius:10px;padding:11px 14px 11px 40px;color:var(--text);font-size:0.88rem;box-sizing:border-box;outline:none;transition:all 0.2s;}
.acc-input.no-icon{padding-left:14px;}
.acc-input:focus{border-color:var(--orange);box-shadow:0 0 0 3px rgba(249,115,22,0.08);background:rgba(255,255,255,0.06);}
.acc-input::placeholder{color:#3e4a5c;}
.acc-input:disabled{background:rgba(255,255,255,0.02);color:#3e4a5c;cursor:not-allowed;border-color:rgba(255,255,255,0.05);}
textarea.acc-input{resize:vertical;min-height:64px;padding-top:11px;}
.acc-input-badge{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:rgba(34,197,94,0.1);color:#22c55e;font-size:0.65rem;font-weight:700;padding:2px 8px;border-radius:6px;text-transform:uppercase;letter-spacing:0.04em;}
.acc-btn{width:100%;background:linear-gradient(135deg,#f97316,#ea580c);color:#fff;font-weight:800;padding:14px;border-radius:14px;border:none;cursor:pointer;font-size:0.95rem;display:flex;align-items:center;justify-content:center;gap:8px;transition:all 0.2s;margin-top:0.5rem;}
.acc-btn:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(249,115,22,0.35);}
.acc-success{background:rgba(34,197,94,0.06);border:1px solid rgba(34,197,94,0.15);border-radius:12px;padding:14px 18px;margin-bottom:1rem;display:flex;align-items:center;gap:10px;}
.acc-success p{color:#22c55e;font-size:0.85rem;font-weight:600;margin:0;}
.acc-hint{color:var(--muted);font-size:0.78rem;margin:0 0 12px;display:flex;align-items:center;gap:6px;}
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
.cdd-options{max-height:200px;overflow-y:auto;}
.cdd-opt{padding:9px 14px;color:#cbd5e1;font-size:0.84rem;cursor:pointer;transition:all 0.15s;}
.cdd-opt:hover{background:rgba(249,115,22,0.1);color:var(--orange);}
.cdd-opt.active{background:rgba(249,115,22,0.15);color:var(--orange);font-weight:600;}
.cdd-divider{padding:6px 14px;color:#475569;font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;border-top:1px solid rgba(255,255,255,0.05);border-bottom:1px solid rgba(255,255,255,0.05);background:rgba(255,255,255,0.02);}
@media(max-width:500px){.acc-grid{grid-template-columns:1fr;}}
</style>
@endsection

@section('content')
<div class="ud-layout">
    @include('user.partials.sidebar', ['activePage' => 'account'])
    <div class="ud-main">

    @if(session('success'))
    <div class="acc-success">
        <svg width="20" height="20" fill="none" stroke="#22c55e" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <h1 style="color:#fff;font-size:1.4rem;font-weight:800;margin:0 0 20px;">Account Settings</h1>

    <form action="{{ route('user.account.update') }}" method="POST">
        @csrf

        <div class="acc-card">
            <div class="acc-card-header">
                <div class="acc-card-icon" style="background:linear-gradient(135deg,rgba(249,115,22,0.15),rgba(249,115,22,0.05));">
                    <svg width="20" height="20" fill="none" stroke="#f97316" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                </div>
                <div><h3>Personal Information</h3><span>Your basic details</span></div>
            </div>
            <div class="acc-card-body">
                <div class="acc-grid">
                    <div class="acc-field">
                        <label class="acc-label">Full Name</label>
                        <div class="acc-input-wrap">
                            <span class="acc-input-icon"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg></span>
                            <input type="text" name="name" value="{{ Auth::user()->name }}" required class="acc-input">
                        </div>
                    </div>
                    <div class="acc-field">
                        <label class="acc-label">Mobile Number</label>
                        <div class="acc-input-wrap">
                            <span class="acc-input-icon"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg></span>
                            <input type="text" name="mobile_number" value="{{ Auth::user()->mobile_number }}" placeholder="Enter mobile" class="acc-input">
                        </div>
                    </div>
                    <div class="acc-field acc-full">
                        <label class="acc-label">Email Address</label>
                        <div class="acc-input-wrap">
                            <span class="acc-input-icon"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg></span>
                            <input type="email" value="{{ Auth::user()->email }}" disabled class="acc-input">
                            <span class="acc-input-badge">Verified</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="acc-card">
            <div class="acc-card-header">
                <div class="acc-card-icon" style="background:linear-gradient(135deg,rgba(34,211,238,0.15),rgba(34,211,238,0.05));">
                    <svg width="20" height="20" fill="none" stroke="#22d3ee" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                </div>
                <div><h3>Delivery Address</h3><span>Used for order deliveries</span></div>
            </div>
            <div class="acc-card-body">
                <div class="acc-grid">
                    <div class="acc-field acc-full">
                        <label class="acc-label">Street Address</label>
                        <div class="acc-input-wrap">
                            <span class="acc-input-icon top"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg></span>
                            <textarea name="address" rows="2" placeholder="House no., street, locality" class="acc-input">{{ Auth::user()->address }}</textarea>
                        </div>
                    </div>
                    <div class="acc-field">
                        <label class="acc-label">State</label>
                        <input type="hidden" name="state" id="accStateHidden" value="{{ Auth::user()->state }}">
                        <div class="cdd-wrap" id="accStateDrop">
                            <div class="cdd-trigger" onclick="toggleAccDrop('accStateDrop')">
                                <span class="cdd-label{{ Auth::user()->state ? ' selected' : '' }}" id="accStateLabel">{{ Auth::user()->state ?: 'Select State' }}</span>
                                <svg class="cdd-arrow" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                            <div class="cdd-list" id="accStateList">
                                <div class="cdd-search-wrap"><input class="cdd-search" type="text" placeholder="Search state..." oninput="filterAccDrop('accStateOptions',this.value)"></div>
                                <div class="cdd-options" id="accStateOptions">
                                    @foreach(['Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Goa','Gujarat','Haryana','Himachal Pradesh','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur','Meghalaya','Mizoram','Nagaland','Odisha','Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana','Tripura','Uttar Pradesh','Uttarakhand','West Bengal'] as $st)
                                    <div class="cdd-opt{{ Auth::user()->state === $st ? ' active' : '' }}" onclick="pickAccState('{{ $st }}')">{{ $st }}</div>
                                    @endforeach
                                    <div class="cdd-divider">Union Territories</div>
                                    @foreach(['Andaman and Nicobar Islands','Chandigarh','Dadra and Nagar Haveli and Daman and Diu','Delhi','Jammu and Kashmir','Ladakh','Lakshadweep','Puducherry'] as $ut)
                                    <div class="cdd-opt{{ Auth::user()->state === $ut ? ' active' : '' }}" onclick="pickAccState('{{ $ut }}')">{{ $ut }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="acc-field">
                        <label class="acc-label">District</label>
                        <div class="acc-input-wrap">
                            <span class="acc-input-icon"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z"/></svg></span>
                            <input type="text" name="district" value="{{ Auth::user()->district }}" placeholder="Enter district" class="acc-input">
                        </div>
                    </div>
                    <div class="acc-field">
                        <label class="acc-label">City</label>
                        <div class="acc-input-wrap">
                            <span class="acc-input-icon"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg></span>
                            <input type="text" name="city" value="{{ Auth::user()->city }}" placeholder="Enter city" class="acc-input">
                        </div>
                    </div>
                    <div class="acc-field">
                        <label class="acc-label">Pincode</label>
                        <div class="acc-input-wrap">
                            <span class="acc-input-icon"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 9v.906a2.25 2.25 0 01-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 001.183 1.981l6.478 3.488m8.839 2.51l-4.66-2.51m0 0l-1.023-.55a2.25 2.25 0 00-2.134 0l-1.022.55m0 0l-4.661 2.51m16.5 1.615a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V8.844a2.25 2.25 0 011.183-1.981l7.5-4.039a2.25 2.25 0 012.134 0l7.5 4.039a2.25 2.25 0 011.183 1.98V19.5z"/></svg></span>
                            <input type="text" name="pincode" value="{{ Auth::user()->pincode }}" placeholder="6-digit pincode" maxlength="6" class="acc-input">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="acc-btn">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
            Save Changes
        </button>
    </form>

    </div>
</div>
@endsection

@section('js')
<script>
function toggleAccDrop(id){var w=document.getElementById(id);var o=w.classList.contains('open');document.querySelectorAll('.cdd-wrap.open').forEach(function(e){e.classList.remove('open')});if(!o){w.classList.add('open');var s=w.querySelector('.cdd-search');if(s){s.value='';filterAccDrop(w.querySelector('.cdd-options').id,'');s.focus();}}}
function filterAccDrop(id,q){var l=q.toLowerCase();document.querySelectorAll('#'+id+' .cdd-opt').forEach(function(e){e.style.display=e.textContent.toLowerCase().includes(l)?'':'none';});}
function pickAccState(v){document.getElementById('accStateHidden').value=v;document.getElementById('accStateLabel').textContent=v;document.getElementById('accStateLabel').classList.add('selected');document.querySelectorAll('#accStateOptions .cdd-opt').forEach(function(e){e.classList.toggle('active',e.textContent.trim()===v);});document.getElementById('accStateDrop').classList.remove('open');}
document.addEventListener('click',function(e){if(!e.target.closest('.cdd-wrap'))document.querySelectorAll('.cdd-wrap.open').forEach(function(el){el.classList.remove('open');});});
</script>
@endsection
