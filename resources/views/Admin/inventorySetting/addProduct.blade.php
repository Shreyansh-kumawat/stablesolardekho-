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
    .dest-select { position: relative; }
    .dest-select .dest-input { border-radius: 8px; border: 1px solid var(--bdr); padding: .55rem .85rem; font-size: .88rem; font-weight: 600; cursor: pointer; background: #fff; width: 100%; display: flex; align-items: center; justify-content: space-between; transition: border-color .15s; }
    .dest-select .dest-input:hover, .dest-select .dest-input.open { border-color: var(--blue); }
    .dest-select .dest-input .dest-icon { width: 28px; height: 28px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: .85rem; margin-right: 8px; flex-shrink: 0; }
    .dest-select .dest-input .dest-icon.main { background: #d1fae5; color: #059669; }
    .dest-select .dest-input .dest-icon.wh { background: #eef3ff; color: var(--blue); }
    .dest-select .dest-input .dest-label { flex: 1; text-align: left; }
    .dest-select .dest-input .dest-arrow { color: var(--txt2); font-size: .7rem; margin-left: 8px; transition: transform .15s; }
    .dest-select .dest-input.open .dest-arrow { transform: rotate(180deg); }
    .dest-panel { display: none; position: absolute; top: calc(100% + 4px); left: 0; right: 0; background: #fff; border: 1px solid var(--bdr); border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,.12); z-index: 200; overflow: hidden; }
    .dest-panel.open { display: block; }
    .dest-panel .dest-search { padding: 10px 12px; border-bottom: 1px solid #f0f0f0; }
    .dest-panel .dest-search input { width: 100%; border: 1px solid var(--bdr); border-radius: 6px; padding: 7px 10px; font-size: .84rem; outline: none; }
    .dest-panel .dest-search input:focus { border-color: var(--blue); }
    .dest-panel .dest-list { max-height: 220px; overflow-y: auto; }
    .dest-panel .dest-option { padding: 9px 14px; cursor: pointer; font-size: .86rem; display: flex; align-items: center; gap: 8px; border-bottom: 1px solid #f8f8f8; transition: background .1s; }
    .dest-panel .dest-option:hover { background: #f0f5ff; }
    .dest-panel .dest-option.selected { background: #eef3ff; font-weight: 600; }
    .dest-panel .dest-option .d-icon { width: 26px; height: 26px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: .8rem; flex-shrink: 0; }
    .dest-panel .dest-option .d-icon.main { background: #d1fae5; color: #059669; }
    .dest-panel .dest-option .d-icon.wh { background: #eef3ff; color: var(--blue); }
    .dest-panel .dest-option.hidden { display: none; }
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
    .qty-adj-btn { width: 38px; height: 38px; border-radius: 8px; border: none; font-size: 1.2rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all .15s; }
    .qty-minus { background: #fee2e2; color: #dc2626; }
    .qty-minus:hover { background: #fecaca; }
    .qty-plus { background: #d1fae5; color: #059669; }
    .qty-plus:hover { background: #a7f3d0; }
    .searchable-select { position: relative; }
    .search-dropdown { display: none; position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid var(--bdr); border-top: none; border-radius: 0 0 8px 8px; max-height: 220px; overflow-y: auto; z-index: 100; box-shadow: 0 6px 16px rgba(0,0,0,0.1); }
    .search-dropdown.open { display: block; }
    .search-option { padding: 8px 12px; cursor: pointer; font-size: .85rem; color: var(--txt); border-bottom: 1px solid #f5f5f5; }
    .search-option:hover { background: #eef3ff; }
    .search-option.hidden { display: none; }
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
                    <button type="button" class="mode-btn" id="modeBulk" onclick="setMode('bulk')" style="background:#fef3c7 !important; color:#92400e !important;">
                        <i class="fas fa-file-excel me-1"></i> Bulk Serial Upload
                    </button>
                </div>
            </div>
            <div class="col-md-6" id="productSelectorWrap" style="display:none;">
                <label class="form-label">Choose Product</label>
                <div class="searchable-select" id="productSearchWrap">
                    <input type="text" class="form-control" id="productSearchInput" placeholder="Search product by name or code..." autocomplete="off" onclick="toggleDropdown('productDropdown', true)" oninput="filterDropdown('productDropdown', this.value, 'productSelector')">
                    <select class="form-select" id="productSelector" onchange="onProductSelect()" style="display:none;">
                        <option value="">-- Select a product --</option>
                        @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->item_name }} ({{ $p->item_code ?? 'N/A' }})</option>
                        @endforeach
                    </select>
                    <div class="search-dropdown" id="productDropdown">
                        @foreach($products as $p)
                        <div class="search-option" data-value="{{ $p->id }}" data-text="{{ $p->item_name }} ({{ $p->item_code ?? 'N/A' }})" onclick="selectOption('productSelector', '{{ $p->id }}', '{{ addslashes($p->item_name) }} ({{ $p->item_code ?? 'N/A' }})', 'productSearchInput', 'productDropdown'); onProductSelect();">
                            <strong>{{ $p->item_name }}</strong> <span style="color:var(--txt2); font-size:.78rem;">{{ $p->item_code ?? '' }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
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
                        <label class="form-label">Current Quantity <small style="color:var(--txt2); font-weight:500;">(Main Inventory only)</small></label>
                        <input type="hidden" id="epQuantity" name="quantity" value="0">
                        <div style="padding:10px 14px; background:var(--light); border:1px solid var(--bdr); border-radius:8px; text-align:center; font-weight:700; font-size:1.1rem; color:var(--txt);" id="epQtyDisplay">0</div>
                        <div style="display:flex; align-items:center; gap:6px; margin-top:10px;">
                            <button type="button" class="qty-adj-btn qty-minus" onclick="adjustQty(-1)">&#8722;</button>
                            <input type="number" min="0" class="form-control" id="epAdjustQty" placeholder="Enter qty to add/remove" style="flex:1; text-align:center; font-weight:600;">
                            <button type="button" class="qty-adj-btn qty-plus" onclick="adjustQty(1)">+</button>
                        </div>
                        <div id="epQtyHint" style="display:none; margin-top:6px; font-size:.78rem; font-weight:600; padding:4px 8px; border-radius:6px;"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Supplier Name</label>
                        <input type="text" class="form-control" name="supplier_name" placeholder="e.g. Shreyansh Energies">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Unit Price (Purchase)
                            <small style="color:#0369a1; font-weight:500;" id="epLastPriceHint"></small>
                        </label>
                        <input type="number" step="0.01" min="0" class="form-control" name="unit_price" id="epUnitPrice" placeholder="Purchase price per unit" oninput="calcEpGst()">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">GST %</label>
                        <input type="number" step="0.01" min="0" max="100" class="form-control" name="gst_percent" id="epGstPercent" placeholder="e.g. 18" oninput="calcEpGst()">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Amount with GST (per unit)</label>
                        <input type="text" class="form-control" id="epAmountWithGst" readonly placeholder="Auto-calculated" style="background:#f0fdf4; color:#065f46; font-weight:600;">
                        <input type="hidden" name="gst_amount" id="epGstAmountHidden">
                        <input type="hidden" name="total_with_gst" id="epTotalWithGstHidden">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Invoice Number</label>
                        <input type="text" class="form-control" name="invoice_number" placeholder="Invoice #">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Invoice Date</label>
                        <input type="date" class="form-control" name="invoice_date">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Destination <span class="req">*</span></label>
                        <input type="hidden" name="destination_warehouse_id" id="existDestWarehouseId" value="">
                        <div class="dest-select" id="existDestSelect">
                            <div class="dest-input" id="existDestBtn" onclick="toggleDest('exist')">
                                <span class="dest-icon main">&#9679;</span>
                                <span class="dest-label" id="existDestLabel">Main Inventory</span>
                                <span class="dest-arrow">&#9660;</span>
                            </div>
                            <div class="dest-panel" id="existDestPanel">
                                <div class="dest-search">
                                    <input type="text" placeholder="Search destination..." id="existDestSearch" oninput="filterDest('exist', this.value)" autocomplete="off">
                                </div>
                                <div class="dest-list" id="existDestList">
                                    <div class="dest-option selected" data-value="" data-text="Main Inventory" onclick="pickDest('exist', '', 'Main Inventory', 'main')">
                                        <span class="d-icon main">&#9679;</span> Main Inventory
                                    </div>
                                    @foreach($warehouses as $wh)
                                    <div class="dest-option" data-value="{{ $wh->id }}" data-text="{{ $wh->name }}" onclick="pickDest('exist', '{{ $wh->id }}', '{{ addslashes($wh->name) }}', 'wh')">
                                        <span class="d-icon wh">&#9632;</span> {{ $wh->name }}
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Serial Numbers section (shown only when selected product is serial-tracked) --}}
            <div class="sec-card" id="epSerialSection" style="display:none;">
                <p class="sec-label">
                    Serial Numbers
                    <span style="font-weight:400; font-size:.78rem; color:var(--txt2);">(add as many as needed - any gap = new serial)</span>
                </p>
                <div class="serial-tools" style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:10px; align-items:center;">
                    <label class="btn-secondary" style="cursor:pointer; padding:8px 14px; background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; border-radius:8px; font-weight:600; font-size:.85rem;">
                        <i class="fas fa-file-excel me-1"></i> Upload Excel
                        <input type="file" id="epSerialExcel" accept=".xlsx,.xls,.csv" style="display:none;" onchange="handleSerialExcelUpload('ep', this)">
                    </label>
                    <a href="{{ route('inventorySerialTemplate') }}" style="padding:8px 14px; background:#f3f4f6; color:#4b5563; border:1px solid #d1d5db; border-radius:8px; font-weight:600; font-size:.85rem; text-decoration:none;">
                        <i class="fas fa-download me-1"></i> Template
                    </a>
                    <button type="button" onclick="beautifySerialsText('ep')" style="padding:8px 14px; background:#fef3c7; color:#92400e; border:1px solid #fde68a; border-radius:8px; font-weight:600; font-size:.85rem; cursor:pointer;">
                        <i class="fas fa-magic me-1"></i> Beautify
                    </button>
                    <span id="epSerialSummary" style="margin-left:auto; font-size:.85rem; color:var(--txt2); font-weight:600;">0 serial(s) detected</span>
                </div>
                <textarea name="serials_text" id="epSerialsText" rows="6" class="form-control"
                          placeholder="Paste serial numbers - separate by space, line break, or comma. Any gap creates a new serial. Example:&#10;3K6210826-2628-986705182P 3K6210826-2628-986705285P 3K6210826-2628-986705289P&#10;&#10;Click 'Beautify' to arrange one per line."
                          oninput="onSerialsTextChange('ep')"></textarea>
                <div id="epSerialFeedback" style="margin-top:8px; font-size:.82rem;"></div>
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
                        <div class="searchable-select">
                            <input type="text" class="form-control" id="categorySearchInput" placeholder="Search category..." autocomplete="off" onclick="toggleDropdown('categoryDropdown', true)" oninput="filterDropdown('categoryDropdown', this.value, 'categorySelect')">
                            <select name="category_id" id="categorySelect" class="form-select" required style="display:none;">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                @endforeach
                            </select>
                            <div class="search-dropdown" id="categoryDropdown">
                                @foreach($categories as $cat)
                                <div class="search-option" data-value="{{ $cat->id }}" data-text="{{ $cat->category_name }}" onclick="selectOption('categorySelect', '{{ $cat->id }}', '{{ addslashes($cat->category_name) }}', 'categorySearchInput', 'categoryDropdown'); document.getElementById('categorySelect').dispatchEvent(new Event('change'));">
                                    {{ $cat->category_name }}
                                </div>
                                @endforeach
                            </div>
                        </div>
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
                        <div style="position:relative;">
                            <input type="text" class="form-control" name="product_name" id="npProductName" required placeholder="Select category first, or type new name" maxlength="100" value="{{ old('product_name') }}" autocomplete="off"
                                   oninput="npProductNameInput()" onfocus="npShowProductSuggestions()">
                            <div id="npProductSuggestions" style="display:none; position:absolute; top:100%; left:0; right:0; background:#fff; border:1px solid #e5e7eb; border-radius:8px; margin-top:2px; max-height:280px; overflow-y:auto; z-index:100; box-shadow:0 8px 20px rgba(0,0,0,0.08);"></div>
                            <div id="npMatchedProductHint" style="display:none; margin-top:6px; padding:8px 12px; background:#fef3c7; border:1px solid #fde68a; border-radius:6px; font-size:.8rem; color:#78350f;">
                                <i class="fas fa-info-circle me-1"></i>
                                <span id="npMatchedProductMsg"></span>
                                <button type="button" onclick="npSwitchToExisting()" style="margin-left:8px; background:#f97316; color:#fff; border:none; padding:3px 10px; border-radius:5px; font-size:.72rem; font-weight:600; cursor:pointer;">Switch to Existing</button>
                            </div>
                        </div>
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
                        <input type="number" min="0" id="npQuantity" class="form-control" name="quantity" placeholder="0" value="{{ old('quantity', 0) }}" required>
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
                    <div class="col-md-3">
                        <label class="form-label">Serial Tracked</label>
                        <div class="form-check form-switch mt-1">
                            <input class="form-check-input" type="checkbox" name="is_serial_tracked" value="1" id="npIsSerialTracked" onchange="toggleNewProductSerials()">
                            <label class="form-check-label" for="npIsSerialTracked" style="font-size:.85rem;">Enable Serial Tracking</label>
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
                        <input type="number" step="0.01" min="0" class="form-control" name="unit_price" id="npUnitPrice" placeholder="Purchase price per unit" value="{{ old('unit_price') }}" oninput="calcNpGst()">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">GST %</label>
                        <input type="number" step="0.01" min="0" max="100" class="form-control" name="gst_percent" id="npGstPercent" placeholder="e.g. 18" value="{{ old('gst_percent') }}" oninput="calcNpGst()">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Amount with GST (per unit)</label>
                        <input type="text" class="form-control" id="npAmountWithGst" readonly placeholder="Auto-calculated" style="background:#f0fdf4; color:#065f46; font-weight:600;">
                        <input type="hidden" name="gst_amount" id="npGstAmountHidden">
                        <input type="hidden" name="total_with_gst" id="npTotalWithGstHidden">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Invoice Number</label>
                        <input type="text" class="form-control" name="invoice_number" placeholder="Invoice #" value="{{ old('invoice_number') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Invoice Date</label>
                        <input type="date" class="form-control" name="invoice_date" value="{{ old('invoice_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Destination <span class="req">*</span></label>
                        <input type="hidden" name="destination_warehouse_id" id="newDestWarehouseId" value="">
                        <div class="dest-select" id="newDestSelect">
                            <div class="dest-input" id="newDestBtn" onclick="toggleDest('new')">
                                <span class="dest-icon main">&#9679;</span>
                                <span class="dest-label" id="newDestLabel">Main Inventory</span>
                                <span class="dest-arrow">&#9660;</span>
                            </div>
                            <div class="dest-panel" id="newDestPanel">
                                <div class="dest-search">
                                    <input type="text" placeholder="Search destination..." id="newDestSearch" oninput="filterDest('new', this.value)" autocomplete="off">
                                </div>
                                <div class="dest-list" id="newDestList">
                                    <div class="dest-option selected" data-value="" data-text="Main Inventory" onclick="pickDest('new', '', 'Main Inventory', 'main')">
                                        <span class="d-icon main">&#9679;</span> Main Inventory
                                    </div>
                                    @foreach($warehouses as $wh)
                                    <div class="dest-option" data-value="{{ $wh->id }}" data-text="{{ $wh->name }}" onclick="pickDest('new', '{{ $wh->id }}', '{{ addslashes($wh->name) }}', 'wh')">
                                        <span class="d-icon wh">&#9632;</span> {{ $wh->name }}
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Serial Numbers section (shown when Serial Tracked is enabled) --}}
            <div class="sec-card" id="npSerialSection" style="display:none;">
                <p class="sec-label">
                    Serial Numbers
                    <span style="font-weight:400; font-size:.78rem; color:var(--txt2);">(add as many as needed - any gap = new serial)</span>
                </p>
                <div class="serial-tools" style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:10px; align-items:center;">
                    <label class="btn-secondary" style="cursor:pointer; padding:8px 14px; background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; border-radius:8px; font-weight:600; font-size:.85rem;">
                        <i class="fas fa-file-excel me-1"></i> Upload Excel
                        <input type="file" id="npSerialExcel" accept=".xlsx,.xls,.csv" style="display:none;" onchange="handleSerialExcelUpload('np', this)">
                    </label>
                    <a href="{{ route('inventorySerialTemplate') }}" style="padding:8px 14px; background:#f3f4f6; color:#4b5563; border:1px solid #d1d5db; border-radius:8px; font-weight:600; font-size:.85rem; text-decoration:none;">
                        <i class="fas fa-download me-1"></i> Template
                    </a>
                    <button type="button" onclick="beautifySerialsText('np')" style="padding:8px 14px; background:#fef3c7; color:#92400e; border:1px solid #fde68a; border-radius:8px; font-weight:600; font-size:.85rem; cursor:pointer;">
                        <i class="fas fa-magic me-1"></i> Beautify
                    </button>
                    <span id="npSerialSummary" style="margin-left:auto; font-size:.85rem; color:var(--txt2); font-weight:600;">0 serial(s) detected</span>
                </div>
                <textarea name="serials_text" id="npSerialsText" rows="6" class="form-control"
                          placeholder="Paste serial numbers - separate by space, line break, or comma. Any gap creates a new serial. Example:&#10;3K6210826-2628-986705182P 3K6210826-2628-986705285P 3K6210826-2628-986705289P&#10;&#10;Click 'Beautify' to arrange one per line."
                          oninput="onSerialsTextChange('np')"></textarea>
                <div id="npSerialFeedback" style="margin-top:8px; font-size:.82rem;"></div>
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

    {{-- ═══════════════════════════════════════════════════════════
         BULK SERIAL UPLOAD SECTION
         ═══════════════════════════════════════════════════════════ --}}
    <div id="bulkUploadSection" style="display:none;">
        <div class="sec-card">
            <p class="sec-label">Bulk Serial Upload</p>
            <p style="font-size:.85rem; color:var(--txt2); margin-bottom:16px;">
                Upload an invoice Excel/CSV with multiple products - each product block auto-detected, serials extracted, and rendered as separate cards below.
                Only the <strong>"Item &amp; Description"</strong> column matters: product name lines, then <strong>SR.NO</strong> header, then serials until a blank line. Anything after the gap is shown as a warning.
            </p>
            <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
                <label style="cursor:pointer; padding:10px 18px; background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; border:none; border-radius:8px; font-weight:700; font-size:.9rem;">
                    <i class="fas fa-file-excel me-1"></i> Choose Excel File
                    <input type="file" id="bulkExcelInput" accept=".xlsx,.xls,.csv" style="display:none;" onchange="handleBulkExcelUpload(this)">
                </label>
                <a href="{{ route('inventorySerialTemplate') }}" style="padding:10px 18px; background:#f3f4f6; color:#4b5563; border:1px solid #d1d5db; border-radius:8px; font-weight:600; font-size:.9rem; text-decoration:none;">
                    <i class="fas fa-download me-1"></i> Download Template
                </a>
                <button type="button" onclick="addManualBulkCard()" style="padding:10px 18px; background:#ecfdf5; color:#065f46; border:1px solid #86efac; border-radius:8px; font-weight:600; font-size:.9rem; cursor:pointer;">
                    <i class="fas fa-plus me-1"></i> Add Product Manually
                </button>
                <span id="bulkParseStatus" style="font-size:.85rem; color:var(--txt2); font-weight:600;"></span>
            </div>
        </div>

        {{-- Common invoice details --}}
        <div class="sec-card">
            <p class="sec-label">Common Invoice Details <span style="font-weight:400; font-size:.78rem; color:var(--txt2);">(applied to all products below)</span></p>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Supplier Name</label>
                    <input type="text" class="form-control" id="bulkSupplierName" placeholder="e.g. Polycab India Ltd.">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Invoice Number</label>
                    <input type="text" class="form-control" id="bulkInvoiceNumber" placeholder="Invoice #">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Invoice Date</label>
                    <input type="date" class="form-control" id="bulkInvoiceDate">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Destination <span class="req">*</span></label>
                    <select class="form-select" id="bulkDestination">
                        <option value="">Main Inventory</option>
                        @foreach($warehouses as $wh)
                        <option value="{{ $wh->id }}">Warehouse: {{ $wh->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Rendered product cards go here --}}
        <div id="bulkProductCards"></div>

        <div style="display:flex; gap:10px; margin-bottom:2rem;" id="bulkSubmitWrap" hidden>
            <button type="button" class="btn-green" onclick="submitBulkUpload()" id="bulkSubmitBtn">
                <i class="fas fa-check me-1"></i> Save All Products
            </button>
            <button type="button" class="btn-outline" onclick="clearBulkCards()">Clear All</button>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
function setMode(mode) {
    document.getElementById('modeNew').classList.toggle('active', mode === 'new');
    document.getElementById('modeExisting').classList.toggle('active', mode === 'existing');
    document.getElementById('modeBulk').classList.toggle('active', mode === 'bulk');

    document.getElementById('productSelectorWrap').style.display = mode === 'existing' ? '' : 'none';
    document.getElementById('newProductSection').style.display = mode === 'new' ? '' : 'none';
    document.getElementById('bulkUploadSection').style.display = mode === 'bulk' ? '' : 'none';

    if (mode === 'new') {
        document.getElementById('existingProductSection').style.display = 'none';
        document.getElementById('productSelector').value = '';
        document.getElementById('productSearchInput').value = '';
        document.getElementById('existingProductInfo').style.display = 'none';
        document.getElementById('pageTitle').textContent = 'Add Stock';
        document.getElementById('pageDesc').textContent = 'Add a new product or add stock to existing product';
    } else if (mode === 'existing') {
        document.getElementById('pageTitle').textContent = 'Add Stock';
        document.getElementById('pageDesc').textContent = 'Select an existing product to update stock';
    } else if (mode === 'bulk') {
        document.getElementById('existingProductSection').style.display = 'none';
        document.getElementById('pageTitle').textContent = 'Bulk Serial Upload';
        document.getElementById('pageDesc').textContent = 'Upload one invoice Excel — multiple products auto-detected and saved together';
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

            const mainStock = p.main_stock || 0;
            const whStock = p.warehouse_stock || 0;
            const totalStock = p.total_stock || 0;
            document.getElementById('epCurrentStock').innerHTML = totalStock + ' <span style="font-size:.72rem;font-weight:500;color:var(--txt2);">(Main: ' + mainStock + ' + Warehouses: ' + whStock + ')</span>';
            document.getElementById('epQuantity').value = mainStock;
            document.getElementById('epQtyDisplay').textContent = mainStock;
            epOriginalQty = mainStock;
            document.getElementById('epAdjustQty').value = '';
            document.getElementById('epQtyHint').style.display = 'none';

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
            document.getElementById('epGstPercent').value = '';
            document.getElementById('epAmountWithGst').value = '';
            document.getElementById('epGstAmountHidden').value = '';
            document.getElementById('epTotalWithGstHidden').value = '';

            // Prefill last purchase price + gst from most recent IN transaction
            var hint = document.getElementById('epLastPriceHint');
            hint.textContent = '';
            fetch("{{ url('/admin/inventory/last-purchase') }}/" + p.id)
                .then(r => r.json())
                .then(function(data) {
                    if (data && data.unit_price) {
                        document.getElementById('epUnitPrice').value = data.unit_price;
                        if (data.gst_percent) document.getElementById('epGstPercent').value = data.gst_percent;
                        var when = data.when ? ' (last on ' + data.when + ')' : '';
                        hint.textContent = ' - previously ₹' + Number(data.unit_price).toLocaleString('en-IN') + when;
                        calcEpGst();
                    }
                }).catch(function(){});

            // Show / hide Serial Numbers section based on product
            const epSerialSection = document.getElementById('epSerialSection');
            if (p.is_serialNumber_required == 1 || p.is_serialNumber_required === true) {
                epSerialSection.style.display = '';
                document.getElementById('epSerialsText').value = '';
                onSerialsTextChange('ep');
            } else {
                epSerialSection.style.display = 'none';
                document.getElementById('epSerialsText').value = '';
            }
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
    // Load products in this category for smart name suggestions
    npCategoryProducts = [];
    if (this.value) {
        fetch("{{ url('/admin/inventory/products-by-category') }}/" + this.value)
            .then(r => r.json())
            .then(data => { npCategoryProducts = data || []; })
            .catch(() => { npCategoryProducts = []; });
    }
});

var npCategoryProducts = [];
function npShowProductSuggestions() {
    var input = document.getElementById('npProductName');
    var box = document.getElementById('npProductSuggestions');
    var q = (input.value || '').toLowerCase();
    if (!npCategoryProducts.length) { box.style.display = 'none'; return; }
    var matches = npCategoryProducts.filter(function(p) {
        return !q || (p.item_name || '').toLowerCase().includes(q) || (p.item_code || '').toLowerCase().includes(q);
    });
    if (!matches.length) { box.style.display = 'none'; return; }
    box.innerHTML = matches.slice(0, 20).map(function(p) {
        return '<div style="padding:8px 12px; cursor:pointer; border-bottom:1px solid #f3f4f6; font-size:.85rem;" onmouseenter="this.style.background=\'#f1f5fb\'" onmouseleave="this.style.background=\'\'" onclick="npPickProduct(' + p.id + ', ' + JSON.stringify(p.item_name).replace(/"/g,'&quot;') + ')">'
             + '<strong>' + escapeHtmlInline(p.item_name) + '</strong>'
             + (p.item_code ? '<span style="color:#6b7280; font-size:.75rem; margin-left:6px;">(' + escapeHtmlInline(p.item_code) + ')</span>' : '')
             + '</div>';
    }).join('');
    box.style.display = 'block';
}
function npProductNameInput() {
    npShowProductSuggestions();
    // Check exact match => hint switch to Existing
    var input = document.getElementById('npProductName');
    var val = (input.value || '').trim().toLowerCase();
    var match = npCategoryProducts.find(function(p) { return (p.item_name || '').toLowerCase() === val; });
    var hint = document.getElementById('npMatchedProductHint');
    if (match) {
        document.getElementById('npMatchedProductMsg').textContent = 'A product with this name already exists in this category. Switch to "Existing Product" tab to add stock.';
        hint.style.display = '';
        hint.dataset.matchId = match.id;
    } else {
        hint.style.display = 'none';
        hint.dataset.matchId = '';
    }
}
function npPickProduct(id, name) {
    document.getElementById('npProductName').value = name;
    document.getElementById('npProductSuggestions').style.display = 'none';
    npProductNameInput();
}
function npSwitchToExisting() {
    var hint = document.getElementById('npMatchedProductHint');
    var pid = hint.dataset.matchId;
    if (!pid) return;
    // Switch tab to existing
    if (typeof setMode === 'function') {
        setMode('existing');
    } else {
        var existingBtn = document.getElementById('modeExisting');
        if (existingBtn) existingBtn.click();
    }
    // Trigger product selection
    setTimeout(function() {
        var selector = document.getElementById('productSelector');
        if (selector) { selector.value = pid; if (typeof onProductSelect === 'function') onProductSelect(); }
    }, 200);
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('#npProductName') && !e.target.closest('#npProductSuggestions')) {
        var box = document.getElementById('npProductSuggestions');
        if (box) box.style.display = 'none';
    }
});
function escapeHtmlInline(s) {
    return String(s == null ? '' : s).replace(/[&<>"']/g, function(c) {
        return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];
    });
}

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

var epOriginalQty = 0;
function adjustQty(dir) {
    var adj = parseInt(document.getElementById('epAdjustQty').value) || 0;
    if (adj <= 0) return;
    var current = parseInt(document.getElementById('epQuantity').value) || 0;
    var newVal = current + (dir * adj);
    if (newVal < 0) newVal = 0;
    document.getElementById('epQuantity').value = newVal;
    document.getElementById('epQtyDisplay').textContent = newVal;
    document.getElementById('epAdjustQty').value = '';
    var hint = document.getElementById('epQtyHint');
    var diff = newVal - epOriginalQty;
    if (diff !== 0) {
        hint.style.display = '';
        if (diff > 0) {
            hint.style.background = '#d1fae5'; hint.style.color = '#059669';
            hint.textContent = '▲ +' + diff + ' from original (' + epOriginalQty + ')';
        } else {
            hint.style.background = '#fee2e2'; hint.style.color = '#dc2626';
            hint.textContent = '▼ ' + diff + ' from original (' + epOriginalQty + ')';
        }
    } else {
        hint.style.display = 'none';
    }
}

function toggleDropdown(dropdownId, show) {
    document.getElementById(dropdownId).classList.toggle('open', show);
}
function filterDropdown(dropdownId, query, selectId) {
    var dd = document.getElementById(dropdownId);
    dd.classList.add('open');
    var q = query.toLowerCase();
    dd.querySelectorAll('.search-option').forEach(function(opt) {
        var text = opt.getAttribute('data-text').toLowerCase();
        opt.classList.toggle('hidden', q.length > 0 && text.indexOf(q) === -1);
    });
}
function selectOption(selectId, value, text, inputId, dropdownId) {
    var sel = document.getElementById(selectId);
    sel.value = value;
    document.getElementById(inputId).value = text;
    document.getElementById(dropdownId).classList.remove('open');
}
document.addEventListener('click', function(e) {
    document.querySelectorAll('.search-dropdown.open').forEach(function(dd) {
        if (!dd.parentElement.contains(e.target)) dd.classList.remove('open');
    });
    ['new', 'exist'].forEach(function(prefix) {
        var panel = document.getElementById(prefix + 'DestPanel');
        var select = document.getElementById(prefix + 'DestSelect');
        if (panel && select && !select.contains(e.target)) {
            panel.classList.remove('open');
            document.getElementById(prefix + 'DestBtn').classList.remove('open');
        }
    });
});

function toggleDest(prefix) {
    var panel = document.getElementById(prefix + 'DestPanel');
    var btn = document.getElementById(prefix + 'DestBtn');
    var isOpen = panel.classList.contains('open');
    panel.classList.toggle('open', !isOpen);
    btn.classList.toggle('open', !isOpen);
    if (!isOpen) {
        var search = document.getElementById(prefix + 'DestSearch');
        search.value = '';
        filterDest(prefix, '');
        setTimeout(function() { search.focus(); }, 50);
    }
}

function filterDest(prefix, query) {
    var q = query.toLowerCase();
    document.getElementById(prefix + 'DestList').querySelectorAll('.dest-option').forEach(function(opt) {
        var text = opt.getAttribute('data-text').toLowerCase();
        opt.classList.toggle('hidden', q.length > 0 && text.indexOf(q) === -1);
    });
}

function pickDest(prefix, value, text, type) {
    document.getElementById(prefix + 'DestWarehouseId').value = value;
    document.getElementById(prefix + 'DestLabel').textContent = text;
    var icon = document.getElementById(prefix + 'DestBtn').querySelector('.dest-icon');
    icon.className = 'dest-icon ' + type;
    document.getElementById(prefix + 'DestPanel').classList.remove('open');
    document.getElementById(prefix + 'DestBtn').classList.remove('open');
    document.getElementById(prefix + 'DestList').querySelectorAll('.dest-option').forEach(function(opt) {
        opt.classList.toggle('selected', opt.getAttribute('data-value') === value);
    });
}

/* ─────────────── GST calc + last purchase price ─────────────── */
function calcEpGst() {
    var price = parseFloat(document.getElementById('epUnitPrice').value) || 0;
    var gst = parseFloat(document.getElementById('epGstPercent').value) || 0;
    var gstAmt = +(price * gst / 100).toFixed(2);
    var total = +(price + gstAmt).toFixed(2);
    document.getElementById('epAmountWithGst').value = total > 0 ? '₹ ' + total.toLocaleString('en-IN') : '';
    document.getElementById('epGstAmountHidden').value = gstAmt;
    document.getElementById('epTotalWithGstHidden').value = total;
}
function calcNpGst() {
    var price = parseFloat(document.getElementById('npUnitPrice').value) || 0;
    var gst = parseFloat(document.getElementById('npGstPercent').value) || 0;
    var gstAmt = +(price * gst / 100).toFixed(2);
    var total = +(price + gstAmt).toFixed(2);
    document.getElementById('npAmountWithGst').value = total > 0 ? '₹ ' + total.toLocaleString('en-IN') : '';
    document.getElementById('npGstAmountHidden').value = gstAmt;
    document.getElementById('npTotalWithGstHidden').value = total;
}

/* ─────────────── SERIAL NUMBER HANDLING ─────────────── */
function toggleNewProductSerials() {
    var checked = document.getElementById('npIsSerialTracked').checked;
    document.getElementById('npSerialSection').style.display = checked ? '' : 'none';
    if (checked) onSerialsTextChange('np');
}

function parseSerialsFromText(text) {
    if (!text) return [];
    // Split on ANY whitespace, comma, semicolon — gap = new serial
    var parts = text.split(/[\s,;]+/);
    var out = [];
    var seen = {};
    parts.forEach(function(l) {
        var t = l.trim();
        if (!t) return;
        if (seen[t.toUpperCase()]) return;
        seen[t.toUpperCase()] = true;
        out.push(t);
    });
    return out;
}

function findLocalDuplicates(text) {
    if (!text) return [];
    var parts = text.split(/[\s,;]+/).map(function(l){return l.trim();}).filter(Boolean);
    var seen = {}, dupes = [];
    parts.forEach(function(l) {
        var k = l.toUpperCase();
        if (seen[k]) dupes.push(l);
        else seen[k] = true;
    });
    return dupes;
}

function beautifySerialsText(prefix) {
    var textEl = document.getElementById(prefix + 'SerialsText');
    var serials = parseSerialsFromText(textEl.value);
    textEl.value = serials.join('\n');
    onSerialsTextChange(prefix);
}

var serialDupCheckTimer = null;
function onSerialsTextChange(prefix) {
    var textEl = document.getElementById(prefix + 'SerialsText');
    var summaryEl = document.getElementById(prefix + 'SerialSummary');
    var feedbackEl = document.getElementById(prefix + 'SerialFeedback');
    var text = textEl.value;

    var serials = parseSerialsFromText(text);
    var localDupes = findLocalDuplicates(text);

    summaryEl.textContent = serials.length + ' serial(s) detected';
    summaryEl.style.color = serials.length > 0 ? '#059669' : '#6b7280';

    var msgs = [];
    if (localDupes.length) {
        msgs.push('<span style="color:#dc2626;font-weight:600;">Duplicates in your input: ' + localDupes.slice(0,5).join(', ') + (localDupes.length > 5 ? ' +' + (localDupes.length-5) + ' more' : '') + '</span>');
    }
    feedbackEl.innerHTML = msgs.join('<br>');

    // Debounced DB duplicate check
    clearTimeout(serialDupCheckTimer);
    if (serials.length > 0) {
        serialDupCheckTimer = setTimeout(function() { checkSerialsAgainstDb(prefix, serials); }, 700);
    }
}

function checkSerialsAgainstDb(prefix, serials) {
    var feedbackEl = document.getElementById(prefix + 'SerialFeedback');
    var token = document.querySelector('meta[name="csrf-token"]');
    var csrf = token ? token.getAttribute('content') : '';
    fetch("{{ route('inventorySerialsCheckDuplicates') }}", {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},
        body: JSON.stringify({serials: serials})
    })
    .then(function(r){ return r.json(); })
    .then(function(data){
        if (data.duplicates && data.duplicates.length) {
            var current = feedbackEl.innerHTML;
            var dbDupeMsg = '<span style="color:#dc2626;font-weight:600;">Already in database: ' + data.duplicates.slice(0,5).join(', ') + (data.duplicates.length > 5 ? ' +' + (data.duplicates.length-5) + ' more' : '') + '</span>';
            feedbackEl.innerHTML = current ? current + '<br>' + dbDupeMsg : dbDupeMsg;
        }
    })
    .catch(function(){});
}

function handleSerialExcelUpload(prefix, input) {
    var file = input.files[0];
    if (!file) return;
    var feedbackEl = document.getElementById(prefix + 'SerialFeedback');
    feedbackEl.innerHTML = '<span style="color:#2563eb;">Parsing Excel...</span>';

    var fd = new FormData();
    fd.append('file', file);
    var token = document.querySelector('meta[name="csrf-token"]');
    fd.append('_token', token ? token.getAttribute('content') : '');

    fetch("{{ route('inventorySerialsParseExcel') }}", {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': token ? token.getAttribute('content') : '', 'Accept':'application/json'},
        body: fd
    })
    .then(function(r){ return r.json(); })
    .then(function(data){
        if (!data.success) { feedbackEl.innerHTML = '<span style="color:#dc2626;">' + (data.message || 'Failed to parse') + '</span>'; return; }
        if (!data.products || !data.products.length) { feedbackEl.innerHTML = '<span style="color:#dc2626;">No products detected in file.</span>'; return; }

        // Merge all serials from all product blocks
        var allSerials = [];
        var warnings = [];
        data.products.forEach(function(p) {
            (p.serials || []).forEach(function(s){ if (allSerials.indexOf(s) === -1) allSerials.push(s); });
            if (p.warnings && p.warnings.length) warnings = warnings.concat(p.warnings);
        });

        document.getElementById(prefix + 'SerialsText').value = allSerials.join('\n');
        onSerialsTextChange(prefix);

        var msg = '<span style="color:#059669;font-weight:600;">Parsed ' + data.products.length + ' product block(s), ' + allSerials.length + ' serial(s).</span>';
        if (data.products.length > 1) msg += '<br><span style="color:#b45309;">Note: Multiple product blocks found - all serials merged into current selection.</span>';
        if (warnings.length) msg += '<br><span style="color:#b45309;">' + warnings.slice(0,3).join(' | ') + '</span>';
        feedbackEl.innerHTML = msg;
        input.value = '';
    })
    .catch(function(){
        feedbackEl.innerHTML = '<span style="color:#dc2626;">Upload failed.</span>';
    });
}

// Recalculate on qty changes
document.addEventListener('DOMContentLoaded', function() {
    var qtyInput = document.getElementById('npQuantity');
    if (qtyInput) qtyInput.addEventListener('input', function() { if (document.getElementById('npSerialSection').style.display !== 'none') onSerialsTextChange('np'); });
    var epQtyBtns = document.querySelectorAll('.qty-adj-btn');
    epQtyBtns.forEach(function(b) { b.addEventListener('click', function() { setTimeout(function(){ if (document.getElementById('epSerialSection').style.display !== 'none') onSerialsTextChange('ep'); }, 100); }); });
});

/* ═══════════════════════════════════════════════════════════
   BULK SERIAL UPLOAD
   ═══════════════════════════════════════════════════════════ */

var bulkCards = []; // Array of {idx, product_name, serials, skipped, product_id, category_id, sub_category_id, unit_price, gst_percent}
var bulkCardIdxSeq = 0;
var allCategoriesForBulk = @json($categories->map(fn($c) => ['id' => $c->id, 'name' => $c->category_name, 'subs' => $c->subCategories->map(fn($s) => ['id' => $s->id, 'name' => $s->sub_category_name])->values()])->values());

function handleBulkExcelUpload(input) {
    var file = input.files[0];
    if (!file) return;
    var status = document.getElementById('bulkParseStatus');
    status.innerHTML = '<span style="color:#2563eb;">Parsing Excel...</span>';

    var fd = new FormData();
    fd.append('file', file);
    var token = document.querySelector('meta[name="csrf-token"]');

    fetch("{{ route('inventorySerialsParseExcel') }}", {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': token ? token.getAttribute('content') : '', 'Accept': 'application/json'},
        body: fd
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) { status.innerHTML = '<span style="color:#dc2626;">' + (data.message || 'Failed to parse') + '</span>'; return; }
        if (!data.products || !data.products.length) { status.innerHTML = '<span style="color:#dc2626;">No products detected in file.</span>'; return; }

        data.products.forEach(function(p) {
            addBulkCard({
                product_name: p.product_name || '',
                serials: p.serials || [],
                skipped_text: p.skipped_text || '',
                warnings: p.warnings || [],
                db_duplicates: p.db_duplicates || [],
                suggested_product: p.suggested_product || null
            });
        });
        status.innerHTML = '<span style="color:#059669;font-weight:600;">Parsed ' + data.products.length + ' product block(s) - review each card below.</span>';
        input.value = '';
    })
    .catch(err => {
        status.innerHTML = '<span style="color:#dc2626;">Upload failed: ' + err.message + '</span>';
    });
}

function addManualBulkCard() {
    addBulkCard({ product_name: '', serials: [], skipped_text: '', warnings: [], suggested_product: null });
}

function addBulkCard(data) {
    var idx = bulkCardIdxSeq++;
    var card = {
        idx: idx,
        product_name: data.product_name || '',
        serials: data.serials || [],
        skipped_text: data.skipped_text || '',
        warnings: data.warnings || [],
        db_duplicates: data.db_duplicates || [],
        suggested_product: data.suggested_product || null,
        product_id: data.suggested_product ? data.suggested_product.id : null,
        category_id: data.suggested_product ? data.suggested_product.category_id : '',
        sub_category_id: data.suggested_product ? data.suggested_product.sub_category_id : '',
        unit_price: '',
        gst_percent: ''
    };
    bulkCards.push(card);
    renderBulkCards();
}

function removeBulkCard(idx) {
    bulkCards = bulkCards.filter(c => c.idx !== idx);
    renderBulkCards();
}

function clearBulkCards() {
    bulkCards = [];
    renderBulkCards();
    document.getElementById('bulkParseStatus').innerHTML = '';
}

function updateBulkField(idx, field, value) {
    var c = bulkCards.find(x => x.idx === idx);
    if (!c) return;
    c[field] = value;
    if (field === 'category_id') {
        c.sub_category_id = '';
        renderBulkCards();
    }
    if (field === 'serials_text') {
        c.serials = parseSerialsFromText(value);
        renderBulkCardStats(idx);
    }
}

function renderBulkCards() {
    var wrap = document.getElementById('bulkProductCards');
    document.getElementById('bulkSubmitWrap').hidden = bulkCards.length === 0;
    if (bulkCards.length === 0) {
        wrap.innerHTML = '';
        return;
    }
    wrap.innerHTML = bulkCards.map(function(c) {
        var subOptions = '<option value="">Select</option>';
        if (c.category_id) {
            var cat = allCategoriesForBulk.find(x => x.id == c.category_id);
            if (cat) subOptions = '<option value="">Select</option>' + cat.subs.map(s =>
                '<option value="' + s.id + '"' + (c.sub_category_id == s.id ? ' selected' : '') + '>' + escapeHtmlBulk(s.name) + '</option>'
            ).join('');
        }
        var catOptions = '<option value="">Select</option>' + allCategoriesForBulk.map(cat =>
            '<option value="' + cat.id + '"' + (c.category_id == cat.id ? ' selected' : '') + '>' + escapeHtmlBulk(cat.name) + '</option>'
        ).join('');

        var warnHtml = '';
        if (c.warnings && c.warnings.length) {
            warnHtml = '<div style="background:#fef3c7;border:1px solid #fde68a;color:#92400e;padding:8px 12px;border-radius:6px;font-size:.78rem;margin-bottom:10px;">'
                + c.warnings.map(w => '<div><i class="fas fa-exclamation-triangle me-1"></i>' + escapeHtmlBulk(w) + '</div>').join('')
                + '</div>';
        }
        var dupHtml = '';
        if (c.db_duplicates && c.db_duplicates.length) {
            dupHtml = '<div style="background:#fee2e2;border:1px solid #fecaca;color:#991b1b;padding:8px 12px;border-radius:6px;font-size:.78rem;margin-bottom:10px;">'
                + '<i class="fas fa-times-circle me-1"></i> Serials already in DB: ' + c.db_duplicates.slice(0,5).join(', ')
                + (c.db_duplicates.length > 5 ? ' +' + (c.db_duplicates.length-5) + ' more' : '')
                + '</div>';
        }
        var skippedHtml = '';
        if (c.skipped_text) {
            skippedHtml = '<div style="background:#fff7ed;border:1px solid #fed7aa;color:#9a3412;padding:8px 12px;border-radius:6px;font-size:.78rem;margin-bottom:10px;">'
                + '<strong><i class="fas fa-info-circle me-1"></i> Skipped after line gap (not counted):</strong>'
                + '<pre style="margin:6px 0 0;font-size:.72rem;white-space:pre-wrap;font-family:monospace;color:#78350f;">' + escapeHtmlBulk(c.skipped_text) + '</pre>'
                + '<div style="margin-top:6px;font-style:italic;">If any of these should be included, copy and paste into the Serial Numbers box above.</div>'
                + '</div>';
        }
        var suggHtml = '';
        if (c.suggested_product) {
            suggHtml = '<div style="background:#ecfdf5;border:1px solid #86efac;color:#065f46;padding:6px 10px;border-radius:6px;font-size:.75rem;margin-bottom:8px;">'
                + '<i class="fas fa-lightbulb me-1"></i> Matched existing product: <strong>' + escapeHtmlBulk(c.suggested_product.item_name) + '</strong> (code: ' + escapeHtmlBulk(c.suggested_product.item_code || '-') + ')'
                + '</div>';
        }

        return '<div class="sec-card" style="border-left:4px solid #3b82f6; position:relative;">'
             + '<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">'
             + '  <p class="sec-label" style="margin:0;">Product #' + (bulkCards.indexOf(c)+1) + '</p>'
             + '  <button type="button" onclick="removeBulkCard(' + c.idx + ')" style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;padding:5px 12px;border-radius:6px;font-size:.78rem;font-weight:600;cursor:pointer;"><i class="fas fa-trash me-1"></i>Remove</button>'
             + '</div>'
             + suggHtml
             + warnHtml
             + dupHtml
             + '<div class="row g-3">'
             + '  <div class="col-12">'
             + '    <label class="form-label">Product Name <span class="req">*</span> <small style="color:var(--txt2); font-weight:400;">(auto from Excel - editable)</small></label>'
             + '    <input type="text" class="form-control" value="' + escapeAttrBulk(c.product_name) + '" oninput="updateBulkField(' + c.idx + ', \'product_name\', this.value)">'
             + '  </div>'
             + '  <div class="col-md-6">'
             + '    <label class="form-label">Category <span class="req">*</span></label>'
             + '    <select class="form-select" onchange="updateBulkField(' + c.idx + ', \'category_id\', this.value)">' + catOptions + '</select>'
             + '  </div>'
             + '  <div class="col-md-6">'
             + '    <label class="form-label">Sub Category</label>'
             + '    <select class="form-select" onchange="updateBulkField(' + c.idx + ', \'sub_category_id\', this.value)">' + subOptions + '</select>'
             + '  </div>'
             + '  <div class="col-md-4">'
             + '    <label class="form-label">Unit Price (Purchase)</label>'
             + '    <input type="number" step="0.01" min="0" class="form-control" value="' + (c.unit_price || '') + '" oninput="updateBulkField(' + c.idx + ', \'unit_price\', this.value); calcBulkGst(' + c.idx + ')">'
             + '  </div>'
             + '  <div class="col-md-4">'
             + '    <label class="form-label">GST %</label>'
             + '    <input type="number" step="0.01" min="0" max="100" class="form-control" value="' + (c.gst_percent || '') + '" oninput="updateBulkField(' + c.idx + ', \'gst_percent\', this.value); calcBulkGst(' + c.idx + ')">'
             + '  </div>'
             + '  <div class="col-md-4">'
             + '    <label class="form-label">Amount with GST (per unit)</label>'
             + '    <input type="text" class="form-control" readonly id="bulkAmtGst_' + c.idx + '" style="background:#f0fdf4;color:#065f46;font-weight:600;" placeholder="Auto">'
             + '  </div>'
             + '  <div class="col-12">'
             + '    <label class="form-label">Serial Numbers <span class="serial-count-badge" id="bulkSerCnt_' + c.idx + '" style="background:#dbeafe;color:#1e40af;padding:2px 10px;border-radius:12px;font-size:.72rem;font-weight:600;margin-left:8px;">' + c.serials.length + ' serials</span></label>'
             + '    <div style="display:flex; gap:6px; margin-bottom:6px;">'
             + '      <button type="button" onclick="beautifyBulkSerials(' + c.idx + ')" style="padding:6px 12px;background:#fef3c7;color:#92400e;border:1px solid #fde68a;border-radius:6px;font-size:.78rem;font-weight:600;cursor:pointer;"><i class="fas fa-magic me-1"></i>Beautify</button>'
             + '    </div>'
             + '    <textarea rows="5" class="form-control" id="bulkSerText_' + c.idx + '" oninput="updateBulkField(' + c.idx + ', \'serials_text\', this.value)" style="font-family:monospace; font-size:.82rem;" placeholder="Space, comma or new-line separated serials...">' + escapeHtmlBulk(c.serials.join('\n')) + '</textarea>'
             + '  </div>'
             + '</div>'
             + skippedHtml
             + '</div>';
    }).join('');

    // Compute GST after render
    bulkCards.forEach(c => calcBulkGst(c.idx));
}

function renderBulkCardStats(idx) {
    var c = bulkCards.find(x => x.idx === idx);
    if (!c) return;
    var badge = document.getElementById('bulkSerCnt_' + idx);
    if (badge) badge.textContent = c.serials.length + ' serials';
}

function calcBulkGst(idx) {
    var c = bulkCards.find(x => x.idx === idx);
    if (!c) return;
    var price = parseFloat(c.unit_price) || 0;
    var gst = parseFloat(c.gst_percent) || 0;
    var total = +(price + (price * gst / 100)).toFixed(2);
    var el = document.getElementById('bulkAmtGst_' + idx);
    if (el) el.value = total > 0 ? '₹ ' + total.toLocaleString('en-IN') : '';
}

function beautifyBulkSerials(idx) {
    var c = bulkCards.find(x => x.idx === idx);
    if (!c) return;
    var textEl = document.getElementById('bulkSerText_' + idx);
    var serials = parseSerialsFromText(textEl.value);
    c.serials = serials;
    textEl.value = serials.join('\n');
    renderBulkCardStats(idx);
}

function submitBulkUpload() {
    if (bulkCards.length === 0) { alert('No products to save.'); return; }

    // Validate all
    for (var i = 0; i < bulkCards.length; i++) {
        var c = bulkCards[i];
        if (!c.product_name.trim()) { alert('Product #' + (i+1) + ': product name is required.'); return; }
        if (!c.category_id) { alert('Product #' + (i+1) + ' ("' + c.product_name + '"): category is required.'); return; }
        if (c.serials.length === 0) { alert('Product #' + (i+1) + ' ("' + c.product_name + '"): at least one serial number required.'); return; }
    }

    var payload = {
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        destination_type: document.getElementById('bulkDestination').value ? 'warehouse' : 'main',
        destination_warehouse_id: document.getElementById('bulkDestination').value || null,
        supplier_name: document.getElementById('bulkSupplierName').value,
        invoice_number: document.getElementById('bulkInvoiceNumber').value,
        invoice_date: document.getElementById('bulkInvoiceDate').value,
        products: bulkCards.map(c => ({
            product_id: c.product_id || null,
            item_name: c.product_name,
            category_id: c.category_id,
            sub_category_id: c.sub_category_id || null,
            qty: c.serials.length,
            unit_price: c.unit_price || null,
            gst_percent: c.gst_percent || null,
            serials: c.serials
        }))
    };

    var btn = document.getElementById('bulkSubmitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';

    fetch("{{ route('inventorySerialsBulkStore') }}", {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':payload._token,'Accept':'application/json'},
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check me-1"></i> Save All Products';
        if (data.success) {
            alert('Success! ' + (data.results ? data.results.length : bulkCards.length) + ' products saved.');
            window.location.href = "{{ route('inventoryEntries') }}";
        } else {
            alert(data.message || 'Failed to save.');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check me-1"></i> Save All Products';
        alert('Save failed: ' + err.message);
    });
}

function escapeHtmlBulk(s) {
    return String(s == null ? '' : s).replace(/[&<>"']/g, function(c) {
        return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];
    });
}
function escapeAttrBulk(s) {
    return String(s == null ? '' : s).replace(/"/g, '&quot;').replace(/</g, '&lt;');
}
</script>
@endsection
