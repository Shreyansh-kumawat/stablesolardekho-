@extends('layouts.public')

@section('css')
<style>
    :root {
        --bg:     #0b1117;
        --card:   #131929;
        --border: rgba(255,255,255,0.07);
        --text:   #e2e8f0;
        --muted:  #64748b;
        --orange: #f97316;
    }
    body { background: var(--bg); }

    /* ── Hero ── */
    .hero {
        position: relative; overflow: hidden;
        background:
            linear-gradient(rgba(8,16,28,0.88), rgba(8,16,28,0.88)),
            url('https://images.unsplash.com/photo-1509391366360-2e959784a276?auto=format&fit=crop&w=1920&q=80')
            center/cover no-repeat;
        padding: 72px 20px 60px;
        border-bottom: 1px solid rgba(255,255,255,0.07);
    }
    .hero-inner { max-width: 1220px; margin: 0 auto; }
    .hero-crumb {
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 20px;
    }
    .hero-crumb a { color: var(--muted); font-size: 0.82rem; text-decoration: none; }
    .hero-crumb a:hover { color: var(--orange); }
    .hero-crumb span { color: var(--muted); font-size: 0.82rem; }
    .hero-tag {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(249,115,22,0.12); color: var(--orange);
        font-size: 0.72rem; font-weight: 800; letter-spacing: 0.1em;
        text-transform: uppercase; padding: 5px 12px;
        border-radius: 999px; border: 1px solid rgba(249,115,22,0.25);
        margin-bottom: 16px;
    }
    .hero-tag::before {
        content: ''; width: 6px; height: 6px; border-radius: 50%;
        background: var(--orange); display: block;
    }
    .hero-title {
        font-size: 3rem; font-weight: 900; color: #fff;
        letter-spacing: -0.5px; line-height: 1.05; margin: 0 0 14px;
    }
    .hero-title span { color: var(--orange); }
    .hero-sub { color: #94a3b8; font-size: 1rem; margin: 0; max-width: 480px; }
    .hero-count {
        display: inline-block; margin-top: 24px;
        background: rgba(255,255,255,0.06); border: 1px solid var(--border);
        color: var(--text); font-size: 0.82rem; font-weight: 600;
        padding: 7px 18px; border-radius: 999px;
    }

    /* ── Grid ── */
    .wrap { max-width: 1220px; margin: 0 auto; padding: 0 20px; }

    .feat-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        padding: 48px 0 72px;
    }

    /* ── Card ── */
    .feat-card {
        background: var(--card);
        border-radius: 16px; overflow: hidden;
        text-decoration: none; display: flex; flex-direction: column;
        border: 1px solid var(--border);
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        position: relative;
    }
    .feat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 24px 52px rgba(0,0,0,0.55);
        border-color: rgba(249,115,22,0.3);
    }

    /* image */
    .feat-img {
        position: relative; overflow: hidden;
        aspect-ratio: 4/3;
    }
    .feat-img img {
        width: 100%; height: 100%; object-fit: cover;
        display: block; transition: transform 0.5s ease;
    }
    .feat-card:hover .feat-img img { transform: scale(1.07); }
    .feat-img-empty {
        width: 100%; aspect-ratio: 4/3; background: #1a2336;
        display: flex; align-items: center; justify-content: center;
    }

    /* number badge top-left */
    .feat-num {
        position: absolute; top: 12px; left: 12px;
        width: 32px; height: 32px; border-radius: 8px;
        background: rgba(11,17,23,0.75); backdrop-filter: blur(6px);
        border: 1px solid rgba(255,255,255,0.15);
        color: #fff; font-size: 0.72rem; font-weight: 800;
        display: flex; align-items: center; justify-content: center;
        letter-spacing: 0.02em;
    }

    /* hover overlay with "View" */
    .feat-overlay {
        position: absolute; inset: 0;
        background: rgba(8,16,28,0.55);
        display: flex; align-items: center; justify-content: center;
        opacity: 0; transition: opacity 0.25s ease;
    }
    .feat-card:hover .feat-overlay { opacity: 1; }
    .feat-overlay-btn {
        background: #fff; color: #0b1117;
        font-weight: 800; font-size: 0.85rem;
        padding: 10px 24px; border-radius: 8px;
        letter-spacing: 0.02em; transform: translateY(6px);
        transition: transform 0.25s ease;
    }
    .feat-card:hover .feat-overlay-btn { transform: translateY(0); }

    /* info */
    .feat-info { padding: 16px 18px 20px; flex: 1; display: flex; flex-direction: column; }
    .feat-cat  {
        font-size: 0.68rem; font-weight: 800; color: var(--orange);
        text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 6px;
    }
    .feat-name {
        color: var(--text); font-weight: 700; font-size: 1rem;
        line-height: 1.35; margin: 0 0 auto;
    }
    .feat-foot {
        display: flex; align-items: center;
        justify-content: space-between;
        padding-top: 14px; margin-top: 14px;
        border-top: 1px solid rgba(255,255,255,0.06);
    }
    .feat-price { color: var(--orange); font-weight: 900; font-size: 1.15rem; }
    .feat-arrow {
        width: 32px; height: 32px; border-radius: 8px;
        background: rgba(249,115,22,0.1); border: 1px solid rgba(249,115,22,0.2);
        display: flex; align-items: center; justify-content: center;
        color: var(--orange); font-size: 0.9rem;
        transition: background 0.2s, border-color 0.2s;
    }
    .feat-card:hover .feat-arrow {
        background: var(--orange); border-color: var(--orange); color: #fff;
    }

    /* empty state */
    .empty-state {
        text-align: center; padding: 6rem 1rem 8rem;
    }
    .empty-icon {
        width: 64px; height: 64px; border-radius: 16px;
        background: rgba(249,115,22,0.1); border: 1px solid rgba(249,115,22,0.2);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 20px;
    }

    /* pagination */
    .pagination { display: flex; justify-content: center; gap: 6px; flex-wrap: wrap; padding-bottom: 72px; }
    .pg-btn {
        padding: 8px 16px; border-radius: 8px;
        font-size: 0.85rem; text-decoration: none;
        border: 1px solid var(--border); background: var(--card); color: var(--muted);
        transition: border-color 0.15s, color 0.15s;
    }
    .pg-btn:hover  { border-color: var(--orange); color: var(--orange); }
    .pg-btn.active { background: rgba(249,115,22,0.12); color: var(--orange); border-color: rgba(249,115,22,0.35); font-weight: 700; }
    .pg-btn.off    { opacity: 0.3; pointer-events: none; }

    @media (max-width: 900px) {
        .feat-grid  { grid-template-columns: repeat(2, 1fr); gap: 14px; }
        .hero-title { font-size: 2rem; }
    }
    @media (max-width: 480px) {
        .feat-grid  { grid-template-columns: repeat(1, 1fr); }
        .hero-title { font-size: 1.6rem; }
    }
</style>
@endsection

@section('content')

{{-- ── HERO ── --}}
<div class="hero">
    <div class="hero-inner">
        <div class="hero-crumb">
            <a href="{{ route('dashBoardFunction') }}">Home</a>
            <span>/</span>
            <span style="color:var(--text);">Featured</span>
        </div>
        <div class="hero-tag">Hand-picked collection</div>
        <h1 class="hero-title">Our <span>Featured</span><br>Products</h1>
        <p class="hero-sub">Top-rated solar panels, inverters and accessories — selected by our experts for quality and performance.</p>
        <div class="hero-count">{{ $products->total() }} product{{ $products->total() != 1 ? 's' : '' }} featured</div>
    </div>
</div>

{{-- ── GRID ── --}}
<div class="wrap">
    @if($products->count())
    <div class="feat-grid">
        @foreach($products as $i => $product)
        <a href="{{ route('product.show', $product->slug) }}" class="feat-card">

            {{-- Image --}}
            <div class="feat-img">
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->item_name }}">
                @else
                    <div class="feat-img-empty">
                        <span style="color:rgba(249,115,22,0.18);font-size:3rem;font-weight:900;letter-spacing:-2px;">{{ strtoupper(substr($product->item_name,0,2)) }}</span>
                    </div>
                @endif

                {{-- Number badge --}}
                <div class="feat-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>

                {{-- Hover overlay --}}
                <div class="feat-overlay">
                    <div class="feat-overlay-btn">View Product</div>
                </div>
            </div>

            {{-- Info --}}
            <div class="feat-info">
                <div class="feat-cat">{{ $product->category->category_name ?? 'Solar' }}</div>
                <div class="feat-name">{{ $product->item_name }}</div>
                @if($product->brand || $product->manufacturer_warranty || $product->mnre_approved)
                <div style="font-size:0.72rem; color:var(--muted); line-height:1.55; margin-top:2px;">
                    @if($product->brand)<div>Brand: <span style="color:var(--text);">{{ $product->brand }}</span></div>@endif
                    @if($product->manufacturer_warranty)<div>Warranty: <span style="color:var(--text);">{{ $product->manufacturer_warranty }}</span></div>@endif
                    @if($product->mnre_approved)<div>MNRE: <span style="color:var(--text);">{{ $product->mnre_approved }}</span></div>@endif
                </div>
                @endif
                <div class="feat-foot">
                    <div class="feat-price">
                        @if($product->current_sale_price)
                            ₹{{ number_format($product->current_sale_price, 0) }}
                        @else
                            <span style="color:var(--muted);font-size:0.82rem;font-weight:500;">Price on request</span>
                        @endif
                    </div>
                    <div class="feat-arrow">&#8594;</div>
                </div>
            </div>

        </a>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
    <div class="pagination">
        <a class="pg-btn {{ $products->onFirstPage() ? 'off' : '' }}" href="{{ $products->previousPageUrl() }}">&laquo; Prev</a>
        @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
        <a class="pg-btn {{ $page == $products->currentPage() ? 'active' : '' }}" href="{{ $url }}">{{ $page }}</a>
        @endforeach
        <a class="pg-btn {{ !$products->hasMorePages() ? 'off' : '' }}" href="{{ $products->nextPageUrl() }}">Next &raquo;</a>
    </div>
    @else
    <div style="padding-bottom:72px;"></div>
    @endif

    @else
    <div class="empty-state">
        <div class="empty-icon">
            <svg width="28" height="28" fill="none" stroke="var(--orange)" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
            </svg>
        </div>
        <p style="color:#fff;font-size:1.1rem;font-weight:700;margin:0 0 8px;">No featured products yet</p>
        <p style="color:var(--muted);font-size:0.88rem;margin:0 0 24px;">Mark products as featured from the admin panel.</p>
        <a href="{{ route('shop') }}"
           style="background:var(--orange);color:#fff;padding:11px 28px;border-radius:8px;text-decoration:none;font-weight:700;font-size:0.9rem;">
            Browse All Products
        </a>
    </div>
    @endif
</div>

@endsection
