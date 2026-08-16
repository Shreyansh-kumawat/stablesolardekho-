@extends('layouts.adminLayout')
@section('title', 'CP Inventory - ' . $cp->cp_name)
@section('page_title', 'CP Inventory')

@section('css')
<style>
    .cpd-wrap { padding: 1.25rem; }
    .cpd-header { display: flex; align-items: center; gap: 12px; margin-bottom: 1.25rem; flex-wrap: wrap; }
    .cpd-icon { width: 40px; height: 40px; background: #2563eb; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .cpd-header h1 { font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 0; }
    .cpd-header p { font-size: 0.78rem; color: #64748b; margin: 2px 0 0; }
    .cpd-back { display: inline-flex; align-items: center; gap: 4px; padding: 5px 12px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; text-decoration: none; background: #f1f5f9; color: #374151; margin-left: auto; }
    .cpd-back:hover { background: #e2e8f0; color: #374151; }
    .cpd-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .cpd-controls { display: flex; align-items: center; justify-content: flex-end; padding: 12px 16px; border-bottom: 1px solid #f1f5f9; }
    .cpd-table { width: 100%; border-collapse: collapse; font-size: 0.84rem; }
    .cpd-table thead { background: #f8fafc; }
    .cpd-table th { padding: 10px 14px; font-weight: 700; color: #374151; text-transform: uppercase; font-size: 0.72rem; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; text-align: left; }
    .cpd-table td { padding: 10px 14px; border-bottom: 1px solid #f1f5f9; color: #374151; vertical-align: middle; }
    .cpd-table tbody tr:hover { background: #f8fafc; }
    .cpd-stock { font-weight: 700; color: #059669; }
    .cpd-btn { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: all 0.15s; }
    .cpd-btn-primary { background: #2563eb; color: #fff; }
    .cpd-btn-primary:hover { background: #1d4ed8; color: #fff; }
    .cpd-btn-green { background: #059669; color: #fff; }
    .cpd-btn-green:hover { background: #047857; color: #fff; }
    .cpd-btn-cancel { background: #f3f4f6; color: #374151; }
    .cpd-btn-cancel:hover { background: #e5e7eb; }
    .cpd-empty { text-align: center; padding: 3rem 1rem; color: #94a3b8; }

    .cpd-modal-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4); z-index: 1000; align-items: center; justify-content: center; }
    .cpd-modal-overlay.active { display: flex; }
    .cpd-modal { background: #fff; border-radius: 12px; padding: 1.5rem; width: 90%; max-width: 420px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
    .cpd-modal h3 { font-size: 1rem; font-weight: 700; color: #1f2937; margin: 0 0 1rem; }
    .cpd-modal label { display: block; font-size: .8rem; font-weight: 600; color: #374151; margin-bottom: .35rem; }
    .cpd-modal input, .cpd-modal select { width: 100%; padding: 8px 10px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: .85rem; margin-bottom: .75rem; }
    .cpd-modal input:focus, .cpd-modal select:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 2px rgba(37,99,235,0.1); }
    .cpd-modal-actions { display: flex; gap: 8px; justify-content: flex-end; margin-top: .5rem; }
    .searchable-select { position: relative; }
    .searchable-select input.ss-input { width: 100%; padding: 8px 10px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: .85rem; margin-bottom: .75rem; }
    .searchable-select input.ss-input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 2px rgba(37,99,235,0.1); }
    .ss-dropdown { display: none; position: absolute; top: calc(100% - .75rem); left: 0; right: 0; background: #fff; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 8px 8px; max-height: 200px; overflow-y: auto; z-index: 100; box-shadow: 0 6px 16px rgba(0,0,0,0.1); }
    .ss-dropdown.open { display: block; }
    .ss-option { padding: 8px 12px; cursor: pointer; font-size: .83rem; color: #374151; border-bottom: 1px solid #f5f5f5; }
    .ss-option:hover { background: #eef3ff; }
    .ss-option.hidden { display: none; }
</style>
@endsection

@section('content')
<div class="cpd-wrap">
    <div class="cpd-header">
        <div class="cpd-icon">
            <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
        </div>
        <div>
            <h1>{{ $cp->cp_name }} - Inventory</h1>
            <p>Manage inventory for this channel partner</p>
        </div>
        <a href="{{ route('adminCpInventoryList') }}" class="cpd-back">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Back to List
        </a>
    </div>

    <div class="cpd-card">
        <div class="cpd-controls">
            <button class="cpd-btn cpd-btn-primary" onclick="document.getElementById('addStockModal').classList.add('active')">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Stock
            </button>
        </div>
        <div style="overflow-x:auto;">
            <table class="cpd-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Item Code</th>
                        <th>UOM</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventory as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->item_code }}</td>
                        <td>{{ $item->uom }}</td>
                        <td>{{ $item->category_name ?? '-' }}</td>
                        <td><span class="cpd-stock">{{ $item->available_qty }}</span></td>
                        <td>
                            <button class="cpd-btn cpd-btn-green" style="font-size:.7rem;padding:4px 8px;" onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->item_name) }}', {{ $item->available_qty }})">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z"/></svg>
                                Edit
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="cpd-empty">No inventory items for this CP. Click "Add Stock" to add.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="cpd-modal-overlay" id="addStockModal">
    <div class="cpd-modal">
        <h3>Add Stock for {{ $cp->cp_name }}</h3>
        <form method="POST" action="{{ route('adminCpAddStock', $cp->id) }}">
            @csrf
            <label>Product</label>
            <div class="searchable-select">
                <input type="text" class="ss-input" placeholder="Search product..." autocomplete="off" onclick="ssToggle(this, true)" oninput="ssFilter(this)">
                <select name="product_id" id="addStockProduct" required onchange="updateStockHint()" style="display:none;">
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                    @php $whStock = $product->inventory ? $product->inventory->available_qty : 0; @endphp
                    <option value="{{ $product->id }}" data-stock="{{ $whStock }}" data-price="{{ $product->current_sale_price ?? 0 }}">
                        {{ $product->item_name }} ({{ $product->item_code }})
                    </option>
                    @endforeach
                </select>
                <div class="ss-dropdown">
                    @foreach($products as $product)
                    @php $whStock = $product->inventory ? $product->inventory->available_qty : 0; @endphp
                    <div class="ss-option" data-value="{{ $product->id }}" data-text="{{ $product->item_name }} ({{ $product->item_code }})" onclick="ssPick(this)">
                        <strong>{{ $product->item_name }}</strong> <span style="color:#9ca3af;font-size:.75rem;">{{ $product->item_code }} — Stock: {{ $whStock }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div id="stockHintBox" style="display:none; margin:-0.5rem 0 0.75rem; padding:8px 10px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:6px; font-size:.78rem;">
                <span style="color:#059669; font-weight:600;">Warehouse Stock: <span id="stockHintQty">0</span></span>
                <span style="color:#64748b; margin-left:8px;">| Price: ₹<span id="stockHintPrice">0</span></span>
            </div>
            <label>Quantity</label>
            <input type="number" name="quantity" id="addStockQty" min="1" required placeholder="Enter quantity">
            <div class="cpd-modal-actions">
                <button type="button" class="cpd-btn cpd-btn-cancel" onclick="document.getElementById('addStockModal').classList.remove('active')">Cancel</button>
                <button type="submit" class="cpd-btn cpd-btn-primary">Add Stock</button>
            </div>
        </form>
    </div>
</div>

<div class="cpd-modal-overlay" id="editStockModal">
    <div class="cpd-modal">
        <h3>Edit Stock: <span id="editItemName"></span></h3>
        <form method="POST" id="editStockForm">
            @csrf
            <label>Available Quantity</label>
            <input type="number" name="available_qty" id="editQtyInput" min="0" required>
            <div class="cpd-modal-actions">
                <button type="button" class="cpd-btn cpd-btn-cancel" onclick="document.getElementById('editStockModal').classList.remove('active')">Cancel</button>
                <button type="submit" class="cpd-btn cpd-btn-green">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
function openEditModal(invId, itemName, currentQty) {
    document.getElementById('editItemName').textContent = itemName;
    document.getElementById('editQtyInput').value = currentQty;
    document.getElementById('editStockForm').action = '{{ url("admin/cp-inventory") }}/' + invId + '/admin-update-stock';
    document.getElementById('editStockModal').classList.add('active');
}

function ssToggle(input, show) { input.closest('.searchable-select').querySelector('.ss-dropdown').classList.toggle('open', show); }
function ssFilter(input) {
    var dd = input.closest('.searchable-select').querySelector('.ss-dropdown');
    dd.classList.add('open');
    var q = input.value.toLowerCase();
    dd.querySelectorAll('.ss-option').forEach(function(o) { o.classList.toggle('hidden', q.length > 0 && o.getAttribute('data-text').toLowerCase().indexOf(q) === -1); });
}
function ssPick(opt) {
    var wrap = opt.closest('.searchable-select');
    var sel = wrap.querySelector('select');
    sel.value = opt.getAttribute('data-value');
    wrap.querySelector('.ss-input').value = opt.getAttribute('data-text');
    wrap.querySelector('.ss-dropdown').classList.remove('open');
    sel.dispatchEvent(new Event('change'));
}
document.addEventListener('click', function(e) { document.querySelectorAll('.ss-dropdown.open').forEach(function(dd) { if (!dd.parentElement.contains(e.target)) dd.classList.remove('open'); }); });

function updateStockHint() {
    var sel = document.getElementById('addStockProduct');
    var opt = sel.options[sel.selectedIndex];
    var box = document.getElementById('stockHintBox');
    var qtyInput = document.getElementById('addStockQty');
    if (!opt.value) { box.style.display = 'none'; qtyInput.removeAttribute('max'); return; }
    var stock = parseInt(opt.dataset.stock) || 0;
    var price = opt.dataset.price || '0';
    document.getElementById('stockHintQty').textContent = stock;
    document.getElementById('stockHintPrice').textContent = price;
    qtyInput.setAttribute('max', stock);
    box.style.display = 'block';
}
</script>
@endsection
