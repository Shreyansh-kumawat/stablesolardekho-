@extends('layouts.adminLayout')

@section('css')
<style>
    @keyframes btn-spin {
        to { transform: rotate(360deg); }
    }
    .btn-spinner {
        display: inline-block;
        width: 13px;
        height: 13px;
        border: 2px solid rgba(255,255,255,0.35);
        border-top-color: #fff;
        border-radius: 50%;
        animation: btn-spin 0.7s linear infinite;
        vertical-align: middle;
        margin-right: 5px;
    }
    .btn-loading {
        opacity: 0.75;
        cursor: not-allowed !important;
        pointer-events: none;
    }
</style>
@endsection

@section('content')
<div class="p-6">
    <h2 style="font-size:1.4rem; font-weight:700; color:#2d3436; margin-bottom:1.5rem;">Manage Banners</h2>

    {{-- Add New Banner --}}
    <div style="background:#fff; border:1px solid #e1e8ed; border-radius:12px; padding:1.5rem; margin-bottom:2rem;">
        <h3 style="font-size:1rem; font-weight:600; color:#2d3436; margin-bottom:1rem;">Add New Banner</h3>
        <form id="add-banner-form" action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px,1fr)); gap:1rem;">
                <div>
                    <label style="display:block; font-size:0.8rem; color:#636e72; margin-bottom:4px;">Title</label>
                    <input type="text" name="title" placeholder="Banner title"
                        style="width:100%; border:1px solid #e1e8ed; border-radius:8px; padding:8px 12px; font-size:0.85rem; color:#2d3436; outline:none;">
                </div>
                <div>
                    <label style="display:block; font-size:0.8rem; color:#636e72; margin-bottom:4px;">Subtitle</label>
                    <input type="text" name="subtitle" placeholder="Banner subtitle"
                        style="width:100%; border:1px solid #e1e8ed; border-radius:8px; padding:8px 12px; font-size:0.85rem; color:#2d3436; outline:none;">
                </div>
                <div>
                    <label style="display:block; font-size:0.8rem; color:#636e72; margin-bottom:4px;">Button Text</label>
                    <input type="text" name="button_text" placeholder="e.g. Shop Now"
                        style="width:100%; border:1px solid #e1e8ed; border-radius:8px; padding:8px 12px; font-size:0.85rem; color:#2d3436; outline:none;">
                </div>
                <div>
                    <label style="display:block; font-size:0.8rem; color:#636e72; margin-bottom:4px;">Button Link</label>
                    <select name="button_link"
                        style="width:100%; border:1px solid #e1e8ed; border-radius:8px; padding:8px 12px; font-size:0.85rem; color:#2d3436; outline:none; background:#fff;">
                        <option value="">-- No Link --</option>
                        <option value="/shop">🛒 Shop (All Products)</option>
                        @foreach($categories as $cat)
                            <option value="/shop?category={{ $cat->id }}">📂 {{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:0.8rem; color:#636e72; margin-bottom:4px;">Position</label>
                    <select name="sort_order"
                        style="width:100%; border:1px solid #e1e8ed; border-radius:8px; padding:8px 12px; font-size:0.85rem; color:#2d3436; outline:none; background:#fff;">
                        @for($i = 1; $i <= max($banners->count() + 1, 6); $i++)
                            <option value="{{ $i }}">{{ $i }}{{ $i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th')) }} Banner</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:0.8rem; color:#636e72; margin-bottom:4px;">Image <span style="color:red;">*</span></label>
                    <input type="file" name="image" accept="image/*" required
                        style="font-size:0.82rem; color:#2d3436;">
                </div>
            </div>
            <div style="margin-top:1rem;">
                <button type="submit" id="add-banner-btn"
                    style="background:#4A90E2; color:#fff; font-weight:600; padding:8px 24px; border:none; border-radius:8px; cursor:pointer; font-size:0.85rem;">
                    Add Banner
                </button>
            </div>
        </form>
    </div>

    {{-- Banner List --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px,1fr)); gap:1.2rem;">
        @forelse($banners as $banner)
        <div style="background:#fff; border:1px solid #e1e8ed; border-radius:12px; overflow:hidden;">
            <div style="position:relative;">
                <img src="{{ Storage::url($banner->image) }}" alt="{{ $banner->title }}"
                    style="width:100%; height:170px; object-fit:cover; display:block;">
                <span style="position:absolute; top:10px; right:10px; padding:3px 10px; border-radius:20px; font-size:0.72rem; font-weight:700; color:#fff; background:{{ $banner->is_active ? '#27ae60' : '#e74c3c' }};">
                    {{ $banner->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div style="padding:1rem;">
                <p style="font-weight:600; color:#2d3436; font-size:0.9rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $banner->title ?? 'No Title' }}</p>
                <p style="color:#636e72; font-size:0.8rem; margin-top:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $banner->subtitle ?? '-' }}</p>
                @php $pos = $loop->iteration; @endphp
                <p style="color:#4A90E2; font-size:0.75rem; margin-top:4px; font-weight:600;">Position: {{ $banner->sort_order }}{{ $banner->sort_order == 1 ? 'st' : ($banner->sort_order == 2 ? 'nd' : ($banner->sort_order == 3 ? 'rd' : 'th')) }} Banner</p>

                <div style="display:flex; gap:8px; margin-top:12px; flex-wrap:wrap;">
                    <form class="toggle-form" action="{{ route('admin.banners.toggle', $banner) }}" method="POST">
                        @csrf
                        <button type="submit" class="toggle-btn"
                            style="background:{{ $banner->is_active ? '#f39c12' : '#27ae60' }}; color:#fff; border:none; padding:5px 12px; border-radius:6px; font-size:0.75rem; font-weight:600; cursor:pointer;">
                            {{ $banner->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>

                    <button onclick="openEdit({{ $banner->id }}, '{{ addslashes($banner->title) }}', '{{ addslashes($banner->subtitle) }}', '{{ addslashes($banner->button_text) }}', '{{ addslashes($banner->button_link) }}', {{ $banner->sort_order }})"
                        style="background:#4A90E2; color:#fff; border:none; padding:5px 12px; border-radius:6px; font-size:0.75rem; font-weight:600; cursor:pointer;">
                        Edit
                    </button>

                    <form class="delete-form" action="{{ route('admin.banners.destroy', $banner) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn"
                            style="background:#e74c3c; color:#fff; border:none; padding:5px 12px; border-radius:6px; font-size:0.75rem; font-weight:600; cursor:pointer;">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column:1/-1; text-align:center; color:#636e72; padding:3rem 0;">No banners added yet.</div>
        @endforelse
    </div>
</div>

{{-- Edit Modal --}}
<div id="edit-modal" style="display:none; position:fixed; inset:0; z-index:1000; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:14px; padding:1.5rem; width:100%; max-width:480px; margin:1rem;">
        <h3 style="font-weight:700; color:#2d3436; font-size:1rem; margin-bottom:1rem;">Edit Banner</h3>
        <form id="edit-form" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display:flex; flex-direction:column; gap:0.8rem;">
                <div>
                    <label style="font-size:0.8rem; color:#636e72;">Title</label>
                    <input type="text" name="title" id="edit-title"
                        style="width:100%; border:1px solid #e1e8ed; border-radius:8px; padding:8px 12px; font-size:0.85rem; color:#2d3436; margin-top:4px; box-sizing:border-box;">
                </div>
                <div>
                    <label style="font-size:0.8rem; color:#636e72;">Subtitle</label>
                    <input type="text" name="subtitle" id="edit-subtitle"
                        style="width:100%; border:1px solid #e1e8ed; border-radius:8px; padding:8px 12px; font-size:0.85rem; color:#2d3436; margin-top:4px; box-sizing:border-box;">
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.8rem;">
                    <div>
                        <label style="font-size:0.8rem; color:#636e72;">Button Text</label>
                        <input type="text" name="button_text" id="edit-btn-text"
                            style="width:100%; border:1px solid #e1e8ed; border-radius:8px; padding:8px 12px; font-size:0.85rem; color:#2d3436; margin-top:4px; box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="font-size:0.8rem; color:#636e72;">Button Link</label>
                        <select name="button_link" id="edit-btn-link"
                            style="width:100%; border:1px solid #e1e8ed; border-radius:8px; padding:8px 12px; font-size:0.85rem; color:#2d3436; margin-top:4px; box-sizing:border-box; background:#fff;">
                            <option value="">-- No Link --</option>
                            <option value="/shop">🛒 Shop (All Products)</option>
                            @foreach($categories as $cat)
                                <option value="/shop?category={{ $cat->id }}">📂 {{ $cat->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label style="font-size:0.8rem; color:#636e72;">Position</label>
                    <select name="sort_order" id="edit-sort"
                        style="width:100%; border:1px solid #e1e8ed; border-radius:8px; padding:8px 12px; font-size:0.85rem; color:#2d3436; margin-top:4px; box-sizing:border-box; background:#fff;">
                        @for($i = 1; $i <= max($banners->count() + 1, 6); $i++)
                            <option value="{{ $i }}">{{ $i }}{{ $i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th')) }} Banner</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label style="font-size:0.8rem; color:#636e72;">New Image (optional)</label>
                    <input type="file" name="image" accept="image/*" style="margin-top:4px; font-size:0.82rem;">
                </div>
            </div>
            <div style="display:flex; gap:10px; margin-top:1.2rem;">
                <button type="submit" id="edit-submit-btn"
                    style="flex:1; background:#4A90E2; color:#fff; border:none; padding:9px; border-radius:8px; font-weight:600; cursor:pointer; font-size:0.85rem;">
                    Update
                </button>
                <button type="button" onclick="closeEdit()"
                    style="flex:1; background:#f5f7fa; color:#2d3436; border:1px solid #e1e8ed; padding:9px; border-radius:8px; font-weight:600; cursor:pointer; font-size:0.85rem;">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    // ── Loading helper ──────────────────────────────────────────────
    function setLoading(btn, text) {
        btn.disabled = true;
        btn.classList.add('btn-loading');
        btn.innerHTML = '<span class="btn-spinner"></span>' + text;
    }

    // ── Add Banner form ─────────────────────────────────────────────
    document.getElementById('add-banner-form').addEventListener('submit', function () {
        setLoading(document.getElementById('add-banner-btn'), 'Adding...');
    });

    // ── Edit Banner form ────────────────────────────────────────────
    document.getElementById('edit-form').addEventListener('submit', function () {
        setLoading(document.getElementById('edit-submit-btn'), 'Updating...');
    });

    // ── Toggle (Activate/Deactivate) forms ─────────────────────────
    document.querySelectorAll('.toggle-form').forEach(function (form) {
        form.addEventListener('submit', function () {
            const btn = this.querySelector('.toggle-btn');
            setLoading(btn, btn.textContent.trim() === 'Activate' ? 'Activating...' : 'Deactivating...');
        });
    });

    // ── Delete forms ────────────────────────────────────────────────
    document.querySelectorAll('.delete-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (!confirm('Delete this banner? This cannot be undone.')) return;
            const btn = this.querySelector('.delete-btn');
            setLoading(btn, 'Deleting...');
            this.submit();
        });
    });

    // ── Edit modal open/close ───────────────────────────────────────
    function openEdit(id, title, subtitle, btnText, btnLink, sortOrder) {
        // Reset button state in case modal was previously submitted
        const submitBtn = document.getElementById('edit-submit-btn');
        submitBtn.disabled = false;
        submitBtn.classList.remove('btn-loading');
        submitBtn.innerHTML = 'Update';

        document.getElementById('edit-form').action = '/admin/banners/' + id;
        document.getElementById('edit-title').value = title;
        document.getElementById('edit-subtitle').value = subtitle;
        document.getElementById('edit-btn-text').value = btnText;

        const linkSelect = document.getElementById('edit-btn-link');
        linkSelect.value = btnLink;
        if (linkSelect.value !== btnLink) linkSelect.value = '';

        document.getElementById('edit-sort').value = sortOrder;
        document.getElementById('edit-modal').style.display = 'flex';
    }

    function closeEdit() {
        document.getElementById('edit-modal').style.display = 'none';
    }
</script>
@endsection
