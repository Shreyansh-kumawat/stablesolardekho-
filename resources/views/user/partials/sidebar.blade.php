<style>
.ud-layout{min-height:100vh;display:flex;max-width:1200px;margin:0 auto;padding:24px 16px;gap:24px;}
.ud-sidebar{width:240px;flex-shrink:0;position:sticky;top:100px;align-self:flex-start;}
.ud-sidebar-card{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:16px;overflow:hidden;}
.ud-sidebar-profile{padding:20px;text-align:center;border-bottom:1px solid rgba(255,255,255,0.06);}
.ud-sidebar-avatar{width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,#f97316,#ea580c);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.4rem;font-weight:900;margin:0 auto 10px;}
.ud-sidebar-name{color:#fff;font-size:.95rem;font-weight:700;margin:0;}
.ud-sidebar-email{color:#64748b;font-size:.72rem;margin:2px 0 0;overflow:hidden;text-overflow:ellipsis;}
.ud-sidebar-nav{padding:8px;}
.ud-sidebar-nav a,.ud-sidebar-nav button{display:flex;align-items:center;gap:10px;width:100%;padding:10px 14px;border-radius:10px;color:#94a3b8;font-size:.84rem;font-weight:500;text-decoration:none;border:none;background:none;cursor:pointer;transition:all .15s;text-align:left;}
.ud-sidebar-nav a:hover,.ud-sidebar-nav button:hover{background:rgba(255,255,255,0.06);color:#e2e8f0;}
.ud-sidebar-nav a.active{background:rgba(249,115,22,0.12);color:#f97316;font-weight:600;}
.ud-sidebar-nav a i,.ud-sidebar-nav button i{width:18px;text-align:center;font-size:.85rem;}
.ud-sidebar-nav .divider{border-top:1px solid rgba(255,255,255,0.06);margin:6px 0;}
.ud-main{flex:1;min-width:0;}
@media(max-width:768px){
    .ud-layout{flex-direction:column;padding:16px;}
    .ud-sidebar{width:100%;position:static;}
    .ud-sidebar-nav{display:flex;flex-wrap:wrap;gap:4px;padding:8px;}
    .ud-sidebar-nav a,.ud-sidebar-nav button{padding:8px 12px;font-size:.78rem;}
    .ud-sidebar-nav .divider{display:none;}
    .ud-sidebar-profile{padding:14px;display:flex;align-items:center;gap:12px;text-align:left;}
    .ud-sidebar-avatar{width:40px;height:40px;font-size:1rem;margin:0;}
}
</style>

@php $activePage = $activePage ?? 'dashboard'; @endphp

<div class="ud-sidebar">
    <div class="ud-sidebar-card">
        <div class="ud-sidebar-profile">
            <div class="ud-sidebar-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div>
                <p class="ud-sidebar-name">{{ Auth::user()->name }}</p>
                <p class="ud-sidebar-email">{{ Auth::user()->email }}</p>
            </div>
        </div>
        <div class="ud-sidebar-nav">
            <a href="{{ route('user.dashboard') }}" class="{{ $activePage === 'dashboard' ? 'active' : '' }}"><i class="fas fa-th-large"></i> Dashboard</a>
            @if(auth()->user()->role_id != 4)
            <a href="{{ route('user.orders') }}" class="{{ $activePage === 'orders' ? 'active' : '' }}"><i class="fas fa-shopping-bag"></i> My Orders</a>
            @endif
            <a href="{{ route('user.referrals') }}" class="{{ $activePage === 'referrals' ? 'active' : '' }}"><i class="fas fa-share-alt"></i> My Referrals</a>
            <a href="{{ route('user.account') }}" class="{{ $activePage === 'account' ? 'active' : '' }}"><i class="fas fa-user-cog"></i> Account</a>
            <a href="{{ route('user.dashboard') }}?section=password" class="{{ $activePage === 'password' ? 'active' : '' }}"><i class="fas fa-lock"></i> Change Password</a>
            <div class="divider"></div>
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">@csrf
                <button type="submit" style="color:#f87171;"><i class="fas fa-sign-out-alt"></i> Sign Out</button>
            </form>
        </div>
    </div>
</div>
