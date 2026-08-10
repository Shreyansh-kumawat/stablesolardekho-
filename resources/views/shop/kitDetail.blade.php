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

    .kd-hero {
        background: linear-gradient(rgba(8,16,28,0.94), rgba(8,16,28,0.94)),
                    url('https://images.unsplash.com/photo-1508514177221-188b1cf16e9d?auto=format&fit=crop&w=1920&q=80')
                    center/cover no-repeat;
        padding: 32px 20px 24px;
        border-bottom: 1px solid var(--border);
    }
    .kd-hero-inner { max-width: 1100px; margin: 0 auto; }
    .kd-crumb { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; }
    .kd-crumb a { color: var(--muted); font-size: 0.8rem; text-decoration: none; }
    .kd-crumb a:hover { color: var(--orange); }
    .kd-crumb span { color: var(--muted); font-size: 0.8rem; }

    .kd-wrap { max-width: 1100px; margin: 0 auto; padding: 30px 20px 60px; }

    .kd-top { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem; }
    .kd-img-box { background: var(--card); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; display: flex; align-items: center; justify-content: center; min-height: 300px; }
    .kd-img-box img { width: 100%; height: 100%; object-fit: cover; max-height: 400px; }
    .kd-img-empty { color: rgba(249,115,22,0.2); font-size: 4rem; font-weight: 900; }

    .kd-info { display: flex; flex-direction: column; gap: 8px; }
    .kd-cat { font-size: 0.72rem; color: var(--orange); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; }
    .kd-name { font-size: 1.5rem; font-weight: 800; color: var(--text); line-height: 1.3; }
    .kd-desc { font-size: 0.85rem; color: var(--muted); line-height: 1.6; margin-top: 4px; }
    .kd-price { font-size: 1.6rem; font-weight: 800; color: var(--orange); margin-top: 8px; }
    .kd-price-note { font-size: 0.75rem; color: var(--muted); }
    .kd-stock { display: inline-block; padding: 4px 12px; border-radius: 6px; font-size: 0.78rem; font-weight: 700; margin-top: 6px; }
    .kd-stock.in { background: rgba(22,163,74,0.12); color: #22c55e; }
    .kd-stock.out { background: rgba(220,38,38,0.12); color: #ef4444; }
    .kd-kit-badge { display: inline-flex; align-items: center; gap: 5px; background: rgba(249,115,22,0.1); color: var(--orange); font-size: 0.75rem; font-weight: 700; padding: 4px 12px; border-radius: 6px; border: 1px solid rgba(249,115,22,0.2); margin-top: 6px; }

    .kd-cta { margin-top: 16px; }
    .kd-cta-btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px; background: var(--orange); color: #fff; border: none; border-radius: 10px; font-size: 0.9rem; font-weight: 700; cursor: pointer; text-decoration: none; transition: background 0.15s; }
    .kd-cta-btn:hover { background: #ea580c; color: #fff; }

    .kd-section { margin-bottom: 2rem; }
    .kd-section-title { font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 8px; }

    /* BOM Table */
    .kd-bom { width: 100%; border-collapse: collapse; }
    .kd-bom-wrap { background: var(--card); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
    .kd-bom thead { background: rgba(249,115,22,0.08); }
    .kd-bom th { padding: 10px 14px; font-size: 0.72rem; font-weight: 700; color: var(--orange); text-transform: uppercase; letter-spacing: 0.5px; text-align: left; }
    .kd-bom td { padding: 8px 14px; font-size: 0.82rem; color: var(--text); border-top: 1px solid var(--border); }
    .kd-bom-cat { font-weight: 700; color: var(--orange); background: rgba(249,115,22,0.04); }
    .kd-bom-cat td { padding: 6px 14px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.03em; border-top: 2px solid var(--border); }

    /* Slab Pricing */
    .kd-slab { width: 100%; border-collapse: collapse; }
    .kd-slab-wrap { background: var(--card); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; max-width: 500px; }
    .kd-slab thead { background: rgba(249,115,22,0.08); }
    .kd-slab th { padding: 10px 14px; font-size: 0.72rem; font-weight: 700; color: var(--orange); text-transform: uppercase; letter-spacing: 0.5px; text-align: left; }
    .kd-slab td { padding: 9px 14px; font-size: 0.85rem; color: var(--text); border-top: 1px solid var(--border); }
    .kd-slab-qty { font-weight: 700; }
    .kd-slab-price { font-weight: 700; color: var(--orange); }

    /* Related */
    .kd-related-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem; }
    .kd-rel-card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; text-decoration: none; transition: border-color 0.15s; }
    .kd-rel-card:hover { border-color: var(--orange); }
    .kd-rel-img { width: 100%; height: 140px; object-fit: cover; }
    .kd-rel-img-empty { width: 100%; height: 140px; background: rgba(249,115,22,0.05); display: flex; align-items: center; justify-content: center; color: rgba(249,115,22,0.2); font-size: 1.5rem; font-weight: 900; }
    .kd-rel-body { padding: 10px 12px; }
    .kd-rel-name { font-size: 0.82rem; font-weight: 700; color: var(--text); margin-bottom: 4px; }
    .kd-rel-price { font-size: 0.9rem; font-weight: 700; color: var(--orange); }

    @media (max-width: 768px) {
        .kd-top { grid-template-columns: 1fr; }
        .kd-slab-wrap { max-width: 100%; }
        .kd-bom-wrap { overflow-x: auto; }
    }
</style>
@endsection

@section("content")
<div class="kd-hero">
    <div class="kd-hero-inner">
        <div class="kd-crumb">
            <a href="{{ route('dashBoardFunction') }}">Home</a>
            <span>/</span>
            <a href="{{ route('shop') }}">Shop</a>
            <span>/</span>
            <span style="color:var(--text);">{{ $kit->item_name }}</span>
        </div>
    </div>
</div>

<div class="kd-wrap">
    <div class="kd-top">
        <div class="kd-img-box">
            @if($kit->image)
                <img src="{{ Storage::url($kit->image) }}" alt="{{ $kit->item_name }}">
            @else
                <span class="kd-img-empty">KIT</span>
            @endif
        </div>
        <div class="kd-info">
            @if($kit->category)
                <div class="kd-cat">{{ $kit->category->category_name }}</div>
            @endif
            <div class="kd-name">{{ $kit->item_name }}</div>
            <div class="kd-kit-badge">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
                Complete Solar Kit
            </div>
            @if($kit->description)
                <div class="kd-desc">{{ $kit->description }}</div>
            @endif
            <div class="kd-price">
                &#8377;{{ number_format($kit->kitSlabPrices->first()->price ?? $kit->current_sale_price) }}
                @if($kit->kitSlabPrices->count() > 1)
                    <span class="kd-price-note">onwards (bulk discounts available)</span>
                @endif
            </div>
            <span class="kd-stock {{ $kit->quantity > 0 ? 'in' : 'out' }}">
                {{ $kit->quantity > 0 ? $kit->quantity . ' kits in stock' : 'Out of stock' }}
            </span>
            <div class="kd-cta">
                <a href="https://wa.me/919876543210?text=I'm interested in {{ urlencode($kit->item_name) }}" target="_blank" class="kd-cta-btn">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492l4.632-1.467A11.932 11.932 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818c-2.168 0-4.19-.582-5.938-1.598l-.424-.254-2.752.872.87-2.685-.278-.44A9.778 9.778 0 012.182 12c0-5.414 4.404-9.818 9.818-9.818S21.818 6.586 21.818 12s-4.404 9.818-9.818 9.818z"/></svg>
                    Enquire on WhatsApp
                </a>
            </div>
        </div>
    </div>

    @if($kit->kitItems->count())
    <div class="kd-section">
        <div class="kd-section-title">
            <svg width="18" height="18" fill="none" stroke="var(--orange)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm0 5.25h.007v.008H3.75V12zm0 5.25h.007v.008H3.75v-.008z"/></svg>
            Kit Composition - What's Included
        </div>
        <div class="kd-bom-wrap">
            <table class="kd-bom">
                <thead>
                    <tr>
                        <th style="width:40%;">Category</th>
                        <th style="width:40%;">Item</th>
                        <th style="width:20%;">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @php $lastCat = ''; @endphp
                    @foreach($kit->kitItems as $item)
                        @if($item->category_label !== $lastCat)
                            @php $lastCat = $item->category_label; @endphp
                            <tr class="kd-bom-cat">
                                <td colspan="3">{{ $item->category_label }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td></td>
                            <td>{{ $item->item_name }}</td>
                            <td style="font-weight:600;">{{ $item->quantity_label }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if($kit->kitSlabPrices->count())
    <div class="kd-section">
        <div class="kd-section-title">
            <svg width="18" height="18" fill="none" stroke="var(--orange)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Bulk Pricing
        </div>
        <div class="kd-slab-wrap">
            <table class="kd-slab">
                <thead>
                    <tr>
                        <th>No. of Kits</th>
                        <th>Price per Kit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kit->kitSlabPrices as $slab)
                    <tr>
                        <td class="kd-slab-qty">
                            @if($slab->max_qty)
                                {{ $slab->min_qty == $slab->max_qty ? $slab->min_qty . ' Kit' : $slab->min_qty . ' - ' . $slab->max_qty . ' Kits' }}
                            @else
                                {{ $slab->min_qty }}+ Kits
                            @endif
                        </td>
                        <td class="kd-slab-price">&#8377;{{ number_format($slab->price, 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if($relatedKits->count())
    <div class="kd-section">
        <div class="kd-section-title">
            <svg width="18" height="18" fill="none" stroke="var(--orange)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
            Other Solar Kits
        </div>
        <div class="kd-related-grid">
            @foreach($relatedKits as $rk)
            <a href="{{ route('kit.show', $rk->slug) }}" class="kd-rel-card">
                @if($rk->image)
                    <img src="{{ Storage::url($rk->image) }}" class="kd-rel-img" alt="{{ $rk->item_name }}">
                @else
                    <div class="kd-rel-img-empty">{{ strtoupper(substr($rk->item_name,0,2)) }}</div>
                @endif
                <div class="kd-rel-body">
                    <div class="kd-rel-name">{{ $rk->item_name }}</div>
                    <div class="kd-rel-price">&#8377;{{ $rk->current_sale_price }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
