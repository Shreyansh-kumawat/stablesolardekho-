@extends("layouts.public")

@section("css")
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
    .pd-hero {
        background: linear-gradient(rgba(8,16,28,0.94), rgba(8,16,28,0.94)),
                    url("https://images.unsplash.com/photo-1508514177221-188b1cf16e9d?auto=format&fit=crop&w=1920&q=80")
                    center/cover no-repeat;
        padding: 36px 20px 32px;
        border-bottom: 1px solid var(--border);
    }
    .pd-hero-inner { max-width: 1220px; margin: 0 auto; }
    .pd-crumb { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .pd-crumb a { color: var(--muted); font-size: 0.8rem; text-decoration: none; transition: color 0.15s; }
    .pd-crumb a:hover { color: var(--orange); }
    .pd-crumb span { color: var(--muted); font-size: 0.8rem; }
    .pd-crumb .current { color: var(--text); }
    .pd-wrap { max-width: 1220px; margin: 0 auto; padding: 48px 20px 80px; }
    .pd-cols { display: grid; grid-template-columns: 1fr 1fr; gap: 56px; align-items: start; }
    .pd-main-img {
        position: relative; border-radius: 12px; overflow: hidden;
        background: var(--card); border: 1px solid var(--border); aspect-ratio: 4/3;
    }
    .pd-main-img img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.5s ease; }
    .pd-main-img:hover img { transform: scale(1.04); }
    .pd-main-img-empty { width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; }
    .pd-main-img-empty svg { opacity: 0.18; }
    .pd-main-img-empty span { color: rgba(249,115,22,0.3); font-size: 2rem; font-weight: 900; letter-spacing: 4px; }
    .pd-featured-badge {
        position: absolute; top: 14px; left: 14px;
        background: var(--orange); color: #fff;
        font-size: 0.68rem; font-weight: 800;
        padding: 4px 12px; border-radius: 20px; letter-spacing: 0.08em; text-transform: uppercase;
    }
    .pd-thumbs { display: flex; gap: 10px; margin-top: 14px; flex-wrap: wrap; }
    .pd-thumb {
        width: 72px; height: 58px; border-radius: 8px; overflow: hidden;
        border: 2px solid transparent; cursor: pointer;
        transition: border-color 0.15s, opacity 0.15s; flex-shrink: 0; opacity: 0.6;
    }
    .pd-thumb.active { border-color: var(--orange); opacity: 1; }
    .pd-thumb:hover { opacity: 0.9; border-color: rgba(249,115,22,0.4); }
    .pd-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .pd-badges { display: flex; gap: 10px; margin-top: 20px; flex-wrap: wrap; }
    .pd-badge {
        display: flex; align-items: center; gap: 7px;
        background: rgba(255,255,255,0.03); border: 1px solid var(--border);
        border-radius: 8px; padding: 7px 12px; flex: 1; min-width: 110px;
    }
    .pd-badge-icon { flex-shrink: 0; display: flex; align-items: center; }
    .pd-badge-text { font-size: 0.72rem; color: var(--muted); line-height: 1.3; }
    .pd-badge-text strong { display: block; color: var(--text); font-size: 0.75rem; }
    .pd-cat-tag {
        display: inline-flex; align-items: center; gap: 6px;
        color: var(--orange); font-size: 0.72rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: 0.12em;
        background: rgba(249,115,22,0.08); border: 1px solid rgba(249,115,22,0.18);
        padding: 4px 12px; border-radius: 20px; margin-bottom: 14px; text-decoration: none;
    }
    .pd-cat-tag:hover { background: rgba(249,115,22,0.14); }
    .pd-title { font-size: 1.95rem; font-weight: 900; color: #fff; line-height: 1.18; margin: 0 0 8px; letter-spacing: -0.3px; }
    .pd-sku { font-size: 0.78rem; color: var(--muted); margin-bottom: 22px; }
    .pd-sku span { color: rgba(249,115,22,0.8); font-weight: 600; }
    .pd-price-row { display: flex; align-items: baseline; gap: 12px; margin-bottom: 22px; }
    .pd-price { font-size: 2.4rem; font-weight: 900; color: var(--orange); line-height: 1; }
    .pd-price-note { font-size: 0.8rem; color: var(--muted); }
    .pd-price-na { font-size: 1.1rem; color: var(--muted); font-weight: 500; }
    .pd-divider { border: none; border-top: 1px solid var(--border); margin: 22px 0; }
    .pd-desc { color: #94a3b8; font-size: 0.9rem; line-height: 1.75; margin-bottom: 0; }
    .pd-spec-row { display: flex; align-items: center; padding: 9px 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
    .pd-spec-row:first-child { border-top: 1px solid rgba(255,255,255,0.05); }
    .pd-spec-label { width: 38%; color: var(--muted); font-size: 0.82rem; flex-shrink: 0; }
    .pd-spec-val { color: var(--text); font-size: 0.82rem; font-weight: 600; }
    .pd-qty-row { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; }
    .pd-qty-label { font-size: 0.82rem; color: var(--muted); }
    .pd-qty-ctrl { display: flex; align-items: center; border: 1px solid var(--border); border-radius: 8px; overflow: hidden; }
    .pd-qty-btn { width: 36px; height: 36px; background: rgba(255,255,255,0.05); border: none; color: var(--text); font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.15s; }
    .pd-qty-btn:hover { background: rgba(249,115,22,0.12); color: var(--orange); }
    .pd-qty-input { width: 48px; height: 36px; background: transparent; border: none; text-align: center; color: var(--text); font-size: 0.9rem; font-weight: 700; -moz-appearance: textfield; }
    .pd-qty-input::-webkit-inner-spin-button, .pd-qty-input::-webkit-outer-spin-button { -webkit-appearance: none; }
    .pd-btn-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .pd-btn { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 14px 20px; border-radius: 10px; font-weight: 800; font-size: 0.9rem; cursor: pointer; text-decoration: none; border: none; transition: all 0.2s ease; }
    .pd-btn-cart { background: rgba(249,115,22,0.1); border: 1.5px solid rgba(249,115,22,0.35); color: var(--orange); }
    .pd-btn-cart:hover { background: rgba(249,115,22,0.18); border-color: var(--orange); transform: translateY(-1px); }
    .pd-btn-buy { background: var(--orange); color: #fff; }
    .pd-btn-buy:hover { background: #ea6c0a; transform: translateY(-1px); box-shadow: 0 8px 28px rgba(249,115,22,0.35); }
    .pd-login-nudge { background: rgba(255,255,255,0.03); border: 1px solid var(--border); border-radius: 10px; padding: 1.1rem; text-align: center; margin-top: 22px; }
    .pd-login-nudge p { color: var(--muted); font-size: 0.85rem; margin: 0 0 12px; }
    .pd-login-btn { display: inline-block; background: var(--orange); color: #fff; padding: 10px 28px; border-radius: 8px; font-weight: 800; font-size: 0.88rem; border: none; cursor: pointer; }
    .pd-related { margin-top: 72px; }
    .pd-related-header { display: flex; align-items: center; gap: 14px; margin-bottom: 24px; }
    .pd-related-header h2 { font-size: 1.3rem; font-weight: 900; color: #fff; margin: 0; white-space: nowrap; }
    .pd-related-line { flex: 1; height: 1px; background: var(--border); }
    .pd-related-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
    .rel-card { background: var(--card); border: 1px solid var(--border); border-radius: 8px; overflow: hidden; text-decoration: none; display: flex; flex-direction: column; transition: transform 0.22s, box-shadow 0.22s, border-color 0.22s; }
    .rel-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(0,0,0,0.45); border-color: rgba(249,115,22,0.25); }
    .rel-img { position: relative; overflow: hidden; aspect-ratio: 4/3; }
    .rel-img img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.4s; }
    .rel-card:hover .rel-img img { transform: scale(1.06); }
    .rel-img-empty { width: 100%; aspect-ratio: 4/3; background: #1a2336; display: flex; align-items: center; justify-content: center; }
    .rel-body { padding: 12px 14px 16px; flex: 1; display: flex; flex-direction: column; }
    .rel-cat { font-size: 0.65rem; font-weight: 800; color: var(--orange); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 4px; }
    .rel-name { color: var(--text); font-weight: 700; font-size: 0.86rem; line-height: 1.32; margin: 0 0 auto; }
    .rel-footer { display: flex; align-items: center; justify-content: space-between; padding-top: 10px; margin-top: 12px; border-top: 1px solid rgba(255,255,255,0.05); gap: 8px; }
    .rel-price { color: var(--orange); font-weight: 900; font-size: 1rem; }
    .rel-arrow { width: 30px; height: 30px; border-radius: 7px; background: rgba(249,115,22,0.1); border: 1px solid rgba(249,115,22,0.2); display: flex; align-items: center; justify-content: center; color: var(--orange); font-size: 0.85rem; transition: background 0.2s; }
    .rel-card:hover .rel-arrow { background: var(--orange); border-color: var(--orange); color: #fff; }
    @media (max-width: 900px) {
        .pd-cols { grid-template-columns: 1fr; gap: 36px; }
        .pd-related-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 480px) {
        .pd-title { font-size: 1.5rem; }
        .pd-price { font-size: 1.9rem; }
        .pd-btn-row { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section("content")

<div class="pd-hero">
    <div class="pd-hero-inner">
        <div class="pd-crumb">
            <a href="{{ route('dashBoardFunction') }}">Home</a>
            <span>/</span>
            <a href="{{ route('shop') }}">Shop</a>
            @if($product->category)
                <span>/</span>
                <a href="{{ route('shop.category', $product->category->slug ?? '') }}">{{ $product->category->category_name }}</a>
            @endif
            <span>/</span>
            <span class="current">{{ $product->item_name }}</span>
        </div>
    </div>
</div>

<div class="pd-wrap">
<div class="pd-cols">

  {{-- Left: Gallery --}}
  <div>
      <div class="pd-main-img">
          @if($product->image)
              <img src="{{ Storage::url($product->image) }}" alt="{{ $product->item_name }}" id="mainImg">
          @else
              <div class="pd-main-img-empty">
                  <svg width="72" height="72" fill="none" viewBox="0 0 24 24" stroke="var(--orange)" stroke-width="1">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
                  </svg>
                  <span>{{ strtoupper(substr($product->item_name, 0, 2)) }}</span>
              </div>
          @endif
          @if($product->is_featured)
              <div class="pd-featured-badge">Featured</div>
          @endif
      </div>

      @php $galleryImages = $product->images ?? collect(); @endphp
      @if($product->image || $galleryImages->count())
      <div class="pd-thumbs">
          @if($product->image)
          <div class="pd-thumb active" onclick="switchImg(this,'{{ Storage::url($product->image) }}')">
              <img src="{{ Storage::url($product->image) }}" alt="Main">
          </div>
          @endif
          @foreach($galleryImages as $gi)
          <div class="pd-thumb" onclick="switchImg(this,'{{ Storage::url($gi->image) }}')">
              <img src="{{ Storage::url($gi->image) }}" alt="Gallery">
          </div>
          @endforeach
      </div>
      @endif

      <div class="pd-badges">
          <div class="pd-badge">
              <span class="pd-badge-icon">
                  <svg width="18" height="18" fill="none" stroke="var(--orange)" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              </span>
              <div class="pd-badge-text"><strong>Expert Install</strong>Certified Technicians</div>
          </div>
          <div class="pd-badge">
              <span class="pd-badge-icon">
                  <svg width="18" height="18" fill="none" stroke="var(--orange)" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </span>
              <div class="pd-badge-text"><strong>Quality Assured</strong>Top-Grade Products</div>
          </div>
          <div class="pd-badge">
              <span class="pd-badge-icon">
                  <svg width="18" height="18" fill="none" stroke="var(--orange)" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
              </span>
              <div class="pd-badge-text"><strong>Warranty</strong>Manufacturer Backed</div>
          </div>
      </div>
  </div>

  {{-- Right: Info --}}
  <div>
      @if($product->category)
      <a href="{{ route('shop.category', $product->category->slug ?? '') }}" class="pd-cat-tag">
          &bull;&nbsp; {{ $product->category->category_name }}
      </a>
      @endif

      <h1 class="pd-title">{{ $product->item_name }}</h1>
      <div class="pd-sku">SKU: <span>{{ $product->item_code ?? $product->slug }}</span></div>

      <div class="pd-price-row">
          @if($product->current_sale_price)
              <div class="pd-price">&#8377;{{ number_format($product->current_sale_price, 0) }}</div>
              <div class="pd-price-note">Incl. taxes &amp; charges</div>
          @else
              <div class="pd-price-na">Price on request</div>
          @endif
      </div>

      <hr class="pd-divider">

      @if($product->description)
      <p class="pd-desc">{{ $product->description }}</p>
      @endif

      <div style="margin-top:22px;">
          <div class="pd-spec-row"><span class="pd-spec-label">Product ID</span><span class="pd-spec-val">{{ $product->slug }}</span></div>
          @if($product->item_code)<div class="pd-spec-row"><span class="pd-spec-label">Item Code</span><span class="pd-spec-val">{{ $product->item_code }}</span></div>@endif
          @if($product->category)<div class="pd-spec-row"><span class="pd-spec-label">Category</span><span class="pd-spec-val">{{ $product->category->category_name }}</span></div>@endif
          @if($product->subCategory)<div class="pd-spec-row"><span class="pd-spec-label">Sub-Category</span><span class="pd-spec-val">{{ $product->subCategory->sub_category_name }}</span></div>@endif
          @if($product->uom)<div class="pd-spec-row"><span class="pd-spec-label">Unit</span><span class="pd-spec-val">{{ $product->uom }}</span></div>@endif
          @if($product->type)<div class="pd-spec-row"><span class="pd-spec-label">Type</span><span class="pd-spec-val">{{ $product->type }}</span></div>@endif
          @if($product->brand)<div class="pd-spec-row"><span class="pd-spec-label">Brand</span><span class="pd-spec-val">{{ $product->brand }}</span></div>@endif
          @if($product->model)<div class="pd-spec-row"><span class="pd-spec-label">Model</span><span class="pd-spec-val">{{ $product->model }}</span></div>@endif
          @if($product->operating_voltage)<div class="pd-spec-row"><span class="pd-spec-label">Operating Voltage</span><span class="pd-spec-val">{{ $product->operating_voltage }}</span></div>@endif
          @if($product->solar_panel_type)<div class="pd-spec-row"><span class="pd-spec-label">Solar Panel Type</span><span class="pd-spec-val">{{ $product->solar_panel_type }}</span></div>@endif
          @if($product->mnre_approved)<div class="pd-spec-row"><span class="pd-spec-label">MNRE Approved</span><span class="pd-spec-val">{{ $product->mnre_approved }}</span></div>@endif
          @if($product->certifications)<div class="pd-spec-row"><span class="pd-spec-label">Certifications</span><span class="pd-spec-val">{{ $product->certifications }}</span></div>@endif
          @if($product->manufacturer_warranty)<div class="pd-spec-row"><span class="pd-spec-label">Warranty</span><span class="pd-spec-val">{{ $product->manufacturer_warranty }}</span></div>@endif
          @if($product->number_of_cells)<div class="pd-spec-row"><span class="pd-spec-label">Number of Cells</span><span class="pd-spec-val">{{ $product->number_of_cells }}</span></div>@endif
          @if($product->encapsulate)<div class="pd-spec-row"><span class="pd-spec-label">Encapsulate</span><span class="pd-spec-val">{{ $product->encapsulate }}</span></div>@endif
          @if($product->country_of_origin)<div class="pd-spec-row"><span class="pd-spec-label">Country of Origin</span><span class="pd-spec-val">{{ $product->country_of_origin }}</span></div>@endif
          @if($product->input_voltage)<div class="pd-spec-row"><span class="pd-spec-label">Input Voltage</span><span class="pd-spec-val">{{ $product->input_voltage }}</span></div>@endif
          @if($product->max_supported_panel_power)<div class="pd-spec-row"><span class="pd-spec-label">Max Panel Power</span><span class="pd-spec-val">{{ $product->max_supported_panel_power }}</span></div>@endif
      </div>

      <hr class="pd-divider">

      @auth
      @php $stock = $product->quantity ?? 0; @endphp
      @if($stock > 0)
      <div class="pd-qty-row">
          <span class="pd-qty-label">Quantity</span>
          <div class="pd-qty-ctrl">
              <button class="pd-qty-btn" type="button" onclick="changeQty(-1)">&#8722;</button>
              <input class="pd-qty-input" type="number" id="qtyInput" value="1" min="1" max="{{ $stock }}" data-max="{{ $stock }}">
              <button class="pd-qty-btn" type="button" onclick="changeQty(1)">+</button>
          </div>
          <span style="font-size:0.75rem;color:var(--muted);">{{ $stock }} in stock</span>
      </div>
      <div class="pd-btn-row">
          <form action="{{ route('cart.add') }}" method="POST">
              @csrf
              <input type="hidden" name="product_id" value="{{ $product->id }}">
              <input type="hidden" name="quantity" id="cartQty" value="1">
              <button type="submit" class="pd-btn pd-btn-cart" style="width:100%;">
                  <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.6 8H19M7 13L5.4 5M10 21a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z"/></svg>
                  Add to Cart
              </button>
          </form>
          <a href="#" id="buyNowLink" class="pd-btn pd-btn-buy" style="width:100%;text-decoration:none;text-align:center;box-sizing:border-box;">
              Buy Now
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
          </a>
      </div>
      @else
      <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:10px;padding:1rem;text-align:center;margin-top:4px;">
          <p style="color:#ef4444;font-weight:700;font-size:0.9rem;margin:0;">Out of Stock</p>
          <p style="color:var(--muted);font-size:0.8rem;margin:6px 0 0;">This product is currently unavailable.</p>
      </div>
      @endif
      @else
      <div class="pd-login-nudge">
          <p>Login to place an order or add to cart</p>
          <button onclick="openAuthModal('login')" class="pd-login-btn">Login / Register</button>
      </div>
      @endauth
  </div>

</div>

@php
    $related = App\Models\Product::where('is_active', true)
        ->where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->with('category')
        ->take(4)->get();
@endphp
@if($related->count())
<div class="pd-related">
    <div class="pd-related-header">
        <h2>Related Products</h2>
        <div class="pd-related-line"></div>
        @if($product->category)
        <a href="{{ route('shop.category', $product->category->slug ?? '') }}"
           style="color:var(--orange);font-size:0.82rem;font-weight:700;text-decoration:none;white-space:nowrap;">View All &rarr;</a>
        @endif
    </div>
    <div class="pd-related-grid">
        @foreach($related as $rp)
        <a href="{{ route('product.show', $rp->slug) }}" class="rel-card">
            <div class="rel-img">
                @if($rp->image)<img src="{{ Storage::url($rp->image) }}" alt="{{ $rp->item_name }}">
                @else<div class="rel-img-empty"><span style="color:rgba(249,115,22,0.2);font-size:1.6rem;font-weight:900;">{{ strtoupper(substr($rp->item_name,0,2)) }}</span></div>@endif
            </div>
            <div class="rel-body">
                <div class="rel-cat">{{ $rp->category->category_name ?? '' }}</div>
                <div class="rel-name">{{ $rp->item_name }}</div>
                <div class="rel-footer">
                    <div class="rel-price">
                        @if($rp->current_sale_price) &#8377;{{ number_format($rp->current_sale_price, 0) }}
                        @else <span style="color:var(--muted);font-size:0.77rem;font-weight:500;">On request</span>@endif
                    </div>
                    <div class="rel-arrow">&#8594;</div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

</div>

<script>
function switchImg(thumb, src) {
    document.querySelectorAll(".pd-thumb").forEach(function(t){ t.classList.remove("active"); });
    thumb.classList.add("active");
    var mi = document.getElementById("mainImg");
    if (mi) mi.src = src;
}
function updateBuyNowLink(qty) {
    var link = document.getElementById("buyNowLink");
    if (link) link.href = "{{ route('order.checkout') }}?product_id={{ $product->id }}&qty=" + qty;
}
function changeQty(delta) {
    var input = document.getElementById("qtyInput");
    var cartQty = document.getElementById("cartQty");
    var maxStock = parseInt(input.dataset.max) || 1;
    var val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > maxStock) val = maxStock;
    input.value = val;
    if (cartQty) cartQty.value = val;
    updateBuyNowLink(val);
}
var qtyInput = document.getElementById("qtyInput");
if (qtyInput) {
    var maxStock = parseInt(qtyInput.dataset.max) || 1;
    qtyInput.addEventListener("input", function() {
        var v = parseInt(this.value) || 1;
        if (v < 1) v = 1;
        if (v > maxStock) { v = maxStock; this.value = v; }
        var cartQty = document.getElementById("cartQty");
        if (cartQty) cartQty.value = v;
        updateBuyNowLink(v);
    });
}
updateBuyNowLink(1);
</script>

@endsection
