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

    .btn-add-main {
        background: var(--blue); color: #fff;
        border: none; border-radius: 8px;
        padding: 0.5rem 1.1rem; font-size: 0.85rem; font-weight: 600;
        cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
        transition: background 0.15s;
    }
    .btn-add-main:hover { background: var(--blue-dark); }

    .cat-toolbar {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .cat-toolbar input {
        flex: 1; min-width: 160px;
        border: 1px solid var(--border); border-radius: 8px;
        padding: 7px 12px; font-size: 0.82rem; color: var(--text);
        outline: none;
    }
    .cat-toolbar input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(74,144,226,0.12); }
    .cat-toolbar select {
        border: 1px solid var(--border); border-radius: 8px;
        padding: 7px 10px; font-size: 0.82rem; color: var(--text);
        background: var(--white); cursor: pointer; outline: none;
    }
    .cat-toolbar select:focus { border-color: var(--blue); }

    .cat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 0.9rem;
    }

    .cat-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
        transition: box-shadow 0.15s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .cat-card { cursor: pointer; }
    .cat-card:hover { box-shadow: 0 4px 14px rgba(0,0,0,0.08); }

    .cat-card-img {
        width: 100%; height: 120px;
        object-fit: cover;
        display: block;
        background: #f1f3f5;
    }
    .cat-card-img-placeholder {
        width: 100%; height: 120px;
        background: #f1f3f5;
        display: flex; align-items: center; justify-content: center;
    }

    .cat-card-body { padding: 0.85rem; }

    .cat-card-name {
        font-size: 0.9rem; font-weight: 700; color: var(--text);
        margin: 0 0 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .cat-card-desc {
        font-size: 0.78rem; color: var(--muted);
        margin: 0 0 8px;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
        overflow: hidden; min-height: 2.4em;
    }

    .cat-card-meta {
        display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
    }

    .badge-active {
        background: #ebfbee; color: #2f9e44;
        border: 1px solid #8ce99a;
        padding: 2px 8px; border-radius: 20px;
        font-size: 0.7rem; font-weight: 700;
    }
    .badge-inactive {
        background: #fff5f5; color: #c92a2a;
        border: 1px solid #ffa8a8;
        padding: 2px 8px; border-radius: 20px;
        font-size: 0.7rem; font-weight: 700;
    }
    .pill-count {
        display: inline-flex; align-items: center; gap: 4px;
        background: #e7f5ff; color: #1c7ed6;
        border: 1px solid #a5d8ff;
        padding: 2px 8px; border-radius: 20px;
        font-size: 0.7rem; font-weight: 700;
    }


    .no-results {
        grid-column: 1 / -1;
        text-align: center; padding: 3rem 1rem;
        color: var(--muted); font-size: 0.9rem;
    }

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
    .img-preview-box {
        border: 2px dashed var(--border); border-radius: 8px;
        padding: 12px; display: none; margin-top: 8px;
        background: #f9fbfc; text-align: center;
    }
    .img-preview-box img { height: 80px; border-radius: 6px; object-fit: cover; }

    @media (max-width: 640px) {
        .cat-grid { grid-template-columns: 1fr; }
        .stat-card { padding: 0.75rem 1rem; }
        .stat-value { font-size: 1.2rem; }
    }
</style>
@endsection

@section('content')
<div style="padding: 1.25rem;">

    {{-- Page Header --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem; flex-wrap:wrap; gap:0.75rem;">
        <div>
            <h1 style="font-size:1.2rem; font-weight:700; color:var(--text); margin:0;">
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

    {{-- Toolbar --}}
    <div class="cat-toolbar">
        <input type="text" id="catSearch" placeholder="Search categories...">
        <select id="catSort">
            <option value="latest">Latest First</option>
            <option value="oldest">Oldest First</option>
            <option value="name_asc">Name A &rarr; Z</option>
            <option value="name_desc">Name Z &rarr; A</option>
            <option value="products_desc">Most Products</option>
            <option value="products_asc">Least Products</option>
        </select>
    </div>

    {{-- Category Cards --}}
    <div class="cat-grid" id="catGrid">
        @foreach($category_list as $cat)
        <div class="cat-card"
             data-name="{{ strtolower($cat->category_name) }}"
             data-desc="{{ strtolower($cat->category_description ?? '') }}"
             data-id="{{ $cat->id }}"
             data-products="{{ $cat->products_count }}">
            @if($cat->image)
                <img src="{{ Storage::url($cat->image) }}" class="cat-card-img" alt="{{ $cat->category_name }}">
            @else
                <div class="cat-card-img-placeholder">
                    <i class="fas fa-image" style="color:#cbd5e1; font-size:2rem;"></i>
                </div>
            @endif
            <div class="cat-card-body">
                <p class="cat-card-name">{{ $cat->category_name }}</p>
                <p class="cat-card-desc">{{ $cat->category_description ?: 'No description' }}</p>
                <div class="cat-card-meta">
                    @if($cat->active_status == 1)
                        <span class="badge-active"><i class="fas fa-circle" style="font-size:0.45rem; vertical-align:middle; margin-right:3px;"></i>Active</span>
                    @else
                        <span class="badge-inactive"><i class="fas fa-circle" style="font-size:0.45rem; vertical-align:middle; margin-right:3px;"></i>Inactive</span>
                    @endif
                    <span class="pill-count">
                        <i class="fas fa-box" style="font-size:0.6rem;"></i>
                        {{ $cat->products_count }} product{{ $cat->products_count != 1 ? 's' : '' }}
                    </span>
                    <span class="pill-count" style="background:#f3f0ff; color:#7048e8; border-color:#d0bfff;">
                        <i class="fas fa-layer-group" style="font-size:0.6rem;"></i>
                        {{ $cat->sub_categories_count }} subcategor{{ $cat->sub_categories_count != 1 ? 'ies' : 'y' }}
                    </span>
                </div>
            </div>
        </div>
        @endforeach
        <div class="no-results" id="noResults" style="display:none;">
            <i class="fas fa-search" style="font-size:1.5rem; margin-bottom:8px; display:block;"></i>
            No categories match your search.
        </div>
    </div>

    @if($category_list->isEmpty())
    <div style="text-align:center; padding:3rem 1rem; color:var(--muted);">
        <i class="fas fa-folder-open" style="font-size:2rem; margin-bottom:8px; display:block;"></i>
        <p>No categories yet. Click "Add New Category" to get started.</p>
    </div>
    @endif
</div>

{{-- Add Category Modal --}}
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

@endsection

@section('js')
<script>
$(document).ready(function () {

    var grid = document.getElementById('catGrid');
    var cards = Array.from(grid.querySelectorAll('.cat-card'));
    var noResults = document.getElementById('noResults');

    function filterAndSort() {
        var query = document.getElementById('catSearch').value.toLowerCase();
        var sort = document.getElementById('catSort').value;

        var visible = cards.filter(function(card) {
            var name = card.getAttribute('data-name');
            var desc = card.getAttribute('data-desc');
            var match = name.indexOf(query) > -1 || desc.indexOf(query) > -1;
            card.style.display = match ? '' : 'none';
            return match;
        });

        visible.sort(function(a, b) {
            switch (sort) {
                case 'latest':
                    return parseInt(b.getAttribute('data-id')) - parseInt(a.getAttribute('data-id'));
                case 'oldest':
                    return parseInt(a.getAttribute('data-id')) - parseInt(b.getAttribute('data-id'));
                case 'name_asc':
                    return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                case 'name_desc':
                    return b.getAttribute('data-name').localeCompare(a.getAttribute('data-name'));
                case 'products_desc':
                    return parseInt(b.getAttribute('data-products')) - parseInt(a.getAttribute('data-products'));
                case 'products_asc':
                    return parseInt(a.getAttribute('data-products')) - parseInt(b.getAttribute('data-products'));
                default:
                    return 0;
            }
        });

        visible.forEach(function(card) { grid.appendChild(card); });
        grid.appendChild(noResults);
        noResults.style.display = visible.length === 0 ? '' : 'none';
    }

    document.getElementById('catSearch').addEventListener('input', filterAndSort);
    document.getElementById('catSort').addEventListener('change', filterAndSort);

    $(document).on('click', '.cat-card', function() {
        var id = $(this).data('id');
        window.location.href = '/admin/category/' + id;
    });

    function setLoading(btn, label) {
        btn.disabled = true;
        btn.classList.add('btn-loading');
        btn.innerHTML = '<span class="btn-spinner"></span>' + label;
    }

    $('#addCategoryBtn').on('click', function () {
        document.getElementById('addCatForm').reset();
        document.getElementById('addCatPreviewBox').style.display = 'none';
        var btn = document.getElementById('addCatSubmitBtn');
        btn.disabled = false; btn.classList.remove('btn-loading');
        btn.innerHTML = '<i class="fas fa-plus me-1"></i>Add Category';
        new bootstrap.Modal(document.getElementById('addCatModal')).show();
    });

    document.getElementById('addCatForm').addEventListener('submit', function () {
        setLoading(document.getElementById('addCatSubmitBtn'), 'Adding...');
    });

    document.getElementById('addCatImageInput').addEventListener('change', function () {
        var file = this.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('addCatPreviewImg').src = e.target.result;
            document.getElementById('addCatPreviewBox').style.display = 'block';
        };
        reader.readAsDataURL(file);
    });


});
</script>
@endsection
