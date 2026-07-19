@extends('layouts.public')
@section('title', 'My Dashboard')

@section('css')
<style>
.udash{min-height:100vh;padding:40px 0;}
.udash-wrap{max-width:1000px;margin:0 auto;padding:0 16px;}
.udash h1{color:#fff;font-size:1.75rem;font-weight:800;margin:0 0 4px;}
.udash .subtitle{color:#94a3b8;font-size:.95rem;margin-bottom:28px;}
.udash-greeting{background:linear-gradient(135deg,rgba(249,115,22,0.12),rgba(99,102,241,0.08));border:1px solid rgba(249,115,22,0.15);border-radius:16px;padding:24px;margin-bottom:24px;display:flex;align-items:center;gap:16px;}
.udash-avatar{width:56px;height:56px;border-radius:14px;background:linear-gradient(135deg,#f97316,#ea580c);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.4rem;font-weight:900;flex-shrink:0;}
.udash-greeting h2{color:#fff;font-size:1.2rem;font-weight:700;margin:0 0 2px;}
.udash-greeting p{color:#94a3b8;font-size:.85rem;margin:0;}
.udash-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:28px;}
.udash-stat{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:14px;padding:18px;text-align:center;}
.udash-stat .val{font-size:1.5rem;font-weight:800;color:#fff;margin:0;}
.udash-stat .lbl{font-size:.72rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin:4px 0 0;}
.udash-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:28px;}
.udash-card{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:14px;overflow:hidden;}
.udash-card-hdr{padding:14px 18px;border-bottom:1px solid rgba(255,255,255,0.06);display:flex;justify-content:space-between;align-items:center;}
.udash-card-hdr h3{color:#e2e8f0;font-size:.9rem;font-weight:700;margin:0;}
.udash-card-hdr a{color:#f97316;font-size:.78rem;font-weight:600;text-decoration:none;}
.udash-card-hdr a:hover{text-decoration:underline;}
.udash-card-body{padding:14px 18px;}
.udash-list{list-style:none;padding:0;margin:0;}
.udash-list li{padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.04);display:flex;justify-content:space-between;align-items:center;}
.udash-list li:last-child{border-bottom:none;}
.udash-list .item-main{font-size:.85rem;color:#e2e8f0;font-weight:600;}
.udash-list .item-sub{font-size:.75rem;color:#64748b;}
.udash-badge{display:inline-block;padding:2px 8px;border-radius:12px;font-size:.7rem;font-weight:600;}
.udash-referral-box{background:linear-gradient(135deg,rgba(249,115,22,0.08),rgba(234,88,12,0.04));border:1px solid rgba(249,115,22,0.2);border-radius:14px;padding:20px;text-align:center;}
.udash-referral-box .code{font-size:1.3rem;font-weight:800;color:#f97316;letter-spacing:2px;margin:8px 0;}
.udash-referral-box .link-row{display:flex;gap:8px;justify-content:center;align-items:center;margin-top:10px;}
.udash-referral-box input{background:rgba(0,0,0,.3);border:1px solid rgba(255,255,255,.1);border-radius:8px;padding:8px 12px;color:#e2e8f0;font-size:.82rem;width:300px;text-align:center;}
.udash-referral-box button{background:#f97316;color:#fff;border:none;border-radius:8px;padding:8px 14px;font-weight:600;cursor:pointer;font-size:.82rem;}
.udash-empty{text-align:center;padding:24px;color:#475569;font-size:.85rem;}
.udash-quick-links{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;}
.udash-qlink{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:12px;padding:16px;text-align:center;text-decoration:none;transition:all .2s;}
.udash-qlink:hover{background:rgba(249,115,22,0.08);border-color:rgba(249,115,22,0.2);transform:translateY(-2px);}
.udash-qlink i{font-size:1.3rem;color:#f97316;display:block;margin-bottom:8px;}
.udash-qlink span{color:#e2e8f0;font-size:.82rem;font-weight:600;}
@media(max-width:768px){
    .udash-stats{grid-template-columns:repeat(2,1fr);}
    .udash-grid{grid-template-columns:1fr;}
    .udash-quick-links{grid-template-columns:repeat(2,1fr);}
    .udash-referral-box input{width:100%;}
    .udash-referral-box .link-row{flex-direction:column;}
}
</style>
@endsection

@section('content')
<div class="udash">
<div class="udash-wrap">

    <div class="udash-greeting">
        <div class="udash-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <div>
            <h2>Welcome back, {{ $user->name }}!</h2>
            <p>Here's your activity overview</p>
        </div>
    </div>

    <div class="udash-stats">
        <div class="udash-stat">
            <p class="val">{{ $totalOrders }}</p>
            <p class="lbl">Total Orders</p>
        </div>
        <div class="udash-stat">
            <p class="val">{{ $totalReferrals }}</p>
            <p class="lbl">Referrals</p>
        </div>
        <div class="udash-stat">
            <p class="val" style="color:#10b981;">₹{{ number_format($totalEarned) }}</p>
            <p class="lbl">Earned</p>
        </div>
        <div class="udash-stat">
            <p class="val" style="color:#f59e0b;">₹{{ number_format($pendingCashback) }}</p>
            <p class="lbl">Pending</p>
        </div>
    </div>

    @if($referralCode)
    <div class="udash-referral-box">
        <p style="color:#94a3b8;font-size:.8rem;margin:0;">Your Referral Code</p>
        <p class="code">{{ $referralCode->code }}</p>
        <div class="link-row">
            <input type="text" id="refLink" readonly value="{{ url('/refer/'.$referralCode->code) }}">
            <button onclick="document.getElementById('refLink').select();document.execCommand('copy');this.textContent='Copied!';var b=this;setTimeout(function(){b.textContent='Copy Link';},2000);">Copy Link</button>
        </div>
    </div>
    @else
    <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:14px;padding:20px;text-align:center;margin-bottom:24px;">
        <p style="color:#64748b;font-size:.85rem;margin:0;"><i class="fas fa-info-circle" style="margin-right:6px;"></i> Your referral code will be generated once your solar installation is delivered.</p>
    </div>
    @endif

    <div class="udash-grid" style="margin-top:24px;">
        <div class="udash-card">
            <div class="udash-card-hdr">
                <h3><i class="fas fa-shopping-bag" style="color:#f97316;margin-right:6px;"></i> Recent Orders</h3>
                <a href="{{ route('user.orders') }}">View All →</a>
            </div>
            <div class="udash-card-body">
                @if($orders->count())
                <ul class="udash-list">
                    @foreach($orders as $order)
                    <li>
                        <div>
                            <div class="item-main">{{ $order->order_number }}</div>
                            <div class="item-sub">{{ $order->created_at->format('d M Y') }} &middot; ₹{{ number_format($order->total_amount) }}</div>
                        </div>
                        @php $oc=['pending'=>['#fef3c7','#b45309'],'confirmed'=>['#dbeafe','#1d4ed8'],'shipped'=>['#ede9fe','#6d28d9'],'delivered'=>['#d1fae5','#047857'],'cancelled'=>['#fee2e2','#b91c1c']]; $c=$oc[$order->status]??['#f3f4f6','#64748b']; @endphp
                        <span class="udash-badge" style="background:{{ $c[0] }};color:{{ $c[1] }};">{{ ucfirst($order->status) }}</span>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="udash-empty">No orders yet</div>
                @endif
            </div>
        </div>

        <div class="udash-card">
            <div class="udash-card-hdr">
                <h3><i class="fas fa-users" style="color:#f97316;margin-right:6px;"></i> Recent Referrals</h3>
                <a href="{{ route('user.referrals') }}">View All →</a>
            </div>
            <div class="udash-card-body">
                @if($referralLeads->count())
                <ul class="udash-list">
                    @foreach($referralLeads as $lead)
                    <li>
                        <div>
                            <div class="item-main">{{ $lead->name }}</div>
                            <div class="item-sub">{{ $lead->city ?? $lead->phone }} &middot; {{ $lead->created_at->format('d M Y') }}</div>
                        </div>
                        @php $sc=['pending'=>['#f3f4f6','#4b5563'],'contacted'=>['#dbeafe','#1d4ed8'],'installed'=>['#ede9fe','#6d28d9'],'payment_done'=>['#fef3c7','#b45309'],'cashback_approved'=>['#d1fae5','#047857'],'rejected'=>['#fee2e2','#b91c1c']]; $s=$sc[$lead->status]??['#f3f4f6','#4b5563']; @endphp
                        <span class="udash-badge" style="background:{{ $s[0] }};color:{{ $s[1] }};">{{ ucwords(str_replace('_',' ',$lead->status)) }}</span>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="udash-empty">No referrals yet</div>
                @endif
            </div>
        </div>
    </div>

    <div style="margin-top:28px;">
        <h3 style="color:#e2e8f0;font-size:.95rem;font-weight:700;margin-bottom:14px;">Quick Links</h3>
        <div class="udash-quick-links">
            <a href="{{ route('user.orders') }}" class="udash-qlink">
                <i class="fas fa-shopping-bag"></i>
                <span>My Orders</span>
            </a>
            <a href="{{ route('user.referrals') }}" class="udash-qlink">
                <i class="fas fa-share-alt"></i>
                <span>My Referrals</span>
            </a>
            <a href="{{ route('user.account') }}" class="udash-qlink">
                <i class="fas fa-user-cog"></i>
                <span>Account</span>
            </a>
            <a href="{{ route('contactUs') }}" class="udash-qlink">
                <i class="fas fa-headset"></i>
                <span>Support</span>
            </a>
        </div>
    </div>

</div>
</div>
@endsection
