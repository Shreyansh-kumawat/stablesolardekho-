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

    /* ── Page hero ── */
    .shop-hero {
        background: linear-gradient(rgba(8,16,28,0.92), rgba(8,16,28,0.92)),
                    url('https://images.unsplash.com/photo-1508514177221-188b1cf16e9d?auto=format&fit=crop&w=1920&q=80')
                    center/cover no-repeat;
        padding: 48px 20px 40px;
        border-bottom: 1px solid var(--border);
    }
    .shop-hero-inner { max-width: 1220px; margin: 0 auto; }
    .shop-crumb {
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 14px;
    }
    .shop-crumb a  { color: var(--muted); font-size: 0.8rem; text-decoration: none; }
    .shop-crumb a:hover { color: var(--orange); }
    .shop-crumb span { color: var(--muted); font-size: 0.8rem; }
    .shop-hero h1 {
        font-size: 2.2rem; font-weight: 900; color: #fff;
        margin: 0 0 6px; letter-spacing: -0.3px;
    }
    .shop-hero h1 span { color: var(--orange); }
    .shop-hero p { color: #94a3b8; font-size: 0.92rem; margin: 0; }

    /* ── Search & Filter Bar ── */
    .shop-search-bar {
        max-width: 1220px; margin: -20px auto 0; padding: 0 20px;
        position: relative; z-index: 10;
    }
    .shop-search-inner {
        background: var(--card); border: 1px solid var(--border);
        border-radius: 12px; padding: 16px 20px;
        display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
    }
    .shop-search-input {
        flex: 1; min-width: 200px; background: rgba(255,255,255,0.06);
        border: 1px solid var(--border); border-radius: 8px;
        padding: 10px 14px 10px 38px; font-size: 0.88rem;
        color: var(--text); outline: none; transition: border-color 0.2s;
    }
    .shop-search-input:focus { border-color: var(--orange); }
    .shop-search-input::placeholder { color: var(--muted); }
    .shop-search-wrap {
        flex: 1; min-width: 200px; position: relative;
    }
    .shop-search-icon {
        position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
        color: var(--muted); font-size: 0.85rem; pointer-events: none;
    }
    .shop-sort-select {
        background: rgba(255,255,255,0.06); border: 1px solid var(--border);
        border-radius: 8px; padding: 10px 14px; font-size: 0.84rem;
        color: var(--text); outline: none; cursor: pointer;
        transition: border-color 0.2s;
    }
    .shop-sort-select:focus { border-color: var(--orange); }
    .shop-sort-select option { background: #1a2336; color: var(--text); }
    .shop-search-btn {
        background: var(--orange); color: #fff; border: none;
        border-radius: 8px; padding: 10px 20px; font-weight: 700;
        font-size: 0.85rem; cursor: pointer; transition: background 0.2s;
    }
    .shop-search-btn:hover { background: #ea580c; }
    .shop-clear-btn {
        background: transparent; color: var(--muted); border: 1px solid var(--border);
        border-radius: 8px; padding: 10px 14px; font-size: 0.84rem;
        cursor: pointer; text-decoration: none; transition: all 0.2s;
    }
    .shop-clear-btn:hover { color: var(--text); border-color: var(--text); }
    .shop-search-input-clear {
        position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
        background: none; border: none; color: var(--muted); font-size: 1.1rem;
        cursor: pointer; padding: 2px 6px; display: none;
    }
    .shop-search-input-clear:hover { color: var(--text); }

    /* ── Layout ── */
    .shop-wrap { max-width: 1220px; margin: 0 auto; padding: 36px 20px 72px; }

    /* ── Sidebar ── */
    .shop-sidebar {
        width: 210px; flex-shrink: 0;
        background: var(--card); border: 1px solid var(--border);
        border-radius: 8px; overflow: hidden;
        position: sticky; top: 80px;
    }
    .sidebar-list { padding: 8px; }
    .sidebar-link {
        display: flex; align-items: center; justify-content: space-between;
        padding: 9px 10px; border-radius: 8px;
        font-size: 0.85rem; text-decoration: none;
        color: var(--muted); font-weight: 500;
        transition: background 0.15s, color 0.15s;
        border-left: 3px solid transparent;
        margin-bottom: 2px;
    }
    .sidebar-link:hover { background: rgba(255,255,255,0.05); color: var(--text); }
    .sidebar-link.active {
        background: rgba(249,115,22,0.10);
        color: var(--orange); font-weight: 700;
        border-left-color: var(--orange);
    }
    .sidebar-count {
        font-size: 0.72rem; font-weight: 700;
        background: rgba(255,255,255,0.07);
        color: var(--muted); padding: 2px 7px;
        border-radius: 20px; min-width: 22px; text-align: center;
    }
    .sidebar-link.active .sidebar-count {
        background: rgba(249,115,22,0.15); color: var(--orange);
    }

    /* ── Main content ── */
    .shop-main { flex: 1; min-width: 0; }
    .shop-topbar {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 20px; flex-wrap: wrap; gap: 10px;
    }
    .shop-topbar-count { color: var(--muted); font-size: 0.85rem; }

    /* ── Grid ── */
    .shop-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    /* ── Product Card ── */
    .prod-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 8px; overflow: hidden;
        text-decoration: none; display: flex; flex-direction: column;
        transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
    }
    .prod-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 44px rgba(0,0,0,0.5);
        border-color: rgba(249,115,22,0.28);
    }
    .prod-img {
        position: relative; overflow: hidden; aspect-ratio: 4/3;
    }
    .prod-img img {
        width: 100%; height: 100%; object-fit: cover; display: block;
        transition: transform 0.45s ease;
    }
    .prod-card:hover .prod-img img { transform: scale(1.06); }
    .prod-img-empty {
        width: 100%; aspect-ratio: 4/3; background: #1a2336;
        display: flex; align-items: center; justify-content: center;
    }
    .prod-img-overlay {
        position: absolute; inset: 0;
        background: rgba(8,16,28,0.5);
        display: flex; align-items: center; justify-content: center;
        opacity: 0; transition: opacity 0.22s;
    }
    .prod-card:hover .prod-img-overlay { opacity: 1; }
    .prod-overlay-btn {
        background: #fff; color: #0b1117;
        font-weight: 800; font-size: 0.82rem;
        padding: 9px 22px; border-radius: 8px;
        transform: translateY(6px); transition: transform 0.22s;
    }
    .prod-card:hover .prod-overlay-btn { transform: translateY(0); }

    .prod-featured-badge {
        position: absolute; top: 10px; right: 10px;
        background: rgba(249,115,22,0.9); color: #fff;
        font-size: 0.66rem; font-weight: 800;
        padding: 3px 9px; border-radius: 20px; letter-spacing: 0.06em;
    }

    .prod-body { padding: 14px 16px 18px; flex: 1; display: flex; flex-direction: column; }
    .prod-cat {
        font-size: 0.67rem; font-weight: 800; color: var(--orange);
        text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 5px;
    }
    .prod-name {
        color: var(--text); font-weight: 700; font-size: 0.92rem;
        line-height: 1.35; margin: 0 0 auto;
    }
    .prod-footer {
        display: flex; align-items: center; justify-content: space-between;
        padding-top: 12px; margin-top: 14px;
        border-top: 1px solid rgba(255,255,255,0.06);
        gap: 8px;
    }
    .prod-price { color: var(--orange); font-weight: 900; font-size: 1.1rem; line-height: 1; }
    .prod-arrow {
        width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0;
        background: rgba(249,115,22,0.1); border: 1px solid rgba(249,115,22,0.2);
        display: flex; align-items: center; justify-content: center;
        color: var(--orange); font-size: 0.9rem;
        transition: background 0.2s, border-color 0.2s;
    }
    .prod-card:hover .prod-arrow {
        background: var(--orange); border-color: var(--orange); color: #fff;
    }

    /* ── Empty ── */
    .shop-empty { text-align: center; padding: 5rem 1rem; }

    /* ── Pagination ── */
    .shop-pagination { display: flex; justify-content: center; gap: 6px; flex-wrap: wrap; margin-top: 32px; }
    .pg { padding: 8px 16px; border-radius: 8px; font-size: 0.85rem; text-decoration: none; border: 1px solid var(--border); background: var(--card); color: var(--muted); transition: all 0.15s; }
    .pg:hover  { border-color: var(--orange); color: var(--orange); }
    .pg.active { background: rgba(249,115,22,0.12); color: var(--orange); border-color: rgba(249,115,22,0.35); font-weight: 700; }
    .pg.off    { opacity: 0.3; pointer-events: none; }

    @media (max-width: 900px) {
        .shop-sidebar { display: none; }
        .shop-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 480px) {
        .shop-grid { grid-template-columns: repeat(1, 1fr); }
    }
</style>
@endsection

@section('content')

{{-- Hero --}}
<div class="shop-hero">
    <div class="shop-hero-inner">
        <div class="shop-crumb">
            <a href="{{ route('dashBoardFunction') }}">Home</a>
            <span>/</span>
            @if(isset($activeCategory))
                <a href="{{ route('shop') }}">Shop</a>
                <span>/</span>
                <span style="color:var(--text);">{{ $activeCategory->category_name }}</span>
            @else
                <span style="color:var(--text);">Shop</span>
            @endif
        </div>
        <h1>
            @if(isset($activeCategory))
                {{ $activeCategory->category_name }}
            @else
                All <span>Products</span>
            @endif
        </h1>
        <p>
            @if(isset($activeCategory))
                {{ $products->total() }} product{{ $products->total() != 1 ? 's' : '' }} in this category
            @else
                Browse our complete range of solar products
            @endif
        </p>
    </div>
</div>

{{-- Search & Filter --}}
<div class="shop-search-bar">
    <form class="shop-search-inner" id="shopFilterForm" method="GET" action="{{ isset($activeCategory) ? route('shop.category', $activeCategory->slug) : route('shop') }}">
        <div class="shop-search-wrap">
            <span class="shop-search-icon">&#128269;</span>
            <input type="text" name="search" id="shopSearchInput" class="shop-search-input" placeholder="Search by name, brand, code..." value="{{ request('search') }}" autocomplete="off">
            <button type="button" class="shop-search-input-clear" id="searchClearBtn">&times;</button>
        </div>
        <select name="sort" id="shopSortSelect" class="shop-sort-select">
            <option value="">Latest First</option>
            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
        </select>
        <button type="submit" class="shop-search-btn">Search</button>
        @if(request('search') || request('sort'))
        <a href="{{ isset($activeCategory) ? route('shop.category', $activeCategory->slug) : route('shop') }}" class="shop-clear-btn">Clear</a>
        @endif
    </form>
</div>

{{-- Body --}}
<div class="shop-wrap">

    <div class="shop-main">
        <div class="shop-topbar">
            <span class="shop-topbar-count">{{ $products->total() }} product{{ $products->total() != 1 ? 's' : '' }} found</span>
        </div>

        @if($products->count())
        <div class="shop-grid">
            @foreach($products as $product)
            <a href="{{ route('product.show', $product->slug) }}" class="prod-card"
               data-name="{{ strtolower($product->item_name) }}"
               data-brand="{{ strtolower($product->brand ?? '') }}"
               data-code="{{ strtolower($product->item_code ?? '') }}"
               data-category="{{ strtolower($product->category->category_name ?? '') }}"
               data-price="{{ $product->current_sale_price ?? 0 }}">
                <div class="prod-img">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->item_name }}">
                    @else
                        <div class="prod-img-empty">
                            <span style="color:rgba(249,115,22,0.2);font-size:2rem;font-weight:900;">{{ strtoupper(substr($product->item_name,0,2)) }}</span>
                        </div>
                    @endif
                    <div class="prod-img-overlay">
                        <div class="prod-overlay-btn">View Product</div>
                    </div>
                    @if($product->is_featured)
                        <span class="prod-featured-badge">FEATURED</span>
                    @endif
                </div>
                <div class="prod-body">
                    <div class="prod-cat">{{ $product->category->category_name ?? '' }}</div>
                    <div class="prod-name">{{ $product->item_name }}</div>
                    @if($product->brand || $product->manufacturer_warranty || $product->mnre_approved)
                    <div style="font-size:0.72rem; color:var(--muted); line-height:1.55; margin-top:2px;">
                        @if($product->brand)<div>Brand: <span style="color:var(--text);">{{ $product->brand }}</span></div>@endif
                        @if($product->manufacturer_warranty)<div>Warranty: <span style="color:var(--text);">{{ $product->manufacturer_warranty }}</span></div>@endif
                        @if($product->mnre_approved)<div>MNRE: <span style="color:var(--text);">{{ $product->mnre_approved }}</span></div>@endif
                    </div>
                    @endif
                    <div class="prod-footer">
                        <div class="prod-price">
                            @if($product->current_sale_price)
                                ₹{{ number_format($product->current_sale_price, 0) }}
                            @else
                                <span style="color:var(--muted);font-size:0.8rem;font-weight:500;">Price on request</span>
                            @endif
                        </div>
                        <div class="prod-arrow">&#8594;</div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        @if($products->hasPages())
        <div class="shop-pagination">
            <a class="pg {{ $products->onFirstPage() ? 'off' : '' }}" href="{{ $products->previousPageUrl() }}">&laquo; Prev</a>
            @php
                $current = $products->currentPage();
                $last = $products->lastPage();
                $start = max(1, $current - 2);
                $end = min($last, $current + 2);
                if ($end - $start < 4) {
                    $start = max(1, $end - 4);
                    $end = min($last, $start + 4);
                }
            @endphp
            @if($start > 1)
                <a class="pg" href="{{ $products->url(1) }}">1</a>
                @if($start > 2)<span class="pg off" style="pointer-events:none;border:none;opacity:0.5;">...</span>@endif
            @endif
            @foreach($products->getUrlRange($start, $end) as $page => $url)
                <a class="pg {{ $page == $current ? 'active' : '' }}" href="{{ $url }}">{{ $page }}</a>
            @endforeach
            @if($end < $last)
                @if($end < $last - 1)<span class="pg off" style="pointer-events:none;border:none;opacity:0.5;">...</span>@endif
                <a class="pg" href="{{ $products->url($last) }}">{{ $last }}</a>
            @endif
            <a class="pg {{ !$products->hasMorePages() ? 'off' : '' }}" href="{{ $products->nextPageUrl() }}">Next &raquo;</a>
        </div>
        @endif

        @else
        <div class="shop-empty">
            <p style="color:#fff;font-size:1.1rem;font-weight:700;margin:0 0 8px;">No products found</p>
            <p style="color:var(--muted);font-size:0.88rem;margin:0 0 24px;">
                @if(request('search'))
                    No results for "{{ request('search') }}". Try a different search term.
                @else
                    Try a different category.
                @endif
            </p>
            <a href="{{ route('shop') }}" style="background:var(--orange);color:#fff;padding:10px 26px;border-radius:8px;text-decoration:none;font-weight:700;font-size:0.9rem;">View All Products</a>
        </div>
        @endif
    </div>
</div>

@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('shopSearchInput');
    var sortSelect = document.getElementById('shopSortSelect');
    var clearBtn = document.getElementById('searchClearBtn');
    var form = document.getElementById('shopFilterForm');

    function toggleClearBtn() {
        if (clearBtn) clearBtn.style.display = searchInput && searchInput.value.length > 0 ? 'block' : 'none';
    }

    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            if (form) form.submit();
        });
    }

    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (form) form.submit();
        });
    }

    toggleClearBtn();
    if (searchInput) searchInput.addEventListener('input', toggleClearBtn);
});
</script>
@endsection
