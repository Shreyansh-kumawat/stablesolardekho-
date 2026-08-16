@extends('layouts.adminLayout')
@section('title', 'Manage Kits')

@section('css')
<style>
    :root { --blue: #2563eb; --blue-dark: #1d4ed8; --text: #1e293b; --muted: #64748b; --border: #e2e8f0; --white: #fff; --orange: #f97316; }

    .kit-wrap { padding: 1.25rem; }
    .kit-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem; }
    .kit-header-left { display: flex; align-items: center; gap: 12px; }
    .kit-icon { width: 40px; height: 40px; background: var(--orange); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .kit-header h1 { font-size: 1.15rem; font-weight: 700; color: var(--text); margin: 0; }
    .kit-header p { font-size: 0.78rem; color: var(--muted); margin: 2px 0 0; }

    .kit-btn { display: inline-flex; align-items: center; gap: 5px; padding: 7px 14px; border-radius: 8px; font-size: 0.82rem; font-weight: 600; border: none; cursor: pointer; transition: background 0.15s; text-decoration: none; }
    .kit-btn-primary { background: var(--orange); color: #fff; }
    .kit-btn-primary:hover { background: #ea580c; }
    .kit-btn-danger { background: #dc2626; color: #fff; }
    .kit-btn-sm { padding: 4px 10px; font-size: 0.75rem; }

    .kit-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 0.75rem; margin-bottom: 1.25rem; }
    .kit-stat { background: var(--white); border: 1px solid var(--border); border-radius: 10px; padding: 12px 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .kit-stat-label { font-size: 0.72rem; font-weight: 600; color: var(--muted); text-transform: uppercase; }
    .kit-stat-value { font-size: 1.3rem; font-weight: 700; color: var(--text); margin-top: 2px; }

    .kit-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1rem; }
    .kit-card { background: var(--white); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); transition: box-shadow 0.15s; }
    .kit-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .kit-card-img { width: 100%; height: 180px; object-fit: cover; background: #f1f5f9; }
    .kit-card-img-empty { width: 100%; height: 180px; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); display: flex; align-items: center; justify-content: center; }
    .kit-card-body { padding: 14px 16px; }
    .kit-card-name { font-size: 0.95rem; font-weight: 700; color: var(--text); margin-bottom: 4px; }
    .kit-card-cat { font-size: 0.72rem; color: var(--muted); text-transform: uppercase; font-weight: 600; letter-spacing: 0.03em; }
    .kit-card-meta { display: flex; gap: 12px; margin-top: 8px; font-size: 0.78rem; flex-wrap: wrap; }
    .kit-card-meta-item { display: flex; align-items: center; gap: 4px; }
    .kit-card-meta-label { color: var(--muted); }
    .kit-card-meta-val { font-weight: 700; color: var(--text); }
    .kit-card-price { font-size: 1.1rem; font-weight: 700; color: var(--orange); margin-top: 6px; }
    .kit-card-stock { font-size: 0.75rem; font-weight: 600; padding: 2px 8px; border-radius: 6px; display: inline-block; margin-top: 4px; }
    .kit-card-stock.in { background: #f0fdf4; color: #16a34a; }
    .kit-card-stock.out { background: #fef2f2; color: #dc2626; }
    .kit-card-actions { display: flex; gap: 6px; margin-top: 10px; padding-top: 10px; border-top: 1px solid var(--border); }
    .kit-card-inactive { opacity: 0.5; }

    .modal-content { border-radius: 12px; border: 1px solid var(--border); box-shadow: 0 10px 30px rgba(0,0,0,0.12); }
    .modal-header { background: #f8f9fa; border-bottom: 1px solid var(--border); border-radius: 12px 12px 0 0; padding: 1rem 1.25rem; }
    .modal-footer { background: #f8f9fa; border-top: 1px solid var(--border); border-radius: 0 0 12px 12px; padding: 1rem 1.25rem; }
    .modal-title { font-size: 0.95rem; font-weight: 700; color: var(--text); }
    .form-label { font-size: 0.82rem; font-weight: 600; color: var(--text); margin-bottom: 4px; }
    .form-control, .form-select { border: 1px solid var(--border); border-radius: 8px; padding: 0.45rem 0.8rem; font-size: 0.84rem; color: var(--text); }
    .form-control:focus, .form-select:focus { border-color: var(--orange); box-shadow: 0 0 0 3px rgba(249,115,22,0.12); outline: none; }

    .dyn-section { background: #f8fafc; border: 1px solid var(--border); border-radius: 8px; padding: 12px; margin-bottom: 12px; }
    .dyn-section-title { font-size: 0.78rem; font-weight: 700; color: var(--text); margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between; }
    .dyn-row { display: flex; gap: 6px; align-items: center; margin-bottom: 8px; }
    .dyn-row select, .dyn-row input { flex: 1; }
    .dyn-remove { width: 28px; height: 28px; border: none; background: #fee2e2; color: #dc2626; border-radius: 6px; cursor: pointer; font-size: 1rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .dyn-add { font-size: 0.75rem; font-weight: 600; color: var(--orange); border: 1px dashed var(--orange); background: none; padding: 4px 10px; border-radius: 6px; cursor: pointer; }
    .stock-info { font-size: 0.68rem; color: var(--muted); margin-top: 2px; }

    .kit-empty { text-align: center; padding: 3rem; background: var(--white); border: 1px solid var(--border); border-radius: 12px; color: var(--muted); }

    .searchable-select { position: relative; }
    .searchable-select input.ss-input { width: 100%; border: 1px solid var(--border); border-radius: 8px; padding: 0.45rem 0.8rem; font-size: 0.84rem; color: var(--text); }
    .searchable-select input.ss-input:focus { border-color: var(--orange); box-shadow: 0 0 0 3px rgba(249,115,22,0.12); outline: none; }
    .ss-dropdown { display: none; position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid var(--border); border-top: none; border-radius: 0 0 8px 8px; max-height: 180px; overflow-y: auto; z-index: 1060; box-shadow: 0 6px 16px rgba(0,0,0,0.1); }
    .ss-dropdown.open { display: block; }
    .ss-option { padding: 7px 10px; cursor: pointer; font-size: .82rem; color: #374151; border-bottom: 1px solid #f5f5f5; }
    .ss-option:hover { background: #fff7ed; }
    .ss-option.hidden { display: none; }
    @media (max-width: 768px) {
        .kit-grid { grid-template-columns: 1fr; }
        .kit-stats { grid-template-columns: repeat(2, 1fr); }
    }
</style>
@endsection

@section('content')
<div class="kit-wrap">
    <div class="kit-header">
        <div class="kit-header-left">
            <div class="kit-icon">
                <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
            </div>
            <div>
                <h1>Solar Kits</h1>
                <p>Manage complete solar installation kits</p>
            </div>
        </div>
        <button class="kit-btn kit-btn-primary" data-bs-toggle="modal" data-bs-target="#addKitModal">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Kit
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" style="font-size:0.84rem; border-radius:10px; margin-bottom:1rem;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size:0.65rem;"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" style="font-size:0.84rem; border-radius:10px; margin-bottom:1rem;">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size:0.65rem;"></button>
        </div>
    @endif

    <div class="kit-stats">
        <div class="kit-stat">
            <div class="kit-stat-label">Total Kits</div>
            <div class="kit-stat-value">{{ $kits->count() }}</div>
        </div>
        <div class="kit-stat">
            <div class="kit-stat-label">Active Kits</div>
            <div class="kit-stat-value">{{ $kits->where('is_active', 1)->count() }}</div>
        </div>
        <div class="kit-stat">
            <div class="kit-stat-label">Total Available</div>
            <div class="kit-stat-value">{{ $kits->sum('max_kits') }}</div>
        </div>
    </div>

    @if($kits->count())
    <div class="kit-grid">
        @foreach($kits as $kit)
        <div class="kit-card {{ !$kit->is_active ? 'kit-card-inactive' : '' }}">
            @if($kit->image)
                <img src="{{ Storage::url($kit->image) }}" alt="{{ $kit->item_name }}" class="kit-card-img">
            @else
                <div class="kit-card-img-empty">
                    <svg width="48" height="48" fill="none" stroke="rgba(249,115,22,0.3)" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
                </div>
            @endif
            <div class="kit-card-body">
                <div class="kit-card-cat">{{ $kit->category->category_name ?? 'Uncategorized' }}</div>
                <div class="kit-card-name">{{ $kit->item_name }}</div>
                <div class="kit-card-price">&#8377;{{ $kit->current_sale_price }}</div>
                <div class="kit-card-meta">
                    <div class="kit-card-meta-item">
                        <span class="kit-card-meta-label">Available:</span>
                        <span class="kit-card-meta-val">{{ $kit->max_kits }} kits</span>
                    </div>
                    <div class="kit-card-meta-item">
                        <span class="kit-card-meta-label">Items:</span>
                        <span class="kit-card-meta-val">{{ $kit->kitItems->count() }}</span>
                    </div>
                    <div class="kit-card-meta-item">
                        <span class="kit-card-meta-label">Slabs:</span>
                        <span class="kit-card-meta-val">{{ $kit->kitSlabPrices->count() }}</span>
                    </div>
                </div>
                <span class="kit-card-stock {{ $kit->max_kits > 0 ? 'in' : 'out' }}">
                    {{ $kit->max_kits > 0 ? 'In Stock' : 'Out of Stock' }}
                </span>

                <div class="kit-card-actions">
                    <button class="kit-btn kit-btn-sm" style="background:#eff6ff;color:#2563eb;" onclick="openEditKit({{ $kit->id }})">Edit</button>
                    <button class="kit-btn kit-btn-sm" style="background:{{ $kit->is_active ? '#fef2f2' : '#f0fdf4' }};color:{{ $kit->is_active ? '#dc2626' : '#16a34a' }};" onclick="toggleKitActive({{ $kit->id }})">
                        {{ $kit->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                    <form method="POST" action="{{ route('adminKitDelete', $kit->id) }}" class="d-inline delete-kit-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="kit-btn kit-btn-danger kit-btn-sm">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="kit-empty">
        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" style="margin-bottom:10px;opacity:0.4;"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
        <p style="font-weight:600; margin-bottom:4px;">No kits added yet</p>
        <p style="font-size:0.8rem;">Click "Add Kit" to create your first solar kit.</p>
    </div>
    @endif
</div>

<!-- Add Kit Modal -->
<div class="modal fade" id="addKitModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('adminKitStore') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Kit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:1.25rem; max-height:70vh; overflow-y:auto;">
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label">Kit Name *</label>
                            <input type="text" name="kit_name" class="form-control" required placeholder="e.g. Adani 3KW Bi Facial Kit">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Base Price (1 Kit) *</label>
                            <input type="text" name="base_price" class="form-control" required placeholder="e.g. 133000">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Kit description (optional)"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-check-label" style="font-size:0.82rem;">
                            <input type="checkbox" name="is_featured" value="1" class="form-check-input"> Featured Kit
                        </label>
                    </div>

                    <!-- Kit Items -->
                    <div class="dyn-section">
                        <div class="dyn-section-title">
                            <span>Kit Items (Select Products)</span>
                            <button type="button" class="dyn-add" onclick="addItemRow('add')">+ Add Item</button>
                        </div>
                        <div class="dyn-row" style="margin-bottom:4px;">
                            <small style="flex:1;color:var(--muted);font-size:0.7rem;font-weight:600;">Category</small>
                            <small style="flex:1;color:var(--muted);font-size:0.7rem;font-weight:600;">Product</small>
                            <small style="flex:0.4;color:var(--muted);font-size:0.7rem;font-weight:600;">Qty per Kit</small>
                            <span style="width:28px;"></span>
                        </div>
                        <div id="addItemsContainer">
                            <div class="dyn-row">
                                <div style="flex:1;" class="searchable-select">
                                    <input type="text" class="ss-input" placeholder="Search category..." autocomplete="off" onclick="ssToggle(this,true)" oninput="ssFilter(this)">
                                    <select name="items[0][category_id]" class="form-select kit-cat-select" onchange="loadProducts(this, 'add', 0)" style="display:none;">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="ss-dropdown">
                                        @foreach($categories as $cat)
                                        <div class="ss-option" data-value="{{ $cat->id }}" data-text="{{ $cat->category_name }}" onclick="ssPick(this)">{{ $cat->category_name }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <div style="flex:1;" class="searchable-select">
                                    <input type="text" class="ss-input ss-prod-input" placeholder="Select category first..." autocomplete="off" disabled onclick="ssToggle(this,true)" oninput="ssFilter(this)">
                                    <select name="items[0][component_product_id]" class="form-select kit-prod-select" onchange="updateQtyMax(this.closest('.dyn-row'))" style="display:none;" disabled>
                                        <option value="">Select Product</option>
                                    </select>
                                    <div class="ss-dropdown ss-prod-dropdown"></div>
                                </div>
                                <div style="flex:0.4;display:flex;flex-direction:column;gap:2px;">
                                    <input type="number" name="items[0][quantity]" class="form-control kit-qty-input" placeholder="Qty" min="1" value="1">
                                    <small class="kit-stock-hint" style="color:var(--muted);font-size:0.65rem;"></small>
                                </div>
                                <button type="button" class="dyn-remove" onclick="this.closest('.dyn-row').remove()">&times;</button>
                            </div>
                        </div>
                    </div>

                    <!-- Slab Pricing -->
                    <div class="dyn-section">
                        <div class="dyn-section-title">
                            <span>Slab Pricing (Bulk Discounts)</span>
                            <button type="button" class="dyn-add" onclick="addSlabRow('add')">+ Add Slab</button>
                        </div>
                        <div class="dyn-row" style="margin-bottom:4px;">
                            <small style="flex:1;color:var(--muted);font-size:0.7rem;font-weight:600;">Min Qty</small>
                            <small style="flex:1;color:var(--muted);font-size:0.7rem;font-weight:600;">Max Qty</small>
                            <small style="flex:1;color:var(--muted);font-size:0.7rem;font-weight:600;">Price (&#8377;)</small>
                            <span style="width:28px;"></span>
                        </div>
                        <div id="addSlabsContainer">
                            <div class="dyn-row">
                                <input type="number" name="slabs[0][min_qty]" class="form-control" placeholder="1" min="1" style="flex:1;">
                                <input type="number" name="slabs[0][max_qty]" class="form-control" placeholder="empty = no limit" style="flex:1;">
                                <input type="number" name="slabs[0][price]" class="form-control" placeholder="133000" step="0.01" style="flex:1;">
                                <button type="button" class="dyn-remove" onclick="this.parentElement.remove()">&times;</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="kit-btn kit-btn-sm" style="background:#e2e8f0;color:var(--text);" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="kit-btn kit-btn-primary">Save Kit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Kit Modal -->
<div class="modal fade" id="editKitModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="editKitForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:1.25rem; max-height:70vh; overflow-y:auto;">
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label">Kit Name *</label>
                            <input type="text" name="kit_name" id="editKitName" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Base Price (1 Kit) *</label>
                            <input type="text" name="base_price" id="editKitPrice" class="form-control" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="editKitDesc" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-check-label" style="font-size:0.82rem;">
                            <input type="checkbox" name="is_featured" value="1" id="editKitFeatured" class="form-check-input"> Featured Kit
                        </label>
                    </div>

                    <!-- Kit Items -->
                    <div class="dyn-section">
                        <div class="dyn-section-title">
                            <span>Kit Items (Select Products)</span>
                            <button type="button" class="dyn-add" onclick="addItemRow('edit')">+ Add Item</button>
                        </div>
                        <div class="dyn-row" style="margin-bottom:4px;">
                            <small style="flex:1;color:var(--muted);font-size:0.7rem;font-weight:600;">Category</small>
                            <small style="flex:1;color:var(--muted);font-size:0.7rem;font-weight:600;">Product</small>
                            <small style="flex:0.4;color:var(--muted);font-size:0.7rem;font-weight:600;">Qty per Kit</small>
                            <span style="width:28px;"></span>
                        </div>
                        <div id="editItemsContainer"></div>
                    </div>

                    <!-- Slab Pricing -->
                    <div class="dyn-section">
                        <div class="dyn-section-title">
                            <span>Slab Pricing (Bulk Discounts)</span>
                            <button type="button" class="dyn-add" onclick="addSlabRow('edit')">+ Add Slab</button>
                        </div>
                        <div class="dyn-row" style="margin-bottom:4px;">
                            <small style="flex:1;color:var(--muted);font-size:0.7rem;font-weight:600;">Min Qty</small>
                            <small style="flex:1;color:var(--muted);font-size:0.7rem;font-weight:600;">Max Qty</small>
                            <small style="flex:1;color:var(--muted);font-size:0.7rem;font-weight:600;">Price (&#8377;)</small>
                            <span style="width:28px;"></span>
                        </div>
                        <div id="editSlabsContainer"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="kit-btn kit-btn-sm" style="background:#e2e8f0;color:var(--text);" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="kit-btn kit-btn-primary">Update Kit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
let addItemIdx = 1, editItemIdx = 0, addSlabIdx = 1, editSlabIdx = 0;

const categoryOptions = `<option value="">Select Category</option>@foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->category_name }}</option>@endforeach`;
const categoryDropdownHtml = `@foreach($categories as $cat)<div class="ss-option" data-value="{{ $cat->id }}" data-text="{{ $cat->category_name }}" onclick="ssPick(this)">{{ $cat->category_name }}</div>@endforeach`;
const catNameMap = { @foreach($categories as $cat){{ $cat->id }}: "{{ addslashes($cat->category_name) }}", @endforeach };

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

function loadProducts(catSelect, prefix, idx) {
    const catId = catSelect.value;
    const row = catSelect.closest('.dyn-row');
    const prodSelect = row.querySelector('.kit-prod-select');
    const prodInput = row.querySelector('.ss-prod-input');
    const prodDropdown = row.querySelector('.ss-prod-dropdown');
    prodSelect.innerHTML = '<option value="">Loading...</option>';
    prodSelect.disabled = true;
    if (prodInput) { prodInput.value = ''; prodInput.disabled = true; prodInput.placeholder = 'Loading...'; }
    if (prodDropdown) prodDropdown.innerHTML = '';

    if (!catId) {
        prodSelect.innerHTML = '<option value="">Select Product</option>';
        if (prodInput) { prodInput.placeholder = 'Select category first...'; }
        updateQtyMax(row);
        return;
    }

    fetch(`{{ route("getProducts") }}?category_id=${catId}`)
        .then(r => r.json())
        .then(products => {
            let opts = '<option value="">Select Product</option>';
            let ddHtml = '';
            products.forEach(p => {
                if (p.is_kit) return;
                const stock = p.quantity ?? 0;
                opts += `<option value="${p.id}" data-stock="${stock}">${p.item_name} (Stock: ${stock})</option>`;
                ddHtml += `<div class="ss-option" data-value="${p.id}" data-text="${p.item_name} (Stock: ${stock})" onclick="ssPick(this)">${p.item_name} <span style="color:#94a3b8;font-size:.72rem;">Stock: ${stock}</span></div>`;
            });
            prodSelect.innerHTML = opts;
            prodSelect.disabled = false;
            prodSelect.onchange = () => updateQtyMax(row);
            if (prodInput) { prodInput.disabled = false; prodInput.placeholder = 'Search product...'; }
            if (prodDropdown) prodDropdown.innerHTML = ddHtml;
            updateQtyMax(row);
        });
}

function updateQtyMax(row) {
    const prodSelect = row.querySelector('.kit-prod-select');
    const qtyInput = row.querySelector('.kit-qty-input');
    const hint = row.querySelector('.kit-stock-hint');
    if (!qtyInput) return;
    const selected = prodSelect?.selectedOptions[0];
    const stock = selected ? parseInt(selected.dataset.stock || 0) : 0;
    if (selected && selected.value) {
        qtyInput.max = stock;
        if (parseInt(qtyInput.value) > stock) qtyInput.value = stock > 0 ? stock : 1;
        if (hint) hint.textContent = stock + ' left';
    } else {
        qtyInput.removeAttribute('max');
        if (hint) hint.textContent = '';
    }
}

function addItemRow(prefix) {
    const idx = prefix === 'add' ? addItemIdx++ : editItemIdx++;
    const container = document.getElementById(prefix + 'ItemsContainer');
    const row = document.createElement('div');
    row.className = 'dyn-row';
    row.innerHTML = `
        <div style="flex:1;" class="searchable-select">
            <input type="text" class="ss-input" placeholder="Search category..." autocomplete="off" onclick="ssToggle(this,true)" oninput="ssFilter(this)">
            <select name="items[${idx}][category_id]" class="form-select kit-cat-select" onchange="loadProducts(this, '${prefix}', ${idx})" style="display:none;">
                ${categoryOptions}
            </select>
            <div class="ss-dropdown">${categoryDropdownHtml}</div>
        </div>
        <div style="flex:1;" class="searchable-select">
            <input type="text" class="ss-input ss-prod-input" placeholder="Select category first..." autocomplete="off" disabled onclick="ssToggle(this,true)" oninput="ssFilter(this)">
            <select name="items[${idx}][component_product_id]" class="form-select kit-prod-select" onchange="updateQtyMax(this.closest('.dyn-row'))" style="display:none;" disabled>
                <option value="">Select Product</option>
            </select>
            <div class="ss-dropdown ss-prod-dropdown"></div>
        </div>
        <div style="flex:0.4;display:flex;flex-direction:column;gap:2px;">
            <input type="number" name="items[${idx}][quantity]" class="form-control kit-qty-input" placeholder="Qty" min="1" value="1">
            <small class="kit-stock-hint" style="color:var(--muted);font-size:0.65rem;"></small>
        </div>
        <button type="button" class="dyn-remove" onclick="this.closest('.dyn-row').remove()">&times;</button>`;
    container.appendChild(row);
}

function addSlabRow(prefix) {
    const idx = prefix === 'add' ? addSlabIdx++ : editSlabIdx++;
    const container = document.getElementById(prefix + 'SlabsContainer');
    const row = document.createElement('div');
    row.className = 'dyn-row';
    row.innerHTML = `
        <input type="number" name="slabs[${idx}][min_qty]" class="form-control" placeholder="1" min="1" style="flex:1;">
        <input type="number" name="slabs[${idx}][max_qty]" class="form-control" placeholder="empty = no limit" style="flex:1;">
        <input type="number" name="slabs[${idx}][price]" class="form-control" placeholder="133000" step="0.01" style="flex:1;">
        <button type="button" class="dyn-remove" onclick="this.parentElement.remove()">&times;</button>`;
    container.appendChild(row);
}

function openEditKit(id) {
    fetch(`/admin/kits/${id}/data`)
        .then(r => r.json())
        .then(kit => {
            document.getElementById('editKitForm').action = `/admin/kits/${id}`;
            document.getElementById('editKitName').value = kit.item_name;
            document.getElementById('editKitPrice').value = kit.current_sale_price || '';
            document.getElementById('editKitDesc').value = kit.description || '';
            document.getElementById('editKitFeatured').checked = kit.is_featured == 1;

            const itemsC = document.getElementById('editItemsContainer');
            itemsC.innerHTML = '';
            editItemIdx = 0;
            const itemPromises = (kit.kit_items || []).map((item, i) => {
                editItemIdx = i + 1;
                const catId = item.category_id || '';
                const prodId = item.component_product_id || '';
                const qty = item.quantity || 1;
                const prodName = item.component_product ? item.component_product.item_name : item.item_name;

                const row = document.createElement('div');
                row.className = 'dyn-row';
                let catOpts = categoryOptions.replace(`value="${catId}"`, `value="${catId}" selected`);
                const catDisplayName = catNameMap[catId] || '';

                row.innerHTML = `
                    <div style="flex:1;" class="searchable-select">
                        <input type="text" class="ss-input" value="${catDisplayName}" placeholder="Search category..." autocomplete="off" onclick="ssToggle(this,true)" oninput="ssFilter(this)">
                        <select name="items[${i}][category_id]" class="form-select kit-cat-select" onchange="loadProducts(this, 'edit', ${i})" style="display:none;">
                            ${catOpts}
                        </select>
                        <div class="ss-dropdown">${categoryDropdownHtml}</div>
                    </div>
                    <div style="flex:1;" class="searchable-select">
                        <input type="text" class="ss-input ss-prod-input" value="${prodName || ''}" placeholder="Search product..." autocomplete="off" onclick="ssToggle(this,true)" oninput="ssFilter(this)">
                        <select name="items[${i}][component_product_id]" class="form-select kit-prod-select" style="display:none;">
                            <option value="${prodId}" selected>${prodName}</option>
                        </select>
                        <div class="ss-dropdown ss-prod-dropdown"></div>
                    </div>
                    <div style="flex:0.4;display:flex;flex-direction:column;gap:2px;">
                        <input type="number" name="items[${i}][quantity]" class="form-control kit-qty-input" value="${qty}" min="1">
                        <small class="kit-stock-hint" style="color:var(--muted);font-size:0.65rem;"></small>
                    </div>
                    <button type="button" class="dyn-remove" onclick="this.closest('.dyn-row').remove()">&times;</button>`;
                itemsC.appendChild(row);

                if (catId) {
                    return fetch(`{{ route("getProducts") }}?category_id=${catId}`)
                        .then(r => r.json())
                        .then(products => {
                            const sel = row.querySelector('.kit-prod-select');
                            const prodInput = row.querySelector('.ss-prod-input');
                            const prodDd = row.querySelector('.ss-prod-dropdown');
                            let opts = '<option value="">Select Product</option>';
                            let ddHtml = '';
                            products.forEach(p => {
                                if (p.is_kit) return;
                                const selected = p.id == prodId ? ' selected' : '';
                                const stock = p.quantity ?? 0;
                                opts += `<option value="${p.id}" data-stock="${stock}"${selected}>${p.item_name} (Stock: ${stock})</option>`;
                                ddHtml += `<div class="ss-option" data-value="${p.id}" data-text="${p.item_name} (Stock: ${stock})" onclick="ssPick(this)">${p.item_name} <span style="color:#94a3b8;font-size:.72rem;">Stock: ${stock}</span></div>`;
                            });
                            sel.innerHTML = opts;
                            sel.onchange = () => updateQtyMax(row);
                            if (prodDd) prodDd.innerHTML = ddHtml;
                            updateQtyMax(row);
                        });
                }
            });

            const slabsC = document.getElementById('editSlabsContainer');
            slabsC.innerHTML = '';
            editSlabIdx = 0;
            (kit.kit_slab_prices || []).forEach((slab, i) => {
                editSlabIdx = i + 1;
                const row = document.createElement('div');
                row.className = 'dyn-row';
                row.innerHTML = `
                    <input type="number" name="slabs[${i}][min_qty]" class="form-control" value="${slab.min_qty}" min="1" style="flex:1;">
                    <input type="number" name="slabs[${i}][max_qty]" class="form-control" value="${slab.max_qty || ''}" style="flex:1;">
                    <input type="number" name="slabs[${i}][price]" class="form-control" value="${slab.price}" step="0.01" style="flex:1;">
                    <button type="button" class="dyn-remove" onclick="this.parentElement.remove()">&times;</button>`;
                slabsC.appendChild(row);
            });

            new bootstrap.Modal(document.getElementById('editKitModal')).show();
        });
}

function toggleKitActive(id) {
    fetch(`/admin/kits/${id}/toggle-active`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }})
        .then(r => r.json())
        .then(() => location.reload());
}

document.querySelectorAll('.delete-kit-form').forEach(f => {
    f.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Delete Kit?',
            text: 'This will permanently remove this kit.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Yes, delete'
        }).then(r => { if (r.isConfirmed) f.submit(); });
    });
});
</script>
@endsection
