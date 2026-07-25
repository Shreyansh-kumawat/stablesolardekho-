@extends('layouts.adminLayout')
@section('title', 'My Inventory')

@section('css')
<style>
    .inv-wrap { max-width: 1100px; margin: 0 auto; padding: 1.5rem 1rem; }
    .inv-header { margin-bottom: 1.25rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .75rem; }
    .inv-header-left { display: flex; align-items: center; gap: .75rem; }
    .inv-header-left h1 { font-size: 1.3rem; font-weight: 800; color: #1f2937; margin: 0; }
    .inv-header-left p { font-size: .8rem; color: #6b7280; margin: .15rem 0 0; }
    .inv-icon-box { width: 40px; height: 40px; background: #2563eb; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; }

    .inv-btn { display: inline-flex; align-items: center; gap: 5px; padding: 6px 14px; border-radius: 8px; font-size: 0.78rem; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: all 0.15s; }
    .inv-btn-primary { background: #2563eb; color: #fff; }
    .inv-btn-primary:hover { background: #1d4ed8; color: #fff; }

    .inv-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 16px; }

    .inv-item { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 1.25rem; position: relative; transition: box-shadow 0.2s; }
    .inv-item:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.07); }
    .inv-item-name { font-size: 0.92rem; font-weight: 700; color: #1f2937; margin: 0 0 2px; line-height: 1.3; }
    .inv-item-code { font-size: 0.72rem; color: #9ca3af; font-family: monospace; }
    .inv-item-category { font-size: 0.7rem; color: #6b7280; margin-top: 4px; }

    .inv-stock-section { margin-top: 14px; }
    .inv-stock-label { font-size: 0.68rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
    .inv-stock-value { font-size: 1.6rem; font-weight: 800; color: #059669; line-height: 1; }
    .inv-stock-value.out-of-stock { color: #dc2626; }

    .inv-controls { display: flex; align-items: center; gap: 8px; margin-top: 12px; }
    .inv-qty-btn { width: 36px; height: 36px; border-radius: 8px; border: 1px solid #e5e7eb; background: #f9fafb; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 1.1rem; font-weight: 700; color: #374151; transition: all 0.15s; }
    .inv-qty-btn:hover { background: #e5e7eb; }
    .inv-qty-btn.minus { color: #dc2626; border-color: #fecaca; }
    .inv-qty-btn.minus:hover { background: #fee2e2; }
    .inv-qty-btn.plus { color: #059669; border-color: #a7f3d0; }
    .inv-qty-btn.plus:hover { background: #d1fae5; }
    .inv-qty-input { width: 60px; height: 36px; text-align: center; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 0.9rem; font-weight: 700; color: #1f2937; }
    .inv-qty-input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 2px rgba(37,99,235,0.1); }

    .inv-oos { margin-top: 10px; display: flex; align-items: center; justify-content: space-between; }
    .inv-oos-badge { font-size: 0.72rem; font-weight: 700; color: #dc2626; background: #fee2e2; padding: 3px 10px; border-radius: 20px; }
    .inv-delete-btn { font-size: 0.7rem; font-weight: 600; color: #dc2626; background: none; border: 1px solid #fecaca; border-radius: 6px; padding: 3px 10px; cursor: pointer; transition: all 0.15s; }
    .inv-delete-btn:hover { background: #fee2e2; }

    .inv-save-msg { font-size: 0.68rem; color: #059669; font-weight: 600; margin-left: 4px; opacity: 0; transition: opacity 0.3s; }
    .inv-save-msg.show { opacity: 1; }

    .inv-empty { text-align: center; padding: 4rem 2rem; }
    .inv-empty-icon { width: 64px; height: 64px; background: #f3f4f6; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
    .inv-empty h3 { font-size: 1rem; font-weight: 700; color: #374151; margin: 0 0 4px; }
    .inv-empty p { font-size: 0.82rem; color: #9ca3af; margin: 0; }

    .inv-modal-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4); z-index: 1000; align-items: center; justify-content: center; }
    .inv-modal-overlay.active { display: flex; }
    .inv-modal { background: #fff; border-radius: 14px; padding: 1.5rem; width: 90%; max-width: 420px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
    .inv-modal h3 { font-size: 1rem; font-weight: 700; color: #1f2937; margin: 0 0 1rem; }
    .inv-modal label { display: block; font-size: .8rem; font-weight: 600; color: #374151; margin-bottom: .35rem; }
    .inv-modal input, .inv-modal select { width: 100%; padding: 8px 10px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: .85rem; margin-bottom: .75rem; }
    .inv-modal input:focus, .inv-modal select:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 2px rgba(37,99,235,0.1); }
    .inv-modal-actions { display: flex; gap: 8px; justify-content: flex-end; margin-top: .5rem; }
    .inv-btn-cancel { background: #f3f4f6; color: #374151; }
    .inv-btn-cancel:hover { background: #e5e7eb; }

    .inv-total-bar { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 14px 20px; margin-bottom: 16px; display: flex; align-items: center; gap: 12px; }
    .inv-total-bar span:first-child { font-size: 0.78rem; font-weight: 600; color: #6b7280; }
    .inv-total-bar strong { font-size: 1.1rem; font-weight: 800; color: #1f2937; }

    @media(max-width:640px) {
        .inv-grid { grid-template-columns: 1fr; }
        .inv-wrap { padding: 12px; }
    }
</style>
@endsection

@section('content')
<div class="inv-wrap">
    <div class="inv-header">
        <div class="inv-header-left">
            <div class="inv-icon-box">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
            </div>
            <div>
                <h1>My Inventory</h1>
                <p>Manage your stock. Delivered orders are auto-added here.</p>
            </div>
        </div>
        <button class="inv-btn inv-btn-primary" onclick="document.getElementById('addStockModal').classList.add('active')">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Stock
        </button>
    </div>

    @if(($inventory_list ?? collect())->count() > 0)
    <div class="inv-total-bar">
        <span>Total Items:</span>
        <strong>{{ $inventory_list->count() }}</strong>
        <span style="margin-left:auto;">Total Stock:</span>
        <strong>{{ $inventory_list->sum('current_stock') }}</strong>
    </div>

    <div class="inv-grid">
        @foreach($inventory_list as $item)
        <div class="inv-item" id="inv-item-{{ $item->inv_id }}">
            <div class="inv-item-name">{{ $item->item_name }}</div>
            <div class="inv-item-code">{{ $item->item_code }}</div>
            @if($item->category_name)
            <div class="inv-item-category">{{ $item->category_name }}</div>
            @endif

            <div class="inv-stock-section">
                <div class="inv-stock-label">Current Stock</div>
                <div class="inv-stock-value {{ $item->current_stock <= 0 ? 'out-of-stock' : '' }}" id="stock-display-{{ $item->inv_id }}">
                    {{ $item->current_stock }}
                </div>
            </div>

            @if($item->current_stock <= 0)
            <div class="inv-oos" id="oos-section-{{ $item->inv_id }}">
                <span class="inv-oos-badge">Out of Stock</span>
                <form method="POST" action="{{ route('cpDeleteInventory', $item->inv_id) }}" style="display:inline;" onsubmit="return confirm('Remove this item from inventory?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inv-delete-btn">
                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:2px;"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                        Remove
                    </button>
                </form>
            </div>
            @endif

            <div class="inv-controls">
                <button class="inv-qty-btn minus" onclick="adjustQty({{ $item->inv_id }}, -1)">−</button>
                <input type="number" class="inv-qty-input" id="qty-input-{{ $item->inv_id }}" value="{{ $item->current_stock }}" min="0" onchange="saveQty({{ $item->inv_id }})">
                <button class="inv-qty-btn plus" onclick="adjustQty({{ $item->inv_id }}, 1)">+</button>
                <span class="inv-save-msg" id="save-msg-{{ $item->inv_id }}">Saved!</span>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="inv-empty">
        <div class="inv-empty-icon">
            <svg width="28" height="28" fill="none" stroke="#9ca3af" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
        </div>
        <h3>No inventory items yet</h3>
        <p>Your delivered orders will automatically appear here, or click "Add Stock" to add manually.</p>
    </div>
    @endif
</div>

<div class="inv-modal-overlay" id="addStockModal">
    <div class="inv-modal">
        <h3>Add Stock</h3>
        <form method="POST" action="{{ route('cpAddStock') }}">
            @csrf
            <label>Product</label>
            <select name="product_id" required>
                <option value="">Select Product</option>
                @foreach(\App\Models\Product::orderBy('item_name')->get() as $product)
                <option value="{{ $product->id }}">{{ $product->item_name }} ({{ $product->item_code }})</option>
                @endforeach
            </select>
            <label>Quantity</label>
            <input type="number" name="quantity" min="1" required placeholder="Enter quantity">
            <div class="inv-modal-actions">
                <button type="button" class="inv-btn inv-btn-cancel" onclick="document.getElementById('addStockModal').classList.remove('active')">Cancel</button>
                <button type="submit" class="inv-btn inv-btn-primary">Add Stock</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
var saveTimers = {};

function adjustQty(invId, delta) {
    var input = document.getElementById('qty-input-' + invId);
    var val = parseInt(input.value) || 0;
    val += delta;
    if (val < 0) val = 0;
    input.value = val;
    saveQty(invId);
}

function saveQty(invId) {
    clearTimeout(saveTimers[invId]);
    saveTimers[invId] = setTimeout(function() {
        var qty = parseInt(document.getElementById('qty-input-' + invId).value) || 0;
        var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('{{ url("channel-partner/cp-inventory") }}/' + invId + '/update-stock', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ available_qty: qty })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                var display = document.getElementById('stock-display-' + invId);
                display.textContent = data.qty;
                if (data.qty <= 0) {
                    display.classList.add('out-of-stock');
                    showOos(invId);
                } else {
                    display.classList.remove('out-of-stock');
                    hideOos(invId);
                }
                var msg = document.getElementById('save-msg-' + invId);
                msg.classList.add('show');
                setTimeout(function() { msg.classList.remove('show'); }, 1500);
            }
        })
        .catch(function() {});
    }, 500);
}

function showOos(invId) {
    var section = document.getElementById('oos-section-' + invId);
    if (!section) {
        var item = document.getElementById('inv-item-' + invId);
        var controls = item.querySelector('.inv-controls');
        var div = document.createElement('div');
        div.className = 'inv-oos';
        div.id = 'oos-section-' + invId;
        div.innerHTML = '<span class="inv-oos-badge">Out of Stock</span>' +
            '<form method="POST" action="{{ url("channel-partner/cp-inventory") }}/' + invId + '/delete" style="display:inline;" onsubmit="return confirm(\'Remove this item from inventory?\')">' +
            '<input type="hidden" name="_token" value="' + document.querySelector('meta[name="csrf-token"]').getAttribute('content') + '">' +
            '<input type="hidden" name="_method" value="DELETE">' +
            '<button type="submit" class="inv-delete-btn">Remove</button></form>';
        item.insertBefore(div, controls);
    }
}

function hideOos(invId) {
    var section = document.getElementById('oos-section-' + invId);
    if (section) section.remove();
}
</script>
@endsection
