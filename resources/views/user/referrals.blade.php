@extends('layouts.public')
@section('title', 'My Referrals')

@section('content')
<div class="ud-layout">
    @include('user.partials.sidebar', ['activePage' => 'referrals'])
    <div class="ud-main">

    <h1 style="color:#fff;font-size:1.4rem;font-weight:800;margin:0 0 4px;">My Referrals</h1>
    <p style="color:#94a3b8;font-size:.85rem;margin-bottom:24px;">Share your code, earn cashback on every successful installation.</p>

    <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:14px;padding:24px;margin-bottom:24px;">
        @if($referralCode)
        <div style="display:flex;flex-wrap:wrap;align-items:center;gap:16px;justify-content:space-between;">
            <div>
                <p style="color:#94a3b8;font-size:.8rem;text-transform:uppercase;letter-spacing:.5px;margin:0 0 6px;">Your Referral Code</p>
                <span style="font-size:1.5rem;font-weight:800;color:#f97316;letter-spacing:2px;">{{ $referralCode->code }}</span>
            </div>
            <div style="flex:1;min-width:250px;">
                <p style="color:#94a3b8;font-size:.8rem;margin:0 0 6px;">Share this link</p>
                <div style="display:flex;gap:8px;">
                    <input type="text" readonly value="{{ url('/refer/'.$referralCode->code) }}" id="refLink"
                           style="flex:1;padding:10px 12px;background:rgba(0,0,0,.3);border:1px solid rgba(255,255,255,.1);border-radius:8px;color:#fff;font-size:.85rem;">
                    <button onclick="document.getElementById('refLink').select();document.execCommand('copy');this.textContent='Copied!';setTimeout(()=>this.textContent='Copy',1500)"
                            style="padding:10px 18px;background:#f97316;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;font-size:.85rem;">Copy</button>
                </div>
            </div>
        </div>
        @else
        <p style="color:#94a3b8;margin:0;">You don't have a referral code yet. It will be generated after your installation is complete.</p>
        @endif
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:28px;">
        <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:12px;padding:18px;text-align:center;">
            <p style="color:#94a3b8;font-size:.75rem;text-transform:uppercase;margin:0 0 4px;">Total Referrals</p>
            <p style="color:#fff;font-size:1.5rem;font-weight:800;margin:0;">{{ $leads->count() }}</p>
        </div>
        <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:12px;padding:18px;text-align:center;">
            <p style="color:#94a3b8;font-size:.75rem;text-transform:uppercase;margin:0 0 4px;">Successful</p>
            <p style="color:#10b981;font-size:1.5rem;font-weight:800;margin:0;">{{ $leads->whereIn('status',['payment_done','cashback_approved'])->count() }}</p>
        </div>
        <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:12px;padding:18px;text-align:center;">
            <p style="color:#94a3b8;font-size:.75rem;text-transform:uppercase;margin:0 0 4px;">Total Earned</p>
            <p style="color:#10b981;font-size:1.5rem;font-weight:800;margin:0;">₹{{ number_format($totalEarned) }}</p>
        </div>
        <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:12px;padding:18px;text-align:center;">
            <p style="color:#94a3b8;font-size:.75rem;text-transform:uppercase;margin:0 0 4px;">Pending</p>
            <p style="color:#f59e0b;font-size:1.5rem;font-weight:800;margin:0;">₹{{ number_format($pendingAmount) }}</p>
        </div>
    </div>

    <h2 style="color:#fff;font-size:1.1rem;font-weight:700;margin-bottom:12px;">Your Referral Leads</h2>
    <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:14px;overflow:hidden;margin-bottom:28px;">
        @forelse($leads as $lead)
        <div style="padding:16px 20px;border-bottom:1px solid rgba(255,255,255,0.04);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
            <div>
                <p style="color:#fff;font-weight:600;margin:0;">{{ $lead->name }}</p>
                <p style="color:#64748b;font-size:.8rem;margin:2px 0 0;">{{ $lead->city ?? '' }} {{ $lead->state ? '/ '.$lead->state : '' }} · {{ $lead->created_at->format('d M Y') }}</p>
            </div>
            <div>
                @php $c=['pending'=>'#64748b','contacted'=>'#3b82f6','installed'=>'#8b5cf6','payment_done'=>'#f59e0b','cashback_approved'=>'#10b981','rejected'=>'#ef4444']; @endphp
                <span style="background:{{ $c[$lead->status]??'#64748b' }};color:#fff;font-size:.75rem;font-weight:600;padding:4px 12px;border-radius:20px;">{{ ucwords(str_replace('_',' ',$lead->status)) }}</span>
            </div>
        </div>
        @empty
        <p style="color:#64748b;text-align:center;padding:32px;margin:0;">No referrals yet. Share your link to get started!</p>
        @endforelse
    </div>

    @if($cashbacks->count())
    <h2 style="color:#fff;font-size:1.1rem;font-weight:700;margin-bottom:12px;">Cashback History</h2>
    <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:14px;overflow:hidden;">
        @foreach($cashbacks as $cb)
        <div style="padding:16px 20px;border-bottom:1px solid rgba(255,255,255,0.04);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
            <div>
                <p style="color:#fff;font-weight:600;margin:0;">{{ $cb->lead->name ?? 'N/A' }}</p>
                <p style="color:#64748b;font-size:.8rem;margin:2px 0 0;">{{ $cb->cashback_percentage }}% of ₹{{ number_format($cb->deal_amount) }}</p>
            </div>
            <div style="text-align:right;">
                <p style="color:#10b981;font-weight:700;font-size:1.1rem;margin:0;">₹{{ number_format($cb->cashback_amount) }}</p>
                @php $cc=['pending'=>'#f59e0b','approved'=>'#3b82f6','paid'=>'#10b981','rejected'=>'#ef4444']; @endphp
                <span style="color:{{ $cc[$cb->status]??'#64748b' }};font-size:.75rem;font-weight:600;">{{ ucfirst($cb->status) }}@if($cb->paid_at) · {{ $cb->paid_at->format('d M') }}@endif</span>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    </div>
</div>
@endsection
