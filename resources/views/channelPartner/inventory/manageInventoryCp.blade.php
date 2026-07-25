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
    .inv-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
    .inv-table { width: 100%; border-collapse: collapse; font-size: 0.84rem; }
    .inv-table thead th { background: #f9fafb; padding: 10px 14px; font-size: .72rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: .05em; text-align: left; border-bottom: 1px solid #e5e7eb; white-space: nowrap; }
    .inv-table tbody td { padding: 10px 14px; color: #374151; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
    .inv-table tbody tr:hover { background: #f9fafb; }
    .inv-btn { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: all 0.15s; }
    .inv-btn-primary { background: #2563eb; color: #fff; }
    .inv-btn-primary:hover { background: #1d4ed8; color: #fff; }
    .inv-btn-green { background: #059669; color: #fff; }
    .inv-btn-green:hover { background: #047857; color: #fff; }
    .inv-btn-sm { padding: 4px 8px; font-size: 0.7rem; }
    .inv-stock { font-weight: 700; color: #059669; }
    .inv-empty { text-align: center; padding: 3rem 1rem; color: #94a3b8; }

    .inv-modal-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4); z-index: 1000; align-items: center; justify-content: center; }
    .inv-modal-overlay.active { display: flex; }
    .inv-modal { background: #fff; border-radius: 12px; padding: 1.5rem; width: 90%; max-width: 420px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
    .inv-modal h3 { font-size: 1rem; font-weight: 700; color: #1f2937; margin: 0 0 1rem; }
    .inv-modal label { display: block; font-size: .8rem; font-weight: 600; color: #374151; margin-bottom: .35rem; }
    .inv-modal input, .inv-modal select { width: 100%; padding: 8px 10px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: .85rem; margin-bottom: .75rem; }
    .inv-modal input:focus, .inv-modal select:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 2px rgba(37,99,235,0.1); }
    .inv-modal-actions { display: flex; gap: 8px; justify-content: flex-end; margin-top: .5rem; }
    .inv-btn-cancel { background: #f3f4f6; color: #374151; }
    .inv-btn-cancel:hover { background: #e5e7eb; }

    @media(max-width:768px) {
        .inv-wrap { padding: 12px; }
        .inv-table { font-size: 0.75rem; }
        .inv-table th, .inv-table td { padding: 8px 10px; }
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
                <p>View and manage your stock</p>
            </div>
        </div>
        <button class="inv-btn inv-btn-primary" onclick="document.getElementById('addStockModal').classList.add('active')">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Stock
        </button>
    </div>

    <div class="inv-card">
        <div style="overflow-x:auto;">
            <table class="inv-table">
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
                    @forelse(($inventory_list ?? []) as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ data_get($item, 'item_name', '') }}</td>
                        <td>{{ data_get($item, 'item_code', '') }}</td>
                        <td>{{ data_get($item, 'uom', '') }}</td>
                        <td>{{ data_get($item, 'category_name', '') }}</td>
                        <td><span class="inv-stock">{{ data_get($item, 'current_stock', 0) }}</span></td>
                        <td>
                            @if(isset($item->inv_id))
                            <button class="inv-btn inv-btn-green inv-btn-sm" onclick="openEditModal({{ $item->inv_id }}, '{{ addslashes(data_get($item, 'item_name', '')) }}', {{ data_get($item, 'current_stock', 0) }})">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z"/></svg>
                                Edit
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="inv-empty">No inventory items yet. Click "Add Stock" to add your first item.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
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

<div class="inv-modal-overlay" id="editStockModal">
    <div class="inv-modal">
        <h3>Edit Stock: <span id="editItemName"></span></h3>
        <form method="POST" id="editStockForm">
            @csrf
            <label>Available Quantity</label>
            <input type="number" name="available_qty" id="editQtyInput" min="0" required>
            <div class="inv-modal-actions">
                <button type="button" class="inv-btn inv-btn-cancel" onclick="document.getElementById('editStockModal').classList.remove('active')">Cancel</button>
                <button type="submit" class="inv-btn inv-btn-green">Update</button>
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
    document.getElementById('editStockForm').action = '{{ url("channel-partner/cp-inventory") }}/' + invId + '/update-stock';
    document.getElementById('editStockModal').classList.add('active');
}
</script>
@endsection
