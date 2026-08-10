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

    .inv-total-bar { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 14px 20px; margin-bottom: 16px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
    .inv-total-bar span:first-child { font-size: 0.78rem; font-weight: 600; color: #6b7280; }
    .inv-total-bar strong { font-size: 1.1rem; font-weight: 800; color: #1f2937; }

    .inv-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }

    .inv-item { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 1.25rem; position: relative; transition: box-shadow 0.2s; }
    .inv-item:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.07); }
    .inv-item-name { font-size: 0.92rem; font-weight: 700; color: #1f2937; margin: 0 0 2px; line-height: 1.3; }
    .inv-item-code { font-size: 0.72rem; color: #9ca3af; font-family: monospace; }
    .inv-item-category { font-size: 0.7rem; color: #6b7280; margin-top: 4px; }

    .inv-stock-section { margin-top: 14px; }
    .inv-stock-label { font-size: 0.68rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
    .inv-stock-value { font-size: 1.6rem; font-weight: 800; color: #059669; line-height: 1; }
    .inv-stock-value.out-of-stock { color: #dc2626; }

    .inv-oos { margin-top: 10px; display: flex; align-items: center; justify-content: space-between; }
    .inv-oos-badge { font-size: 0.72rem; font-weight: 700; color: #dc2626; background: #fee2e2; padding: 3px 10px; border-radius: 20px; }
    .inv-delete-btn { font-size: 0.7rem; font-weight: 600; color: #dc2626; background: none; border: 1px solid #fecaca; border-radius: 6px; padding: 3px 10px; cursor: pointer; transition: all 0.15s; }
    .inv-delete-btn:hover { background: #fee2e2; }

    .inv-actions { display: flex; gap: 8px; margin-top: 14px; flex-wrap: wrap; }
    .inv-btn { display: inline-flex; align-items: center; gap: 5px; padding: 7px 14px; border-radius: 8px; font-size: 0.78rem; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: all 0.15s; }
    .inv-btn-minus { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
    .inv-btn-minus:hover { background: #fecaca; }
    .inv-btn-plus { background: #d1fae5; color: #059669; border: 1px solid #a7f3d0; }
    .inv-btn-plus:hover { background: #a7f3d0; }
    .inv-btn-primary { background: #2563eb; color: #fff; }
    .inv-btn-primary:hover { background: #1d4ed8; color: #fff; }
    .inv-btn-cancel { background: #f3f4f6; color: #374151; }
    .inv-btn-cancel:hover { background: #e5e7eb; }

    .inv-form-inline { display: none; margin-top: 12px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 12px; }
    .inv-form-inline.active { display: block; }
    .inv-form-inline label { display: block; font-size: .75rem; font-weight: 600; color: #374151; margin-bottom: 4px; }
    .inv-form-inline input, .inv-form-inline textarea { width: 100%; padding: 7px 10px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: .82rem; margin-bottom: 8px; }
    .inv-form-inline input:focus, .inv-form-inline textarea:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 2px rgba(37,99,235,0.1); }
    .inv-form-inline textarea { resize: vertical; min-height: 50px; }

    .inv-modal-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4); z-index: 1000; align-items: center; justify-content: center; }
    .inv-modal-overlay.active { display: flex; }
    .inv-modal { background: #fff; border-radius: 14px; padding: 1.5rem; width: 90%; max-width: 420px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
    .inv-modal h3 { font-size: 1rem; font-weight: 700; color: #1f2937; margin: 0 0 1rem; }
    .inv-modal label { display: block; font-size: .8rem; font-weight: 600; color: #374151; margin-bottom: .35rem; }
    .inv-modal input, .inv-modal select { width: 100%; padding: 8px 10px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: .85rem; margin-bottom: .75rem; }
    .inv-modal input:focus, .inv-modal select:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 2px rgba(37,99,235,0.1); }
    .inv-modal-actions { display: flex; gap: 8px; justify-content: flex-end; margin-top: .5rem; }
    .inv-modal p { font-size: .78rem; color: #6b7280; margin: 0 0 1rem; }

    .inv-empty { text-align: center; padding: 4rem 2rem; }
    .inv-empty-icon { width: 64px; height: 64px; background: #f3f4f6; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
    .inv-empty h3 { font-size: 1rem; font-weight: 700; color: #374151; margin: 0 0 4px; }
    .inv-empty p { font-size: 0.82rem; color: #9ca3af; margin: 0; }

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
                <p>Stock is added when orders are delivered. Use minus to log usage.</p>
            </div>
        </div>
        
        <button class="inv-btn inv-btn-primary" onclick="document.getElementById('reorderModal').classList.add('active')">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Request More Stock
        </button>
    </div>

    @if(session('success'))
    <div style="background:#d1fae5;color:#065f46;padding:10px 16px;border-radius:8px;font-size:.82rem;font-weight:600;margin-bottom:16px;">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div style="background:#fee2e2;color:#991b1b;padding:10px 16px;border-radius:8px;font-size:.82rem;font-weight:600;margin-bottom:16px;">
        {{ session('error') }}
    </div>
    @endif

    @if($inventory->count() > 0)
    <div class="inv-total-bar">
        <span>Total Items:</span>
        <strong>{{ $inventory->count() }}</strong>
        <span style="margin-left:auto;">Total Stock:</span>
        <strong>{{ $inventory->sum('current_stock') }}</strong>
    </div>

    <div class="inv-grid">
        @foreach($inventory as $item)
        <div class="inv-item" id="inv-item-{{ $item->inv_id }}">
            <div class="inv-item-name">{{ $item->item_name }}</div>
            <div class="inv-item-code">{{ $item->item_code }}</div>
            @if($item->category_name)
            <div class="inv-item-category">{{ $item->category_name }}</div>
            @endif

            <div class="inv-stock-section">
                <div class="inv-stock-label">Current Stock</div>
                <div class="inv-stock-value {{ $item->current_stock <= 0 ? 'out-of-stock' : '' }}">
                    {{ $item->current_stock }} {{ $item->uom ?? '' }}
                </div>
            </div>

            @if($item->current_stock <= 0)
            <div class="inv-oos">
                <span class="inv-oos-badge">Out of Stock</span>
                <form method="POST" action="{{ route('cpDeleteInventory', $item->inv_id) }}" style="display:inline;" onsubmit="return confirm('Remove this item from inventory?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inv-delete-btn">Remove</button>
                </form>
            </div>
            @endif

            <div class="inv-actions">
                @if($item->current_stock > 0)
                <button type="button" class="inv-btn inv-btn-minus" onclick="toggleForm('minus-form-{{ $item->inv_id }}')">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15"/></svg>
                    Use Stock
                </button>
                @endif
                <button type="button" class="inv-btn inv-btn-plus" onclick="toggleForm('reorder-form-{{ $item->inv_id }}')">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Request More
                </button>
            </div>

            
            @if($item->current_stock > 0)
            <div class="inv-form-inline" id="minus-form-{{ $item->inv_id }}">
                <form method="POST" action="{{ route('cpReduceStock', $item->inv_id) }}">
                    @csrf
                    <label>Quantity to use</label>
                    <input type="number" name="quantity" min="1" max="{{ $item->current_stock }}" required placeholder="e.g. 20">
                    <label>Entry / Note</label>
                    <textarea name="remarks" placeholder="Solar Entry or Note" rows="2"></textarea>
                    <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:4px;">
                        <button type="button" class="inv-btn inv-btn-cancel" onclick="toggleForm('minus-form-{{ $item->inv_id }}')">Cancel</button>
                        <button type="submit" class="inv-btn inv-btn-minus">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15"/></svg>
                            Submit
                        </button>
                    </div>
                </form>
            </div>
            @endif

            <div class="inv-form-inline" id="reorder-form-{{ $item->inv_id }}">
                <form method="POST" action="{{ route('cpReorder') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                    <label>Quantity to request</label>
                    <input type="number" name="quantity" min="1" required placeholder="Enter quantity">
                    <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:4px;">
                        <button type="button" class="inv-btn inv-btn-cancel" onclick="toggleForm('reorder-form-{{ $item->inv_id }}')">Cancel</button>
                        <button type="submit" class="inv-btn inv-btn-plus">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            Place Re-Order
                        </button>
                    </div>
                </form>
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
        <p>Your delivered orders will automatically appear here. Click "Request More Stock" to place a re-order.</p>
    </div>
    @endif
</div>

{{-- Re-order Modal --}}
<div class="inv-modal-overlay" id="reorderModal">
    <div class="inv-modal">
        <h3>Request More Stock</h3>
        <p>This will create a re-order request. Stock will be added after admin approves and delivers.</p>
        <form method="POST" action="{{ route('cpReorder') }}">
            @csrf
            <label>Product</label>
            <select name="product_id" required>
                <option value="">Select Product</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->item_name }} ({{ $product->item_code }})</option>
                @endforeach
            </select>
            <label>Quantity</label>
            <input type="number" name="quantity" min="1" required placeholder="Enter quantity">
            <div class="inv-modal-actions">
                <button type="button" class="inv-btn inv-btn-cancel" onclick="document.getElementById('reorderModal').classList.remove('active')">Cancel</button>
                <button type="submit" class="inv-btn inv-btn-primary">Place Re-Order</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
function toggleForm(id) {
    var form = document.getElementById(id);
    if (form) form.classList.toggle('active');
}
</script>
@endsection
