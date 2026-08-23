@extends('layouts.public')
@section('title', 'My Requests')

@section('css')
<style>
.ud-layout{min-height:100vh;display:flex;max-width:1200px;margin:0 auto;padding:24px 16px;gap:24px;}
.ud-main{flex:1;min-width:0;}
.rfq-page-hdr{margin-bottom:20px;}
.rfq-page-hdr h2{color:#fff;font-size:1.3rem;font-weight:800;margin:0 0 4px;}
.rfq-page-hdr p{color:#64748b;font-size:.85rem;margin:0;}
.rfq-card{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:14px;overflow:hidden;margin-bottom:14px;transition:border-color .2s;}
.rfq-card:hover{border-color:rgba(249,115,22,0.3);}
.rfq-card-hdr{display:flex;justify-content:space-between;align-items:center;padding:14px 18px;border-bottom:1px solid rgba(255,255,255,0.04);}
.rfq-card-hdr .rfq-id{color:#94a3b8;font-size:.75rem;font-weight:600;}
.rfq-card-hdr .rfq-date{color:#475569;font-size:.72rem;}
.rfq-card-body{padding:14px 18px;}
.rfq-item-desc{color:#e2e8f0;font-size:.9rem;font-weight:600;margin:0 0 8px;line-height:1.4;}
.rfq-meta{display:flex;flex-wrap:wrap;gap:16px;font-size:.78rem;color:#64748b;}
.rfq-meta span{display:flex;align-items:center;gap:4px;}
.rfq-status{display:inline-block;padding:3px 10px;border-radius:12px;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.3px;}
.rfq-status.pending{background:rgba(251,191,36,0.15);color:#f59e0b;}
.rfq-status.processing{background:rgba(59,130,246,0.15);color:#3b82f6;}
.rfq-status.quoted{background:rgba(139,92,246,0.15);color:#8b5cf6;}
.rfq-status.accepted{background:rgba(34,197,94,0.15);color:#22c55e;}
.rfq-status.rejected{background:rgba(239,68,68,0.15);color:#ef4444;}
.rfq-status.closed{background:rgba(100,116,139,0.15);color:#64748b;}
.rfq-quote-box{margin-top:12px;background:rgba(139,92,246,0.06);border:1px solid rgba(139,92,246,0.15);border-radius:10px;padding:12px 16px;}
.rfq-quote-box .label{font-size:.72rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.3px;}
.rfq-quote-box .value{color:#e2e8f0;font-size:.95rem;font-weight:700;margin-top:2px;}
.rfq-quote-row{display:flex;gap:20px;flex-wrap:wrap;margin-top:8px;}
.rfq-quote-item{flex:1;min-width:120px;}
.rfq-remark{margin-top:8px;font-size:.82rem;color:#94a3b8;font-style:italic;}
.rfq-empty{text-align:center;padding:40px 20px;color:#475569;}
.rfq-empty h3{color:#94a3b8;font-size:1rem;font-weight:700;margin:8px 0;}
.rfq-empty a{color:#f97316;font-weight:600;text-decoration:none;}
.rfq-wa-btn{display:inline-flex;align-items:center;gap:6px;margin-top:10px;padding:6px 14px;border-radius:8px;background:#25d366;color:#fff;font-size:.78rem;font-weight:600;text-decoration:none;transition:background .2s;}
.rfq-wa-btn:hover{background:#1ebe5a;}
@media(max-width:768px){.ud-layout{flex-direction:column;padding:16px;}}
</style>
@endsection

@section('content')
<div class="ud-layout">
    @include('user.partials.sidebar', ['activePage' => 'requests'])

    <div class="ud-main">
        <div class="rfq-page-hdr">
            <h2>My Requests</h2>
            <p>Track your product quote requests and their status</p>
        </div>

        @forelse($rfqs as $rfq)
        <div class="rfq-card">
            <div class="rfq-card-hdr">
                <span class="rfq-id">RFQ #{{ $rfq->id }}</span>
                <span class="rfq-date">{{ $rfq->created_at->format('d M Y, h:i A') }}</span>
            </div>
            <div class="rfq-card-body">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;">
                    <p class="rfq-item-desc">{{ $rfq->item_description }}</p>
                    <span class="rfq-status {{ $rfq->status }}">{{ $rfq->status }}</span>
                </div>
                <div class="rfq-meta">
                    <span><i class="fas fa-cubes"></i> Qty: {{ $rfq->quantity }}</span>
                    @if($rfq->preferred_brand)
                    <span><i class="fas fa-tag"></i> {{ $rfq->preferred_brand }}</span>
                    @endif
                    @if($rfq->city)
                    <span><i class="fas fa-map-marker-alt"></i> {{ $rfq->city }}</span>
                    @endif
                </div>

                @if(in_array($rfq->status, ['quoted', 'accepted']))
                <div class="rfq-quote-box">
                    @if($rfq->product)
                    <div class="label">Matched Product</div>
                    <div class="value">{{ $rfq->product->item_name }}</div>
                    @endif
                    <div class="rfq-quote-row">
                        @if($rfq->quoted_price)
                        <div class="rfq-quote-item">
                            <div class="label">Quoted Price</div>
                            <div class="value">Rs {{ number_format($rfq->quoted_price, 2) }}</div>
                        </div>
                        @endif
                        @if($rfq->discount_percent)
                        <div class="rfq-quote-item">
                            <div class="label">Discount</div>
                            <div class="value">{{ $rfq->discount_percent }}%</div>
                        </div>
                        @endif
                        @if($rfq->final_price)
                        <div class="rfq-quote-item">
                            <div class="label">Final Price</div>
                            <div class="value" style="color:#22c55e;">Rs {{ number_format($rfq->final_price, 2) }}</div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                @if($rfq->admin_remarks)
                <p class="rfq-remark">"{{ $rfq->admin_remarks }}"</p>
                @endif

                @if($rfq->status === 'quoted')
                <a href="https://wa.me/917014920144?text={{ urlencode('Hi, I received a quote for RFQ #' . $rfq->id . ' - ' . $rfq->item_description . '. I would like to proceed.') }}"
                   target="_blank" class="rfq-wa-btn">
                    <i class="fab fa-whatsapp"></i> Chat on WhatsApp
                </a>
                @endif
            </div>
        </div>
        @empty
        <div class="rfq-empty">
            <i class="fas fa-file-invoice" style="font-size:2rem;color:#334155;"></i>
            <h3>No requests yet</h3>
            <p style="font-size:.85rem;">Looking for a specific solar product? <a href="{{ route('shop') }}#requestQuote">Submit a request</a></p>
        </div>
        @endforelse
    </div>
</div>
@endsection
