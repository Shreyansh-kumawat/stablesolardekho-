@extends('layouts.adminLayout')

@section('css')
<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
<style>
    :root { --blue: #4A90E2; --light: #f5f7fa; --txt: #2d3436; --txt2: #636e72; --bdr: #e1e8ed; --green: #27ae60; }
    body { background: var(--light); }
    .pg-head { background: #fff; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; border-radius: 8px; border: 1px solid var(--bdr); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .5rem; }
    .pg-head h1 { font-size: 1.2rem; font-weight: 700; margin: 0; color: var(--txt); }
    .pg-head p { color: var(--txt2); font-size: .82rem; margin: .2rem 0 0; }
    .sec-card { background: #fff; border: 1px solid var(--bdr); border-radius: 10px; padding: 1.25rem 1.5rem; margin-bottom: 1.25rem; }
    .sec-label { font-size: .82rem; font-weight: 700; color: var(--blue); text-transform: uppercase; letter-spacing: .06em; margin: 0 0 .75rem; padding-bottom: .5rem; border-bottom: 1px solid #eef1f5; }
    .form-label { font-weight: 600; color: var(--txt); font-size: .82rem; margin-bottom: .35rem; }
    .form-control, .form-select { border-radius: 6px; border: 1px solid var(--bdr); padding: .5rem .75rem; font-size: .88rem; }
    .form-control:focus, .form-select:focus { box-shadow: 0 0 0 .2rem rgba(74,144,226,.15); border-color: var(--blue); }
    .req { color: #e74c3c; }
    .btn-blue { background: var(--blue); border: none; color: #fff; padding: .6rem 1.5rem; border-radius: 8px; font-weight: 700; font-size: .9rem; cursor: pointer; }
    .btn-blue:hover { background: #3b7dc4; color: #fff; }
    .btn-green { background: var(--green); border: none; color: #fff; padding: .6rem 1.5rem; border-radius: 8px; font-weight: 700; font-size: .9rem; cursor: pointer; }
    .btn-green:hover { background: #219a52; color: #fff; }
    .btn-outline { background: #fff; border: 1px solid var(--bdr); color: var(--txt); padding: .6rem 1.5rem; border-radius: 8px; font-weight: 600; font-size: .9rem; cursor: pointer; text-decoration: none; }
    .btn-outline:hover { background: var(--light); }
    .upload-area { border: 2px dashed var(--bdr); border-radius: 8px; padding: 1.25rem; text-align: center; cursor: pointer; transition: border-color .15s; position: relative; }
    .upload-area:hover { border-color: var(--blue); }
    .upload-area input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
    .upload-area svg { margin-bottom: .35rem; }
    .upload-area p { margin: 0; font-size: .8rem; color: var(--txt2); }
    .custom-spec-row { display: flex; gap: .5rem; align-items: center; margin-bottom: .5rem; }
    .custom-spec-row input { flex: 1; }
    .custom-spec-row button { flex-shrink: 0; background: none; border: none; color: #e74c3c; cursor: pointer; padding: .25rem; }
    .preview-strip { display: flex; gap: .5rem; flex-wrap: wrap; margin-top: .5rem; }
    .preview-strip img { width: 56px; height: 56px; object-fit: cover; border-radius: 6px; border: 1px solid var(--bdr); }
    .mode-toggle { display: flex; gap: 0; border: 1px solid var(--bdr); border-radius: 8px; overflow: hidden; }
    .mode-btn { padding: .55rem 1.25rem; font-size: .85rem; font-weight: 600; cursor: pointer; border: none; background: #fff; color: var(--txt2); transition: all .15s; }
    .mode-btn.active { background: var(--blue); color: #fff; }
    .mode-btn:hover:not(.active) { background: var(--light); }
    .product-search-wrap { position: relative; }
    .product-search-wrap select { font-size: .9rem; }
    @media(max-width:768px) { .sec-card { padding: 1rem; } .pg-head { flex-direction: column; align-items: flex-start; } }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="pg-head">
        <div>
            <h1 id="pageTitle">{{ isset($editProduct) ? 'Edit Product' : 'Add Stock' }}</h1>
            <p id="pageDesc">{{ isset($editProduct) ? 'Update product details and stock' : 'Add a new product or update existing product stock' }}</p>
        </div>
        <div style="display:flex;gap:.5rem;align-items:center;">
            <a href="{{ route('inventoryEntries') }}" class="btn-outline">View Entries</a>
            <a href="{{ route('manageInventory') }}" class="btn-outline">Inventory List</a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:8px;font-size:.88rem;">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:8px;font-size:.88rem;">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Mode selector --}}
    <div class="sec-card">
        <p class="sec-label">Select Product</p>
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <div class="mode-toggle">
                    <button type="button" class="mode-btn {{ !isset($editProduct) ? 'active' : '' }}" id="modeNew" onclick="setMode('new')">New Product</button>
                    <button type="button" class="mode-btn {{ isset($editProduct) ? 'active' : '' }}" id="modeExisting" onclick="setMode('existing')">Existing Product</button>
                </div>
            </div>
            <div class="col-md-6" id="productSelectorWrap" style="{{ !isset($editProduct) ? 'display:none;' : '' }}">
                <label class="form-label">Choose Product</label>
                <select class="form-select" id="productSelector">
                    <option value="">-- Select a product --</option>
                    @foreach($products as $p)
                    <option value="{{ $p->id }}" {{ (isset($editProduct) && $editProduct->id == $p->id) ? 'selected' : '' }}>
                        {{ $p->item_name }} ({{ $p->item_code ?? 'N/A' }}) — {{ $p->category->category_name ?? '' }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3" id="loadBtnWrap" style="{{ !isset($editProduct) ? 'display:none;' : '' }}">
                <button type="button" class="btn-blue" onclick="loadProduct()" id="loadBtn">Load Product</button>
            </div>
        </div>
    </div>

    <form action="{{ isset($editProduct) ? route('inventoryUpdateProduct', $editProduct->id) : route('inventoryStoreProduct') }}" method="POST" enctype="multipart/form-data" id="addProductForm">
        @csrf
        @if(isset($editProduct))
        @method('PUT')
        @endif

        {{-- Classification --}}
        <div class="sec-card">
            <p class="sec-label">Classification</p>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Category <span class="req">*</span></label>
                    <select name="category_id" id="categorySelect" class="form-select" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (isset($editProduct) && $editProduct->category_id == $cat->id) ? 'selected' : '' }}>{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Sub Category</label>
                    <select name="sub_category_id" id="subCategorySelect" class="form-select">
                        <option value="">Select Sub Category</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Product Info --}}
        <div class="sec-card">
            <p class="sec-label">Product Information</p>
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Product Name <span class="req">*</span></label>
                    <input type="text" class="form-control" name="product_name" id="fProductName" required placeholder="e.g. Solar Panel 400W" maxlength="100" value="{{ $editProduct->item_name ?? old('product_name') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Product Code <span class="req">*</span></label>
                    <input type="text" class="form-control" name="item_code" id="fItemCode" required placeholder="e.g. SOL-001" maxlength="50" value="{{ $editProduct->item_code ?? old('item_code') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sale Price (&#8377;)</label>
                    <input type="text" class="form-control" name="current_sale_price" id="fSalePrice" placeholder="e.g. 20000" value="{{ $editProduct->current_sale_price ?? old('current_sale_price') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Quantity <span class="req">*</span></label>
                    <input type="number" min="0" class="form-control" name="quantity" id="fQuantity" placeholder="0" value="{{ isset($editProduct) ? ($editProduct->inventory->available_qty ?? $editProduct->quantity ?? 0) : old('quantity', 0) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">UOM <span class="req">*</span></label>
                    <select name="uom" id="fUom" class="form-select" required>
                        <option value="">Select</option>
                        @foreach(['Piece','Kilogram','Liter','Meter','Box','Pack','Watt','KW','Set'] as $u)
                        <option value="{{ $u }}" {{ (isset($editProduct) && $editProduct->uom == $u) ? 'selected' : (old('uom')==$u?'selected':'') }}>{{ $u }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Featured</label>
                    <div class="form-check form-switch mt-1">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="fFeatured" {{ (isset($editProduct) && $editProduct->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="fFeatured" style="font-size:.85rem;">Mark as Featured</label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" id="fDescription" rows="2" placeholder="Brief product description (optional)">{{ $editProduct->description ?? old('description') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Inventory Details --}}
        <div class="sec-card">
            <p class="sec-label">Inventory Details</p>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Supplier</label>
                    <select name="supplier_id" id="fSupplier" class="form-select">
                        <option value="">Select Supplier (optional)</option>
                        @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}">{{ $sup->cp_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Unit Price (Purchase)</label>
                    <input type="number" step="0.01" min="0" class="form-control" name="unit_price" id="fUnitPrice" placeholder="Purchase price per unit" value="{{ old('unit_price') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Invoice Number</label>
                    <input type="text" class="form-control" name="invoice_number" id="fInvoiceNumber" placeholder="Invoice #" value="{{ old('invoice_number') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Invoice Date</label>
                    <input type="date" class="form-control" name="invoice_date" id="fInvoiceDate" value="{{ old('invoice_date') }}">
                </div>
            </div>
        </div>

        {{-- Specifications --}}
        <div class="sec-card">
            <p class="sec-label">Specifications <small style="color:var(--txt2);text-transform:none;letter-spacing:0;font-weight:400;">(all optional)</small></p>
            <div class="row g-2 mb-2">
                <div class="col-md-4"><label class="form-label mb-0">Type</label><input type="text" class="form-control" name="type" id="fType" placeholder="e.g. Solar Panel" value="{{ $editProduct->type ?? '' }}"></div>
                <div class="col-md-4"><label class="form-label mb-0">Brand</label><input type="text" class="form-control" name="brand" id="fBrand" placeholder="e.g. Waaree, Adani" value="{{ $editProduct->brand ?? '' }}"></div>
                <div class="col-md-4"><label class="form-label mb-0">Model</label><input type="text" class="form-control" name="product_model" id="fModel" placeholder="e.g. WS-545" value="{{ $editProduct->model ?? '' }}"></div>
            </div>
            <div class="row g-2 mb-2">
                <div class="col-md-4"><label class="form-label mb-0">Operating Voltage</label><input type="text" class="form-control" name="operating_voltage" id="fOpVolt" placeholder="e.g. 24V / 48V" value="{{ $editProduct->operating_voltage ?? '' }}"></div>
                <div class="col-md-4"><label class="form-label mb-0">Solar Panel Type</label><input type="text" class="form-control" name="solar_panel_type" id="fPanelType" placeholder="e.g. Mono PERC" value="{{ $editProduct->solar_panel_type ?? '' }}"></div>
                <div class="col-md-4"><label class="form-label mb-0">MNRE Approved</label><input type="text" class="form-control" name="mnre_approved" id="fMnre" placeholder="e.g. Yes / No" value="{{ $editProduct->mnre_approved ?? '' }}"></div>
            </div>
            <div class="row g-2 mb-2">
                <div class="col-md-6"><label class="form-label mb-0">Certifications</label><input type="text" class="form-control" name="certifications" id="fCerts" placeholder="e.g. BIS, IEC 61215" value="{{ $editProduct->certifications ?? '' }}"></div>
                <div class="col-md-6"><label class="form-label mb-0">Manufacturer Warranty</label><input type="text" class="form-control" name="manufacturer_warranty" id="fWarranty" placeholder="e.g. 25 Years" value="{{ $editProduct->manufacturer_warranty ?? '' }}"></div>
            </div>
            <div class="row g-2 mb-2">
                <div class="col-md-4"><label class="form-label mb-0">Number of Cells</label><input type="text" class="form-control" name="number_of_cells" id="fCells" placeholder="e.g. 144" value="{{ $editProduct->number_of_cells ?? '' }}"></div>
                <div class="col-md-4"><label class="form-label mb-0">Encapsulate</label><input type="text" class="form-control" name="encapsulate" id="fEncap" placeholder="e.g. EVA" value="{{ $editProduct->encapsulate ?? '' }}"></div>
                <div class="col-md-4"><label class="form-label mb-0">Country of Origin</label><input type="text" class="form-control" name="country_of_origin" id="fCountry" placeholder="e.g. India" value="{{ $editProduct->country_of_origin ?? '' }}"></div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-md-6"><label class="form-label mb-0">Input Voltage</label><input type="text" class="form-control" name="input_voltage" id="fInputVolt" placeholder="e.g. 12V - 48V" value="{{ $editProduct->input_voltage ?? '' }}"></div>
                <div class="col-md-6"><label class="form-label mb-0">Max Supported Panel Power</label><input type="text" class="form-control" name="max_supported_panel_power" id="fMaxPanel" placeholder="e.g. 6000W" value="{{ $editProduct->max_supported_panel_power ?? '' }}"></div>
            </div>

            {{-- Custom Specifications --}}
            <div style="border-top:1px solid #eef1f5;padding-top:.75rem;margin-top:.5rem;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.5rem;">
                    <label class="form-label mb-0" style="color:var(--blue);">Custom Specifications</label>
                    <button type="button" onclick="addCustomSpec()" style="background:var(--blue);color:#fff;border:none;border-radius:6px;padding:.3rem .75rem;font-size:.78rem;font-weight:600;cursor:pointer;">+ Add Spec</button>
                </div>
                <div id="customSpecsContainer">
                    @if(isset($editProduct) && $editProduct->customSpecs->count())
                        @foreach($editProduct->customSpecs as $spec)
                        <div class="custom-spec-row">
                            <input type="text" class="form-control" name="custom_spec_names[]" placeholder="Spec name" value="{{ $spec->spec_name }}">
                            <input type="text" class="form-control" name="custom_spec_values[]" placeholder="Spec value" value="{{ $spec->spec_value }}">
                            <button type="button" onclick="this.parentElement.remove()" title="Remove">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        {{-- Photos --}}
        <div class="sec-card">
            <p class="sec-label">Photos</p>
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Main Photo</label>
                    <div class="upload-area" id="mainUpload">
                        <input type="file" name="image" accept="image/*" id="mainImageInput">
                        <svg width="24" height="24" fill="none" stroke="var(--txt2)" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p><strong>Click to upload</strong> main photo</p>
                    </div>
                    <div class="preview-strip" id="mainPreview">
                        @if(isset($editProduct) && $editProduct->image)
                        <img src="/serve/{{ $editProduct->image }}" alt="Current">
                        @endif
                    </div>
                </div>
                <div class="col-md-7">
                    <label class="form-label">Gallery Photos <small style="color:var(--txt2);">(up to 8)</small></label>
                    <div class="upload-area" id="galleryUpload">
                        <input type="file" name="product_images[]" accept="image/*" multiple id="galleryInput">
                        <svg width="24" height="24" fill="none" stroke="var(--txt2)" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p><strong>Click to upload</strong> gallery images</p>
                    </div>
                    <div class="preview-strip" id="galleryPreview"></div>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div style="display:flex;gap:.75rem;margin-bottom:2rem;">
            <button type="submit" class="{{ isset($editProduct) ? 'btn-green' : 'btn-blue' }}" id="submitBtn">
                {{ isset($editProduct) ? 'Update Product' : 'Add Product to Inventory' }}
            </button>
            <button type="button" class="btn-outline" onclick="resetForm()">Clear Form</button>
        </div>
    </form>
</div>
@endsection

@section('js')
<script src="/assets/js/jquery.dataTables.min.js"></script>
<script>
const subCats = @json($categories->mapWithKeys(fn($c) => [$c->id => $c->subCategories->map(fn($s) => ['id'=>$s->id,'name'=>$s->sub_category_name])]));
let currentMode = '{{ isset($editProduct) ? "existing" : "new" }}';
let editId = {{ isset($editProduct) ? $editProduct->id : 'null' }};

@if(isset($editProduct) && $editProduct->sub_category_id)
(function() {
    const sel = document.getElementById('subCategorySelect');
    const subs = subCats[{{ $editProduct->category_id }}] || [];
    subs.forEach(s => {
        const opt = document.createElement('option');
        opt.value = s.id;
        opt.textContent = s.name;
        if (s.id == {{ $editProduct->sub_category_id ?? 'null' }}) opt.selected = true;
        sel.appendChild(opt);
    });
})();
@endif

document.getElementById('categorySelect').addEventListener('change', function() {
    const sel = document.getElementById('subCategorySelect');
    sel.innerHTML = '<option value="">Select Sub Category</option>';
    const subs = subCats[this.value] || [];
    subs.forEach(s => {
        sel.innerHTML += '<option value="'+s.id+'">'+s.name+'</option>';
    });
});

function setMode(mode) {
    currentMode = mode;
    document.getElementById('modeNew').classList.toggle('active', mode === 'new');
    document.getElementById('modeExisting').classList.toggle('active', mode === 'existing');
    document.getElementById('productSelectorWrap').style.display = mode === 'existing' ? '' : 'none';
    document.getElementById('loadBtnWrap').style.display = mode === 'existing' ? '' : 'none';

    if (mode === 'new') {
        editId = null;
        resetForm();
        document.getElementById('productSelector').value = '';
        setFormAction('{{ route("inventoryStoreProduct") }}', 'POST');
        document.getElementById('pageTitle').textContent = 'Add Stock';
        document.getElementById('pageDesc').textContent = 'Add a new product or update existing product stock';
        document.getElementById('submitBtn').textContent = 'Add Product to Inventory';
        document.getElementById('submitBtn').className = 'btn-blue';
    }
}

function setFormAction(url, method) {
    const form = document.getElementById('addProductForm');
    form.action = url;
    let methodInput = form.querySelector('input[name="_method"]');
    if (method === 'PUT') {
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            form.appendChild(methodInput);
        }
        methodInput.value = 'PUT';
    } else if (methodInput) {
        methodInput.remove();
    }
}

function loadProduct() {
    const id = document.getElementById('productSelector').value;
    if (!id) return alert('Please select a product first.');

    fetch('/admin/inventory/product-json/' + id)
        .then(r => r.json())
        .then(p => {
            editId = p.id;
            setFormAction('/admin/inventory/update-product/' + p.id, 'PUT');
            document.getElementById('pageTitle').textContent = 'Edit Product';
            document.getElementById('pageDesc').textContent = 'Update product details and stock';
            document.getElementById('submitBtn').textContent = 'Update Product';
            document.getElementById('submitBtn').className = 'btn-green';

            document.getElementById('categorySelect').value = p.category_id || '';
            document.getElementById('categorySelect').dispatchEvent(new Event('change'));
            setTimeout(() => {
                if (p.sub_category_id) document.getElementById('subCategorySelect').value = p.sub_category_id;
            }, 100);

            document.getElementById('fProductName').value = p.item_name || '';
            document.getElementById('fItemCode').value = p.item_code || '';
            document.getElementById('fSalePrice').value = p.current_sale_price || '';
            document.getElementById('fQuantity').value = p.inventory ? p.inventory.available_qty : (p.quantity || 0);
            document.getElementById('fUom').value = p.uom || '';
            document.getElementById('fFeatured').checked = p.is_featured == 1;
            document.getElementById('fDescription').value = p.description || '';

            document.getElementById('fType').value = p.type || '';
            document.getElementById('fBrand').value = p.brand || '';
            document.getElementById('fModel').value = p.model || '';
            document.getElementById('fOpVolt').value = p.operating_voltage || '';
            document.getElementById('fPanelType').value = p.solar_panel_type || '';
            document.getElementById('fMnre').value = p.mnre_approved || '';
            document.getElementById('fCerts').value = p.certifications || '';
            document.getElementById('fWarranty').value = p.manufacturer_warranty || '';
            document.getElementById('fCells').value = p.number_of_cells || '';
            document.getElementById('fEncap').value = p.encapsulate || '';
            document.getElementById('fCountry').value = p.country_of_origin || '';
            document.getElementById('fInputVolt').value = p.input_voltage || '';
            document.getElementById('fMaxPanel').value = p.max_supported_panel_power || '';

            const specContainer = document.getElementById('customSpecsContainer');
            specContainer.innerHTML = '';
            if (p.custom_specs && p.custom_specs.length) {
                p.custom_specs.forEach(s => {
                    addCustomSpec(s.spec_name, s.spec_value);
                });
            }

            const mainPreview = document.getElementById('mainPreview');
            mainPreview.innerHTML = '';
            if (p.image) {
                mainPreview.innerHTML = '<img src="/serve/' + p.image + '" alt="Current">';
            }
        })
        .catch(() => alert('Failed to load product data.'));
}

function resetForm() {
    const form = document.getElementById('addProductForm');
    form.reset();
    document.getElementById('customSpecsContainer').innerHTML = '';
    document.getElementById('mainPreview').innerHTML = '';
    document.getElementById('galleryPreview').innerHTML = '';
    document.getElementById('subCategorySelect').innerHTML = '<option value="">Select Sub Category</option>';
    if (currentMode === 'new') {
        setFormAction('{{ route("inventoryStoreProduct") }}', 'POST');
        editId = null;
        document.getElementById('submitBtn').textContent = 'Add Product to Inventory';
        document.getElementById('submitBtn').className = 'btn-blue';
    }
}

function addCustomSpec(name, value) {
    const row = document.createElement('div');
    row.className = 'custom-spec-row';
    row.innerHTML = '<input type="text" class="form-control" name="custom_spec_names[]" placeholder="Spec name" value="'+(name||'')+'">'
        + '<input type="text" class="form-control" name="custom_spec_values[]" placeholder="Spec value" value="'+(value||'')+'">'
        + '<button type="button" onclick="this.parentElement.remove()" title="Remove">'
        + '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>'
        + '</button>';
    document.getElementById('customSpecsContainer').appendChild(row);
}

document.getElementById('mainImageInput').addEventListener('change', function() {
    const p = document.getElementById('mainPreview');
    p.innerHTML = '';
    if (this.files[0]) {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(this.files[0]);
        p.appendChild(img);
    }
});

document.getElementById('galleryInput').addEventListener('change', function() {
    const p = document.getElementById('galleryPreview');
    p.innerHTML = '';
    Array.from(this.files).slice(0, 8).forEach(f => {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(f);
        p.appendChild(img);
    });
});
</script>
@endsection
