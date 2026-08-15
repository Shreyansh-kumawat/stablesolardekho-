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
    .prod-summary { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px,1fr)); gap: .75rem; }
    .prod-field { background: var(--light); border-radius: 8px; padding: .6rem .85rem; }
    .prod-field .lbl { font-size: .7rem; font-weight: 600; color: var(--txt2); text-transform: uppercase; letter-spacing: .03em; }
    .prod-field .val { font-size: .88rem; font-weight: 600; color: var(--txt); margin-top: 2px; }
    .prod-img-thumb { width: 64px; height: 64px; object-fit: cover; border-radius: 8px; border: 1px solid var(--bdr); }
    .spec-pills { display: flex; flex-wrap: wrap; gap: .4rem; margin-top: .5rem; }
    .spec-pill { background: #eef3ff; color: var(--blue); font-size: .75rem; font-weight: 600; padding: .25rem .6rem; border-radius: 6px; }
    @media(max-width:768px) { .sec-card { padding: 1rem; } .pg-head { flex-direction: column; align-items: flex-start; } .prod-summary { grid-template-columns: 1fr 1fr; } }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="pg-head">
        <div>
            <h1 id="pageTitle">Add Stock</h1>
            <p id="pageDesc">Add a new product or add stock to existing product</p>
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
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <div class="mode-toggle">
                    <button type="button" class="mode-btn active" id="modeNew" onclick="setMode('new')">New Product</button>
                    <button type="button" class="mode-btn" id="modeExisting" onclick="setMode('existing')">Existing Product</button>
                </div>
            </div>
            <div class="col-md-6" id="productSelectorWrap" style="display:none;">
                <label class="form-label">Choose Product</label>
                <select class="form-select" id="productSelector" onchange="onProductSelect()">
                    <option value="">-- Select a product --</option>
                    @foreach($products as $p)
                    <option value="{{ $p->id }}">{{ $p->item_name }} ({{ $p->item_code ?? 'N/A' }})</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- ============ EXISTING PRODUCT: Read-only summary + stock form ============ --}}
    <div id="existingProductSection" style="display:none;">
        <div class="sec-card">
            <p class="sec-label">Product Details</p>
            <div id="existingProductLoading" style="text-align:center;padding:1rem;color:var(--txt2);display:none;">Loading...</div>
            <div id="existingProductInfo" style="display:none;">
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;">
                    <img id="epImage" class="prod-img-thumb" src="" alt="" style="display:none;">
                    <div>
                        <div style="font-size:1.05rem;font-weight:700;color:var(--txt);" id="epName"></div>
                        <div style="font-size:.8rem;color:var(--txt2);" id="epCode"></div>
                    </div>
                </div>
                <div class="prod-summary">
                    <div class="prod-field"><div class="lbl">Category</div><div class="val" id="epCategory"></div></div>
                    <div class="prod-field"><div class="lbl">Sub Category</div><div class="val" id="epSubCategory"></div></div>
                    <div class="prod-field"><div class="lbl">Sale Price</div><div class="val" id="epPrice"></div></div>
                    <div class="prod-field"><div class="lbl">UOM</div><div class="val" id="epUom"></div></div>
                    <div class="prod-field"><div class="lbl">Current Stock</div><div class="val" id="epCurrentStock" style="color:var(--green);"></div></div>
                    <div class="prod-field"><div class="lbl">Featured</div><div class="val" id="epFeatured"></div></div>
                </div>
                <div id="epSpecsWrap" style="margin-top:.75rem;display:none;">
                    <div style="font-size:.75rem;font-weight:600;color:var(--txt2);text-transform:uppercase;margin-bottom:.35rem;">Specifications</div>
                    <div class="spec-pills" id="epSpecs"></div>
                </div>
            </div>
        </div>

        <form method="POST" id="existingStockForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="existing_stock_only" value="1">

            <div class="sec-card">
                <p class="sec-label">Product Settings</p>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Sale Price (&#8377;)</label>
                        <input type="text" class="form-control" name="current_sale_price" id="epSalePrice" placeholder="e.g. 20000">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Featured</label>
                        <div class="form-check form-switch mt-1">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="epFeaturedCheck">
                            <label class="form-check-label" for="epFeaturedCheck" style="font-size:.85rem;">Mark as Featured</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sec-card">
                <p class="sec-label">Update Stock</p>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">New Quantity <span class="req">*</span></label>
                        <input type="number" min="0" class="form-control" name="quantity" id="epQuantity" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Supplier Name</label>
                        <input type="text" class="form-control" name="supplier_name" placeholder="e.g. Shreyansh Energies">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Unit Price (Purchase)</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="unit_price" placeholder="Purchase price per unit">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Invoice Number</label>
                        <input type="text" class="form-control" name="invoice_number" placeholder="Invoice #">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Invoice Date</label>
                        <input type="date" class="form-control" name="invoice_date">
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:.75rem;margin-bottom:2rem;">
                <button type="submit" class="btn-green" id="epSubmitBtn">Update Stock</button>
            </div>
        </form>
    </div>

    {{-- ============ NEW PRODUCT: Full creation form ============ --}}
    <div id="newProductSection">
        <form action="{{ route('inventoryStoreProduct') }}" method="POST" enctype="multipart/form-data" id="addProductForm">
            @csrf

            {{-- Classification --}}
            <div class="sec-card">
                <p class="sec-label">Classification</p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Category <span class="req">*</span></label>
                        <select name="category_id" id="categorySelect" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
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
                        <input type="text" class="form-control" name="product_name" required placeholder="e.g. Solar Panel 400W" maxlength="100" value="{{ old('product_name') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Product Code <span class="req">*</span></label>
                        <input type="text" class="form-control" name="item_code" required placeholder="e.g. SOL-001" maxlength="50" value="{{ old('item_code') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sale Price (&#8377;)</label>
                        <input type="text" class="form-control" name="current_sale_price" placeholder="e.g. 20000" value="{{ old('current_sale_price') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Quantity <span class="req">*</span></label>
                        <input type="number" min="0" class="form-control" name="quantity" placeholder="0" value="{{ old('quantity', 0) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">UOM <span class="req">*</span></label>
                        <select name="uom" class="form-select" required>
                            <option value="">Select</option>
                            @foreach(['Piece','Kilogram','Liter','Meter','Box','Pack','Watt','KW','Set'] as $u)
                            <option value="{{ $u }}" {{ old('uom')==$u?'selected':'' }}>{{ $u }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Featured</label>
                        <div class="form-check form-switch mt-1">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured">
                            <label class="form-check-label" for="isFeatured" style="font-size:.85rem;">Mark as Featured</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="2" placeholder="Brief product description (optional)">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Inventory Details --}}
            <div class="sec-card">
                <p class="sec-label">Inventory Details</p>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Supplier Name</label>
                        <input type="text" class="form-control" name="supplier_name" placeholder="e.g. Shreyansh Energies" value="{{ old('supplier_name') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Unit Price (Purchase)</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="unit_price" placeholder="Purchase price per unit" value="{{ old('unit_price') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Invoice Number</label>
                        <input type="text" class="form-control" name="invoice_number" placeholder="Invoice #" value="{{ old('invoice_number') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Invoice Date</label>
                        <input type="date" class="form-control" name="invoice_date" value="{{ old('invoice_date') }}">
                    </div>
                </div>
            </div>

            {{-- Specifications --}}
            <div class="sec-card">
                <p class="sec-label">Specifications <small style="color:var(--txt2);text-transform:none;letter-spacing:0;font-weight:400;">(all optional)</small></p>
                <div class="row g-2 mb-2">
                    <div class="col-md-4"><label class="form-label mb-0">Type</label><input type="text" class="form-control" name="type" placeholder="e.g. Solar Panel"></div>
                    <div class="col-md-4"><label class="form-label mb-0">Brand</label><input type="text" class="form-control" name="brand" placeholder="e.g. Shreyansh, Adani"></div>
                    <div class="col-md-4"><label class="form-label mb-0">Model</label><input type="text" class="form-control" name="product_model" placeholder="e.g. WS-545"></div>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-md-4"><label class="form-label mb-0">Operating Voltage</label><input type="text" class="form-control" name="operating_voltage" placeholder="e.g. 24V / 48V"></div>
                    <div class="col-md-4"><label class="form-label mb-0">Solar Panel Type</label><input type="text" class="form-control" name="solar_panel_type" placeholder="e.g. Mono PERC"></div>
                    <div class="col-md-4"><label class="form-label mb-0">MNRE Approved</label><input type="text" class="form-control" name="mnre_approved" placeholder="e.g. Yes / No"></div>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-md-6"><label class="form-label mb-0">Certifications</label><input type="text" class="form-control" name="certifications" placeholder="e.g. BIS, IEC 61215"></div>
                    <div class="col-md-6"><label class="form-label mb-0">Manufacturer Warranty</label><input type="text" class="form-control" name="manufacturer_warranty" placeholder="e.g. 25 Years"></div>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-md-4"><label class="form-label mb-0">Number of Cells</label><input type="text" class="form-control" name="number_of_cells" placeholder="e.g. 144"></div>
                    <div class="col-md-4"><label class="form-label mb-0">Encapsulate</label><input type="text" class="form-control" name="encapsulate" placeholder="e.g. EVA"></div>
                    <div class="col-md-4"><label class="form-label mb-0">Country of Origin</label><input type="text" class="form-control" name="country_of_origin" placeholder="e.g. India"></div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-md-6"><label class="form-label mb-0">Input Voltage</label><input type="text" class="form-control" name="input_voltage" placeholder="e.g. 12V - 48V"></div>
                    <div class="col-md-6"><label class="form-label mb-0">Max Supported Panel Power</label><input type="text" class="form-control" name="max_supported_panel_power" placeholder="e.g. 6000W"></div>
                </div>

                <div style="border-top:1px solid #eef1f5;padding-top:.75rem;margin-top:.5rem;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.5rem;">
                        <label class="form-label mb-0" style="color:var(--blue);">Custom Specifications</label>
                        <button type="button" onclick="addCustomSpec()" style="background:var(--blue);color:#fff;border:none;border-radius:6px;padding:.3rem .75rem;font-size:.78rem;font-weight:600;cursor:pointer;">+ Add Spec</button>
                    </div>
                    <div id="customSpecsContainer"></div>
                </div>
            </div>

            {{-- Photos --}}
            <div class="sec-card">
                <p class="sec-label">Photos</p>
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">Main Photo</label>
                        <div class="upload-area">
                            <input type="file" name="image" accept="image/*" id="mainImageInput">
                            <svg width="24" height="24" fill="none" stroke="var(--txt2)" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p><strong>Click to upload</strong> main photo</p>
                        </div>
                        <div class="preview-strip" id="mainPreview"></div>
                    </div>
                    <div class="col-md-7">
                        <label class="form-label">Gallery Photos <small style="color:var(--txt2);">(up to 8)</small></label>
                        <div class="upload-area">
                            <input type="file" name="product_images[]" accept="image/*" multiple id="galleryInput">
                            <svg width="24" height="24" fill="none" stroke="var(--txt2)" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p><strong>Click to upload</strong> gallery images</p>
                        </div>
                        <div class="preview-strip" id="galleryPreview"></div>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:.75rem;margin-bottom:2rem;">
                <button type="submit" class="btn-blue">Add Product to Inventory</button>
                <button type="reset" class="btn-outline">Clear Form</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
function setMode(mode) {
    document.getElementById('modeNew').classList.toggle('active', mode === 'new');
    document.getElementById('modeExisting').classList.toggle('active', mode === 'existing');
    document.getElementById('productSelectorWrap').style.display = mode === 'existing' ? '' : 'none';
    document.getElementById('newProductSection').style.display = mode === 'new' ? '' : 'none';
    document.getElementById('existingProductSection').style.display = mode === 'existing' ? 'none' : 'none';

    if (mode === 'new') {
        document.getElementById('existingProductSection').style.display = 'none';
        document.getElementById('productSelector').value = '';
        document.getElementById('existingProductInfo').style.display = 'none';
        document.getElementById('pageTitle').textContent = 'Add Stock';
        document.getElementById('pageDesc').textContent = 'Add a new product or add stock to existing product';
    } else {
        document.getElementById('newProductSection').style.display = 'none';
        document.getElementById('pageTitle').textContent = 'Add Stock';
        document.getElementById('pageDesc').textContent = 'Select an existing product to update stock';
    }
}

function onProductSelect() {
    const id = document.getElementById('productSelector').value;
    if (!id) {
        document.getElementById('existingProductSection').style.display = 'none';
        return;
    }

    document.getElementById('existingProductSection').style.display = '';
    document.getElementById('existingProductLoading').style.display = '';
    document.getElementById('existingProductInfo').style.display = 'none';

    fetch('/admin/inventory/product-json/' + id)
        .then(r => r.json())
        .then(p => {
            document.getElementById('existingProductLoading').style.display = 'none';
            document.getElementById('existingProductInfo').style.display = '';

            const img = document.getElementById('epImage');
            if (p.image) {
                img.src = '/serve/' + p.image;
                img.style.display = '';
            } else {
                img.style.display = 'none';
            }

            document.getElementById('epName').textContent = p.item_name || '';
            document.getElementById('epCode').textContent = p.item_code || '';
            document.getElementById('epCategory').textContent = catNames[p.category_id] || '-';
            document.getElementById('epSubCategory').textContent = p.sub_category_id ? (subCatNames[p.sub_category_id] || '-') : '-';
            document.getElementById('epPrice').textContent = p.current_sale_price ? '₹' + Number(p.current_sale_price).toLocaleString() : '-';
            document.getElementById('epUom').textContent = p.uom || '-';

            const currentStock = p.inventory ? p.inventory.available_qty : (p.quantity || 0);
            document.getElementById('epCurrentStock').textContent = currentStock;
            document.getElementById('epQuantity').value = currentStock;

            document.getElementById('epFeatured').textContent = p.is_featured == 1 ? 'Yes' : 'No';

            document.getElementById('epSalePrice').value = p.current_sale_price || '';
            document.getElementById('epFeaturedCheck').checked = p.is_featured == 1;

            const specsWrap = document.getElementById('epSpecsWrap');
            const specsPills = document.getElementById('epSpecs');
            specsPills.innerHTML = '';
            let hasSpecs = false;

            const specFields = [
                ['Type', p.type], ['Brand', p.brand], ['Model', p.model],
                ['Voltage', p.operating_voltage], ['Panel Type', p.solar_panel_type],
                ['MNRE', p.mnre_approved], ['Certs', p.certifications],
                ['Warranty', p.manufacturer_warranty], ['Cells', p.number_of_cells],
                ['Origin', p.country_of_origin]
            ];
            specFields.forEach(([lbl, val]) => {
                if (val) {
                    specsPills.innerHTML += '<span class="spec-pill">' + lbl + ': ' + val + '</span>';
                    hasSpecs = true;
                }
            });
            if (p.custom_specs && p.custom_specs.length) {
                p.custom_specs.forEach(s => {
                    specsPills.innerHTML += '<span class="spec-pill">' + s.spec_name + ': ' + (s.spec_value || '-') + '</span>';
                    hasSpecs = true;
                });
            }
            specsWrap.style.display = hasSpecs ? '' : 'none';

            document.getElementById('existingStockForm').action = '/admin/inventory/update-product/' + p.id;

            const form = document.getElementById('existingStockForm');
            form.querySelector('input[name="supplier_name"]').value = '';
            form.querySelector('input[name="unit_price"]').value = '';
            form.querySelector('input[name="invoice_number"]').value = '';
            form.querySelector('input[name="invoice_date"]').value = '';
        })
        .catch(() => {
            document.getElementById('existingProductLoading').style.display = 'none';
            alert('Failed to load product data.');
        });
}

function addCustomSpec() {
    const row = document.createElement('div');
    row.className = 'custom-spec-row';
    row.innerHTML = '<input type="text" class="form-control" name="custom_spec_names[]" placeholder="Spec name">'
        + '<input type="text" class="form-control" name="custom_spec_values[]" placeholder="Spec value">'
        + '<button type="button" onclick="this.parentElement.remove()" title="Remove">'
        + '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>'
        + '</button>';
    document.getElementById('customSpecsContainer').appendChild(row);
}

const subCats = @json($categories->mapWithKeys(fn($c) => [$c->id => $c->subCategories->map(fn($s) => ['id'=>$s->id,'name'=>$s->sub_category_name])]));
const catNames = @json($categories->pluck('category_name', 'id'));
const subCatNames = @json($categories->flatMap(fn($c) => $c->subCategories)->pluck('sub_category_name', 'id'));

document.getElementById('categorySelect').addEventListener('change', function() {
    const sel = document.getElementById('subCategorySelect');
    sel.innerHTML = '<option value="">Select Sub Category</option>';
    (subCats[this.value] || []).forEach(s => {
        sel.innerHTML += '<option value="'+s.id+'">'+s.name+'</option>';
    });
});

document.getElementById('mainImageInput').addEventListener('change', function() {
    const p = document.getElementById('mainPreview');
    p.innerHTML = '';
    if (this.files[0]) { const img = document.createElement('img'); img.src = URL.createObjectURL(this.files[0]); p.appendChild(img); }
});
document.getElementById('galleryInput').addEventListener('change', function() {
    const p = document.getElementById('galleryPreview');
    p.innerHTML = '';
    Array.from(this.files).slice(0, 8).forEach(f => { const img = document.createElement('img'); img.src = URL.createObjectURL(f); p.appendChild(img); });
});
</script>
@endsection
