@extends('layouts.public')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    :root {
        --bg:      #0b1117;
        --card:    #131929;
        --card2:   #1a2236;
        --border:  rgba(255,255,255,0.07);
        --text:    #e2e8f0;
        --muted:   #64748b;
        --orange:  #f97316;
        --orange2: #fb923c;
    }

    body { background: var(--bg); font-size: 14px; }

    /* ── layout ── */
    .wrap { max-width: 1220px; margin: 0 auto; padding: 0 20px; }
    .sec  { padding: 56px 0 0; }

    /* ── section header ── */
    .sec-head {
        display: flex; align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 20px; gap: 12px; flex-wrap: wrap;
    }
    .sec-title { color: #fff; font-size: 1.45rem; font-weight: 800; margin: 0; }
    .sec-sub   { color: var(--muted); font-size: 0.82rem; margin: 4px 0 0; }
    .view-all  {
        color: var(--text); font-size: 0.8rem; font-weight: 600;
        background: var(--card2); border: 1px solid var(--border);
        padding: 7px 16px; border-radius: 8px; text-decoration: none;
        white-space: nowrap; transition: border-color 0.2s;
        flex-shrink: 0;
    }
    .view-all:hover { border-color: var(--orange); color: var(--orange); }

    /* ══════════════════════════════════
       CATEGORIES
    ══════════════════════════════════ */
    .cat-scroll {
        display: flex; gap: 12px; overflow-x: auto;
        scrollbar-width: none; padding-bottom: 6px;
    }
    .cat-scroll::-webkit-scrollbar { display: none; }

    /* category card — image on top, text below, no bg */
    .cat-card {
        flex-shrink: 0;
        width: 148px;
        text-decoration: none; display: flex;
        flex-direction: column; gap: 0;
        cursor: pointer;
        transition: transform 0.25s ease;
    }
    .cat-card:hover { transform: translateY(-4px); }

    .cat-card-img-wrap {
        width: 100%; height: 148px;
        border-radius: 12px; overflow: hidden;
        position: relative;
    }
    .cat-card-img {
        width: 100%; height: 100%; object-fit: cover;
        display: block;
        transition: transform 0.45s ease;
    }
    .cat-card:hover .cat-card-img { transform: scale(1.07); }

    .cat-card-fallback {
        width: 100%; height: 148px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
    }
    .cat-card-fallback-letter {
        font-size: 3rem; font-weight: 900; line-height: 1;
        opacity: 0.3; color: #fff;
    }

    .cat-card-info { padding: 10px 4px 0; }
    .cat-card-name {
        color: #e2e8f0; font-weight: 700; font-size: 0.87rem;
        line-height: 1.25; margin: 0 0 3px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .cat-card-count { color: var(--muted); font-size: 0.73rem; }


    /* ══════════════════════════════════
       FEATURED PRODUCTS
    ══════════════════════════════════ */
    .feat-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
    }
    .feat-card {
        background: var(--card);
        border-radius: 12px; overflow: hidden;
        text-decoration: none; display: flex; flex-direction: column;
        transition: transform 0.22s ease, box-shadow 0.22s ease;
        border: 1px solid rgba(255,255,255,0.06);
    }
    .feat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 18px 44px rgba(0,0,0,0.6), 0 0 0 1px rgba(249,115,22,0.3);
    }
    .feat-img-wrap {
        position: relative; overflow: hidden;
        aspect-ratio: 3/2;
    }
    .feat-img-wrap img {
        width: 100%; height: 100%;
        object-fit: cover; display: block;
        transition: transform 0.4s ease;
    }
    .feat-card:hover .feat-img-wrap img { transform: scale(1.06); }
    .feat-img-empty {
        width: 100%; height: 100%;
        background: #1a2336;
        display: flex; align-items: center; justify-content: center;
    }
    .feat-badge {
        position: absolute; top: 10px; left: 10px;
        background: var(--orange); color: #fff;
        font-size: 0.6rem; font-weight: 800;
        padding: 3px 8px; border-radius: 4px;
        letter-spacing: 0.08em; text-transform: uppercase;
    }
    .feat-info { padding: 16px 18px 20px; flex: 1; display: flex; flex-direction: column; gap: 5px; }
    .feat-cat  { font-size: 0.7rem; font-weight: 700; color: var(--orange); text-transform: uppercase; letter-spacing: 0.07em; }
    .feat-name { color: var(--text); font-weight: 700; font-size: 0.97rem; line-height: 1.35; margin: 0; }
    .feat-foot { display: flex; align-items: center; justify-content: space-between; margin-top: auto; padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.06); }
    .feat-price { color: var(--orange); font-weight: 900; font-size: 1.1rem; }
    .feat-view  { color: var(--muted); font-size: 0.75rem; font-weight: 600; }
    .feat-card:hover .feat-view { color: var(--orange); }


    /* ══════════════════════════════════
       ALL PRODUCTS
    ══════════════════════════════════ */
    .all-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }
    .all-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 16px; overflow: hidden;
        text-decoration: none; display: block;
        transition: transform 0.22s ease, border-color 0.22s ease, box-shadow 0.22s ease;
    }
    .all-card:hover {
        transform: translateY(-4px);
        border-color: rgba(249,115,22,0.3);
        box-shadow: 0 12px 28px rgba(0,0,0,0.4);
    }
    .all-img-wrap {
        overflow: hidden;
        aspect-ratio: 1/1;
    }
    .all-img-wrap img {
        width: 100%; height: 100%;
        object-fit: cover; display: block;
        transition: transform 0.4s ease;
    }
    .all-card:hover .all-img-wrap img { transform: scale(1.06); }
    .all-img-empty {
        width: 100%; height: 100%; background: #1a2336;
        display: flex; align-items: center; justify-content: center;
    }
    .all-info { padding: 13px 15px 16px; }
    .all-name  { color: var(--text); font-weight: 700; font-size: 0.92rem; margin: 0 0 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .all-cat   { color: var(--muted); font-size: 0.75rem; margin: 0 0 8px; }
    .all-price { color: var(--orange); font-weight: 800; font-size: 0.97rem; }

    /* ── stats bar ── */
    .stats-bar {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        border: 1px solid var(--border);
        border-radius: 16px; overflow: hidden;
        background: var(--card);
    }
    .stat-item {
        padding: 22px 28px; display: flex; align-items: center; gap: 14px;
        border-right: 1px solid var(--border);
    }
    .stat-item:last-child { border-right: none; }
    .stat-icon-wrap {
        width: 42px; height: 42px; border-radius: 10px;
        background: rgba(249,115,22,0.12);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .stat-num   { color: #fff; font-weight: 900; font-size: 1.3rem; line-height: 1; }
    .stat-label { color: var(--muted); font-size: 0.77rem; margin-top: 3px; }

    /* ── Why solar: editorial ── */
    .benefit-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.5rem; align-items: center;
    }
    .benefit-item {
        display: flex; align-items: flex-start; gap: 14px;
        padding: 18px; background: var(--card);
        border: 1px solid var(--border); border-radius: 14px;
        transition: border-color 0.2s;
    }
    .benefit-item:hover { border-color: rgba(249,115,22,0.3); }
    .benefit-num {
        flex-shrink: 0; width: 44px; height: 44px; border-radius: 12px;
        background: rgba(249,115,22,0.1); color: var(--orange);
        display: flex; align-items: center; justify-content: center;
        border: 1px solid rgba(249,115,22,0.15);
    }
    .benefit-title { color: #fff; font-weight: 700; font-size: 0.9rem; margin: 0 0 4px; }
    .benefit-desc  { color: var(--muted); font-size: 0.82rem; line-height: 1.55; margin: 0; }

    /* ── how it works timeline ── */
    .steps-row {
        display: grid; grid-template-columns: repeat(4, 1fr);
        gap: 0; position: relative;
    }
    .steps-row::before {
        content: ''; position: absolute;
        top: 20px; left: calc(12.5% + 4px); right: calc(12.5% + 4px);
        height: 1px; background: rgba(249,115,22,0.25);
    }
    .step-item { padding: 0 16px; text-align: center; }
    .step-num {
        width: 42px; height: 42px; border-radius: 50%;
        background: var(--card); border: 2px solid var(--orange);
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 0.9rem; color: var(--orange);
        margin: 0 auto 16px; position: relative; z-index: 1;
    }
    .step-title { color: #fff; font-weight: 700; font-size: 0.88rem; margin-bottom: 6px; }
    .step-desc  { color: var(--muted); font-size: 0.78rem; line-height: 1.55; }

    /* ── testimonials ── */
    .review-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
        gap: 14px;
    }
    .review-card {
        background: var(--card); border: 1px solid var(--border);
        border-radius: 16px; padding: 20px;
    }
    .review-top { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
    .review-avatar {
        width: 40px; height: 40px; border-radius: 50%;
        background: linear-gradient(135deg, var(--orange), #fb923c);
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 0.85rem; color: #fff; flex-shrink: 0;
    }
    .review-name { color: #fff; font-weight: 700; font-size: 0.88rem; margin: 0; }
    .review-role { color: var(--muted); font-size: 0.74rem; margin: 2px 0 0; }
    .review-stars { display: flex; gap: 2px; margin-bottom: 10px; }
    .review-text  { color: #94a3b8; font-size: 0.85rem; line-height: 1.7; margin: 0; }

    /* ── quote form ── */
    .qform { background: var(--card); border: 1px solid var(--border); border-radius: 18px; padding: 32px; }
    .qinput {
        width: 100%; padding: 11px 14px;
        background: var(--card2); border: 1px solid var(--border);
        border-radius: 10px; color: var(--text);
        font-size: 0.88rem; box-sizing: border-box; outline: none;
        transition: border-color 0.2s;
    }
    .qinput:focus { border-color: var(--orange); }
    .qlabel { display: block; color: var(--muted); font-size: 0.75rem; font-weight: 600; margin-bottom: 6px; }
    .bill-chip {
        display: inline-block; padding: 7px 14px;
        border-radius: 8px; border: 1px solid var(--border);
        color: var(--muted); font-size: 0.8rem; font-weight: 600;
        transition: all 0.18s; user-select: none; background: var(--card2); cursor: pointer;
    }
    .bill-chip.active { background: rgba(249,115,22,0.12); color: var(--orange); border-color: rgba(249,115,22,0.4); }

    /* ── skeleton ── */
    @keyframes shimmer {
        0%   { background-position: -1000px 0; }
        100% { background-position:  1000px 0; }
    }
    .skel {
        background: linear-gradient(90deg, rgba(255,255,255,0.05) 25%, rgba(255,255,255,0.10) 50%, rgba(255,255,255,0.05) 75%);
        background-size: 2000px 100%;
        animation: shimmer 1.8s infinite linear;
        border-radius: 10px;
    }
    .skel-label { text-align:center; color:#374151; font-size:0.8rem; margin-top:0.8rem; font-weight:600; }

    /* ── scroll reveal ── */
    .sr { opacity: 0; transform: translateY(28px); transition: opacity 0.7s ease, transform 0.7s ease; }
    .sr.visible { opacity: 1; transform: translateY(0); }

    /* ── banner ken burns ── */
    @keyframes kenburns { 0%{transform:scale(1)} 100%{transform:scale(1.08)} }
    .banner-slide-img { animation: kenburns 12s ease alternate infinite; }

    /* ── section glow divider ── */
    .sec-glow { height: 1px; background: linear-gradient(90deg, transparent, rgba(249,115,22,0.25) 30%, rgba(249,115,22,0.4) 50%, rgba(249,115,22,0.25) 70%, transparent); margin: 0 auto; max-width: 600px; }

    /* ══ RESPONSIVE ══ */
    @media (max-width: 1024px) {
        .feat-grid { grid-template-columns: repeat(2, 1fr); }
        .all-grid  { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .sec { padding: 40px 0 0; }
        .sec-title { font-size: 1.25rem; }
        .feat-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .all-grid  { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .steps-row { grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .steps-row::before { display: none; }
        .stats-bar { grid-template-columns: 1fr 1fr; }
        .stat-item { border-right: none; border-bottom: 1px solid var(--border); }
        .review-grid { grid-template-columns: 1fr; }
        .banner-slide-img { height: 340px !important; }
        .banner-slide h1 { font-size: 2rem !important; }
        .banner-slide p { font-size: 0.88rem !important; }
    }
    @media (max-width: 480px) {
        .wrap { padding: 0 14px; }
        .sec { padding: 32px 0 0; }
        .sec-title { font-size: 1.1rem; }
        .feat-grid { grid-template-columns: 1fr; gap: 14px; }
        .all-grid  { grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .steps-row { grid-template-columns: 1fr; gap: 1.2rem; }
        .stats-bar { grid-template-columns: 1fr; }
        .stat-item { border-bottom: 1px solid var(--border); border-right: none; }
        .cat-card  { width: 110px; }
        .cat-card-img-wrap, .cat-card-fallback { height: 110px; }
        .cat-card-name { font-size: 0.78rem; }
        .benefit-grid { grid-template-columns: 1fr; }
        .banner-slide-img { height: 240px !important; }
        .banner-slide h1 { font-size: 1.4rem !important; max-width: 280px !important; }
        .banner-slide p { font-size: 0.8rem !important; max-width: 240px !important; margin-bottom: 16px !important; }
        .banner-slide a { padding: 10px 22px !important; font-size: 0.85rem !important; }
        .feat-info { padding: 12px 14px 16px; }
        .all-info { padding: 10px 12px 14px; }
        .all-name { font-size: 0.82rem; }
        .all-price { font-size: 0.88rem; }
    }
</style>
@endsection

@section('content')

{{-- ───────────────── BANNER ───────────────── --}}
@if($banners->count())
<div id="banner-slider" style="position:relative; width:100%; overflow:hidden; background:var(--bg);">
    <div id="banner-track" style="display:flex; transition:transform 0.5s ease;">
        @foreach($banners as $i => $banner)
        <div class="banner-slide" style="min-width:100%; position:relative;">
            <img src="{{ Storage::url($banner->image) }}" alt="{{ $banner->title }}" class="banner-slide-img"
                 style="width:100%; height:480px; object-fit:cover; display:block; opacity:0.65;">
            <div style="position:absolute; inset:0; display:flex; flex-direction:column; align-items:flex-start; justify-content:center; padding:0 6%; background:linear-gradient(to right, rgba(11,17,23,0.88) 0%, rgba(11,17,23,0.5) 50%, transparent 100%);">
                @if($banner->title)
                <h1 style="color:#fff; font-size:2.8rem; font-weight:900; margin:0 0 10px; line-height:1.1; letter-spacing:-0.5px; max-width:520px;">{{ $banner->title }}</h1>
                @endif
                @if($banner->subtitle)
                <p style="color:#94a3b8; font-size:1rem; margin:0 0 28px; max-width:420px;">{{ $banner->subtitle }}</p>
                @endif
                @if($banner->button_text)
                <a href="{{ $banner->button_link ?? '/shop' }}"
                   style="background:var(--orange); color:#fff; font-weight:800; padding:13px 32px; border-radius:10px; text-decoration:none; font-size:0.95rem; display:inline-block; transition:background 0.2s, transform 0.2s;"
                   onmouseover="this.style.background='#ea6c0a'; this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.background='var(--orange)'; this.style.transform='none'">
                    {{ $banner->button_text }}
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @if($banners->count() > 1)
    <button onclick="prevSlide()" style="position:absolute;top:50%;left:1.2rem;transform:translateY(-50%);background:rgba(0,0,0,0.45);border:1px solid rgba(255,255,255,0.12);color:#fff;width:44px;height:44px;border-radius:50%;font-size:1.3rem;cursor:pointer;line-height:1;">&#8249;</button>
    <button onclick="nextSlide()" style="position:absolute;top:50%;right:1.2rem;transform:translateY(-50%);background:rgba(0,0,0,0.45);border:1px solid rgba(255,255,255,0.12);color:#fff;width:44px;height:44px;border-radius:50%;font-size:1.3rem;cursor:pointer;line-height:1;">&#8250;</button>
    <div style="position:absolute;bottom:1.2rem;left:50%;transform:translateX(-50%);display:flex;gap:6px;">
        @foreach($banners as $i => $banner)
        <span class="bdot" data-index="{{ $i }}" onclick="goToSlide({{ $i }})"
              style="width:6px;height:6px;border-radius:3px;background:rgba(255,255,255,0.3);cursor:pointer;transition:all 0.3s;display:block;"></span>
        @endforeach
    </div>
    @endif
</div>
<script>
let bc=0,bt={{ $banners->count() }},btr=document.getElementById('banner-track'),bds=document.querySelectorAll('.bdot');
function goToSlide(n){bc=(n+bt)%bt;btr.style.transform=`translateX(-${bc*100}%)`;bds.forEach((d,i)=>{d.style.background=i===bc?'#f97316':'rgba(255,255,255,0.3)';d.style.width=i===bc?'20px':'6px';});}
function nextSlide(){goToSlide(bc+1);}function prevSlide(){goToSlide(bc-1);}
if(bds.length)goToSlide(0);setInterval(nextSlide,5000);
</script>
@else
<div style="position:relative;width:100%;background:var(--card);overflow:hidden;">
    <div class="skel" style="width:100%;height:480px;"></div>
    <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:flex-start;justify-content:center;padding:0 6%;gap:14px;">
        <div class="skel" style="width:380px;height:42px;"></div>
        <div class="skel" style="width:280px;height:20px;"></div>
        <div class="skel" style="width:140px;height:46px;border-radius:10px;"></div>
        <p class="skel-label">No banners — <a href="{{ route('admin.banners') }}" style="color:var(--orange);">add from admin</a></p>
    </div>
</div>
@endif


{{-- ───────────────── CATEGORIES ───────────────── --}}
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
<div class="wrap sec sr">
    <div class="sec-head">
        <div>
            <h2 class="sec-title">Explore Categories</h2>
            <p class="sec-sub">Find solar products by type</p>
        </div>
        <a href="{{ route('categories') }}" class="view-all">View All</a>
    </div>
    @if($categories->count())
    <div class="cat-scroll">
        @foreach($categories as $idx => $cat)
        <a href="{{ route('shop.category', $cat->slug) }}" class="cat-card">
            @if($cat->image)
                <div class="cat-card-img-wrap">
                    <img src="{{ Storage::url($cat->image) }}" alt="{{ $cat->category_name }}" class="cat-card-img">
                </div>
            @else
                <div class="cat-card-fallback" style="background:{{ $catGradients[$idx % count($catGradients)] }};">
                    <div class="cat-card-fallback-letter">{{ strtoupper(substr($cat->category_name, 0, 1)) }}</div>
                </div>
            @endif
            <div class="cat-card-info">
                <div class="cat-card-name">{{ $cat->category_name }}</div>
                <div class="cat-card-count">{{ $cat->products_count }} items</div>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="cat-scroll">
        @for($i = 0; $i < 5; $i++)
        <div style="flex-shrink:0;width:155px;height:195px;border-radius:18px;overflow:hidden;">
            <div class="skel" style="width:100%;height:100%;border-radius:18px;"></div>
        </div>
        @endfor
    </div>
    <p class="skel-label">No categories — <a href="{{ route('manageCategory') }}" style="color:var(--orange);">add from admin</a></p>
    @endif
</div>


{{-- ───────────────── FEATURED PRODUCTS ───────────────── --}}
<div class="sec-glow" style="margin-top:56px;"></div>
<div style="background: linear-gradient(rgba(8,18,32,0.91), rgba(8,18,32,0.91)), url('{{ asset('imgs/solarr-1170x658.jpg') }}') center/cover no-repeat; border-top:1px solid rgba(255,255,255,0.08); border-bottom:1px solid rgba(255,255,255,0.08); padding:56px 0;">
<div class="wrap sr">
    <div class="sec-head">
        <div>
            <span style="display:inline-flex; align-items:center; gap:6px; background:linear-gradient(135deg, rgba(249,115,22,0.15), rgba(249,115,22,0.06)); color:var(--orange); font-size:0.7rem; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; padding:5px 14px; border-radius:20px; margin-bottom:10px; border:1px solid rgba(249,115,22,0.18); backdrop-filter:blur(4px);">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                Hand-picked
            </span>
            <h2 class="sec-title" style="margin:0;">Featured Products</h2>
        </div>
        <a href="{{ route('featured') }}" class="view-all">View All</a>
    </div>
    @if($featuredProducts->count())
    <div class="feat-grid">
        @foreach($featuredProducts as $product)
        <a href="{{ route('product.show', $product->slug) }}" class="feat-card">
            <div class="feat-img-wrap">
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->item_name }}">
                @else
                    <div class="feat-img-empty" style="width:100%;aspect-ratio:4/3;">
                        <span style="color:rgba(249,115,22,0.2);font-size:2.5rem;font-weight:900;letter-spacing:-1px;">{{ strtoupper(substr($product->item_name,0,2)) }}</span>
                    </div>
                @endif
            </div>
            <div class="feat-info">
                <div class="feat-cat">{{ $product->category->category_name ?? 'Solar' }}</div>
                <div class="feat-name">{{ $product->item_name }}</div>
                @if($product->brand || $product->manufacturer_warranty || $product->mnre_approved)
                <div style="font-size:0.75rem; color:var(--muted); line-height:1.6; margin-top:2px;">
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
                            <span style="color:var(--muted);font-size:0.8rem;font-weight:500;">On request</span>
                        @endif
                    </div>
                    <span class="feat-view">View &rarr;</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="feat-grid">
        @for($i = 0; $i < 4; $i++)
        <div style="background:var(--card);border-radius:20px;overflow:hidden;">
            <div class="skel" style="aspect-ratio:4/3;border-radius:0;"></div>
            <div style="padding:14px 16px 18px;">
                <div class="skel" style="height:10px;width:40%;margin-bottom:8px;"></div>
                <div class="skel" style="height:14px;width:85%;margin-bottom:14px;"></div>
                <div class="skel" style="height:16px;width:35%;"></div>
            </div>
        </div>
        @endfor
    </div>
    <p class="skel-label">No featured products — <a href="{{ route('manageProducts') }}" style="color:var(--orange);">mark as featured</a></p>
    @endif
</div>
</div>


{{-- ───────────────── ALL PRODUCTS ───────────────── --}}
<div class="wrap sec sr">
    <div class="sec-head">
        <div>
            <h2 class="sec-title">All Products</h2>
            <p class="sec-sub">Complete solar product range</p>
        </div>
        <a href="{{ route('shop') }}" class="view-all">Browse All</a>
    </div>
    @if($allProducts->count())
    <div class="all-grid">
        @foreach($allProducts as $product)
        <a href="{{ route('product.show', $product->slug) }}" class="all-card">
            <div class="all-img-wrap">
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->item_name }}">
                @else
                    <div class="all-img-empty" style="width:100%;aspect-ratio:1/1;">
                        <span style="color:rgba(249,115,22,0.18);font-size:2rem;font-weight:900;">{{ strtoupper(substr($product->item_name,0,2)) }}</span>
                    </div>
                @endif
            </div>
            <div class="all-info">
                <div class="all-name">{{ $product->item_name }}</div>
                <div class="all-cat">{{ $product->category->category_name ?? '' }}</div>
                @if($product->brand || $product->manufacturer_warranty || $product->mnre_approved)
                <div style="font-size:0.7rem; color:var(--muted); line-height:1.55; margin-top:2px;">
                    @if($product->brand)<div>Brand: <span style="color:var(--text);">{{ $product->brand }}</span></div>@endif
                    @if($product->manufacturer_warranty)<div>Warranty: <span style="color:var(--text);">{{ $product->manufacturer_warranty }}</span></div>@endif
                    @if($product->mnre_approved)<div>MNRE: <span style="color:var(--text);">{{ $product->mnre_approved }}</span></div>@endif
                </div>
                @endif
                <div class="all-price">
                    @if($product->current_sale_price)
                        ₹{{ number_format($product->current_sale_price, 0) }}
                    @else
                        <span style="color:var(--muted);font-size:0.75rem;font-weight:500;">On request</span>
                    @endif
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="all-grid">
        @for($i = 0; $i < 8; $i++)
        <div style="background:var(--card);border:1px solid var(--border);border-radius:16px;overflow:hidden;">
            <div class="skel" style="aspect-ratio:1/1;border-radius:0;"></div>
            <div style="padding:10px 12px 13px;">
                <div class="skel" style="height:13px;width:88%;margin-bottom:6px;"></div>
                <div class="skel" style="height:10px;width:55%;margin-bottom:6px;"></div>
                <div class="skel" style="height:14px;width:38%;"></div>
            </div>
        </div>
        @endfor
    </div>
    <p class="skel-label">No products — <a href="{{ route('manageProducts') }}" style="color:var(--orange);">add from admin</a></p>
    @endif
</div>


{{-- ───────────────── STATS BAR ───────────────── --}}
<div class="sec-glow" style="margin-top:56px;"></div>
<div class="wrap sec sr">
    <div class="stats-bar">
        <div class="stat-item">
            <div class="stat-icon-wrap">
                <svg width="20" height="20" fill="none" stroke="var(--orange)" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
            </div>
            <div>
                <div class="stat-num">Fast Delivery</div>
                <div class="stat-label">PAN India shipping available</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon-wrap">
                <svg width="20" height="20" fill="none" stroke="var(--orange)" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
            </div>
            <div>
                <div class="stat-num">5 Year Warranty</div>
                <div class="stat-label">On all solar products</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon-wrap">
                <svg width="20" height="20" fill="none" stroke="var(--orange)" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
            </div>
            <div>
                <div class="stat-num">Govt. Subsidy</div>
                <div class="stat-label">Up to 40% subsidy assistance</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon-wrap">
                <svg width="20" height="20" fill="none" stroke="var(--orange)" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
            </div>
            <div>
                <div class="stat-num">500+ Customers</div>
                <div class="stat-label">Trusted across Rajasthan</div>
            </div>
        </div>
    </div>
</div>


{{-- ───────────────── WHY SOLAR ───────────────── --}}
<div class="sec-glow" style="margin-top:56px;"></div>
<div style="background: linear-gradient(135deg, rgba(249,115,22,0.06) 0%, rgba(15,23,42,0) 60%); border-top:1px solid rgba(249,115,22,0.1); border-bottom:1px solid rgba(249,115,22,0.1); padding:56px 0;">
<div class="wrap sr">
    <div class="sec-head" style="margin-bottom:32px;">
        <div>
            <span style="display:inline-flex; align-items:center; gap:6px; background:linear-gradient(135deg, rgba(249,115,22,0.15), rgba(249,115,22,0.06)); color:var(--orange); font-size:0.7rem; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; padding:5px 14px; border-radius:20px; margin-bottom:10px; border:1px solid rgba(249,115,22,0.18);">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                Why Solar?
            </span>
            <h2 class="sec-title" style="margin:0;">Why Switch to Solar?</h2>
            <p class="sec-sub" style="margin-top:6px;">Smart investment, clean energy, government-backed savings</p>
        </div>
    </div>
    <div class="benefit-grid">
        @php
        $benefits = [
            ['icon'=>'<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/><circle cx="12" cy="12" r="5"/></svg>', 'title'=>'Save Up to 90% on Bills', 'desc'=>'Solar panels generate free electricity from sunlight, slashing your monthly bill from day one.'],
            ['icon'=>'<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/></svg>', 'title'=>'25+ Years of Performance', 'desc'=>'Built to last with minimal maintenance — top-tier panels deliver reliable output for over two decades.'],
            ['icon'=>'<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="M7 12.5l3 3 7-7"/></svg>', 'title'=>'100% Clean Energy', 'desc'=>'Zero carbon emissions, zero pollution. Power your home with pure, renewable sunlight energy.'],
            ['icon'=>'<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M19 5h-4l-1-2H10L9 5H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2z"/><path d="M12 15a3 3 0 100-6 3 3 0 000 6z"/></svg>', 'title'=>'Govt. Subsidy up to 40%', 'desc'=>'Central government offers up to 40% subsidy on residential rooftop solar under PM Surya Ghar Yojana.'],
            ['icon'=>'<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2z"/></svg>', 'title'=>'Property Value Boost', 'desc'=>'Solar-equipped homes sell 10–15% higher — a smart upgrade that pays for itself and adds resale value.'],
            ['icon'=>'<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>', 'title'=>'End-to-End Service', 'desc'=>'From site survey to installation and subsidy paperwork — our certified team handles everything for you.'],
        ];
        @endphp
        @foreach($benefits as $i => $b)
        <div class="benefit-item">
            <div class="benefit-num">{!! $b['icon'] !!}</div>
            <div>
                <div class="benefit-title">{{ $b['title'] }}</div>
                <p class="benefit-desc">{{ $b['desc'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
</div>


{{-- ───────────────── HOW IT WORKS ───────────────── --}}
<div class="wrap sec sr">
    <div class="sec-head">
        <div>
            <h2 class="sec-title">How It Works</h2>
            <p class="sec-sub">From inquiry to installation in 4 simple steps</p>
        </div>
    </div>
    <div style="background:var(--card); border:1px solid var(--border); border-radius:18px; padding:40px 36px;">
        <div class="steps-row">
            @php
            $steps = [
                ['title'=>'Site Visit',    'desc'=>'Our engineers assess your roof space and energy needs.'],
                ['title'=>'System Design', 'desc'=>'We design the optimal setup for maximum savings.'],
                ['title'=>'Installation',  'desc'=>'Certified technicians install your system in 1–2 days.'],
                ['title'=>'Go Solar',      'desc'=>'Start generating free electricity immediately.'],
            ];
            @endphp
            @foreach($steps as $i => $s)
            <div class="step-item">
                <div class="step-num">{{ $i + 1 }}</div>
                <div class="step-title">{{ $s['title'] }}</div>
                <div class="step-desc">{{ $s['desc'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>


{{-- ───────────────── TESTIMONIALS ───────────────── --}}
<div class="sec-glow" style="margin-top:56px;"></div>
<div class="wrap sec sr">
    <div class="sec-head">
        <div>
            <h2 class="sec-title">Customer Reviews</h2>
            <p class="sec-sub">What our solar family says</p>
        </div>
        <div style="display:flex;align-items:center;gap:6px;">
            <div style="display:flex;">@for($s=0;$s<5;$s++)<svg width="14" height="14" fill="#f97316" viewBox="0 0 24 24"><path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>@endfor</div>
            <span style="color:var(--muted);font-size:0.8rem;">500+ reviews</span>
        </div>
    </div>
    <div class="review-grid">
        @php
        $reviews = [
            ['name'=>'Ramesh Sharma','city'=>'Jaipur','review'=>'Bill dropped from ₹4,500 to ₹300/month after installing a 5kW system. Excellent quality and service.','rating'=>'4/5','initials'=>'RS'],
            ['name'=>'Priya Verma','city'=>'Jodhpur','review'=>'Professional team, installed in one day, and filed the subsidy on our behalf. Highly recommended.','rating'=>'5/5','initials'=>'PV'],
            ['name'=>'Mukesh Patel','city'=>'Udaipur','review'=>'After 6 months the savings speak for themselves. Top quality panels and outstanding after-sales support.','rating'=>'5/5','initials'=>'MP'],
        ];
        @endphp
        @foreach($reviews as $r)
        <div class="review-card">
            <div class="review-top">
                <div class="review-avatar">{{ $r['initials'] }}</div>
                <div>
                    <div class="review-name">{{ $r['name'] }}</div>
                    <div class="review-role">{{ $r['city'] }}, Rajasthan &nbsp;·&nbsp; {{ $r['rating'] }} ★</div>
                </div>
            </div>
            <p class="review-text">"{{ $r['review'] }}"</p>
        </div>
        @endforeach
    </div>
</div>


{{-- ───────────────── QUOTE FORM ───────────────── --}}
<div class="wrap" style="padding-top:56px; padding-bottom:72px;">
    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:3rem; align-items:center;">
        <div>
            <p style="color:var(--orange); font-size:0.78rem; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; margin:0 0 12px;">Free Consultation</p>
            <h2 style="color:#fff; font-size:2rem; font-weight:900; margin:0 0 14px; line-height:1.15;">Get your free<br>solar quote today</h2>
            <p style="color:var(--muted); font-size:0.9rem; line-height:1.75; margin:0 0 28px; max-width:380px;">Share a few details and our solar expert will call you back with a customised solution and savings estimate.</p>
            <div style="display:flex;flex-direction:column;gap:12px;">
                @foreach(['No hidden charges','Subsidy assistance included','Installation by certified team'] as $point)
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:22px;height:22px;border-radius:50%;background:rgba(249,115,22,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="12" height="12" fill="none" stroke="var(--orange)" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    </div>
                    <span style="color:#cbd5e1; font-size:0.87rem;">{{ $point }}</span>
                </div>
                @endforeach
            </div>
            <img src="{{ asset('imgs/iPhone02.png') }}" alt="Solar Panel & Inverter" style="max-width:320px; width:100%; margin-top:32px; filter:drop-shadow(0 20px 40px rgba(0,0,0,0.4)); display:block;">
        </div>
        <div class="qform">
            <form action="{{ route('userQuoteQuery') }}" method="POST">
                @csrf
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
                    <div>
                        <label class="qlabel">Full Name</label>
                        <input type="text" name="name" required placeholder="Your name" class="qinput">
                    </div>
                    <div>
                        <label class="qlabel">WhatsApp Number</label>
                        <input type="text" name="mob_no" required placeholder="10-digit number" pattern="[6-9]\d{9}" maxlength="10" class="qinput">
                    </div>
                </div>
                <div style="margin-bottom:14px;">
                    <label class="qlabel" style="margin-bottom:10px;display:block;">Monthly Electricity Bill</label>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;">
                        @foreach(['<1500'=>'< ₹1,500','1500-2500'=>'₹1,500–2,500','2500-4000'=>'₹2,500–4,000','4000-8000'=>'₹4,000–8,000','>8000'=>'> ₹8,000'] as $val=>$lbl)
                        <label style="cursor:pointer;">
                            <input type="radio" name="bill" value="{{ $val }}" required style="display:none;" class="bill-radio">
                            <span class="bill-chip">{{ $lbl }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:20px;">
                    <div>
                        <label class="qlabel">PIN Code</label>
                        <input type="text" name="pin" required placeholder="6-digit PIN" pattern="\d{6}" maxlength="6" class="qinput">
                    </div>
                    <div>
                        <label class="qlabel">City</label>
                        <input type="text" name="city" placeholder="Your city" class="qinput">
                    </div>
                </div>
                <button type="submit"
                        style="width:100%;padding:13px;background:var(--orange);color:#fff;font-weight:800;font-size:0.95rem;border:none;border-radius:10px;cursor:pointer;transition:background 0.2s;"
                        onmouseover="this.style.background='#ea6c0a'" onmouseout="this.style.background='var(--orange)'">
                    Get My Free Quote
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.bill-radio').forEach(r => {
    r.addEventListener('change', function() {
        document.querySelectorAll('.bill-chip').forEach(c => c.classList.remove('active'));
        this.nextElementSibling.classList.add('active');
    });
});

(function(){
    var obs = new IntersectionObserver(function(entries){
        entries.forEach(function(e){ if(e.isIntersecting){ e.target.classList.add('visible'); obs.unobserve(e.target); } });
    }, {threshold: 0.12});
    document.querySelectorAll('.sr').forEach(function(el){ obs.observe(el); });
})();
</script>

@endsection
