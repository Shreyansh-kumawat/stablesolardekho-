@extends('layouts.adminLayout')

@section('css')
<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
<style>
    :root {
        --primary-blue: #4A90E2;
        --primary-light: #f5f7fa;
        --text-primary: #2d3436;
        --text-secondary: #636e72;
        --border-color: #e1e8ed;
        --hover-bg: #f1f3f5;
        --card-bg: #ffffff;
    }

    body {
        background: var(--primary-light);
        color: var(--text-primary);
    }

    .page-header {
        background: #ffffff;
        padding: 1.5rem 0;
        margin-bottom: 1.5rem;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .page-header h1 {
        color: var(--text-primary);
        font-weight: 600;
        margin: 0;
        font-size: 1.25rem;
    }

    .page-header p {
        color: var(--text-secondary);
        margin: 0.35rem 0 0 0;
        font-size: 0.9rem;
    }

    .card {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background: var(--card-bg);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .card-body {
        padding: 1.5rem;
    }

    .btn-group-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        align-items: center;
        margin-bottom: 1.25rem;
        padding: 0.75rem;
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 8px;
    }

    .btn-success,
    .btn-primary {
        background: var(--primary-blue);
        border: 1px solid var(--primary-blue);
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.85rem;
        box-shadow: none;
    }

    .btn-success:hover,
    .btn-primary:hover {
        background: #3b7dc4;
        border-color: #3b7dc4;
        color: #fff;
    }

    .btn-secondary {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.85rem;
        border: 1px solid var(--border-color);
        background: #fff;
        color: var(--text-primary);
    }

    .btn-secondary:hover {
        background: var(--hover-bg);
    }

    .btn-sm {
        padding: 0.4rem 0.75rem;
        font-size: 0.8rem;
    }

    .form-control,
    .form-select {
        border-radius: 6px;
        border: 1px solid var(--border-color);
        padding: 0.55rem 0.75rem;
        font-size: 0.9rem;
    }

    .form-control:focus,
    .form-select:focus {
        box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.15);
        border-color: var(--primary-blue);
    }

    .form-label {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 0.85rem;
    }

    .form-check-input:checked {
        background-color: var(--primary-blue);
        border-color: var(--primary-blue);
    }

    .table thead th {
        background: #f8f9fa;
        color: var(--text-primary);
        font-weight: 600;
        border-bottom: 1px solid var(--border-color);
        padding: 0.9rem;
        font-size: 0.8rem;
        text-transform: uppercase;
    }

    .table tbody td {
        padding: 0.85rem;
        vertical-align: middle;
        border-color: var(--border-color);
    }

    .table tbody tr:hover {
        background-color: var(--hover-bg);
    }

    .table-responsive {
        border-radius: 8px;
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    /* DataTables export buttons - spaced out */
    .dt-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 0.5rem;
    }

    .dt-button {
        background: var(--primary-blue) !important;
        border: 1px solid var(--primary-blue) !important;
        border-radius: 6px !important;
        padding: 0.45rem 0.9rem !important;
        font-weight: 600 !important;
        color: #fff !important;
        font-size: 0.8rem !important;
        box-shadow: none !important;
        margin: 0 !important;
    }

    .dt-button:hover {
        background: #3b7dc4 !important;
        border-color: #3b7dc4 !important;
    }

    .text-muted-custom {
        color: var(--text-secondary);
        font-size: 0.85rem;
    }

    .modal-content.glass-modal {
        background: #ffffff;
        border: none;
        border-radius: 12px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.18);
    }
    .modal-header {
        background: #fff;
        border-bottom: 1px solid var(--border-color);
        padding: 1.1rem 1.5rem;
        border-radius: 12px 12px 0 0;
    }
    .modal-title {
        font-weight: 700;
        color: var(--text-primary);
        font-size: 1rem;
        display: flex; align-items: center; gap: 8px;
    }
    .modal-title svg { flex-shrink: 0; }
    .modal-body { padding: 1.5rem; overflow-y: auto; max-height: calc(100vh - 180px); }
    .modal-dialog-scrollable .modal-content { max-height: 92vh; display: flex; flex-direction: column; }
    .modal-dialog-scrollable .modal-body { flex: 1 1 auto; }
    .modal-footer {
        background: #f8f9fa;
        border-top: 1px solid var(--border-color);
        padding: 0.9rem 1.5rem;
        border-radius: 0 0 12px 12px;
        gap: 10px;
    }
    /* Section labels inside modal */
    .form-section-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-secondary);
        margin: 0 0 10px;
        padding-bottom: 6px;
        border-bottom: 1px solid var(--border-color);
    }
    /* Upload zone */
    .upload-zone {
        border: 1.5px dashed #cbd5e1;
        border-radius: 8px;
        padding: 14px 12px;
        text-align: center;
        background: #fafbfc;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
        position: relative;
    }
    .upload-zone:hover { border-color: var(--primary-blue); background: #f0f7ff; }
    .upload-zone input[type=file] {
        position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
    }
    .upload-zone-icon { color: #94a3b8; margin-bottom: 4px; }
    .upload-zone-text { font-size: 0.8rem; color: var(--text-secondary); line-height: 1.4; }
    .upload-zone-text strong { color: var(--primary-blue); }
    /* Preview strip */
    .img-preview-strip { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
    .img-preview-strip img {
        width: 56px; height: 56px; object-fit: cover;
        border-radius: 6px; border: 1px solid var(--border-color);
    }
    /* Featured toggle */
    .feat-toggle-wrap { display: flex; align-items: center; gap: 10px; }
    .feat-toggle-label { font-size: 0.85rem; font-weight: 600; color: var(--text-primary); cursor: pointer; }
    /* Modal footer buttons */
    .btn-cancel {
        background: #fff; border: 1px solid var(--border-color);
        color: var(--text-primary); padding: 0.5rem 1.1rem;
        border-radius: 7px; font-weight: 600; font-size: 0.85rem;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-cancel:hover { background: var(--hover-bg); }
    .btn-save {
        background: var(--primary-blue); border: 1px solid var(--primary-blue);
        color: #fff; padding: 0.5rem 1.3rem;
        border-radius: 7px; font-weight: 700; font-size: 0.85rem;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-save:hover { background: #3b7dc4; border-color: #3b7dc4; color: #fff; }

    /* ── Product Card Grid ── */
    .prod-toolbar {
        display: flex; align-items: center; gap: 12px;
        flex-wrap: wrap; margin-bottom: 20px;
    }
    .prod-search-wrap {
        flex: 1; min-width: 200px; position: relative;
    }
    .prod-search-wrap svg {
        position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
        color: #94a3b8; pointer-events: none;
    }
    .prod-search {
        width: 100%; padding: 0.52rem 0.75rem 0.52rem 2.2rem;
        border: 1px solid var(--border-color); border-radius: 7px;
        font-size: 0.88rem; background: #fff; color: var(--text-primary);
        outline: none; transition: border-color 0.15s;
    }
    .prod-search:focus { border-color: var(--primary-blue); box-shadow: 0 0 0 3px rgba(74,144,226,0.1); }
    .prod-count { font-size: 0.82rem; color: var(--text-secondary); white-space: nowrap; }

    .prod-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
    }

    .pcard {
        background: #fff; border: 1px solid var(--border-color);
        border-radius: 10px; overflow: hidden;
        display: flex; flex-direction: column;
        transition: box-shadow 0.2s, transform 0.2s, border-color 0.2s;
    }
    .pcard:hover {
        box-shadow: 0 8px 28px rgba(0,0,0,0.1);
        transform: translateY(-2px);
        border-color: #c5d8f5;
    }
    .pcard-img {
        width: 100%; aspect-ratio: 16/9; overflow: hidden;
        background: #f1f3f5; position: relative; flex-shrink: 0;
    }
    .pcard-img img {
        width: 100%; height: 100%; object-fit: cover; display: block;
        transition: transform 0.4s;
    }
    .pcard:hover .pcard-img img { transform: scale(1.04); }
    .pcard-img-empty {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg,#f8f9fa,#e9ecef);
    }
    .pcard-badges {
        position: absolute; top: 9px; left: 9px;
        display: flex; flex-direction: column; gap: 4px;
    }
    .pcard-badge {
        font-size: 0.65rem; font-weight: 700; letter-spacing: 0.06em;
        padding: 3px 9px; border-radius: 20px;
        display: inline-block;
    }
    .pcard-badge-cat  { background: rgba(74,144,226,0.9); color: #fff; }
    .pcard-badge-feat { background: rgba(249,115,22,0.9); color: #fff; }

    .pcard-body {
        padding: 14px 16px; flex: 1; display: flex; flex-direction: column; gap: 8px;
    }
    .pcard-name {
        font-size: 0.95rem; font-weight: 700; color: var(--text-primary);
        line-height: 1.3; margin: 0;
    }
    .pcard-code {
        font-size: 0.75rem; color: var(--text-secondary);
        font-family: monospace; margin: 0;
    }
    .pcard-meta {
        display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-top: 2px;
    }
    .pcard-price {
        font-size: 1.05rem; font-weight: 800; color: #1c7ed6;
    }
    .pcard-price-na { font-size: 0.8rem; color: var(--text-secondary); }
    .pcard-chip {
        font-size: 0.72rem; font-weight: 700;
        padding: 3px 9px; border-radius: 20px;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .pcard-chip-qty  { background: #f0f7ff; color: #1c7ed6; border: 1px solid #bfdbfe; }
    .pcard-chip-uom  { background: #f8f9fa; color: #495057; border: 1px solid #dee2e6; }

    .pcard-footer {
        display: flex; align-items: center; justify-content: space-between;
        padding: 10px 16px; border-top: 1px solid var(--border-color);
        gap: 8px; flex-wrap: wrap;
    }
    .pcard-toggles { display: flex; gap: 6px; }
    .pcard-toggle {
        font-size: 0.72rem; font-weight: 700; padding: 4px 11px;
        border-radius: 20px; border: 1px solid; cursor: pointer;
        transition: all 0.15s;
    }
    .pcard-edit {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 0.78rem; font-weight: 700;
        padding: 6px 14px; border-radius: 7px;
        background: var(--primary-blue); color: #fff; border: none;
        cursor: pointer; transition: background 0.15s;
    }
    .pcard-edit:hover { background: #3b7dc4; }

    /* ── Pagination ── */
    .prod-pagination {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 10px; margin-top: 22px;
        padding-top: 16px; border-top: 1px solid var(--border-color);
    }
    .prod-pg-info { font-size: 0.82rem; color: var(--text-secondary); }
    .prod-pg-btns { display: flex; gap: 4px; }
    .prod-pg-btn {
        min-width: 34px; height: 34px; border-radius: 7px;
        border: 1px solid var(--border-color);
        background: #fff; color: var(--text-primary);
        font-size: 0.82rem; font-weight: 600; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center;
        padding: 0 10px; transition: all 0.15s;
    }
    .prod-pg-btn:hover:not(:disabled) { border-color: var(--primary-blue); color: var(--primary-blue); }
    .prod-pg-btn.active { background: var(--primary-blue); border-color: var(--primary-blue); color: #fff; }
    .prod-pg-btn:disabled { opacity: 0.38; cursor: default; }

    /* ── Empty state ── */
    .prod-empty {
        text-align: center; padding: 4rem 1rem;
        color: var(--text-secondary);
    }
    .prod-empty svg { opacity: 0.25; margin-bottom: 12px; }

    @media (max-width: 992px) { .prod-grid { grid-template-columns: repeat(2,1fr); } }
    @media (max-width: 560px) { .prod-grid { grid-template-columns: 1fr; } }

    /* ── Modal Custom Dropdowns ── */
    .mdd-trigger {
        display:flex; align-items:center; justify-content:space-between;
        padding:0.55rem 0.75rem;
        border:1px solid var(--border-color);
        border-radius:6px; background:#fff;
        font-size:0.9rem; cursor:pointer; user-select:none;
        color:#94a3b8; transition:border-color 0.15s, box-shadow 0.15s;
        min-height: 38px;
    }
    .mdd-trigger:hover { border-color:#aab4c4; }
    .mdd-trigger.has-val { color:var(--text-primary); }
    .mdd-trigger.is-open { border-color:var(--primary-blue); box-shadow:0 0 0 0.2rem rgba(74,144,226,0.15); }
    .mdd-trigger .mdd-arrow { flex-shrink:0; color:#94a3b8; transition:transform 0.2s; }
    .mdd-trigger.is-open .mdd-arrow { transform:rotate(180deg); color:var(--primary-blue); }
    .mdd-list {
        display:none; position:fixed; z-index:9999;
        background:#fff; border:1px solid var(--primary-blue);
        border-radius:0 0 7px 7px; overflow-y:auto; max-height:200px;
        box-shadow:0 8px 24px rgba(0,0,0,0.15);
    }
    .mdd-search {
        width:100%; padding:7px 10px; border:none; border-bottom:1px solid var(--border-color);
        font-size:0.85rem; outline:none; color:var(--text-primary);
    }
    .mdd-opt {
        padding:8px 12px; font-size:0.88rem; cursor:pointer; color:var(--text-primary);
        transition:background 0.12s;
    }
    .mdd-opt:hover { background:#f0f7ff; color:var(--primary-blue); }
    .mdd-opt.mdd-active { background:#e7f5ff; color:#1c7ed6; font-weight:700; }
    .mdd-custom-wrap { margin-top:6px; }
    .mdd-custom-wrap input {
        width:100%; padding:0.55rem 0.75rem; border:1px solid var(--primary-blue);
        border-radius:6px; font-size:0.88rem; color:var(--text-primary); outline:none;
    }
</style>
@endsection

@section('content')

    <!-- Page Header -->
    <div class="page-header">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 style="display:flex;align-items:center;gap:8px;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Manage Products
                </h1>
                <p>{{ count($product_list) }} product{{ count($product_list) != 1 ? 's' : '' }} total</p>
                @if(Auth::user()->role_id == 2)
                <div style="display:flex;gap:12px;margin-top:6px;flex-wrap:wrap;">
                    @if(Auth::user()->hasAdminPermission('products.add'))
                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:0.78rem;font-weight:600;color:#166534;"><span style="width:8px;height:8px;border-radius:50%;background:#166534;display:inline-block;"></span> Add</span>
                    @else
                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:0.78rem;font-weight:600;color:#dc2626;"><span style="width:8px;height:8px;border-radius:50%;background:#dc2626;display:inline-block;"></span> Add</span>
                    @endif
                    @if(Auth::user()->hasAdminPermission('products.edit'))
                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:0.78rem;font-weight:600;color:#166534;"><span style="width:8px;height:8px;border-radius:50%;background:#166534;display:inline-block;"></span> Edit</span>
                    @else
                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:0.78rem;font-weight:600;color:#dc2626;"><span style="width:8px;height:8px;border-radius:50%;background:#dc2626;display:inline-block;"></span> Edit</span>
                    @endif
                    @if(Auth::user()->hasAdminPermission('products.delete'))
                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:0.78rem;font-weight:600;color:#166534;"><span style="width:8px;height:8px;border-radius:50%;background:#166534;display:inline-block;"></span> Remove</span>
                    @else
                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:0.78rem;font-weight:600;color:#dc2626;"><span style="width:8px;height:8px;border-radius:50%;background:#dc2626;display:inline-block;"></span> Remove</span>
                    @endif
                </div>
                @endif
            </div>
            <button type="button" class="btn-save" id="addNewProductBtn" style="padding:0.6rem 1.3rem;font-size:0.88rem;">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Add New Product
            </button>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="card shadow">
        <div class="card-body">

            <!-- Toolbar: search + count -->
            <div class="prod-toolbar">
                <div class="prod-search-wrap">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" class="prod-search" id="prodSearch" placeholder="Search by name, code or category…">
                </div>
                <span class="prod-count" id="prodCount"></span>
            </div>

            <!-- Card Grid -->
            <div class="prod-grid" id="prodGrid">
                @forelse($product_list as $product)
                <div class="pcard"
                     data-search="{{ strtolower($product->item_name . ' ' . ($product->item_code ?? '') . ' ' . ($product->category->category_name ?? '')) }}">

                    {{-- Image --}}
                    <div class="pcard-img">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->item_name }}">
                        @else
                            <div class="pcard-img-empty">
                                <svg width="36" height="36" fill="none" stroke="#cbd5e1" stroke-width="1.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                        <div class="pcard-badges">
                            @if($product->category)
                                <span class="pcard-badge pcard-badge-cat">{{ $product->category->category_name }}</span>
                            @endif
                            @if($product->is_featured)
                                <span class="pcard-badge pcard-badge-feat">Featured</span>
                            @endif
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="pcard-body">
                        <p class="pcard-name">{{ $product->item_name }}</p>
                        <p class="pcard-code">{{ $product->item_code ?? '—' }}</p>
                        <div class="pcard-meta">
                            @if($product->current_sale_price)
                                <span class="pcard-price">&#8377;{{ number_format($product->current_sale_price, 0) }}</span>
                            @else
                                <span class="pcard-price-na">Price on request</span>
                            @endif
                            <span class="pcard-chip pcard-chip-qty">
                                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                Qty: {{ $product->quantity ?? 0 }}
                            </span>
                            <span class="pcard-chip pcard-chip-uom">{{ $product->uom ?? '—' }}</span>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="pcard-footer">
                        <div class="pcard-toggles">
                            <button type="button"
                                class="pcard-toggle toggle-featured-btn"
                                data-id="{{ $product->id }}"
                                data-featured="{{ $product->is_featured ? 1 : 0 }}"
                                style="background:{{ $product->is_featured ? '#e7f5ff' : '#f1f3f5' }};color:{{ $product->is_featured ? '#1c7ed6' : '#868e96' }};border-color:{{ $product->is_featured ? '#a5d8ff' : '#dee2e6' }};">
                                {{ $product->is_featured ? 'Featured' : 'Not Featured' }}
                            </button>
                            <button type="button"
                                class="pcard-toggle toggle-active-btn"
                                data-id="{{ $product->id }}"
                                data-active="{{ $product->is_active ? 1 : 0 }}"
                                style="background:{{ $product->is_active ? '#ebfbee' : '#fff5f5' }};color:{{ $product->is_active ? '#2f9e44' : '#c92a2a' }};border-color:{{ $product->is_active ? '#8ce99a' : '#ffa8a8' }};">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </div>
                        <div style="display:flex;gap:6px;align-items:center;">
                            <button type="button" class="pcard-edit edit-product-btn"
                                data-id="{{ $product->id }}"
                                data-category-id="{{ $product->category_id }}"
                                data-uom="{{ $product->uom }}"
                                data-sub-category-id="{{ $product->sub_category_id }}"
                                data-name="{{ $product->item_name }}"
                                data-code="{{ $product->item_code }}"
                                data-price="{{ $product->current_sale_price }}"
                                data-qty="{{ $product->quantity ?? 0 }}"
                                data-description="{{ $product->description }}"
                                data-featured="{{ $product->is_featured ? 1 : 0 }}"
                                data-image="{{ $product->image ? Storage::url($product->image) : '' }}"
                                data-spec-type="{{ $product->type }}"
                                data-spec-brand="{{ $product->brand }}"
                                data-spec-model="{{ $product->model }}"
                                data-spec-voltage="{{ $product->operating_voltage }}"
                                data-spec-panel-type="{{ $product->solar_panel_type }}"
                                data-spec-mnre="{{ $product->mnre_approved }}"
                                data-spec-certs="{{ $product->certifications }}"
                                data-spec-warranty="{{ $product->manufacturer_warranty }}"
                                data-spec-cells="{{ $product->number_of_cells }}"
                                data-spec-encap="{{ $product->encapsulate }}"
                                data-spec-origin="{{ $product->country_of_origin }}"
                                data-spec-inputv="{{ $product->input_voltage }}"
                                data-spec-maxpower="{{ $product->max_supported_panel_power }}">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </button>
                            <form action="{{ route('product.delete', $product->id) }}" method="POST" class="delete-prod-form" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="pcard-edit delete-prod-btn" style="background:#e03131;" data-name="{{ $product->item_name }}">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="prod-empty" style="grid-column:1/-1;">
                    <svg width="56" height="56" fill="none" stroke="#94a3b8" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <p style="font-size:0.95rem;font-weight:600;color:var(--text-primary);margin:0 0 4px;">No products yet</p>
                    <p style="font-size:0.85rem;margin:0;">Click "Add New Product" to get started.</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="prod-pagination" id="prodPagination" style="display:none;">
                <span class="prod-pg-info" id="pgInfo"></span>
                <div class="prod-pg-btns" id="pgBtns"></div>
            </div>

        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content glass-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">
                        <svg width="16" height="16" fill="none" stroke="var(--primary-blue)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Add New Product
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addProductForm" action="{{ route('saveNewProduct') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        {{-- Section: Classification --}}
                        <p class="form-section-label">Classification</p>
                        <div class="mb-3">
                            <label class="form-label">Category <span style="color:#e74c3c;">*</span></label>
                            <input type="hidden" name="category_id" id="addCatHidden" required>
                            <div class="mdd-trigger" id="addCatTrigger" onclick="mddToggle('addCatList', this)">
                                <span id="addCatLabel" style="flex:1;">Select category</span>
                                <svg class="mdd-arrow" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                            <div class="mdd-list" id="addCatList">
                                <input class="mdd-search" type="text" placeholder="Search..." oninput="mddFilter('addCatList', this.value)">
                                @foreach($categories as $category)
                                <div class="mdd-opt" onclick="mddPick('addCatHidden','addCatLabel','addCatList','addCatTrigger','{{ $category->id }}','{{ $category->category_name }}')">{{ $category->category_name }}</div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Section: Product Info --}}
                        <p class="form-section-label">Product Info</p>
                        <div class="row mb-3">
                            <div class="col-md-8 mb-3 mb-md-0">
                                <label class="form-label">Product Name <span style="color:#e74c3c;">*</span></label>
                                <input type="text" class="form-control" id="productName" name="product_name" required placeholder="e.g. Solar Panel 400W" maxlength="100">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Product Code <span style="color:#e74c3c;">*</span></label>
                                <input type="text" class="form-control" id="productCode" name="item_code" required placeholder="e.g. SOL-001" maxlength="50">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">
                                <label class="form-label">Sale Price (&#8377;)</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="current_sale_price" placeholder="0.00">
                            </div>
                            <div class="col-4">
                                <label class="form-label">Quantity <span style="color:#e74c3c;">*</span></label>
                                <input type="number" min="0" class="form-control" name="quantity" placeholder="0" value="0" required>
                            </div>
                            <div class="col-4">
                                <label class="form-label">UOM <span style="color:#e74c3c;">*</span></label>
                                <input type="hidden" name="uom" id="addUomHidden" required>
                                <div class="mdd-trigger" id="addUomTrigger" onclick="mddToggle('addUomList', this)">
                                    <span id="addUomLabel" style="flex:1;">Select</span>
                                    <svg class="mdd-arrow" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                                <div class="mdd-list" id="addUomList">
                                    <div class="mdd-opt" onclick="uomPick('add','Piece','Piece')">Piece</div>
                                    <div class="mdd-opt" onclick="uomPick('add','Kilogram','Kilogram')">Kilogram</div>
                                    <div class="mdd-opt" onclick="uomPick('add','Liter','Liter')">Liter</div>
                                    <div class="mdd-opt" onclick="uomPick('add','Meter','Meter')">Meter</div>
                                    <div class="mdd-opt" onclick="uomPick('add','Box','Box')">Box</div>
                                    <div class="mdd-opt" onclick="uomPick('add','Pack','Pack')">Pack</div>
                                    <div class="mdd-opt" onclick="uomPick('add','Watt','Watt')">Watt</div>
                                    <div class="mdd-opt" onclick="uomPick('add','KW','KW')">KW</div>
                                    <div class="mdd-opt" onclick="uomPick('add','Set','Set')">Set</div>
                                    <div class="mdd-opt" style="color:#4A90E2;border-top:1px solid #e1e8ed;" onclick="uomPick('add','__custom__','Custom (specify below)')">Custom (specify below)</div>
                                </div>
                                <div class="mdd-custom-wrap" id="addCustomUomWrap" style="display:none;">
                                    <input type="text" id="addCustomUomInput" placeholder="Please specify UOM…">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="2" placeholder="Brief product description (optional)"></textarea>
                        </div>

                        {{-- Section: Specifications --}}
                        <p class="form-section-label">Specifications <small class="text-muted">(all optional)</small></p>
                        <div class="row g-2 mb-3">
                            <div class="col-md-4"><label class="form-label mb-0">Type</label><input type="text" class="form-control" name="type" placeholder="e.g. Solar Panel"></div>
                            <div class="col-md-4"><label class="form-label mb-0">Brand</label><input type="text" class="form-control" name="brand" placeholder="e.g. Waaree, Adani"></div>
                            <div class="col-md-4"><label class="form-label mb-0">Model</label><input type="text" class="form-control" name="product_model" placeholder="e.g. WS-545"></div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-4"><label class="form-label mb-0">Operating Voltage</label><input type="text" class="form-control" name="operating_voltage" placeholder="e.g. 24V / 48V"></div>
                            <div class="col-md-4"><label class="form-label mb-0">Solar Panel Type</label><input type="text" class="form-control" name="solar_panel_type" placeholder="e.g. Mono PERC"></div>
                            <div class="col-md-4"><label class="form-label mb-0">MNRE Approved</label><input type="text" class="form-control" name="mnre_approved" placeholder="e.g. Yes / No"></div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6"><label class="form-label mb-0">Certifications & Approvals</label><input type="text" class="form-control" name="certifications" placeholder="e.g. BIS, IEC 61215, IEC 61730"></div>
                            <div class="col-md-6"><label class="form-label mb-0">Manufacturer Warranty</label><input type="text" class="form-control" name="manufacturer_warranty" placeholder="e.g. 25 Years"></div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-4"><label class="form-label mb-0">Number of Cells</label><input type="text" class="form-control" name="number_of_cells" placeholder="e.g. 144"></div>
                            <div class="col-md-4"><label class="form-label mb-0">Encapsulate</label><input type="text" class="form-control" name="encapsulate" placeholder="e.g. EVA"></div>
                            <div class="col-md-4"><label class="form-label mb-0">Country of Origin</label><input type="text" class="form-control" name="country_of_origin" placeholder="e.g. India"></div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6"><label class="form-label mb-0">Input Voltage</label><input type="text" class="form-control" name="input_voltage" placeholder="e.g. 12V - 48V"></div>
                            <div class="col-md-6"><label class="form-label mb-0">Max Supported Panel Power</label><input type="text" class="form-control" name="max_supported_panel_power" placeholder="e.g. 6000W"></div>
                        </div>

                        {{-- Section: Photos --}}
                        <p class="form-section-label">Photos</p>
                        <div class="row mb-3">
                            <div class="col-md-5 mb-3 mb-md-0">
                                <label class="form-label">Main Photo</label>
                                <div class="upload-zone" id="addMainZone">
                                    <input type="file" name="image" accept="image/*" id="addProductImage">
                                    <div class="upload-zone-icon">
                                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div class="upload-zone-text"><strong>Click to upload</strong> main photo<br><span style="font-size:0.72rem;">1 image only</span></div>
                                </div>
                                <div class="img-preview-strip" id="addImgPreview"></div>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label">Gallery Photos <small class="text-muted">(up to 8)</small></label>
                                <div class="upload-zone" id="addGalleryZone">
                                    <input type="file" name="product_images[]" accept="image/*" multiple id="addGalleryInput">
                                    <div class="upload-zone-icon">
                                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div class="upload-zone-text"><strong>Click to upload</strong> gallery images<br><span style="font-size:0.72rem;">Max 8 images</span></div>
                                </div>
                                <div class="img-preview-strip" id="addGalleryPreview"></div>
                            </div>
                        </div>

                        {{-- Featured --}}
                        <div class="form-check form-switch" style="padding-left:2.5rem;">
                            <input class="form-check-input" type="checkbox" id="addIsFeatured" name="is_featured" value="1">
                            <label class="form-check-label" for="addIsFeatured" style="font-size:0.88rem;font-weight:600;cursor:pointer;">Mark as Featured</label>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Cancel
                        </button>
                        <button type="submit" class="btn-save">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Add Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content glass-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">
                        <svg width="16" height="16" fill="none" stroke="var(--primary-blue)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Product
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editProductForm" method="POST" action="{{ route('updateProduct') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" id="editProductId">
                    <div class="modal-body">

                        {{-- Section: Classification --}}
                        <p class="form-section-label">Classification</p>
                        <div class="mb-3">
                            <label class="form-label">Category <span style="color:#e74c3c;">*</span></label>
                            <input type="hidden" name="category_id" id="editCatHidden" required>
                            <div class="mdd-trigger" id="editCatTrigger" onclick="mddToggle('editCatList', this)">
                                <span id="editCatLabel" style="flex:1;">Select category</span>
                                <svg class="mdd-arrow" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                            <div class="mdd-list" id="editCatList">
                                <input class="mdd-search" type="text" placeholder="Search..." oninput="mddFilter('editCatList', this.value)">
                                @foreach($categories as $category)
                                <div class="mdd-opt" data-val="{{ $category->id }}" data-label="{{ $category->category_name }}" onclick="mddPick('editCatHidden','editCatLabel','editCatList','editCatTrigger','{{ $category->id }}','{{ $category->category_name }}')">{{ $category->category_name }}</div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Section: Product Info --}}
                        <p class="form-section-label">Product Info</p>
                        <div class="row mb-3">
                            <div class="col-md-8 mb-3 mb-md-0">
                                <label class="form-label">Product Name <span style="color:#e74c3c;">*</span></label>
                                <input type="text" class="form-control" id="editProductName" name="product_name" required maxlength="100">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Product Code <span style="color:#e74c3c;">*</span></label>
                                <input type="text" class="form-control" id="editProductCode" name="item_code" required maxlength="50">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">
                                <label class="form-label">Sale Price (&#8377;)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="editProductPrice" name="current_sale_price" placeholder="0.00">
                            </div>
                            <div class="col-4">
                                <label class="form-label">Quantity <span style="color:#e74c3c;">*</span></label>
                                <input type="number" min="0" class="form-control" id="editProductQty" name="quantity" placeholder="0" value="0" required>
                            </div>
                            <div class="col-4">
                                <label class="form-label">UOM <span style="color:#e74c3c;">*</span></label>
                                <input type="hidden" name="uom" id="editUomHidden" required>
                                <div class="mdd-trigger" id="editUomTrigger" onclick="mddToggle('editUomList', this)">
                                    <span id="editUomLabel" style="flex:1;">Select</span>
                                    <svg class="mdd-arrow" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                                <div class="mdd-list" id="editUomList">
                                    <div class="mdd-opt" onclick="uomPick('edit','Piece','Piece')">Piece</div>
                                    <div class="mdd-opt" onclick="uomPick('edit','Kilogram','Kilogram')">Kilogram</div>
                                    <div class="mdd-opt" onclick="uomPick('edit','Liter','Liter')">Liter</div>
                                    <div class="mdd-opt" onclick="uomPick('edit','Meter','Meter')">Meter</div>
                                    <div class="mdd-opt" onclick="uomPick('edit','Box','Box')">Box</div>
                                    <div class="mdd-opt" onclick="uomPick('edit','Pack','Pack')">Pack</div>
                                    <div class="mdd-opt" onclick="uomPick('edit','Watt','Watt')">Watt</div>
                                    <div class="mdd-opt" onclick="uomPick('edit','KW','KW')">KW</div>
                                    <div class="mdd-opt" onclick="uomPick('edit','Set','Set')">Set</div>
                                    <div class="mdd-opt" style="color:#4A90E2;border-top:1px solid #e1e8ed;" onclick="uomPick('edit','__custom__','Custom (specify below)')">Custom (specify below)</div>
                                </div>
                                <div class="mdd-custom-wrap" id="editCustomUomWrap" style="display:none;">
                                    <input type="text" id="editCustomUomInput" placeholder="Please specify UOM…">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="editProductDesc" name="description" rows="2" placeholder="Brief product description (optional)"></textarea>
                        </div>

                        {{-- Section: Specifications --}}
                        <p class="form-section-label">Specifications <small class="text-muted">(all optional)</small></p>
                        <div class="row g-2 mb-3">
                            <div class="col-md-4"><label class="form-label mb-0">Type</label><input type="text" class="form-control" id="editSpecType" name="type"></div>
                            <div class="col-md-4"><label class="form-label mb-0">Brand</label><input type="text" class="form-control" id="editSpecBrand" name="brand"></div>
                            <div class="col-md-4"><label class="form-label mb-0">Model</label><input type="text" class="form-control" id="editSpecModel" name="product_model"></div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-4"><label class="form-label mb-0">Operating Voltage</label><input type="text" class="form-control" id="editSpecVoltage" name="operating_voltage"></div>
                            <div class="col-md-4"><label class="form-label mb-0">Solar Panel Type</label><input type="text" class="form-control" id="editSpecPanelType" name="solar_panel_type"></div>
                            <div class="col-md-4"><label class="form-label mb-0">MNRE Approved</label><input type="text" class="form-control" id="editSpecMnre" name="mnre_approved"></div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6"><label class="form-label mb-0">Certifications & Approvals</label><input type="text" class="form-control" id="editSpecCerts" name="certifications"></div>
                            <div class="col-md-6"><label class="form-label mb-0">Manufacturer Warranty</label><input type="text" class="form-control" id="editSpecWarranty" name="manufacturer_warranty"></div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-4"><label class="form-label mb-0">Number of Cells</label><input type="text" class="form-control" id="editSpecCells" name="number_of_cells"></div>
                            <div class="col-md-4"><label class="form-label mb-0">Encapsulate</label><input type="text" class="form-control" id="editSpecEncap" name="encapsulate"></div>
                            <div class="col-md-4"><label class="form-label mb-0">Country of Origin</label><input type="text" class="form-control" id="editSpecOrigin" name="country_of_origin"></div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6"><label class="form-label mb-0">Input Voltage</label><input type="text" class="form-control" id="editSpecInputV" name="input_voltage"></div>
                            <div class="col-md-6"><label class="form-label mb-0">Max Supported Panel Power</label><input type="text" class="form-control" id="editSpecMaxPower" name="max_supported_panel_power"></div>
                        </div>

                        {{-- Section: Photos --}}
                        <p class="form-section-label">Photos</p>
                        <div class="row mb-3">
                            <div class="col-md-5 mb-3 mb-md-0">
                                <label class="form-label">Main Photo <small class="text-muted">(blank = keep current)</small></label>
                                <div class="upload-zone">
                                    <input type="file" name="image" accept="image/*" id="editProductImageInput">
                                    <div class="upload-zone-icon">
                                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div class="upload-zone-text"><strong>Click to replace</strong> main photo</div>
                                </div>
                                <div class="img-preview-strip" id="editImgPreview"></div>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label">Gallery Photos <small class="text-muted" id="editGalleryCount">(loading…)</small></label>
                                <div id="editGalleryExisting" class="img-preview-strip" style="margin-bottom:8px;"></div>
                                <div class="upload-zone">
                                    <input type="file" name="product_images[]" accept="image/*" multiple id="editGalleryInput">
                                    <div class="upload-zone-icon">
                                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div class="upload-zone-text"><strong>Add more</strong> gallery images<br><span style="font-size:0.72rem;">Max 8 total</span></div>
                                </div>
                                <div class="img-preview-strip" id="editGalleryPreview"></div>
                            </div>
                        </div>

                        {{-- Featured --}}
                        <div class="form-check form-switch" style="padding-left:2.5rem;">
                            <input class="form-check-input" type="checkbox" id="editIsFeatured" name="is_featured" value="1">
                            <label class="form-check-label" for="editIsFeatured" style="font-size:0.88rem;font-weight:600;cursor:pointer;">Mark as Featured</label>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Cancel
                        </button>
                        <button type="submit" class="btn-save">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Update Price Modal -->
    <div class="modal fade" id="updatePriceModal" tabindex="-1" role="dialog" aria-labelledby="updatePriceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content glass-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="updatePriceModalLabel">
                        <i class="fas fa-dollar-sign me-2"></i>Update Product Price
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updatePriceForm" method="POST" action="{{ route('updateProductPrice') }}">
                    @csrf
                    <input type="hidden" name="product_id" id="updatePriceProductId">

                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="currentPrice" class="form-label">Current Price</label>
                            <input type="text" class="form-control" id="currentPrice" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="newPrice" class="form-label">New Price <span style="color: #e74c3c;">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control" id="newPrice" name="new_price" required placeholder="Enter new price">
                            <small class="text-muted-custom">Enter the new sale price for this product</small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Price
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {

            // ── Card grid search + pagination ────────────────────────
            const PER_PAGE = 12;
            let currentPage = 1;
            let filtered = [];

            function allCards() {
                return Array.from(document.querySelectorAll('#prodGrid .pcard'));
            }

            function applyFilter() {
                const q = document.getElementById('prodSearch').value.toLowerCase().trim();
                filtered = allCards().filter(c => !q || c.dataset.search.includes(q));
                currentPage = 1;
                render();
            }

            function render() {
                const cards = allCards();
                cards.forEach(c => c.style.display = 'none');
                const start = (currentPage - 1) * PER_PAGE;
                const pageCards = filtered.slice(start, start + PER_PAGE);
                pageCards.forEach(c => c.style.display = 'flex');

                // Count
                const total = filtered.length;
                document.getElementById('prodCount').textContent =
                    total + ' product' + (total !== 1 ? 's' : '') + ' found';

                // Pagination
                const totalPages = Math.ceil(total / PER_PAGE);
                const pag = document.getElementById('prodPagination');
                const info = document.getElementById('pgInfo');
                const btns = document.getElementById('pgBtns');
                if (totalPages > 1) {
                    pag.style.display = 'flex';
                    const s = start + 1, e = Math.min(start + PER_PAGE, total);
                    info.textContent = `Showing ${s}–${e} of ${total}`;
                    btns.innerHTML = '';

                    const prevBtn = document.createElement('button');
                    prevBtn.className = 'prod-pg-btn';
                    prevBtn.textContent = '← Prev';
                    prevBtn.disabled = currentPage === 1;
                    prevBtn.onclick = () => { currentPage--; render(); };
                    btns.appendChild(prevBtn);

                    const maxPages = 7;
                    let start_p = Math.max(1, currentPage - 3);
                    let end_p   = Math.min(totalPages, start_p + maxPages - 1);
                    if (end_p - start_p < maxPages - 1) start_p = Math.max(1, end_p - maxPages + 1);

                    for (let p = start_p; p <= end_p; p++) {
                        const pb = document.createElement('button');
                        pb.className = 'prod-pg-btn' + (p === currentPage ? ' active' : '');
                        pb.textContent = p;
                        pb.onclick = ((pg) => () => { currentPage = pg; render(); })(p);
                        btns.appendChild(pb);
                    }

                    const nextBtn = document.createElement('button');
                    nextBtn.className = 'prod-pg-btn';
                    nextBtn.textContent = 'Next →';
                    nextBtn.disabled = currentPage === totalPages;
                    nextBtn.onclick = () => { currentPage++; render(); };
                    btns.appendChild(nextBtn);
                } else {
                    pag.style.display = 'none';
                    if (total > 0) info.textContent = '';
                }
            }

            document.getElementById('prodSearch').addEventListener('input', applyFilter);
            applyFilter(); // init

            // ── Modal Custom Dropdown functions ──────────────────────
            window.mddToggle = function(listId, triggerEl) {
                var list = document.getElementById(listId);
                var isOpen = list.style.display === 'block';
                document.querySelectorAll('.mdd-list').forEach(function(l) {
                    l.style.display = 'none';
                    var t = document.getElementById(l.id.replace('List','Trigger'));
                    if (t) t.classList.remove('is-open');
                });
                if (!isOpen) {
                    var rect = triggerEl.getBoundingClientRect();
                    list.style.top = rect.bottom + 'px';
                    list.style.left = rect.left + 'px';
                    list.style.width = rect.width + 'px';
                    list.style.display = 'block';
                    triggerEl.classList.add('is-open');
                    var s = list.querySelector('.mdd-search');
                    if (s) { s.value = ''; mddFilter(listId, ''); s.focus(); }
                }
            };
            window.mddFilter = function(listId, q) {
                q = q.toLowerCase();
                document.querySelectorAll('#' + listId + ' .mdd-opt').forEach(function(o) {
                    o.style.display = o.textContent.toLowerCase().includes(q) ? '' : 'none';
                });
            };
            window.mddPick = function(hiddenId, labelId, listId, triggerId, val, label) {
                document.getElementById(hiddenId).value = val;
                var lbl = document.getElementById(labelId);
                lbl.textContent = label;
                var trigger = document.getElementById(triggerId);
                trigger.classList.add('has-val');
                trigger.classList.remove('is-open');
                document.getElementById(listId).style.display = 'none';
                document.querySelectorAll('#' + listId + ' .mdd-opt').forEach(function(o) {
                    o.classList.toggle('mdd-active', o.textContent.trim() === label);
                });
            };
            window.uomPick = function(prefix, val, label) {
                mddPick(prefix+'UomHidden', prefix+'UomLabel', prefix+'UomList', prefix+'UomTrigger', val, label);
                var customWrap = document.getElementById(prefix+'CustomUomWrap');
                if (customWrap) {
                    customWrap.style.display = val === '__custom__' ? 'block' : 'none';
                    if (val !== '__custom__') {
                        var ci = document.getElementById(prefix+'CustomUomInput');
                        if (ci) ci.value = '';
                    }
                }
            };
            // Close on outside click
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.mdd-trigger') && !e.target.closest('.mdd-list')) {
                    document.querySelectorAll('.mdd-list').forEach(function(l) {
                        l.style.display = 'none';
                        var t = document.getElementById(l.id.replace('List','Trigger'));
                        if (t) t.classList.remove('is-open');
                    });
                }
            });

            // ── Gallery helpers ──────────────────────────────────────
            var addGalleryFiles = [];
            var editGalleryFiles = [];
            const MAX_GALLERY = 8;

            function renderNewGalleryPreviews(filesArr, previewEl, inputEl) {
                previewEl.empty();
                filesArr.forEach(function(file, idx) {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        const wrap = $('<div>').css({position:'relative',display:'inline-block',flexShrink:0});
                        wrap.append(`<img src="${ev.target.result}" title="${file.name}" style="width:56px;height:56px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6;">`);
                        const rmBtn = $('<button type="button">').css({position:'absolute',top:'-5px',right:'-5px',width:'17px',height:'17px',borderRadius:'50%',background:'#ef4444',border:'none',cursor:'pointer',display:'flex',alignItems:'center',justifyContent:'center',padding:0});
                        rmBtn.html('<svg width="9" height="9" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>');
                        rmBtn.on('click', function() {
                            filesArr.splice(idx, 1);
                            const dt = new DataTransfer();
                            filesArr.forEach(function(f) { dt.items.add(f); });
                            inputEl.files = dt.files;
                            renderNewGalleryPreviews(filesArr, previewEl, inputEl);
                            if (inputEl.id === 'editGalleryInput') updateEditGalleryCount();
                        });
                        wrap.append(rmBtn);
                        previewEl.append(wrap);
                    };
                    reader.readAsDataURL(file);
                });
            }

            function mergeGalleryFiles(newFiles, existingArr, existingOnServer) {
                const slotsLeft = MAX_GALLERY - existingOnServer - existingArr.length;
                const toAdd = Array.from(newFiles).slice(0, slotsLeft);
                if (Array.from(newFiles).length > slotsLeft && slotsLeft <= 0) {
                    alert('Max ' + MAX_GALLERY + ' gallery images allowed. No slots left.');
                } else if (Array.from(newFiles).length > toAdd.length) {
                    alert('Max ' + MAX_GALLERY + ' gallery images allowed. Only ' + slotsLeft + ' slot(s) added.');
                }
                toAdd.forEach(function(f) { existingArr.push(f); });
            }

            function updateEditGalleryCount() {
                const existing = $('#editGalleryExisting .gallery-thumb').length;
                const total = existing + editGalleryFiles.length;
                $('#editGalleryCount').text('(' + total + '/8 images)').css('color', total >= 8 ? '#e74c3c' : '');
            }

            // ── Add New Product ──────────────────────────────────────
            $('#addNewProductBtn').on('click', function () {
                $('#addProductForm')[0].reset();
                $('#addImgPreview').empty();
                $('#addGalleryPreview').empty();
                addGalleryFiles = [];
                // Reset custom dropdowns
                $('#addCatHidden').val('');
                $('#addCatLabel').text('Select category').closest('.mdd-trigger').removeClass('has-val');
                $('#addCatList .mdd-opt').removeClass('mdd-active');
                $('#addUomHidden').val('');
                $('#addUomLabel').text('Select').closest('.mdd-trigger').removeClass('has-val');
                $('#addUomList .mdd-opt').removeClass('mdd-active');
                $('#addCustomUomWrap').hide();
                $('#addCustomUomInput').val('');
                new bootstrap.Modal(document.getElementById('addProductModal')).show();
            });

            // Main image preview - Add
            $('#addProductImage').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        $('#addImgPreview').html(`<img src="${e.target.result}" style="width:56px;height:56px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6;">`);
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Gallery preview - Add (accumulate, max 8)
            $('#addGalleryInput').on('change', function() {
                mergeGalleryFiles(this.files, addGalleryFiles, 0);
                this.value = '';
                const dt = new DataTransfer();
                addGalleryFiles.forEach(function(f) { dt.items.add(f); });
                this.files = dt.files;
                renderNewGalleryPreviews(addGalleryFiles, $('#addGalleryPreview'), document.getElementById('addGalleryInput'));
            });

            // Main image preview - Edit
            $('#editProductImageInput').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        $('#editImgPreview').html(`<img src="${e.target.result}" style="width:56px;height:56px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6;">`);
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Gallery preview - Edit (accumulate, max 8)
            $('#editGalleryInput').on('change', function() {
                const existingOnServer = $('#editGalleryExisting .gallery-thumb').length;
                mergeGalleryFiles(this.files, editGalleryFiles, existingOnServer);
                this.value = '';
                const dt = new DataTransfer();
                editGalleryFiles.forEach(function(f) { dt.items.add(f); });
                this.files = dt.files;
                renderNewGalleryPreviews(editGalleryFiles, $('#editGalleryPreview'), document.getElementById('editGalleryInput'));
                updateEditGalleryCount();
            });

            // Delete existing gallery image
            $(document).on('click', '.del-gallery-img', function() {
                const btn = $(this);
                const imgId = btn.data('id');
                if (!confirm('Delete this gallery image?')) return;
                $.ajax({
                    url: '{{ route("product.image.delete", "__ID__") }}'.replace('__ID__', imgId),
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}', _method: 'DELETE' },
                    success: function(res) {
                        if (res.success) {
                            btn.closest('.gallery-thumb').remove();
                            updateEditGalleryCount();
                        }
                    }
                });
            });

            // Edit Product
            $(document).on('click', '.edit-product-btn', function () {
                const btn = $(this);
                const id = btn.data('id');
                const categoryId = btn.data('category-id');
                const uomVal = btn.data('uom') || '';

                $('#editProductId').val(id);
                $('#editProductName').val(btn.data('name'));
                $('#editProductCode').val(btn.data('code'));
                $('#editProductPrice').val(btn.data('price') || '');
                $('#editProductQty').val(btn.data('qty') || 0);
                $('#editProductDesc').val(btn.data('description') || '');
                $('#editIsFeatured').prop('checked', btn.data('featured') == 1);

                // Populate spec fields
                $('#editSpecType').val(btn.data('spec-type') || '');
                $('#editSpecBrand').val(btn.data('spec-brand') || '');
                $('#editSpecModel').val(btn.data('spec-model') || '');
                $('#editSpecVoltage').val(btn.data('spec-voltage') || '');
                $('#editSpecPanelType').val(btn.data('spec-panel-type') || '');
                $('#editSpecMnre').val(btn.data('spec-mnre') || '');
                $('#editSpecCerts').val(btn.data('spec-certs') || '');
                $('#editSpecWarranty').val(btn.data('spec-warranty') || '');
                $('#editSpecCells').val(btn.data('spec-cells') || '');
                $('#editSpecEncap').val(btn.data('spec-encap') || '');
                $('#editSpecOrigin').val(btn.data('spec-origin') || '');
                $('#editSpecInputV').val(btn.data('spec-inputv') || '');
                $('#editSpecMaxPower').val(btn.data('spec-maxpower') || '');

                // Populate category custom dropdown
                const catOpt = $('#editCatList .mdd-opt[data-val="' + categoryId + '"]');
                const catLabel = catOpt.length ? catOpt.text().trim() : '';
                $('#editCatHidden').val(categoryId);
                $('#editCatLabel').text(catLabel || 'Select category');
                $('#editCatTrigger').toggleClass('has-val', !!catLabel);
                $('#editCatList .mdd-opt').removeClass('mdd-active');
                catOpt.addClass('mdd-active');

                // Populate UOM custom dropdown
                const knownUoms = ['Piece','Kilogram','Liter','Meter','Box','Pack','Watt','KW','Set'];
                const isKnown = knownUoms.includes(uomVal);
                if (isKnown || !uomVal) {
                    $('#editUomHidden').val(uomVal);
                    $('#editUomLabel').text(uomVal || 'Select');
                    $('#editUomTrigger').toggleClass('has-val', !!uomVal);
                    $('#editCustomUomWrap').hide();
                    $('#editCustomUomInput').val('');
                } else {
                    // custom value
                    $('#editUomHidden').val('__custom__');
                    $('#editUomLabel').text('Custom (specify below)');
                    $('#editUomTrigger').addClass('has-val');
                    $('#editCustomUomWrap').show();
                    $('#editCustomUomInput').val(uomVal);
                }
                $('#editUomList .mdd-opt').removeClass('mdd-active');

                // Show existing main image
                const img = btn.data('image');
                if (img) { $('#editImgPreview').html(`<img src="${img}" style="width:56px;height:56px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6;">`); }
                else { $('#editImgPreview').empty(); }

                // Reset gallery
                editGalleryFiles = [];
                $('#editGalleryExisting').empty();
                $('#editGalleryPreview').empty();
                $('#editGalleryInput').val('');
                $('#editGalleryCount').text('(loading…)').css('color','');

                // Load gallery images
                $.get('{{ url("admin/products") }}/' + id + '/images', function(images) {
                    const container = $('#editGalleryExisting');
                    images.forEach(function(img) {
                        container.append(`
                            <div class="gallery-thumb" style="position:relative;display:inline-block;flex-shrink:0;">
                                <img src="${img.url}" style="width:56px;height:56px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6;">
                                <button type="button" class="del-gallery-img" data-id="${img.id}"
                                    style="position:absolute;top:-5px;right:-5px;width:17px;height:17px;border-radius:50%;background:#ef4444;color:#fff;border:none;cursor:pointer;font-size:9px;line-height:1;display:flex;align-items:center;justify-content:center;padding:0;">
                                    <svg width="9" height="9" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>`);
                    });
                    updateEditGalleryCount();
                });

                new bootstrap.Modal(document.getElementById('editProductModal')).show();
            });

            // Toggle Featured
            $(document).on('click', '.toggle-featured-btn', function () {
                const btn = $(this);
                const id = btn.data('id');
                $.post('{{ url("admin/products") }}/' + id + '/toggle-featured', { _token: '{{ csrf_token() }}' }, function(res) {
                    if (res.success) {
                        btn.data('featured', res.is_featured ? 1 : 0);
                        btn.text(res.is_featured ? 'Featured' : 'Not Featured');
                        btn.css({ background: res.is_featured ? '#e7f5ff' : '#f1f3f5', color: res.is_featured ? '#1c7ed6' : '#868e96', borderColor: res.is_featured ? '#a5d8ff' : '#dee2e6' });
                    }
                });
            });

            // Toggle Active
            $(document).on('click', '.toggle-active-btn', function () {
                const btn = $(this);
                const id = btn.data('id');
                $.post('{{ url("admin/products") }}/' + id + '/toggle-active', { _token: '{{ csrf_token() }}' }, function(res) {
                    if (res.success) {
                        btn.data('active', res.is_active ? 1 : 0);
                        btn.text(res.is_active ? 'Active' : 'Inactive');
                        btn.css({ background: res.is_active ? '#ebfbee' : '#fff5f5', color: res.is_active ? '#2f9e44' : '#c92a2a', borderColor: res.is_active ? '#8ce99a' : '#ffa8a8' });
                    }
                });
            });

            // ── Delete product confirmation ──────────────────────────
            $(document).on('click', '.delete-prod-btn', function () {
                var btn = $(this);
                var name = btn.data('name');
                Swal.fire({
                    title: 'Delete Product?',
                    text: 'Are you sure you want to delete "' + name + '"? This will also remove all gallery images. This cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e03131',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Delete',
                    cancelButtonText: 'Cancel'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        btn.closest('.delete-prod-form').submit();
                    }
                });
            });

            // Form validation - Update Price
            $('#updatePriceForm').on('submit', function (e) {
                const newPrice = $('#newPrice').val();

                if (!newPrice || newPrice < 0) {
                    e.preventDefault();
                    alert('Please enter a valid price.');
                    return false;
                }
            });

            // Form validation + custom UOM handling - Add
            $('#addProductForm').on('submit', function (e) {
                // Re-populate gallery files into the input before submit
                if (addGalleryFiles.length > 0) {
                    const dt = new DataTransfer();
                    addGalleryFiles.forEach(function(f) { dt.items.add(f); });
                    document.getElementById('addGalleryInput').files = dt.files;
                }
                const categoryId = $('#addCatHidden').val();
                const productName = $('#productName').val().trim();
                const productCode = $('#productCode').val().trim();
                const galleryCount = addGalleryFiles.length;
                let uomVal = $('#addUomHidden').val();

                // Resolve custom UOM
                if (uomVal === '__custom__') {
                    const custom = $('#addCustomUomInput').val().trim();
                    if (!custom) { e.preventDefault(); alert('Please specify the UOM.'); return false; }
                    $('#addUomHidden').val(custom);
                    uomVal = custom;
                }

                if (!categoryId || !productName || !productCode || !uomVal) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                    return false;
                }
                if (galleryCount > 8) {
                    e.preventDefault();
                    alert('Maximum 8 gallery images allowed.');
                    return false;
                }
            });

            // Form validation + custom UOM handling - Edit
            $('#editProductForm').on('submit', function (e) {
                // Re-populate gallery files into the input before submit
                if (editGalleryFiles.length > 0) {
                    const dt = new DataTransfer();
                    editGalleryFiles.forEach(function(f) { dt.items.add(f); });
                    document.getElementById('editGalleryInput').files = dt.files;
                }
                const categoryId = $('#editCatHidden').val();
                const productName = $('#editProductName').val().trim();
                const productCode = $('#editProductCode').val().trim();
                let uomVal = $('#editUomHidden').val();

                // Resolve custom UOM
                if (uomVal === '__custom__') {
                    const custom = $('#editCustomUomInput').val().trim();
                    if (!custom) { e.preventDefault(); alert('Please specify the UOM.'); return false; }
                    $('#editUomHidden').val(custom);
                    uomVal = custom;
                }

                if (!categoryId || !productName || !productCode || !uomVal) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                    return false;
                }
            });
        });
    </script>
@endsection
