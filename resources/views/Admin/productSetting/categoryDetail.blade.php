@extends('layouts.adminLayout')

@section('css')
<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
<style>
    :root {
        --blue: #4A90E2;
        --blue-dark: #3b7dc4;
        --text: #2d3436;
        --muted: #636e72;
        --border: #e1e8ed;
        --bg: #f5f7fa;
        --white: #ffffff;
    }

    @keyframes btn-spin { to { transform: rotate(360deg); } }
    .btn-spinner {
        display: inline-block; width: 13px; height: 13px;
        border: 2px solid rgba(255,255,255,0.35); border-top-color: #fff;
        border-radius: 50%; animation: btn-spin 0.7s linear infinite;
        vertical-align: middle; margin-right: 5px;
    }
    .btn-loading { opacity: 0.75; cursor: not-allowed !important; pointer-events: none; }

    .back-link {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 0.82rem; color: var(--blue); font-weight: 600;
        text-decoration: none; margin-bottom: 1rem;
    }
    .back-link:hover { color: var(--blue-dark); text-decoration: underline; }

    .detail-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        margin-bottom: 1.5rem;
    }
    .detail-header {
        display: flex; gap: 1.25rem; padding: 1.25rem;
        align-items: flex-start; flex-wrap: wrap;
    }
    .detail-img {
        width: 160px; height: 120px;
        object-fit: cover; border-radius: 10px;
        border: 1px solid var(--border);
        flex-shrink: 0;
    }
    .detail-img-placeholder {
        width: 160px; height: 120px;
        background: #f1f3f5; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .detail-info { flex: 1; min-width: 200px; }
    .detail-name { font-size: 1.3rem; font-weight: 700; color: var(--text); margin: 0 0 6px; }
    .detail-desc { font-size: 0.88rem; color: var(--muted); margin: 0 0 12px; line-height: 1.5; }
    .detail-badges { display: flex; gap: 8px; flex-wrap: wrap; }

    .badge-active {
        background: #ebfbee; color: #2f9e44;
        border: 1px solid #8ce99a;
        padding: 3px 10px; border-radius: 20px;
        font-size: 0.75rem; font-weight: 700;
    }
    .badge-inactive {
        background: #fff5f5; color: #c92a2a;
        border: 1px solid #ffa8a8;
        padding: 3px 10px; border-radius: 20px;
        font-size: 0.75rem; font-weight: 700;
    }
    .pill-count {
        display: inline-flex; align-items: center; gap: 4px;
        background: #e7f5ff; color: #1c7ed6;
        border: 1px solid #a5d8ff;
        padding: 3px 10px; border-radius: 20px;
        font-size: 0.75rem; font-weight: 700;
    }

    .section-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1rem; flex-wrap: wrap; gap: 0.75rem;
    }
    .section-title {
        font-size: 1.05rem; font-weight: 700; color: var(--text); margin: 0;
        display: flex; align-items: center; gap: 8px;
    }
    .btn-add {
        background: var(--blue); color: #fff;
        border: none; border-radius: 8px;
        padding: 0.45rem 1rem; font-size: 0.82rem; font-weight: 600;
        cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
        transition: background 0.15s;
    }
    .btn-add:hover { background: var(--blue-dark); }

    .sub-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 0.85rem;
    }
    .sub-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 1rem 1.15rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        transition: box-shadow 0.15s;
    }
    .sub-card:hover { box-shadow: 0 4px 14px rgba(0,0,0,0.08); }
    .sub-name { font-size: 0.92rem; font-weight: 700; color: var(--text); margin: 0 0 4px; }
    .sub-desc { font-size: 0.78rem; color: var(--muted); margin: 0 0 10px; min-height: 1.2em; }
    .sub-meta { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; }
    .sub-actions {
        display: flex; gap: 6px; padding-top: 10px;
        border-top: 1px solid #f3f4f6;
    }
    .btn-edit {
        background: var(--blue); color: #fff;
        border: none; border-radius: 6px;
        padding: 5px 12px; font-size: 0.76rem; font-weight: 600;
        cursor: pointer; display: inline-flex; align-items: center; gap: 4px;
        transition: background 0.15s;
    }
    .btn-edit:hover { background: var(--blue-dark); }
    .btn-del {
        background: #e03131; color: #fff;
        border: none; border-radius: 6px;
        padding: 5px 12px; font-size: 0.76rem; font-weight: 600;
        cursor: pointer; display: inline-flex; align-items: center; gap: 4px;
        transition: background 0.15s;
    }
    .btn-del:hover { background: #c92a2a; }

    .empty-state {
        text-align: center; padding: 2.5rem 1rem;
        color: var(--muted); font-size: 0.88rem;
        grid-column: 1 / -1;
    }

    .modal-content { border-radius: 12px; border: 1px solid var(--border); box-shadow: 0 10px 30px rgba(0,0,0,0.12); }
    .modal-header { background: #f8f9fa; border-bottom: 1px solid var(--border); border-radius: 12px 12px 0 0; padding: 1rem 1.25rem; }
    .modal-footer { background: #f8f9fa; border-top: 1px solid var(--border); border-radius: 0 0 12px 12px; padding: 1rem 1.25rem; }
    .modal-title { font-size: 0.95rem; font-weight: 700; color: var(--text); }
    .form-label { font-size: 0.82rem; font-weight: 600; color: var(--text); margin-bottom: 5px; }
    .form-control {
        border: 1px solid var(--border); border-radius: 8px;
        padding: 0.5rem 0.8rem; font-size: 0.85rem; color: var(--text);
    }
    .form-control:focus {
        border-color: var(--blue); box-shadow: 0 0 0 3px rgba(74,144,226,0.12); outline: none;
    }

    .prod-section { margin-top: 2rem; }
    .prod-list-box {
        background: var(--white); border: 1px solid var(--border); border-radius: 12px;
        max-height: 420px; overflow-y: auto; box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .prod-list-box::-webkit-scrollbar { width: 6px; }
    .prod-list-box::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    .prod-item {
        display: flex; align-items: center; gap: 12px; padding: 10px 16px;
        border-bottom: 1px solid #f1f5f9; cursor: pointer; transition: background 0.12s;
        text-decoration: none; color: inherit;
    }
    .prod-item:hover { background: #f0f4ff; }
    .prod-item:last-child { border-bottom: none; }
    .prod-item-img {
        width: 44px; height: 44px; border-radius: 8px; object-fit: cover;
        border: 1px solid var(--border); flex-shrink: 0;
    }
    .prod-item-placeholder {
        width: 44px; height: 44px; border-radius: 8px; background: #f1f3f5;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        font-size: 0.65rem; color: #adb5bd; font-weight: 700;
    }
    .prod-item-info { flex: 1; min-width: 0; }
    .prod-item-name { font-size: 0.84rem; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .prod-item-code { font-size: 0.72rem; color: var(--muted); }
    .prod-item-price { font-size: 0.82rem; font-weight: 700; color: var(--blue); white-space: nowrap; }
    .prod-item-stock { font-size: 0.7rem; color: var(--muted); white-space: nowrap; }
    .prod-empty { text-align: center; padding: 2rem 1rem; color: var(--muted); font-size: 0.85rem; }

    @media (max-width: 640px) {
        .detail-header { flex-direction: column; }
        .detail-img, .detail-img-placeholder { width: 100%; height: 150px; }
        .sub-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div style="padding: 1.25rem;">

    <a href="{{ route('manageCategory') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Categories
    </a>

    {{-- Category Detail Card --}}
    <div class="detail-card">
        <div class="detail-header">
            @if($category->image)
                <img src="{{ Storage::url($category->image) }}" class="detail-img" alt="{{ $category->category_name }}">
            @else
                <div class="detail-img-placeholder">
                    <i class="fas fa-image" style="color:#cbd5e1; font-size:2.5rem;"></i>
                </div>
            @endif
            <div class="detail-info">
                <h1 class="detail-name">{{ $category->category_name }}</h1>
                <p class="detail-desc">{{ $category->category_description ?: 'No description' }}</p>
                <div class="detail-badges">
                    @if($category->active_status == 1)
                        <span class="badge-active"><i class="fas fa-circle" style="font-size:0.45rem; vertical-align:middle; margin-right:3px;"></i>Active</span>
                    @else
                        <span class="badge-inactive"><i class="fas fa-circle" style="font-size:0.45rem; vertical-align:middle; margin-right:3px;"></i>Inactive</span>
                    @endif
                    <span class="pill-count">
                        <i class="fas fa-box" style="font-size:0.6rem;"></i>
                        {{ $category->products_count }} product{{ $category->products_count != 1 ? 's' : '' }}
                    </span>
                    <span class="pill-count" style="background:#f3f0ff; color:#7048e8; border-color:#d0bfff;">
                        <i class="fas fa-layer-group" style="font-size:0.6rem;"></i>
                        {{ $subCategories->count() }} subcategor{{ $subCategories->count() != 1 ? 'ies' : 'y' }}
                    </span>
                </div>
                <div style="display:flex; gap:8px; margin-top:14px;">
                    <button class="btn-edit" id="editCatBtn"
                        data-id="{{ $category->id }}"
                        data-name="{{ $category->category_name }}"
                        data-description="{{ $category->category_description }}"
                        data-status="{{ $category->active_status }}"
                        data-image="{{ $category->image ? Storage::url($category->image) : '' }}">
                        <i class="fas fa-pen"></i> Edit Category
                    </button>
                    <form action="{{ route('category.delete', $category->id) }}" method="POST" id="deleteCatForm" style="margin:0;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn-del" id="deleteCatBtn" data-name="{{ $category->category_name }}" data-count="{{ $category->products_count }}">
                            <i class="fas fa-trash"></i> Delete Category
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Subcategories Section --}}
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-layer-group" style="color: var(--blue);"></i>
            Subcategories
        </h2>
        <button class="btn-add" id="addSubBtn">
            <i class="fas fa-plus"></i> Add Subcategory
        </button>
    </div>

    <div class="sub-grid">
        @forelse($subCategories as $sub)
        <div class="sub-card">
            <p class="sub-name">{{ $sub->sub_category_name }}</p>
            <p class="sub-desc">{{ $sub->remarks ?: 'No description' }}</p>
            <div class="sub-meta">
                <span class="pill-count">
                    <i class="fas fa-box" style="font-size:0.6rem;"></i>
                    {{ $sub->products_count }} product{{ $sub->products_count != 1 ? 's' : '' }}
                </span>
            </div>
            <div class="sub-actions">
                <button class="btn-edit edit-sub-btn"
                    data-id="{{ $sub->id }}"
                    data-name="{{ $sub->sub_category_name }}"
                    data-description="{{ $sub->remarks }}">
                    <i class="fas fa-pen"></i> Edit
                </button>
                <form action="{{ route('deleteSubCategory', $sub->id) }}" method="POST" class="delete-sub-form" style="margin:0;">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn-del delete-sub-btn" data-name="{{ $sub->sub_category_name }}" data-count="{{ $sub->products_count }}">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-layer-group" style="font-size:1.8rem; margin-bottom:8px; display:block;"></i>
            <p>No subcategories yet. Click "Add Subcategory" to create one.</p>
        </div>
        @endforelse
    </div>

    {{-- Products in this Category --}}
    <div class="prod-section">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-box" style="color: var(--blue);"></i>
                Products in this Category
                <span class="pill-count" style="font-size:0.72rem;">{{ $products->count() }}</span>
            </h2>
        </div>

        <div class="prod-list-box">
            @forelse($products as $product)
            <a href="{{ route('manageProducts') }}?highlight={{ $product->id }}" class="prod-item">
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}" class="prod-item-img" alt="{{ $product->item_name }}">
                @else
                    <div class="prod-item-placeholder">N/A</div>
                @endif
                <div class="prod-item-info">
                    <div class="prod-item-name">{{ $product->item_name }}</div>
                    <div class="prod-item-code">{{ $product->item_code }} &bull; {{ $product->uom }}</div>
                </div>
                <div style="text-align:right;">
                    <div class="prod-item-price">₹{{ $product->current_sale_price }}</div>
                    <div class="prod-item-stock">Stock: {{ $product->quantity }}</div>
                </div>
            </a>
            @empty
            <div class="prod-empty">
                <i class="fas fa-box-open" style="font-size:1.5rem; margin-bottom:6px; display:block;"></i>
                No active products in this category.
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Add Subcategory Modal --}}
<div class="modal fade" id="addSubModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2" style="color: var(--blue);"></i>Add Subcategory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addSubForm" action="{{ route('saveNewSubCategory') }}" method="POST">
                @csrf
                <input type="hidden" name="category_id" value="{{ $category->id }}">
                <div class="modal-body" style="display:flex; flex-direction:column; gap:1rem; padding:1.25rem;">
                    <div>
                        <label class="form-label">Category</label>
                        <input type="text" class="form-control" value="{{ $category->category_name }}" disabled style="background:#f8f9fa;">
                    </div>
                    <div>
                        <label class="form-label">Subcategory Name <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control" name="sub_category_name" id="addSubName"
                            placeholder="e.g. Monocrystalline" maxlength="100" required>
                    </div>
                    <div>
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="sub_category_description" id="addSubDesc"
                            rows="3" placeholder="Brief description..." maxlength="500"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="gap:8px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size:0.85rem;">Cancel</button>
                    <button type="submit" id="addSubSubmitBtn"
                        style="background: var(--blue); color:#fff; border:none; border-radius:8px; padding:0.5rem 1.25rem; font-size:0.85rem; font-weight:600; cursor:pointer;">
                        <i class="fas fa-plus me-1"></i>Add Subcategory
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Subcategory Modal --}}
<div class="modal fade" id="editSubModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-pen me-2" style="color: var(--blue);"></i>Edit Subcategory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editSubForm" method="POST" action="{{ route('updateSubCategory') }}">
                @csrf
                <input type="hidden" name="sub_category_id" id="editSubId">
                <input type="hidden" name="category_id" value="{{ $category->id }}">
                <div class="modal-body" style="display:flex; flex-direction:column; gap:1rem; padding:1.25rem;">
                    <div>
                        <label class="form-label">Category</label>
                        <input type="text" class="form-control" value="{{ $category->category_name }}" disabled style="background:#f8f9fa;">
                    </div>
                    <div>
                        <label class="form-label">Subcategory Name <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control" name="sub_category_name" id="editSubName"
                            maxlength="100" required>
                    </div>
                    <div>
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="sub_category_description" id="editSubDesc"
                            rows="3" maxlength="500"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="gap:8px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size:0.85rem;">Cancel</button>
                    <button type="submit" id="editSubSubmitBtn"
                        style="background: var(--blue); color:#fff; border:none; border-radius:8px; padding:0.5rem 1.25rem; font-size:0.85rem; font-weight:600; cursor:pointer;">
                        <i class="fas fa-save me-1"></i>Update Subcategory
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Category Modal --}}
<div class="modal fade" id="editCatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-pen me-2" style="color: var(--blue);"></i>Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCatForm" method="POST" action="{{ route('updateCategory') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="category_id" value="{{ $category->id }}">
                <div class="modal-body" style="display:flex; flex-direction:column; gap:1rem; padding:1.25rem;">
                    <div>
                        <label class="form-label">Category Name <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control" name="categoryName" id="editCatName"
                            value="{{ $category->category_name }}" maxlength="100" required>
                    </div>
                    <div>
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="categoryDiscription" id="editCatDesc"
                            rows="3" maxlength="500">{{ $category->category_description }}</textarea>
                    </div>
                    <div>
                        <label class="form-label">Status</label>
                        <select class="form-select" name="activeStatus" id="editCatStatus" style="border:1px solid var(--border); border-radius:8px; padding:0.5rem 0.8rem; font-size:0.85rem;">
                            <option value="1" {{ $category->active_status == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $category->active_status != 1 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Category Image <span style="color:#94a3b8; font-weight:400;">(leave empty to keep existing)</span></label>
                        <input type="file" class="form-control" name="image" accept="image/*" id="editCatImageInput">
                        @if($category->image)
                        <div id="editCatPreviewBox" style="margin-top:8px; position:relative; display:inline-block;">
                            <img src="{{ Storage::url($category->image) }}" alt="Current Image" style="max-width:80px; border-radius:6px; border:1px solid #dee2e6;">
                            <button type="button" id="delCatImgBtn" style="position:absolute;top:-5px;right:-5px;width:20px;height:20px;border-radius:50%;background:#ef4444;color:#fff;border:none;cursor:pointer;font-size:10px;display:flex;align-items:center;justify-content:center;padding:0;" title="Delete image">
                                <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            <p style="margin:6px 0 0; font-size:0.75rem; color:#636e72;">Current image</p>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer" style="gap:8px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size:0.85rem;">Cancel</button>
                    <button type="submit" id="editCatSubmitBtn"
                        style="background: var(--blue); color:#fff; border:none; border-radius:8px; padding:0.5rem 1.25rem; font-size:0.85rem; font-weight:600; cursor:pointer;">
                        <i class="fas fa-save me-1"></i>Update Category
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

    function setLoading(btn, label) {
        btn.disabled = true;
        btn.classList.add('btn-loading');
        btn.innerHTML = '<span class="btn-spinner"></span>' + label;
    }

    $('#addSubBtn').on('click', function () {
        document.getElementById('addSubForm').reset();
        var btn = document.getElementById('addSubSubmitBtn');
        btn.disabled = false; btn.classList.remove('btn-loading');
        btn.innerHTML = '<i class="fas fa-plus me-1"></i>Add Subcategory';
        new bootstrap.Modal(document.getElementById('addSubModal')).show();
    });

    document.getElementById('addSubForm').addEventListener('submit', function () {
        setLoading(document.getElementById('addSubSubmitBtn'), 'Adding...');
    });

    $(document).on('click', '.edit-sub-btn', function () {
        var btn = $(this);
        document.getElementById('editSubId').value = btn.data('id');
        document.getElementById('editSubName').value = btn.data('name');
        document.getElementById('editSubDesc').value = btn.data('description') || '';

        var submitBtn = document.getElementById('editSubSubmitBtn');
        submitBtn.disabled = false; submitBtn.classList.remove('btn-loading');
        submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Update Subcategory';
        new bootstrap.Modal(document.getElementById('editSubModal')).show();
    });

    document.getElementById('editSubForm').addEventListener('submit', function () {
        setLoading(document.getElementById('editSubSubmitBtn'), 'Updating...');
    });

    $(document).on('click', '.delete-sub-btn', function () {
        var btn = $(this);
        var name = btn.data('name');
        var count = btn.data('count');
        if (count > 0) {
            Swal.fire({ icon: 'warning', title: 'Cannot Delete', text: 'Subcategory "' + name + '" has ' + count + ' product(s). Remove or reassign them first.', confirmButtonColor: '#4A90E2' });
            return;
        }
        Swal.fire({
            title: 'Delete Subcategory?',
            text: 'Are you sure you want to delete "' + name + '"? This cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e03131',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel'
        }).then(function (result) {
            if (result.isConfirmed) {
                btn.closest('.delete-sub-form').submit();
            }
        });
    });

    $('#editCatBtn').on('click', function () {
        var submitBtn = document.getElementById('editCatSubmitBtn');
        submitBtn.disabled = false; submitBtn.classList.remove('btn-loading');
        submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Update Category';
        new bootstrap.Modal(document.getElementById('editCatModal')).show();
    });

    document.getElementById('editCatForm').addEventListener('submit', function () {
        setLoading(document.getElementById('editCatSubmitBtn'), 'Updating...');
    });

    $('#deleteCatBtn').on('click', function () {
        var name = $(this).data('name');
        var count = $(this).data('count');
        if (count > 0) {
            Swal.fire({ icon: 'warning', title: 'Cannot Delete', text: 'Category "' + name + '" has ' + count + ' product(s). Remove or reassign them first.', confirmButtonColor: '#4A90E2' });
            return;
        }
        Swal.fire({
            title: 'Delete Category?',
            text: 'Are you sure you want to delete "' + name + '"? This cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e03131',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel'
        }).then(function (result) {
            if (result.isConfirmed) {
                document.getElementById('deleteCatForm').submit();
            }
        });
    });

    @if($category->image)
    document.getElementById('delCatImgBtn').addEventListener('click', function () {
        Swal.fire({
            title: 'Delete Image?',
            text: 'This will permanently remove the category image.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e03131',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/categories/{{ $category->id }}/image',
                    type: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    success: function () {
                        var box = document.getElementById('editCatPreviewBox');
                        if (box) box.style.display = 'none';
                        Swal.fire({ icon: 'success', title: 'Deleted', text: 'Category image removed.', timer: 1500, showConfirmButton: false });
                    },
                    error: function () {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Could not delete the image.' });
                    }
                });
            }
        });
    });
    @endif
});
</script>
@endsection
