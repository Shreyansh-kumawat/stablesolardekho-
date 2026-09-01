@extends('layouts.adminLayout')

@section('css')
<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
<style>
    :root { --blue: #4A90E2; --light: #f5f7fa; --txt: #2d3436; --txt2: #636e72; --bdr: #e1e8ed; --green: #27ae60; --red: #e74c3c; }
    body { background: var(--light); }
    .pg-head { background: #fff; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; border-radius: 8px; border: 1px solid var(--bdr); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .75rem; }
    .pg-head h1 { font-size: 1.2rem; font-weight: 700; margin: 0; color: var(--txt); }
    .pg-head p { color: var(--txt2); font-size: .82rem; margin: .2rem 0 0; }
    .btn-blue { background: var(--blue); border: none; color: #fff; padding: .5rem 1.25rem; border-radius: 8px; font-weight: 700; font-size: .85rem; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: .4rem; }
    .btn-blue:hover { background: #3b7dc4; color: #fff; }
    .search-bar { display: flex; gap: .5rem; align-items: center; margin-bottom: 1.25rem; }
    .search-bar input { flex: 1; max-width: 320px; border: 1px solid var(--bdr); border-radius: 8px; padding: .5rem .75rem; font-size: .88rem; outline: none; }
    .search-bar input:focus { border-color: var(--blue); }
    .search-bar select { border: 1px solid var(--bdr); border-radius: 8px; padding: .5rem .75rem; font-size: .85rem; outline: none; }
    .entry-card { background: #fff; border: 1px solid var(--bdr); border-radius: 10px; padding: 1.25rem; margin-bottom: 1rem; transition: box-shadow .15s; }
    .entry-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.06); }
    .entry-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; margin-bottom: .75rem; flex-wrap: wrap; }
    .entry-product { display: flex; align-items: center; gap: .75rem; flex: 1; min-width: 0; }
    .entry-img { width: 52px; height: 52px; border-radius: 8px; object-fit: cover; border: 1px solid var(--bdr); flex-shrink: 0; }
    .entry-img-empty { width: 52px; height: 52px; border-radius: 8px; background: #eef1f5; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-weight: 800; color: rgba(74,144,226,.3); font-size: 1rem; }
    .entry-name { font-weight: 700; font-size: .92rem; color: var(--txt); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .entry-code { font-size: .75rem; color: var(--txt2); }
    .entry-meta { display: flex; gap: .5rem; flex-wrap: wrap; align-items: center; }
    .badge-in { background: #fdf0ef; color: var(--red); padding: .25rem .6rem; border-radius: 12px; font-weight: 700; font-size: .72rem; }
    .badge-out { background: #e8f8ef; color: var(--green); padding: .25rem .6rem; border-radius: 12px; font-weight: 700; font-size: .72rem; }
    .entry-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: .5rem .75rem; }
    .entry-field { font-size: .8rem; }
    .entry-field .lbl { color: var(--txt2); font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; }
    .entry-field .val { color: var(--txt); font-weight: 600; margin-top: 1px; }
    .entry-specs { margin-top: .75rem; padding-top: .75rem; border-top: 1px solid #eef1f5; }
    .entry-specs-title { font-size: .72rem; font-weight: 700; color: var(--blue); text-transform: uppercase; letter-spacing: .05em; margin-bottom: .35rem; }
    .spec-pills { display: flex; flex-wrap: wrap; gap: .35rem; }
    .spec-pill { background: #f0f4f8; padding: .2rem .55rem; border-radius: 6px; font-size: .72rem; color: var(--txt); }
    .spec-pill span { color: var(--txt2); }
    .empty-state { text-align: center; padding: 3rem 1rem; color: var(--txt2); }
    .empty-state svg { margin-bottom: .75rem; }
    @media(max-width:768px) { .entry-grid { grid-template-columns: 1fr 1fr; } }
    @media(max-width:480px) { .entry-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="pg-head">
        <div>
            <h1>Inventory Entries</h1>
            <p>All inventory activity log &mdash; who added what, when</p>
        </div>
        <a href="{{ route('inventoryAddProduct') }}" class="btn-blue">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Product
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:8px;font-size:.88rem;">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="search-bar">
        <input type="text" id="entrySearch" placeholder="Search by product, code, supplier, person, invoice, txn ID, or serial number...">
        <select id="entryTypeFilter">
            <option value="">All Types</option>
            <option value="IN">Stock IN</option>
            <option value="OUT">Stock OUT</option>
        </select>
    </div>

    <div id="entriesContainer">
    @forelse($entries as $entry)
        <div class="entry-card" data-type="{{ $entry->transaction_type }}" data-search="{{ strtolower(($entry->product->item_name ?? '').' '.($entry->product->item_code ?? '').' '.($entry->performer_name ?? '').' '.($entry->supplier_name ?? '').' '.($entry->channelPartner->cp_name ?? '').' '.($entry->invoice_number ?? '').' '.($entry->txn_id ?? '').' '.(isset($entry->batch_serials) ? $entry->batch_serials->pluck('serial_number')->implode(' ') : '')) }}">
            <div class="entry-top">
                <div class="entry-product">
                    @if($entry->product && $entry->product->image)
                        <img src="/serve/{{ $entry->product->image }}" class="entry-img" alt="">
                    @else
                        <div class="entry-img-empty">{{ strtoupper(substr($entry->product->item_name ?? '?', 0, 2)) }}</div>
                    @endif
                    <div style="min-width:0;">
                        <div class="entry-name">{{ $entry->product->item_name ?? 'Deleted Product' }}</div>
                        <div class="entry-code">{{ $entry->product->item_code ?? '-' }} &middot; {{ $entry->product->category->category_name ?? '' }}</div>
                    </div>
                </div>
                <div class="entry-meta">
                    <span class="{{ $entry->transaction_type === 'IN' ? 'badge-in' : 'badge-out' }}">
                        {{ $entry->transaction_type === 'IN' ? '+ IN' : '- OUT' }}
                    </span>
                    <span style="font-size:.78rem;color:var(--txt2);">{{ $entry->created_at ? $entry->created_at->format('d M Y, h:i A') : '-' }}</span>
                </div>
            </div>

            <div class="entry-grid">
                <div class="entry-field">
                    <div class="lbl">Quantity</div>
                    <div class="val">{{ $entry->quantity }} {{ $entry->product->uom ?? '' }}</div>
                </div>
                <div class="entry-field">
                    <div class="lbl">Added By</div>
                    <div class="val">{{ $entry->performer_name ?? '-' }}</div>
                </div>
                @if($entry->transaction_type === 'IN')
                <div class="entry-field price-wrap" data-id="{{ $entry->id }}" style="position:relative;">
                    <div class="lbl">Purchase Price
                        <button type="button" onclick="togglePriceEdit(this)" style="background:none;border:none;color:#4A90E2;cursor:pointer;font-size:.72rem;margin-left:6px;">
                            <i class="fas fa-pencil-alt"></i> Edit
                        </button>
                    </div>
                    <div class="val price-display">
                        @if($entry->unit_price)
                            &#8377;{{ number_format($entry->unit_price, 2) }}
                            @if($entry->gst_percent) <span style="color:#0369a1;font-size:.75rem;font-weight:600;"> + {{ rtrim(rtrim(number_format($entry->gst_percent, 2), '0'), '.') }}% GST</span> @endif
                            @if($entry->total_with_gst) <div style="font-size:.75rem;color:#059669;font-weight:600;">Total: &#8377;{{ number_format($entry->total_with_gst, 2) }}</div> @endif
                        @else
                            <span style="color:#9ca3af;font-style:italic;">Not set</span>
                        @endif
                    </div>
                    <div class="price-edit-form" style="display:none; position:absolute; top:100%; left:0; z-index:100; background:#fff; padding:10px 12px; border:1px solid #d1d5db; border-radius:8px; box-shadow:0 8px 20px rgba(0,0,0,0.12); margin-top:4px; min-width:340px;">
                        <div style="display:flex; gap:6px; align-items:center; flex-wrap:wrap;">
                            <input type="number" step="0.01" min="0" class="price-input" placeholder="Unit Price"
                                   value="{{ $entry->unit_price }}"
                                   style="width:100px;padding:5px 8px;border:1.5px solid #4A90E2;border-radius:6px;font-size:.82rem;">
                            <input type="number" step="0.01" min="0" max="100" class="gst-input" placeholder="GST %"
                                   value="{{ $entry->gst_percent }}"
                                   style="width:70px;padding:5px 8px;border:1.5px solid #4A90E2;border-radius:6px;font-size:.82rem;">
                            <button type="button" onclick="savePriceEdit(this)" style="background:#059669;color:#fff;border:none;padding:6px 12px;border-radius:5px;font-size:.75rem;font-weight:600;cursor:pointer;">
                                <i class="fas fa-check me-1"></i> Save
                            </button>
                            <button type="button" onclick="cancelPriceEdit(this)" style="background:#f3f4f6;color:#374151;border:1px solid #d1d5db;padding:6px 12px;border-radius:5px;font-size:.75rem;font-weight:600;cursor:pointer;">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
                @endif
                @if($entry->invoice_number)
                <div class="entry-field">
                    <div class="lbl">Invoice</div>
                    <div class="val">{{ $entry->invoice_number }}</div>
                </div>
                @endif
                @if($entry->invoice_date)
                <div class="entry-field">
                    <div class="lbl">Invoice Date</div>
                    <div class="val">{{ $entry->invoice_date }}</div>
                </div>
                @endif
                @if($entry->supplier_name)
                <div class="entry-field">
                    <div class="lbl">Supplier</div>
                    <div class="val">{{ $entry->supplier_name }}</div>
                </div>
                @elseif($entry->channelPartner)
                <div class="entry-field">
                    <div class="lbl">{{ $entry->transaction_type === 'IN' ? 'Supplier' : 'Transferred To' }}</div>
                    <div class="val">{{ $entry->channelPartner->cp_name }}</div>
                </div>
                @endif
                @if($entry->txn_id)
                <div class="entry-field">
                    <div class="lbl">Txn ID</div>
                    <div class="val" style="font-family:monospace;font-size:.78rem;">{{ $entry->txn_id }}</div>
                </div>
                @endif
                <div class="entry-field" style="grid-column:1/-1;">
                    <div class="lbl">Remarks</div>
                    <div class="val remark-wrap" data-id="{{ $entry->id }}">
                        <span class="remark-text" onclick="editRemark(this)" style="cursor:pointer;min-width:60px;display:inline-block;{{ $entry->remarks ? '' : 'color:#9ca3af;font-style:italic;' }}">{{ $entry->remarks ?: 'Add remark...' }}</span>
                        <input type="text" class="remark-input" value="{{ $entry->remarks }}" style="display:none;width:100%;padding:4px 8px;border:1.5px solid #4A90E2;border-radius:6px;font-size:.82rem;outline:none;" onblur="saveRemark(this)" onkeydown="if(event.key==='Enter')this.blur()">
                    </div>
                </div>
            </div>

            @if(isset($entry->batch_serials) && $entry->batch_serials->count())
            <div class="entry-serials" style="margin-top:12px; padding:12px 14px; background:#f0f9ff; border:1px solid #bae6fd; border-radius:8px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                    <span style="font-size:.78rem; font-weight:700; color:#0369a1; text-transform:uppercase; letter-spacing:.05em;">
                        <i class="fas fa-barcode me-1"></i> Serial Numbers ({{ $entry->batch_serials->count() }})
                    </span>
                    <button type="button" onclick="toggleSerials(this)" style="background:none; border:1px solid #7dd3fc; color:#0369a1; padding:3px 10px; border-radius:5px; font-size:.72rem; font-weight:600; cursor:pointer;">
                        <i class="fas fa-eye me-1"></i> Show
                    </button>
                </div>
                <div class="serial-chip-wrap" style="display:none; flex-wrap:wrap; gap:5px;">
                    @foreach($entry->batch_serials as $sn)
                    <span style="display:inline-flex; align-items:center; gap:4px; background:#fff; border:1px solid #bae6fd; color:#075985; font-family:monospace; font-size:.72rem; padding:3px 8px; border-radius:4px;" title="Status: {{ $sn->status }}">
                        {{ $sn->serial_number }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            @if($entry->product && ($entry->product->brand || $entry->product->manufacturer_warranty || $entry->product->type || $entry->product->customSpecs->count()))
            <div class="entry-specs">
                <div class="entry-specs-title">Specifications</div>
                <div class="spec-pills">
                    @if($entry->product->type)<div class="spec-pill"><span>Type:</span> {{ $entry->product->type }}</div>@endif
                    @if($entry->product->brand)<div class="spec-pill"><span>Brand:</span> {{ $entry->product->brand }}</div>@endif
                    @if($entry->product->model)<div class="spec-pill"><span>Model:</span> {{ $entry->product->model }}</div>@endif
                    @if($entry->product->manufacturer_warranty)<div class="spec-pill"><span>Warranty:</span> {{ $entry->product->manufacturer_warranty }}</div>@endif
                    @if($entry->product->operating_voltage)<div class="spec-pill"><span>Voltage:</span> {{ $entry->product->operating_voltage }}</div>@endif
                    @if($entry->product->solar_panel_type)<div class="spec-pill"><span>Panel:</span> {{ $entry->product->solar_panel_type }}</div>@endif
                    @if($entry->product->mnre_approved)<div class="spec-pill"><span>MNRE:</span> {{ $entry->product->mnre_approved }}</div>@endif
                    @if($entry->product->country_of_origin)<div class="spec-pill"><span>Origin:</span> {{ $entry->product->country_of_origin }}</div>@endif
                    @foreach($entry->product->customSpecs as $cs)
                    <div class="spec-pill"><span>{{ $cs->spec_name }}:</span> {{ $cs->spec_value }}</div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    @empty
        <div class="empty-state">
            <svg width="48" height="48" fill="none" stroke="var(--txt2)" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
            <p style="font-weight:600;">No inventory entries yet</p>
            <p>Add a product to get started.</p>
        </div>
    @endforelse
    </div>
</div>
@endsection

@section('js')
<script>
document.getElementById('entrySearch').addEventListener('input', filterEntries);
document.getElementById('entryTypeFilter').addEventListener('change', filterEntries);

function filterEntries() {
    const q = document.getElementById('entrySearch').value.toLowerCase();
    const type = document.getElementById('entryTypeFilter').value;
    document.querySelectorAll('.entry-card').forEach(card => {
        const matchQ = !q || card.dataset.search.includes(q);
        const matchT = !type || card.dataset.type === type;
        card.style.display = (matchQ && matchT) ? '' : 'none';
    });
}

function editRemark(span) {
    const wrap = span.closest('.remark-wrap');
    const input = wrap.querySelector('.remark-input');
    span.style.display = 'none';
    input.style.display = '';
    input.focus();
    input.select();
}

function saveRemark(input) {
    const wrap = input.closest('.remark-wrap');
    const span = wrap.querySelector('.remark-text');
    const id = wrap.dataset.id;
    const val = input.value.trim();

    input.style.display = 'none';
    span.style.display = '';
    span.textContent = val || 'Add remark...';
    span.style.color = val ? '' : '#9ca3af';
    span.style.fontStyle = val ? '' : 'italic';

    fetch(`/admin/inventory/entry/${id}/update-remarks`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({ remarks: val })
    });
}

function toggleSerials(btn) {
    var wrap = btn.parentElement.nextElementSibling;
    if (wrap.style.display === 'none') {
        wrap.style.display = 'flex';
        btn.innerHTML = '<i class="fas fa-eye-slash me-1"></i> Hide';
    } else {
        wrap.style.display = 'none';
        btn.innerHTML = '<i class="fas fa-eye me-1"></i> Show';
    }
}

function togglePriceEdit(btn) {
    const wrap = btn.closest('.price-wrap');
    wrap.querySelector('.price-display').style.display = 'none';
    wrap.querySelector('.price-edit-form').style.display = '';
    wrap.querySelector('.price-input').focus();
}
function cancelPriceEdit(btn) {
    const wrap = btn.closest('.price-wrap');
    wrap.querySelector('.price-display').style.display = '';
    wrap.querySelector('.price-edit-form').style.display = 'none';
}
function savePriceEdit(btn) {
    const wrap = btn.closest('.price-wrap');
    const id = wrap.getAttribute('data-id');
    const price = wrap.querySelector('.price-input').value;
    const gst = wrap.querySelector('.gst-input').value;

    fetch(`/admin/inventory/entry/${id}/update-price`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({ unit_price: price, gst_percent: gst })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            var displayHtml = '';
            if (price) {
                displayHtml = '&#8377;' + Number(price).toLocaleString('en-IN', {minimumFractionDigits:2});
                if (gst) displayHtml += ' <span style="color:#0369a1;font-size:.75rem;font-weight:600;"> + ' + gst + '% GST</span>';
                if (data.total_with_gst) displayHtml += '<div style="font-size:.75rem;color:#059669;font-weight:600;">Total: &#8377;' + Number(data.total_with_gst).toLocaleString('en-IN', {minimumFractionDigits:2}) + '</div>';
            } else {
                displayHtml = '<span style="color:#9ca3af;font-style:italic;">Not set</span>';
            }
            wrap.querySelector('.price-display').innerHTML = displayHtml;
            cancelPriceEdit(btn);
        }
    })
    .catch(() => alert('Failed to update.'));
}
</script>
@endsection
