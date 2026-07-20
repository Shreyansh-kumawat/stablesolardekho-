@extends('layouts.adminLayout')
@section('title', 'My Referrals')

@section('css')
<style>
    .cpr-wrap { max-width: 900px; margin: 0 auto; padding: 1.5rem 1rem; }
    .cpr-header { margin-bottom: 1.5rem; display: flex; align-items: center; gap: .75rem; }
    .cpr-icon-box { width: 42px; height: 42px; background: linear-gradient(135deg,#2563eb,#7c3aed); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; flex-shrink: 0; }
    .cpr-header h1 { font-size: 1.3rem; font-weight: 800; color: #1f2937; margin: 0; }
    .cpr-header p { font-size: .8rem; color: #6b7280; margin: .15rem 0 0; }

    .cpr-code-card { background: linear-gradient(135deg,#1e3a5f,#2563eb); border-radius: 14px; padding: 24px; margin-bottom: 20px; color: #fff; }
    .cpr-code-label { font-size: .75rem; text-transform: uppercase; letter-spacing: .5px; color: rgba(255,255,255,.7); margin: 0 0 6px; }
    .cpr-code-value { font-size: 1.6rem; font-weight: 800; letter-spacing: 3px; color: #fbbf24; margin: 0 0 14px; }
    .cpr-link-row { display: flex; gap: 8px; }
    .cpr-link-input { flex: 1; padding: 10px 12px; background: rgba(0,0,0,.25); border: 1px solid rgba(255,255,255,.2); border-radius: 8px; color: #fff; font-size: .82rem; outline: none; }
    .cpr-copy-btn { padding: 10px 20px; background: #fbbf24; color: #1e3a5f; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: .82rem; white-space: nowrap; }
    .cpr-copy-btn:hover { background: #f59e0b; }
    .cpr-slab-badge { display: inline-block; margin-top: 10px; padding: 4px 14px; background: rgba(255,255,255,.15); border-radius: 20px; font-size: .78rem; font-weight: 600; }
    .cpr-no-code { color: rgba(255,255,255,.8); font-size: .9rem; margin: 0; }

    .cpr-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 12px; margin-bottom: 24px; }
    .cpr-stat { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; text-align: center; }
    .cpr-stat-label { font-size: .7rem; text-transform: uppercase; letter-spacing: .04em; color: #9ca3af; margin: 0 0 4px; font-weight: 600; }
    .cpr-stat-value { font-size: 1.4rem; font-weight: 800; color: #1f2937; margin: 0; }
    .cpr-stat-value.green { color: #10b981; }
    .cpr-stat-value.amber { color: #f59e0b; }

    .cpr-section-title { font-size: 1rem; font-weight: 700; color: #1f2937; margin: 0 0 10px; }
    .cpr-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; margin-bottom: 24px; }
    .cpr-lead-row { padding: 14px 18px; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; }
    .cpr-lead-row:last-child { border-bottom: none; }
    .cpr-lead-name { font-weight: 600; color: #1f2937; font-size: .9rem; margin: 0; }
    .cpr-lead-meta { color: #9ca3af; font-size: .78rem; margin: 2px 0 0; }
    .cpr-pill { display: inline-block; padding: 3px 12px; border-radius: 20px; font-size: .72rem; font-weight: 600; }
    .cpr-pill-pending { background: #f3f4f6; color: #6b7280; }
    .cpr-pill-contacted { background: #dbeafe; color: #1e40af; }
    .cpr-pill-installed { background: #ede9fe; color: #6d28d9; }
    .cpr-pill-payment { background: #fef3c7; color: #92400e; }
    .cpr-pill-approved { background: #dcfce7; color: #166534; }
    .cpr-pill-rejected { background: #fee2e2; color: #991b1b; }

    .cpr-cb-row { padding: 14px 18px; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; }
    .cpr-cb-row:last-child { border-bottom: none; }
    .cpr-cb-amount { font-size: 1.05rem; font-weight: 700; color: #10b981; }
    .cpr-cb-detail { color: #9ca3af; font-size: .78rem; }
    .cpr-cb-status { font-size: .75rem; font-weight: 600; }

    .cpr-empty { text-align: center; padding: 32px; color: #9ca3af; font-size: .9rem; }

    @media (max-width: 640px) {
        .cpr-wrap { padding: 12px; }
        .cpr-link-row { flex-direction: column; }
        .cpr-stats { grid-template-columns: repeat(2, 1fr); }
    }
</style>
@endsection

@section('content')
<div class="cpr-wrap">
    <div class="cpr-header">
        <div class="cpr-icon-box">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
            </svg>
        </div>
        <div>
            <h1>My Referrals</h1>
            <p>Share your code, earn cashback on every successful installation</p>
        </div>
    </div>

    <div class="cpr-code-card">
        @if($referralCode)
        <p class="cpr-code-label">Your Referral Code</p>
        <p class="cpr-code-value">{{ $referralCode->code }}</p>
        <p class="cpr-code-label">Share this link</p>
        <div class="cpr-link-row">
            <input type="text" readonly value="{{ url('/refer/'.$referralCode->code) }}" id="cpRefLink" class="cpr-link-input">
            <button onclick="document.getElementById('cpRefLink').select();document.execCommand('copy');this.textContent='Copied!';setTimeout(()=>this.textContent='Copy',1500)" class="cpr-copy-btn">Copy</button>
        </div>
        <span class="cpr-slab-badge">Your current cashback rate: {{ $currentSlab }}%</span>
        @else
        <p class="cpr-no-code">You don't have a referral code yet. Contact admin to get your referral code generated.</p>
        @endif
    </div>

    <div class="cpr-stats">
        <div class="cpr-stat">
            <p class="cpr-stat-label">Total Referrals</p>
            <p class="cpr-stat-value">{{ $leads->count() }}</p>
        </div>
        <div class="cpr-stat">
            <p class="cpr-stat-label">Successful</p>
            <p class="cpr-stat-value green">{{ $leads->whereIn('status',['payment_done','cashback_approved'])->count() }}</p>
        </div>
        <div class="cpr-stat">
            <p class="cpr-stat-label">Total Earned</p>
            <p class="cpr-stat-value green">&#8377;{{ number_format($totalEarned) }}</p>
        </div>
        <div class="cpr-stat">
            <p class="cpr-stat-label">Pending</p>
            <p class="cpr-stat-value amber">&#8377;{{ number_format($pendingAmount) }}</p>
        </div>
    </div>

    <h2 class="cpr-section-title">Your Referral Leads</h2>
    <div class="cpr-card">
        @forelse($leads as $lead)
        <div class="cpr-lead-row">
            <div>
                <p class="cpr-lead-name">{{ $lead->name }}</p>
                <p class="cpr-lead-meta">{{ $lead->city ?? '' }}{{ $lead->state ? ' / '.$lead->state : '' }} &middot; {{ $lead->created_at->format('d M Y') }}</p>
            </div>
            <div>
                @php
                    $statusMap = [
                        'pending' => 'cpr-pill-pending',
                        'contacted' => 'cpr-pill-contacted',
                        'installed' => 'cpr-pill-installed',
                        'payment_done' => 'cpr-pill-payment',
                        'cashback_approved' => 'cpr-pill-approved',
                        'rejected' => 'cpr-pill-rejected',
                    ];
                @endphp
                <span class="cpr-pill {{ $statusMap[$lead->status] ?? 'cpr-pill-pending' }}">{{ ucwords(str_replace('_',' ',$lead->status)) }}</span>
            </div>
        </div>
        @empty
        <p class="cpr-empty">No referrals yet. Share your link to get started!</p>
        @endforelse
    </div>

    @if($cashbacks->count())
    <h2 class="cpr-section-title">Cashback History</h2>
    <div class="cpr-card">
        @foreach($cashbacks as $cb)
        <div class="cpr-cb-row">
            <div>
                <p style="font-weight:600;color:#1f2937;margin:0;font-size:.9rem;">{{ $cb->lead->name ?? 'N/A' }}</p>
                <p class="cpr-cb-detail">{{ $cb->cashback_percentage }}% of &#8377;{{ number_format($cb->deal_amount) }}</p>
            </div>
            <div style="text-align:right;">
                <p class="cpr-cb-amount">&#8377;{{ number_format($cb->cashback_amount) }}</p>
                @php
                    $cbColors = ['pending'=>'#f59e0b','approved'=>'#3b82f6','paid'=>'#10b981','rejected'=>'#ef4444'];
                @endphp
                <span class="cpr-cb-status" style="color:{{ $cbColors[$cb->status] ?? '#6b7280' }}">{{ ucfirst($cb->status) }}@if($cb->paid_at) &middot; {{ $cb->paid_at->format('d M') }}@endif</span>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
