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

    .cat-hero {
        background: linear-gradient(rgba(8,16,28,0.92), rgba(8,16,28,0.92)),
                    url('https://images.unsplash.com/photo-1508514177221-188b1cf16e9d?auto=format&fit=crop&w=1920&q=80')
                    center/cover no-repeat;
        padding: 48px 20px 40px;
        border-bottom: 1px solid var(--border);
    }
    .cat-hero-inner { max-width: 1220px; margin: 0 auto; }
    .cat-crumb {
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 14px;
    }
    .cat-crumb a  { color: var(--muted); font-size: 0.8rem; text-decoration: none; }
    .cat-crumb a:hover { color: var(--orange); }
    .cat-crumb span { color: var(--muted); font-size: 0.8rem; }
    .cat-hero h1 {
        font-size: 2.2rem; font-weight: 900; color: #fff;
        margin: 0 0 6px; letter-spacing: -0.3px;
    }
    .cat-hero h1 em { color: var(--orange); font-style: normal; }
    .cat-hero p { color: #94a3b8; font-size: 0.92rem; margin: 0; }

    .cat-wrap { max-width: 1220px; margin: 0 auto; padding: 36px 20px 72px; }
    .cat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 22px;
    }
    .cg-card {
        background: var(--card); border: 1px solid var(--border);
        border-radius: 10px; overflow: hidden;
        text-decoration: none; display: flex; flex-direction: column;
        transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
    }
    .cg-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 44px rgba(0,0,0,0.5);
        border-color: rgba(249,115,22,0.28);
    }
    .cg-img {
        position: relative; overflow: hidden; aspect-ratio: 4/3;
    }
    .cg-img img {
        width: 100%; height: 100%; object-fit: cover; display: block;
        transition: transform 0.45s ease;
    }
    .cg-card:hover .cg-img img { transform: scale(1.06); }
    .cg-fallback {
        width: 100%; aspect-ratio: 4/3;
        display: flex; align-items: center; justify-content: center;
    }
    .cg-fallback-letter {
        font-size: 3rem; font-weight: 900; color: rgba(255,255,255,0.15);
    }
    .cg-overlay {
        position: absolute; inset: 0;
        background: rgba(8,16,28,0.5);
        display: flex; align-items: center; justify-content: center;
        opacity: 0; transition: opacity 0.22s;
    }
    .cg-card:hover .cg-overlay { opacity: 1; }
    .cg-overlay-btn {
        background: #fff; color: #0b1117;
        font-weight: 800; font-size: 0.82rem;
        padding: 9px 22px; border-radius: 8px;
        transform: translateY(6px); transition: transform 0.22s;
    }
    .cg-card:hover .cg-overlay-btn { transform: translateY(0); }

    .cg-body { padding: 16px 18px 18px; }
    .cg-name {
        color: var(--text); font-weight: 700; font-size: 1rem;
        line-height: 1.35; margin: 0 0 6px;
    }
    .cg-count {
        color: var(--muted); font-size: 0.8rem;
        display: flex; align-items: center; gap: 6px;
    }
    .cg-count-num {
        background: rgba(249,115,22,0.12); color: var(--orange);
        font-weight: 700; font-size: 0.75rem;
        padding: 2px 8px; border-radius: 20px;
    }

    .cat-topbar {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 24px; flex-wrap: wrap; gap: 10px;
    }
    .cat-topbar-count { color: var(--muted); font-size: 0.85rem; }

    @media (max-width: 900px) {
        .cat-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 480px) {
        .cat-grid { grid-template-columns: repeat(1, 1fr); }
        .cat-hero h1 { font-size: 1.6rem; }
    }
</style>
@endsection

@section('content')

<div class="cat-hero">
    <div class="cat-hero-inner">
        <div class="cat-crumb">
            <a href="{{ route('dashBoardFunction') }}">Home</a>
            <span>/</span>
            <span style="color:var(--text);">Categories</span>
        </div>
        <h1>All <em>Categories</em></h1>
        <p>Browse our complete range of solar product categories</p>
    </div>
</div>

<div class="cat-wrap">
    <div class="cat-topbar">
        <span class="cat-topbar-count">{{ $categories->count() }} categor{{ $categories->count() != 1 ? 'ies' : 'y' }}</span>
    </div>

    @php
    $catGradients = [
        'linear-gradient(145deg,#1a3a5c,#0d2440)',
        'linear-gradient(145deg,#1a3a28,#0d2418)',
        'linear-gradient(145deg,#3a1a38,#240d34)',
        'linear-gradient(145deg,#3a2a10,#241a06)',
        'linear-gradient(145deg,#1a2a3a,#0d1a2a)',
        'linear-gradient(145deg,#2a1a10,#180d06)',
    ];
    @endphp

    @if($categories->count())
    <div class="cat-grid">
        @foreach($categories as $idx => $cat)
        <a href="{{ route('shop.category', $cat->slug) }}" class="cg-card">
            <div class="cg-img">
                @if($cat->image)
                    <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->category_name }}">
                @else
                    <div class="cg-fallback" style="background:{{ $catGradients[$idx % count($catGradients)] }};">
                        <div class="cg-fallback-letter">{{ strtoupper(substr($cat->category_name, 0, 1)) }}</div>
                    </div>
                @endif
                <div class="cg-overlay">
                    <div class="cg-overlay-btn">Browse Products</div>
                </div>
            </div>
            <div class="cg-body">
                <div class="cg-name">{{ $cat->category_name }}</div>
                <div class="cg-count">
                    <span class="cg-count-num">{{ $cat->products_count }}</span>
                    product{{ $cat->products_count != 1 ? 's' : '' }}
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div style="text-align:center;padding:5rem 1rem;">
        <p style="color:#fff;font-size:1.1rem;font-weight:700;margin:0 0 8px;">No categories yet</p>
        <p style="color:var(--muted);font-size:0.88rem;margin:0;">Check back soon for our product categories.</p>
    </div>
    @endif
</div>

@endsection
