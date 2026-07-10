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

    @keyframes btn-spin {
        to { transform: rotate(360deg); }
    }
    .btn-spinner {
        display: inline-block;
        width: 13px; height: 13px;
        border: 2px solid rgba(255,255,255,0.35);
        border-top-color: #fff;
        border-radius: 50%;
        animation: btn-spin 0.7s linear infinite;
        vertical-align: middle;
        margin-right: 5px;
    }
    .btn-loading { opacity: 0.75; cursor: not-allowed !important; pointer-events: none; }

    /* Stats row */
    .stat-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.9rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .stat-icon {
        width: 42px; height: 42px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .stat-label { font-size: 0.72rem; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; }
    .stat-value { font-size: 1.5rem; font-weight: 700; color: var(--text); line-height: 1.1; margin-top: 2px; }

    /* Table card */
    .table-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        overflow: hidden;
    }
    .table-card-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .table-card-header h2 {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text);
        margin: 0;
    }

    /* Category image */
    .cat-img {
        width: 46px; height: 46px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid var(--border);
    }
    .cat-img-placeholder {
        width: 46px; height: 46px;
        border-radius: 8px;
        background: #f1f3f5;
        border: 1px solid var(--border);
        display: flex; align-items: center; justify-content: center;
    }

    /* Status badges */
    .badge-active {
        background: #ebfbee; color: #2f9e44;
        border: 1px solid #8ce99a;
        padding: 3px 10px; border-radius: 20px;
        font-size: 0.72rem; font-weight: 700;
    }
    .badge-inactive {
        background: #fff5f5; color: #c92a2a;
        border: 1px solid #ffa8a8;
        padding: 3px 10px; border-radius: 20px;
        font-size: 0.72rem; font-weight: 700;
    }

    /* Product count pill */
    .pill-count {
        display: inline-flex; align-items: center; gap: 4px;
        background: #e7f5ff; color: #1c7ed6;
        border: 1px solid #a5d8ff;
        padding: 3px 10px; border-radius: 20px;
        font-size: 0.72rem; font-weight: 700;
    }

    /* Table */
    table.cat-table { width: 100%; border-collapse: collapse; }
    table.cat-table thead th {
        background: #f8f9fa;
        color: var(--muted);
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.85rem 1rem;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }
    table.cat-table tbody td {
        padding: 0.9rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
        color: var(--text);
        font-size: 0.85rem;
    }
    table.cat-table tbody tr:last-child td { border-bottom: none; }
    table.cat-table tbody tr:hover { background: #f9fbfc; }

    /* Action buttons */
    .btn-edit {
        background: var(--blue); color: #fff;
        border: none; border-radius: 6px;
        padding: 5px 14px; font-size: 0.78rem; font-weight: 600;
        cursor: pointer; display: inline-flex; align-items: center; gap: 5px;
        transition: background 0.15s;
    }
    .btn-edit:hover { background: var(--blue-dark); }

    .btn-add-main {
        background: var(--blue); color: #fff;
        border: none; border-radius: 8px;
        padding: 0.5rem 1.1rem; font-size: 0.85rem; font-weight: 600;
        cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
        transition: background 0.15s;
    }
    .btn-add-main:hover { background: var(--blue-dark); }

    /* Modal */
    .modal-content { border-radius: 12px; border: 1px solid var(--border); box-shadow: 0 10px 30px rgba(0,0,0,0.12); }
    .modal-header { background: #f8f9fa; border-bottom: 1px solid var(--border); border-radius: 12px 12px 0 0; padding: 1rem 1.25rem; }
    .modal-footer { background: #f8f9fa; border-top: 1px solid var(--border); border-radius: 0 0 12px 12px; padding: 1rem 1.25rem; }
    .modal-title { font-size: 0.95rem; font-weight: 700; color: var(--text); }

    .form-label { font-size: 0.82rem; font-weight: 600; color: var(--text); margin-bottom: 5px; }
    .form-control, .form-select {
        border: 1px solid var(--border); border-radius: 8px;
        padding: 0.5rem 0.8rem; font-size: 0.85rem; color: var(--text);
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--blue); box-shadow: 0 0 0 3px rgba(74,144,226,0.12); outline: none;
    }
    .form-hint { font-size: 0.75rem; color: var(--muted); margin-top: 4px; }

    .img-preview-box {
        border: 2px dashed var(--border); border-radius: 8px;
        padding: 12px; display: none; margin-top: 8px;
        background: #f9fbfc; text-align: center;
    }
    .img-preview-box img { height: 80px; border-radius: 6px; object-fit: cover; }

    /* DataTables overrides */
    .dt-buttons { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 0.5rem; }
    .dt-button {
        background: var(--blue) !important; border: 1px solid var(--blue) !important;
        border-radius: 6px !important; padding: 0.4rem 0.85rem !important;
        font-weight: 600 !important; color: #fff !important;
        font-size: 0.78rem !important; box-shadow: none !important; margin: 0 !important;
    }
    .dt-button:hover { background: var(--blue-dark) !important; border-color: var(--blue-dark) !important; }
    div.dataTables_wrapper div.dataTables_filter input { border-radius: 7px; border: 1px solid var(--border); padding: 5px 10px; font-size: 0.82rem; }
    div.dataTables_wrapper div.dataTables_length select { border-radius: 7px !important; border: 1px solid var(--border) !important; font-size: 0.82rem !important; }
    div.dataTables_info, div.dataTables_paginate { font-size: 0.82rem; color: var(--muted); }
    .paginate_button { border-radius: 6px !important; }
    .paginate_button.current { background: var(--blue) !important; border-color: var(--blue) !important; color: #fff !important; }

    /* Table scroll wrapper */
    .cat-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }

    /* Tablet */
    @media (max-width: 900px) {
        .stat-card { padding: 0.75rem 1rem; }
        .stat-value { font-size: 1.2rem; }
        table.cat-table { min-width: 600px; }
    }

    /* Mobile card view */
    @media (max-width: 640px) {
        table.cat-table { min-width: 0; }
        table.cat-table thead { display: none; }
        table.cat-table tbody tr {
            display: block;
            border: 1px solid var(--border);
            border-radius: 10px;
            margin: 0 0 12px;
            padding: 12px;
            background: var(--white);
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }
        table.cat-table tbody tr:hover { background: var(--white); }
        table.cat-table tbody td {
            display: flex; justify-content: space-between; align-items: center;
            padding: 7px 4px; border: none;
            border-bottom: 1px solid #f3f4f6; font-size: 0.82rem;
        }
        table.cat-table tbody td:last-child { border-bottom: none; }
        table.cat-table tbody td::before {
            content: attr(data-label);
            font-weight: 700; color: var(--muted);
            font-size: 0.7rem; text-transform: uppercase;
            letter-spacing: 0.05em; flex-shrink: 0; margin-right: 8px;
        }
        table.cat-table tbody td:first-child { display: none; }
        table.cat-table tbody td[data-label="Actions"] {
            justify-content: flex-end; padding-top: 10px; border-bottom: none;
        }
        table.cat-table tbody td[data-label="Actions"]::before { display: none; }
        .table-card-header { padding: 0.9rem 0.9rem; }
        .table-card-header h2 { font-size: 0.88rem; }
        div.dataTables_wrapper div.dataTables_filter,
        div.dataTables_wrapper div.dataTables_length { text-align: left !important; }
        div.dataTables_wrapper div.dataTables_filter input { width: 100%; box-sizing: border-box; }
        div.dataTables_wrapper .row { gap: 8px; }
    }
</style>
@endsection

@section('content')
<div style="padding: 1.25rem;">

    {{-- Page Header --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem; flex-wrap:wrap; gap:0.75rem;">
        <div>
            <h1 style="font-size:1.2rem; font-weight:700; color:var(--text,#2d3436); margin:0;">
                <i class="fas fa-folder-open me-2" style="color:#4A90E2;"></i>Manage Categories
            </h1>
            <p style="font-size:0.82rem; color:#636e72; margin:3px 0 0;">Organize your product catalog by categories</p>
        </div>
        <button class="btn-add-main" id="addCategoryBtn">
            <i class="fas fa-plus"></i> Add New Category
        </button>
    </div>

    {{-- Stats Row --}}
    @php
        $total    = $category_list->count();
        $active   = $category_list->where('active_status', 1)->count();
        $inactive = $total - $active;
    @endphp
    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr)); gap:0.9rem; margin-bottom:1.25rem;">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e7f5ff;">
                <i class="fas fa-folder" style="color:#1c7ed6; font-size:1rem;"></i>
            </div>
            <div>
                <div class="stat-label">Total</div>
                <div class="stat-value">{{ $total }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#ebfbee;">
                <i class="fas fa-check-circle" style="color:#2f9e44; font-size:1rem;"></i>
            </div>
            <div>
                <div class="stat-label">Active</div>
                <div class="stat-value" style="color:#2f9e44;">{{ $active }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff5f5;">
                <i class="fas fa-times-circle" style="color:#c92a2a; font-size:1rem;"></i>
            </div>
            <div>
                <div class="stat-label">Inactive</div>
                <div class="stat-value" style="color:#c92a2a;">{{ $inactive }}</div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="table-card">
        <div class="table-card-header">
            <h2><i class="fas fa-list me-2" style="color:#4A90E2;"></i>All Categories</h2>
        </div>
        <div class="cat-table-wrap" style="padding:1rem 1.25rem 0.5rem;">
            <table class="cat-table" id="catTable">
                <thead>
                    <tr>
                        <th style="width:4%;">#</th>
                        <th style="width:7%;">Image</th>
                        <th style="width:20%;">Category Name</th>
                        <th style="width:30%;">Description</th>
                        <th style="width:12%;">Products</th>
                        <th style="width:10%;">Status</th>
                        <th style="width:17%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($category_list as $i => $cat)
                    <tr>
                        <td data-label="#" style="color:#94a3b8; font-weight:600;">{{ $i + 1 }}</td>
                        <td data-label="Image">
                            @if($cat->image)
                                <img src="{{ Storage::url($cat->image) }}" class="cat-img" alt="{{ $cat->category_name }}">
                            @else
                                <div class="cat-img-placeholder">
                                    <i class="fas fa-image" style="color:#cbd5e1; font-size:1rem;"></i>
                                </div>
                            @endif
                        </td>
                        <td data-label="Category">
                            <span style="font-weight:600; color:#2d3436;">{{ $cat->category_name }}</span>
                        </td>
                        <td data-label="Description" style="color:#636e72; max-width:220px;">
                            {{ $cat->category_description ? Str::limit($cat->category_description, 55) : '—' }}
                        </td>
                        <td data-label="Products">
                            <span class="pill-count">
                                <i class="fas fa-box" style="font-size:0.65rem;"></i>
                                {{ $cat->products_count }}
                            </span>
                        </td>
                        <td data-label="Status">
                            @if($cat->active_status == 1)
                                <span class="badge-active"><i class="fas fa-circle" style="font-size:0.5rem; vertical-align:middle; margin-right:4px;"></i>Active</span>
                            @else
                                <span class="badge-inactive"><i class="fas fa-circle" style="font-size:0.5rem; vertical-align:middle; margin-right:4px;"></i>Inactive</span>
                            @endif
                        </td>
                        <td data-label="Actions">
                            <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">
                                <button class="btn-edit edit-cat-btn"
                                    data-id="{{ $cat->id }}"
                                    data-name="{{ $cat->category_name }}"
                                    data-description="{{ $cat->category_description }}"
                                    data-status="{{ $cat->active_status }}"
                                    data-image="{{ $cat->image ? Storage::url($cat->image) : '' }}">
                                    <i class="fas fa-pen"></i> Edit
                                </button>
                                <form action="{{ route('category.delete', $cat->id) }}" method="POST" class="delete-cat-form" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-edit delete-cat-btn" style="background:#e03131;font-size:0.78rem;" data-name="{{ $cat->category_name }}" data-count="{{ $cat->products_count }}">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ── Add Category Modal ──────────────────────────────────────────── --}}
<div class="modal fade" id="addCatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2" style="color:#4A90E2;"></i>Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addCatForm" action="{{ route('saveNewCategory') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" style="display:flex; flex-direction:column; gap:1rem; padding:1.25rem;">
                    <div>
                        <label class="form-label">Category Name <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control" name="categoryName" id="addCatName"
                            placeholder="e.g. Solar Panels" maxlength="100" required>
                    </div>
                    <div>
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="categoryDiscription" id="addCatDesc"
                            rows="3" placeholder="Brief category description..." maxlength="500"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Category Image <span style="color:#94a3b8; font-weight:400;">(optional)</span></label>
                        <input type="file" class="form-control" name="image" accept="image/*" id="addCatImageInput">
                        <div class="img-preview-box" id="addCatPreviewBox">
                            <img id="addCatPreviewImg" src="" alt="Preview">
                            <p style="margin:6px 0 0; font-size:0.75rem; color:#636e72;">Preview</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="gap:8px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size:0.85rem;">Cancel</button>
                    <button type="submit" id="addCatSubmitBtn"
                        style="background:#4A90E2; color:#fff; border:none; border-radius:8px; padding:0.5rem 1.25rem; font-size:0.85rem; font-weight:600; cursor:pointer;">
                        <i class="fas fa-plus me-1"></i>Add Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── Edit Category Modal ─────────────────────────────────────────── --}}
<div class="modal fade" id="editCatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-pen me-2" style="color:#4A90E2;"></i>Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCatForm" method="POST" action="{{ route('updateCategory') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="category_id" id="editCatId">
                <div class="modal-body" style="display:flex; flex-direction:column; gap:1rem; padding:1.25rem;">
                    <div>
                        <label class="form-label">Category Name <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control" name="categoryName" id="editCatName"
                            maxlength="100" required>
                    </div>
                    <div>
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="categoryDiscription" id="editCatDesc"
                            rows="3" maxlength="500"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Status</label>
                        <select class="form-select" name="activeStatus" id="editCatStatus">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Category Image <span style="color:#94a3b8; font-weight:400;">(leave empty to keep existing)</span></label>
                        <input type="file" class="form-control" name="image" accept="image/*" id="editCatImageInput">
                        <div class="img-preview-box" id="editCatPreviewBox" style="display:none;">
                            <img id="editCatPreviewImg" src="" alt="Current Image">
                            <p style="margin:6px 0 0; font-size:0.75rem; color:#636e72;">Current image</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="gap:8px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size:0.85rem;">Cancel</button>
                    <button type="submit" id="editCatSubmitBtn"
                        style="background:#4A90E2; color:#fff; border:none; border-radius:8px; padding:0.5rem 1.25rem; font-size:0.85rem; font-weight:600; cursor:pointer;">
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

    // ── DataTable ──────────────────────────────────────────────────
    $('#catTable').DataTable({
        dom: '<"row mb-2"<"col-sm-6"l><"col-sm-6"f>>' +
             '<"row"<"col-12"tr>>' +
             '<"row mt-2"<"col-sm-6"i><"col-sm-6"p>>',
        pageLength: 25,
        language: {
            search: '', searchPlaceholder: 'Search categories...',
            lengthMenu: 'Show _MENU_ entries',
            info: 'Showing _START_–_END_ of _TOTAL_ categories',
            emptyTable: 'No categories found. Click "Add New Category" to get started.'
        },
        columnDefs: [{ orderable: false, targets: [1, 6] }]
    });

    // ── Loading helper ─────────────────────────────────────────────
    function setLoading(btn, label) {
        btn.disabled = true;
        btn.classList.add('btn-loading');
        btn.innerHTML = '<span class="btn-spinner"></span>' + label;
    }

    // ── Open Add modal ─────────────────────────────────────────────
    $('#addCategoryBtn').on('click', function () {
        document.getElementById('addCatForm').reset();
        document.getElementById('addCatPreviewBox').style.display = 'none';
        // Reset submit button state
        const btn = document.getElementById('addCatSubmitBtn');
        btn.disabled = false; btn.classList.remove('btn-loading');
        btn.innerHTML = '<i class="fas fa-plus me-1"></i>Add Category';
        new bootstrap.Modal(document.getElementById('addCatModal')).show();
    });

    // ── Add form submit loading ────────────────────────────────────
    document.getElementById('addCatForm').addEventListener('submit', function () {
        setLoading(document.getElementById('addCatSubmitBtn'), 'Adding...');
    });

    // ── Add image preview ──────────────────────────────────────────
    document.getElementById('addCatImageInput').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('addCatPreviewImg').src = e.target.result;
            document.getElementById('addCatPreviewBox').style.display = 'block';
        };
        reader.readAsDataURL(file);
    });

    // ── Open Edit modal ────────────────────────────────────────────
    $(document).on('click', '.edit-cat-btn', function () {
        const btn = $(this);
        document.getElementById('editCatId').value   = btn.data('id');
        document.getElementById('editCatName').value = btn.data('name');
        document.getElementById('editCatDesc').value = btn.data('description') || '';
        document.getElementById('editCatStatus').value = btn.data('status');

        const img = btn.data('image');
        const previewBox = document.getElementById('editCatPreviewBox');
        if (img) {
            document.getElementById('editCatPreviewImg').src = img;
            previewBox.style.display = 'block';
            document.querySelector('#editCatPreviewBox p').textContent = 'Current image';
        } else {
            previewBox.style.display = 'none';
        }

        // Reset submit button
        const submitBtn = document.getElementById('editCatSubmitBtn');
        submitBtn.disabled = false; submitBtn.classList.remove('btn-loading');
        submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Update Category';

        // Reset file input
        document.getElementById('editCatImageInput').value = '';

        new bootstrap.Modal(document.getElementById('editCatModal')).show();
    });

    // ── Edit form submit loading ───────────────────────────────────
    document.getElementById('editCatForm').addEventListener('submit', function () {
        setLoading(document.getElementById('editCatSubmitBtn'), 'Updating...');
    });

    // ── Delete category confirmation ─────────────────────────────
    $(document).on('click', '.delete-cat-btn', function () {
        var btn = $(this);
        var name = btn.data('name');
        var count = btn.data('count');
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
                btn.closest('.delete-cat-form').submit();
            }
        });
    });

    // ── Edit image preview (new file chosen) ──────────────────────
    document.getElementById('editCatImageInput').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('editCatPreviewImg').src = e.target.result;
            const previewBox = document.getElementById('editCatPreviewBox');
            previewBox.style.display = 'block';
            previewBox.querySelector('p').textContent = 'New image preview';
        };
        reader.readAsDataURL(file);
    });

});
</script>
@endsection
