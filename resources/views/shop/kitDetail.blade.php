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

    .kd-info { display: flex; flex-direction: column; gap: 8px; align-items: flex-start; }
    .kd-cat { font-size: 0.72rem; color: var(--orange); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; }
    .kd-name { font-size: 1.5rem; font-weight: 800; color: var(--text); line-height: 1.3; align-self: stretch; }
    .kd-desc { font-size: 0.85rem; color: var(--muted); line-height: 1.6; margin-top: 4px; align-self: stretch; }
    .kd-price { font-size: 1.6rem; font-weight: 800; color: var(--orange); margin-top: 8px; }
    .kd-price-note { font-size: 0.75rem; color: var(--muted); }
    .kd-stock { display: inline-block; padding: 4px 12px; border-radius: 6px; font-size: 0.78rem; font-weight: 700; margin-top: 6px; width: fit-content; }
    .kd-stock.in { background: rgba(22,163,74,0.12); color: #22c55e; }
    .kd-stock.out { background: rgba(220,38,38,0.12); color: #ef4444; }
    .kd-kit-badge { display: inline-flex; align-items: center; gap: 5px; background: rgba(249,115,22,0.1); color: var(--orange); font-size: 0.75rem; font-weight: 700; padding: 4px 12px; border-radius: 6px; border: 1px solid rgba(249,115,22,0.2); margin-top: 6px; width: fit-content; }

    .kd-order-section { margin-top: 16px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
    .kd-qty-wrap { display: flex; align-items: center; gap: 0; }
    .kd-qty-btn { width: 36px; height: 36px; border: 1px solid var(--border); background: var(--card); color: var(--text); font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .kd-qty-btn:first-child { border-radius: 8px 0 0 8px; }
    .kd-qty-btn:last-child { border-radius: 0 8px 8px 0; }
    .kd-qty-input { width: 50px; height: 36px; border: 1px solid var(--border); border-left: 0; border-right: 0; background: var(--card); color: var(--text); text-align: center; font-size: 0.9rem; font-weight: 700; }
    .kd-qty-input::-webkit-inner-spin-button { -webkit-appearance: none; }
    .kd-order-btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 24px; background: var(--orange); color: #fff; border: none; border-radius: 8px; font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: background 0.15s; }
    .kd-order-btn:hover { background: #ea580c; }
    .kd-order-btn:disabled { opacity: 0.5; cursor: not-allowed; }
    .kd-max-info { font-size: 0.75rem; color: var(--muted); }
    .kd-order-msg { font-size: 0.82rem; margin-top: 8px; }

    .kd-section { margin-bottom: 2rem; }
    .kd-section-title { font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 8px; }

    .kd-bom { width: 100%; border-collapse: collapse; }
    .kd-bom-wrap { background: var(--card); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
    .kd-bom thead { background: rgba(249,115,22,0.08); }
    .kd-bom th { padding: 10px 14px; font-size: 0.72rem; font-weight: 700; color: var(--orange); text-transform: uppercase; letter-spacing: 0.5px; text-align: left; }
    .kd-bom td { padding: 8px 14px; font-size: 0.82rem; color: var(--text); border-top: 1px solid var(--border); }
    .kd-bom-cat { font-weight: 700; color: var(--orange); background: rgba(249,115,22,0.04); }
    .kd-bom-cat td { padding: 6px 14px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.03em; border-top: 2px solid var(--border); }

    .kd-slab { width: 100%; border-collapse: collapse; }
    .kd-slab-wrap { background: var(--card); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; max-width: 500px; }
    .kd-slab thead { background: rgba(249,115,22,0.08); }
    .kd-slab th { padding: 10px 14px; font-size: 0.72rem; font-weight: 700; color: var(--orange); text-transform: uppercase; letter-spacing: 0.5px; text-align: left; }
    .kd-slab td { padding: 9px 14px; font-size: 0.85rem; color: var(--text); border-top: 1px solid var(--border); }
    .kd-slab-qty { font-weight: 700; }
    .kd-slab-price { font-weight: 700; color: var(--orange); }

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
                <img src="{{ url('serve/' . $kit->image) }}" alt="{{ $kit->item_name }}">
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
            <span class="kd-stock {{ $kit->max_kits > 0 ? 'in' : 'out' }}">
                {{ $kit->max_kits > 0 ? $kit->max_kits . ' kits available' : 'Out of stock' }}
            </span>

            @auth
            <div class="kd-order-section">
                <div class="kd-qty-wrap">
                    <button type="button" class="kd-qty-btn" onclick="kitQtyChange(-1)">−</button>
                    <input type="number" id="kitQty" class="kd-qty-input" value="1" min="1" max="{{ $kit->max_kits }}">
                    <button type="button" class="kd-qty-btn" onclick="kitQtyChange(1)">+</button>
                </div>
                <button class="kd-order-btn" id="kitOrderBtn" onclick="orderKit()" {{ $kit->max_kits <= 0 ? 'disabled' : '' }}>
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                    Order Now
                </button>
                <span class="kd-max-info">Max: {{ $kit->max_kits }} kits</span>
            </div>
            <div class="kd-order-msg" id="kitOrderMsg"></div>
            @else
            <div class="kd-order-section">
                <a href="{{ route('login') }}" class="kd-order-btn">Login to Order</a>
            </div>
            @endauth
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
                        <th style="width:20%;">Qty per Kit</th>
                    </tr>
                </thead>
                <tbody>
                    @php $lastCat = ''; @endphp
                    @foreach($kit->kitItems as $item)
                        @php
                            $catName = $item->componentProduct?->category?->category_name ?? $item->category_label;
                            $itemName = $item->componentProduct?->item_name ?? $item->item_name;
                        @endphp
                        @if($catName !== $lastCat)
                            @php $lastCat = $catName; @endphp
                            <tr class="kd-bom-cat">
                                <td colspan="3">{{ $catName }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td></td>
                            <td>{{ $itemName }}</td>
                            <td style="font-weight:600;">{{ $item->quantity }}</td>
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
                    <img src="{{ url('serve/' . $rk->image) }}" class="kd-rel-img" alt="{{ $rk->item_name }}">
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

@section("js")
<script>
const maxKits = {{ $kit->max_kits }};
const slabPrices = @json($kit->kitSlabPrices);

function kitQtyChange(delta) {
    const input = document.getElementById('kitQty');
    let val = parseInt(input.value) || 1;
    val += delta;
    if (val < 1) val = 1;
    if (val > maxKits) val = maxKits;
    input.value = val;
}

document.getElementById('kitQty')?.addEventListener('input', function() {
    let val = parseInt(this.value) || 1;
    if (val < 1) this.value = 1;
    if (val > maxKits) this.value = maxKits;
});

function orderKit() {
    const qty = parseInt(document.getElementById('kitQty').value) || 1;
    const btn = document.getElementById('kitOrderBtn');
    const msg = document.getElementById('kitOrderMsg');

    if (qty < 1 || qty > maxKits) {
        msg.innerHTML = '<span style="color:#ef4444;">Invalid quantity</span>';
        return;
    }

    btn.disabled = true;
    btn.textContent = 'Placing Order...';

    fetch('{{ route("kit.order") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            kit_id: {{ $kit->id }},
            quantity: qty
        })
    })
    .then(r => r.json().then(data => ({ok: r.ok, data})))
    .then(({ok, data}) => {
        if (ok && data.success) {
            msg.innerHTML = '<span style="color:#22c55e;">' + data.message + '</span>';
            window.location.href = data.redirect || '/';
            return;
        } else {
            msg.innerHTML = '<span style="color:#ef4444;">' + (data.error || data.message || 'Order failed') + '</span>';
        }
    })
    .catch(e => {
        msg.innerHTML = '<span style="color:#ef4444;">Error: ' + e.message + '</span>';
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = 'Order Now';
    });
}
</script>
@endsection
