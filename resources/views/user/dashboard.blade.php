@extends('layouts.public')
@section('title', 'My Dashboard')

@section('css')
<style>
.ud-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;}
.ud-stat{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:14px;padding:16px;text-align:center;}
.ud-stat .val{font-size:1.4rem;font-weight:800;color:#fff;margin:0;}
.ud-stat .lbl{font-size:.7rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin:4px 0 0;}
.ud-card{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:14px;overflow:hidden;margin-bottom:20px;}
.ud-card-hdr{padding:14px 18px;border-bottom:1px solid rgba(255,255,255,0.06);display:flex;justify-content:space-between;align-items:center;}
.ud-card-hdr h3{color:#e2e8f0;font-size:.9rem;font-weight:700;margin:0;}
.ud-card-hdr a{color:#f97316;font-size:.78rem;font-weight:600;text-decoration:none;}
.ud-card-body{padding:14px 18px;}
.ud-list{list-style:none;padding:0;margin:0;}
.ud-list li{padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.04);display:flex;justify-content:space-between;align-items:center;}
.ud-list li:last-child{border-bottom:none;}
.ud-list .item-main{font-size:.85rem;color:#e2e8f0;font-weight:600;}
.ud-list .item-sub{font-size:.75rem;color:#64748b;}
.ud-badge{display:inline-block;padding:2px 8px;border-radius:12px;font-size:.7rem;font-weight:600;}
.ud-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;}
.ud-ref-box{background:linear-gradient(135deg,rgba(249,115,22,0.08),rgba(234,88,12,0.04));border:1px solid rgba(249,115,22,0.2);border-radius:14px;padding:20px;text-align:center;margin-bottom:24px;}
.ud-ref-box .code{font-size:1.3rem;font-weight:800;color:#f97316;letter-spacing:2px;margin:6px 0;}
.ud-ref-box .link-row{display:flex;gap:8px;justify-content:center;align-items:center;margin-top:10px;}
.ud-ref-box input{background:rgba(0,0,0,.3);border:1px solid rgba(255,255,255,.1);border-radius:8px;padding:8px 12px;color:#e2e8f0;font-size:.82rem;width:280px;text-align:center;}
.ud-ref-box button{background:#f97316;color:#fff;border:none;border-radius:8px;padding:8px 14px;font-weight:600;cursor:pointer;font-size:.82rem;}
.ud-empty{text-align:center;padding:24px;color:#475569;font-size:.85rem;}
.ud-section{display:none;}
.ud-section.active{display:block;}
.ud-pwd-step{display:none;}
.ud-pwd-step.active{display:block;}
.ud-form-group{margin-bottom:14px;}
.ud-form-group label{display:block;color:#94a3b8;font-size:.8rem;font-weight:600;margin-bottom:4px;}
.ud-form-group input{width:100%;padding:10px 14px;background:rgba(0,0,0,.3);border:1px solid rgba(255,255,255,.1);border-radius:10px;color:#e2e8f0;font-size:.9rem;box-sizing:border-box;}
.ud-form-group input:focus{outline:none;border-color:#f97316;}
.ud-btn{padding:10px 20px;border:none;border-radius:10px;font-weight:600;cursor:pointer;font-size:.85rem;}
.ud-btn-orange{background:#f97316;color:#fff;}
.ud-btn-orange:hover{background:#ea580c;}
.ud-btn-orange:disabled{opacity:.5;cursor:not-allowed;}
.ud-msg{padding:10px 14px;border-radius:10px;font-size:.82rem;font-weight:600;margin-bottom:14px;display:none;}
.ud-msg.success{display:block;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);color:#10b981;}
.ud-msg.error{display:block;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:#ef4444;}
@media(max-width:768px){
    .ud-stats{grid-template-columns:repeat(2,1fr);}
    .ud-grid{grid-template-columns:1fr;}
    .ud-ref-box input{width:100%;}
    .ud-ref-box .link-row{flex-direction:column;}
}
</style>
@endsection

@section('content')
<div class="ud-layout">
    @include('user.partials.sidebar', ['activePage' => 'dashboard'])

    <div class="ud-main">
        {{-- OVERVIEW SECTION --}}
        <div class="ud-section active" id="sec-overview">
            <h1 style="color:#fff;font-size:1.4rem;font-weight:800;margin:0 0 20px;">Welcome back, {{ $user->name }}!</h1>

            <div class="ud-stats">
                <div class="ud-stat"><p class="val">{{ $totalOrders }}</p><p class="lbl">Orders</p></div>
                <div class="ud-stat"><p class="val">{{ $totalReferrals }}</p><p class="lbl">Referrals</p></div>
                <div class="ud-stat"><p class="val" style="color:#10b981;">₹{{ number_format($totalEarned) }}</p><p class="lbl">Earned</p></div>
                <div class="ud-stat"><p class="val" style="color:#f59e0b;">₹{{ number_format($pendingCashback) }}</p><p class="lbl">Pending</p></div>
            </div>

            @if($referralCode)
            <div class="ud-ref-box">
                <p style="color:#94a3b8;font-size:.8rem;margin:0;">Your Referral Code</p>
                <p class="code">{{ $referralCode->code }}</p>
                <div class="link-row">
                    <input type="text" id="refLink" readonly value="{{ url('/refer/'.$referralCode->code) }}">
                    <button onclick="document.getElementById('refLink').select();document.execCommand('copy');this.textContent='Copied!';var b=this;setTimeout(function(){b.textContent='Copy Link';},2000);">Copy Link</button>
                </div>
            </div>

            {{-- Slab Info --}}
            <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:14px;padding:18px 20px;margin-bottom:24px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:14px;">
                    <div>
                        <p style="color:#94a3b8;font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;margin:0 0 4px;">Your Cashback Slab</p>
                        <p style="color:#fff;font-size:1rem;font-weight:700;margin:0;">{{ $successfulReferrals }} successful referral{{ $successfulReferrals != 1 ? 's' : '' }} &mdash; <span style="color:#f97316;">{{ $currentSlabPct }}%</span> cashback on next referral</p>
                    </div>
                </div>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    @foreach($slabs as $slab)
                    @php $isActive = ($successfulReferrals + 1) >= $slab['min'] && ($successfulReferrals + 1) <= $slab['max']; @endphp
                    <div style="flex:1;min-width:100px;padding:10px 12px;border-radius:10px;text-align:center;border:1px solid {{ $isActive ? 'rgba(249,115,22,0.4)' : 'rgba(255,255,255,0.07)' }};background:{{ $isActive ? 'rgba(249,115,22,0.1)' : 'rgba(255,255,255,0.03)' }};">
                        <p style="font-size:1.1rem;font-weight:800;color:{{ $isActive ? '#f97316' : '#64748b' }};margin:0;">{{ $slab['percentage'] }}%</p>
                        <p style="font-size:.7rem;color:#64748b;margin:2px 0 0;">{{ $slab['min'] }}–{{ $slab['max'] >= 999 ? '∞' : $slab['max'] }} referrals</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:14px;padding:16px;text-align:center;margin-bottom:24px;">
                <p style="color:#64748b;font-size:.85rem;margin:0;"><i class="fas fa-info-circle" style="margin-right:6px;"></i> Your referral code will be generated once your solar installation is delivered.</p>
            </div>
            @endif

            <div class="ud-grid">
                <div class="ud-card">
                    <div class="ud-card-hdr"><h3><i class="fas fa-shopping-bag" style="color:#f97316;margin-right:6px;"></i> Recent Orders</h3><a href="{{ route('user.orders') }}">View All →</a></div>
                    <div class="ud-card-body">
                        @if($orders->count())
                        <ul class="ud-list">
                            @foreach($orders as $order)
                            <li>
                                <div><div class="item-main">{{ $order->order_number }}</div><div class="item-sub">{{ $order->created_at->format('d M Y') }} · ₹{{ number_format($order->total_amount) }}</div></div>
                                @php $oc=['pending'=>['#fef3c7','#b45309'],'confirmed'=>['#dbeafe','#1d4ed8'],'shipped'=>['#ede9fe','#6d28d9'],'delivered'=>['#d1fae5','#047857'],'cancelled'=>['#fee2e2','#b91c1c']]; $c=$oc[$order->status]??['#f3f4f6','#64748b']; @endphp
                                <span class="ud-badge" style="background:{{ $c[0] }};color:{{ $c[1] }};">{{ ucfirst($order->status) }}</span>
                            </li>
                            @endforeach
                        </ul>
                        @else<div class="ud-empty">No orders yet</div>@endif
                    </div>
                </div>
                <div class="ud-card">
                    <div class="ud-card-hdr"><h3><i class="fas fa-users" style="color:#f97316;margin-right:6px;"></i> Recent Referrals</h3><a href="{{ route('user.referrals') }}">View All →</a></div>
                    <div class="ud-card-body">
                        @if($referralLeads->count())
                        <ul class="ud-list">
                            @foreach($referralLeads as $lead)
                            <li>
                                <div><div class="item-main">{{ $lead->name }}</div><div class="item-sub">{{ $lead->city ?? $lead->phone }} · {{ $lead->created_at->format('d M Y') }}</div></div>
                                @php $sc=['pending'=>['#f3f4f6','#4b5563'],'contacted'=>['#dbeafe','#1d4ed8'],'installed'=>['#ede9fe','#6d28d9'],'payment_done'=>['#fef3c7','#b45309'],'cashback_approved'=>['#d1fae5','#047857'],'rejected'=>['#fee2e2','#b91c1c']]; $s=$sc[$lead->status]??['#f3f4f6','#4b5563']; @endphp
                                <span class="ud-badge" style="background:{{ $s[0] }};color:{{ $s[1] }};">{{ ucwords(str_replace('_',' ',$lead->status)) }}</span>
                            </li>
                            @endforeach
                        </ul>
                        @else<div class="ud-empty">No referrals yet</div>@endif
                    </div>
                </div>
            </div>
        </div>

        {{-- CHANGE PASSWORD SECTION --}}
        <div class="ud-section" id="sec-password">
            <h1 style="color:#fff;font-size:1.4rem;font-weight:800;margin:0 0 8px;">Change Password</h1>
            <p style="color:#64748b;font-size:.85rem;margin:0 0 24px;">We'll send a verification code to your email for security.</p>

            <div class="ud-card" style="max-width:440px;">
                <div class="ud-card-body" style="padding:24px;">
                    <div id="pwdMsg" class="ud-msg"></div>
                    <div class="ud-pwd-step active" id="pwd-step1">
                        <p style="color:#94a3b8;font-size:.85rem;margin:0 0 14px;">A 6-digit code will be sent to <strong style="color:#e2e8f0;">{{ $user->email }}</strong></p>
                        <button class="ud-btn ud-btn-orange" id="sendOtpBtn" onclick="sendOtp()"><i class="fas fa-paper-plane" style="margin-right:6px;"></i> Send Verification Code</button>
                    </div>
                    <div class="ud-pwd-step" id="pwd-step2">
                        <div class="ud-form-group">
                            <label>Enter 6-digit code sent to your email</label>
                            <input type="text" id="otpInput" maxlength="6" placeholder="000000" style="letter-spacing:8px;text-align:center;font-size:1.2rem;font-weight:700;">
                        </div>
                        <div style="display:flex;gap:10px;">
                            <button class="ud-btn ud-btn-orange" onclick="verifyOtp()">Verify Code</button>
                            <button class="ud-btn" style="background:rgba(255,255,255,0.06);color:#94a3b8;" onclick="sendOtp()">Resend</button>
                        </div>
                    </div>
                    <div class="ud-pwd-step" id="pwd-step3">
                        <div class="ud-form-group">
                            <label>New Password</label>
                            <input type="password" id="newPwd" placeholder="Min 8 characters">
                        </div>
                        <div class="ud-form-group">
                            <label>Confirm Password</label>
                            <input type="password" id="confirmPwd" placeholder="Re-enter password">
                        </div>
                        <button class="ud-btn ud-btn-orange" onclick="changePassword()"><i class="fas fa-check" style="margin-right:6px;"></i> Update Password</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    var params=new URLSearchParams(window.location.search);
    if(params.get('section')==='password'){showSection('password');}
})();
function showSection(name){
    document.querySelectorAll('.ud-section').forEach(function(s){s.classList.remove('active');});
    document.getElementById('sec-'+name).classList.add('active');
    document.getElementById('pwdMsg').className='ud-msg';document.getElementById('pwdMsg').style.display='none';
}
function showMsg(msg,type){
    var el=document.getElementById('pwdMsg');el.textContent=msg;el.className='ud-msg '+type;el.style.display='block';
}
function sendOtp(){
    var btn=document.getElementById('sendOtpBtn');if(btn)btn.disabled=true;
    fetch('{{ route("user.password.sendOtp") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}})
    .then(function(r){return r.json();}).then(function(d){
        if(d.success){showMsg('Code sent to your email!','success');document.querySelectorAll('.ud-pwd-step').forEach(function(s){s.classList.remove('active');});document.getElementById('pwd-step2').classList.add('active');}
        else{showMsg(d.error||'Failed to send code','error');if(btn)btn.disabled=false;}
    }).catch(function(){showMsg('Something went wrong','error');if(btn)btn.disabled=false;});
}
function verifyOtp(){
    var otp=document.getElementById('otpInput').value;
    if(otp.length!==6){showMsg('Enter a 6-digit code','error');return;}
    fetch('{{ route("user.password.verifyOtp") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({otp:otp})})
    .then(function(r){return r.json();}).then(function(d){
        if(d.success){showMsg('Code verified!','success');document.querySelectorAll('.ud-pwd-step').forEach(function(s){s.classList.remove('active');});document.getElementById('pwd-step3').classList.add('active');}
        else{showMsg(d.error||'Invalid code','error');}
    }).catch(function(){showMsg('Something went wrong','error');});
}
function changePassword(){
    var np=document.getElementById('newPwd').value,cp=document.getElementById('confirmPwd').value;
    if(np.length<8){showMsg('Password must be at least 8 characters','error');return;}
    if(np!==cp){showMsg('Passwords do not match','error');return;}
    fetch('{{ route("user.password.change") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({password:np,password_confirmation:cp})})
    .then(function(r){return r.json();}).then(function(d){
        if(d.success){showMsg('Password changed successfully!','success');document.querySelectorAll('.ud-pwd-step').forEach(function(s){s.classList.remove('active');});document.getElementById('pwd-step1').classList.add('active');document.getElementById('sendOtpBtn').disabled=false;}
        else{showMsg(d.error||'Failed to change password','error');}
    }).catch(function(){showMsg('Something went wrong','error');});
}
</script>
@endsection
