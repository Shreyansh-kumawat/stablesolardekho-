@extends('layouts.adminLayout')

@section('css')
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/select2.min.css" rel="stylesheet" />
    <style>
        :root { --primary-blue: #4A90E2; --primary-light: #f5f7fa; --text-primary: #2d3436; --text-secondary: #636e72; --border-color: #e1e8ed; --card-bg: #ffffff; }
        body { background: var(--primary-light); color: var(--text-primary); }
        .page-header { background: #ffffff; padding: 1.5rem 0; margin-bottom: 1.5rem; border-radius: 8px; border: 1px solid var(--border-color); box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .page-header h1 { color: var(--text-primary); font-weight: 600; margin: 0; font-size: 1.25rem; }
        .page-header p { color: var(--text-secondary); margin: 0.35rem 0 0 0; font-size: 0.9rem; }
        .card { border: 1px solid var(--border-color); border-radius: 8px; background: var(--card-bg); box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .card-body { padding: 1.5rem; }
        .btn-primary { background: var(--primary-blue); border: 1px solid var(--primary-blue); color: #fff; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; }
        .btn-primary:hover { background: #3b7dc4; border-color: #3b7dc4; color: #fff; }
        .btn-secondary { padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; border: 1px solid var(--border-color); background: #fff; color: var(--text-primary); }
        .form-control, .form-select { border-radius: 6px; border: 1px solid var(--border-color); padding: 0.55rem 0.75rem; font-size: 0.9rem; }
        .form-control:focus, .form-select:focus { box-shadow: 0 0 0 0.2rem rgba(74,144,226,0.15); border-color: var(--primary-blue); }
        .form-label { font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem; font-size: 0.85rem; }
        .text-muted-custom { color: var(--text-secondary); font-size: 0.85rem; }
        .select2-container { width: 100% !important; }
        .select2-container--default .select2-selection--single { height: 38px !important; border: 1px solid var(--border-color) !important; border-radius: 6px !important; padding: 0.35rem 0.75rem !important; display: flex !important; align-items: center !important; background: #fff !important; }
        .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 1.5 !important; padding-left: 0 !important; color: var(--text-primary) !important; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px !important; right: 6px !important; }
        .arrow-icon { display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: var(--primary-blue); font-weight: 700; }

        .wh-prod-panel { background: #fff; border: 1px solid var(--border-color); border-radius: 8px; margin: 8px 0 16px; overflow: hidden; }
        .wh-prod-header { padding: 10px 14px; background: #f8fbff; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
        .wh-prod-header .title { font-weight: 700; font-size: 0.85rem; color: var(--text-primary); }
        .wh-prod-header .count { font-size: 0.75rem; color: var(--text-secondary); font-weight: 600; background: #eef3ff; padding: 2px 8px; border-radius: 10px; }
        .wh-prod-search { border: 1px solid var(--border-color); padding: 5px 10px; border-radius: 6px; font-size: 0.8rem; min-width: 200px; outline: none; }
        .wh-prod-search:focus { border-color: var(--primary-blue); }
        .wh-prod-scroll { max-height: 260px; overflow-y: auto; }
        .wh-prod-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
        .wh-prod-table thead th { background: #f5f7fa; padding: 8px 12px; text-align: left; font-weight: 700; color: var(--text-secondary); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.04em; border-bottom: 1px solid var(--border-color); position: sticky; top: 0; z-index: 2; }
        .wh-prod-table tbody td { padding: 8px 12px; border-bottom: 1px solid #f1f3f5; vertical-align: middle; }
        .wh-prod-table tbody tr:hover { background: #f8fbff; }
        .wh-prod-table tbody tr.selected { background: #eef3ff; }
        .wh-prod-table tbody tr.hidden { display: none; }
        .wh-qty-badge { display: inline-block; padding: 2px 8px; border-radius: 10px; background: #d3f9d8; color: #2b8a3e; font-weight: 700; font-size: 0.75rem; }
        .wh-qty-badge.low { background: #fff3cd; color: #b8860b; }
        .wh-select-btn { background: var(--primary-blue); color: #fff; border: none; padding: 4px 10px; border-radius: 5px; font-size: 0.72rem; font-weight: 600; cursor: pointer; }
        .wh-select-btn:hover { background: #3b7dc4; }
        .wh-select-btn.picked { background: #2b8a3e; }
        .wh-prod-empty { padding: 24px; text-align: center; color: var(--text-secondary); font-size: 0.85rem; }
        .wh-prod-loading { padding: 20px; text-align: center; color: var(--text-secondary); font-size: 0.85rem; }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="container-fluid">
            <h1><i class="fas fa-exchange-alt me-2"></i>Warehouse to Warehouse Transfer</h1>
            <p>Transfer products between warehouses</p>
        </div>
    </div>

    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size:0.88rem;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size:0.88rem;">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form id="w2wForm" action="{{ route('admin.warehouses.storeW2wTransfer') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label">From Warehouse *</label>
                            <select id="from_warehouse_id" name="from_warehouse_id" class="form-select select2" required>
                                <option value="">Select Source</option>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->id }}">{{ $wh->name }}{{ $wh->city ? ' ('.$wh->city.')' : '' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <div class="arrow-icon w-100 text-center pb-2">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label">To Warehouse *</label>
                            <select id="to_warehouse_id" name="to_warehouse_id" class="form-select select2" required>
                                <option value="">Select Destination</option>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->id }}">{{ $wh->name }}{{ $wh->city ? ' ('.$wh->city.')' : '' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12" id="whProdPanelWrap" style="display:none;">
                            <div class="wh-prod-panel">
                                <div class="wh-prod-header">
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <span class="title"><i class="fas fa-boxes me-1" style="color:var(--primary-blue);"></i> Products in Source Warehouse</span>
                                        <span class="count" id="whProdCount">0 items</span>
                                    </div>
                                    <input type="text" class="wh-prod-search" id="whProdSearch" placeholder="Search by name, code, category...">
                                </div>
                                <div class="wh-prod-scroll">
                                    <table class="wh-prod-table" id="whProdTable">
                                        <thead>
                                            <tr>
                                                <th style="width:32%;">Product</th>
                                                <th style="width:20%;">Category</th>
                                                <th style="width:20%;">Sub Category</th>
                                                <th style="width:14%;">Available Qty</th>
                                                <th style="width:14%; text-align:right;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="whProdTbody"></tbody>
                                    </table>
                                    <div id="whProdEmpty" class="wh-prod-empty" style="display:none;">No products in this warehouse.</div>
                                    <div id="whProdLoading" class="wh-prod-loading" style="display:none;"><i class="fas fa-spinner fa-spin me-1"></i> Loading products...</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Category *</label>
                            <select id="category_id" name="category_id" class="form-select select2" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Sub Category *</label>
                            <select id="sub_category_id" name="sub_category_id" class="form-select select2" required>
                                <option value="">Select Sub Category</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Product *</label>
                            <select id="product_id" name="product_id" class="form-select select2" required>
                                <option value="">Select Product</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Unit Price</label>
                            <input type="number" step="0.01" min="0" id="unit_price" name="unit_price" class="form-control" placeholder="Auto-filled from product">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Quantity *</label>
                            <input type="number" min="1" id="quantity" name="quantity" class="form-control" placeholder="Enter quantity" required>
                            <small class="text-muted-custom" id="available_qty_hint"></small>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Remarks</label>
                            <input type="text" name="remarks" class="form-control" placeholder="Optional remarks">
                        </div>

                        <div class="col-12" id="w2wSerialWrap" style="display:none;">
                            <label class="form-label">
                                Serial Numbers <span style="color:#dc2626;">*</span>
                                <small style="font-weight:400; color:#6b7280;">(serial-tracked product)</small>
                            </label>
                            <div style="background:#f0f9ff; border:1px solid #bae6fd; border-radius:8px; padding:12px;">
                                <div style="display:flex; gap:8px; margin-bottom:10px; flex-wrap:wrap; align-items:center;">
                                    <button type="button" class="btn btn-sm btn-primary" onclick="w2wAutoPick()">Auto-Pick First N</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="w2wClearPicks()">Clear All</button>
                                    <span id="w2wSerialSummary" style="font-size:.85rem; color:#374151; font-weight:600;">0 selected</span>
                                </div>
                                <input type="text" id="w2wSerialSearch" class="form-control mb-2" placeholder="Search serials..." oninput="w2wRenderList()">
                                <div id="w2wSerialList" style="max-height:220px; overflow-y:auto; background:#fff; border:1px solid #e5e7eb; border-radius:6px; padding:6px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-exchange-alt me-1"></i> Transfer Between Warehouses</button>
                        <button type="reset" class="btn btn-secondary">Clear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/js/select2.min.js"></script>
    <script>
        $(function () {
            $('.select2').select2({ width: '100%' });

            const subCategoryUrl = "{{ route('getSubCategory') }}";
            const productUrl = "{{ route('getProducts') }}";
            const getWhQtyUrl = "{{ route('admin.warehouses.getWarehouseProductQty') }}";

            let availableQty = null;

            $('#category_id').on('change', function () {
                $('#sub_category_id').empty().append('<option value="">Select Sub Category</option>');
                $('#product_id').empty().append('<option value="">Select Product</option>');
                resetQty();
                if (!$(this).val()) return;

                $.get(subCategoryUrl, { category_id: $(this).val() }, function (data) {
                    data.forEach(item => {
                        $('#sub_category_id').append('<option value="' + item.id + '">' + item.sub_category_name + '</option>');
                    });
                    $('#sub_category_id').trigger('change.select2');
                });
            });

            $('#sub_category_id').on('change', function () {
                $('#product_id').empty().append('<option value="">Select Product</option>');
                resetQty();
                if (!$(this).val()) return;

                $.get(productUrl, { sub_category_id: $(this).val() }, function (data) {
                    data.forEach(item => {
                        var price = item.current_sale_price || item.sale_price || 0;
                        $('#product_id').append('<option value="' + item.id + '" data-price="' + price + '">' + item.item_name + '</option>');
                    });
                    $('#product_id').trigger('change.select2');
                });
            });

            $('#product_id').on('change', function () {
                var price = $(this).find('option:selected').data('price') || '';
                $('#unit_price').val(price);
                fetchWarehouseQty();
                loadW2wSerials();
            });

            var w2wAvailableSerials = [];
            var w2wSelected = new Set();
            function loadW2wSerials() {
                var pid = $('#product_id').val();
                var whId = $('#from_warehouse_id').val();
                $('#w2wSerialWrap').hide();
                w2wAvailableSerials = [];
                w2wSelected = new Set();
                if (!pid || !whId) return;
                $.get("{{ route('admin.warehouses.getAvailableSerials') }}", { product_id: pid, location: 'warehouse', warehouse_id: whId }, function(data) {
                    if (data && data.is_serial_tracked) {
                        w2wAvailableSerials = data.serials || [];
                        $('#w2wSerialWrap').show();
                        w2wRenderList();
                    }
                });
            }
            window.w2wRenderList = function() {
                var q = ($('#w2wSerialSearch').val() || '').toLowerCase();
                var filtered = w2wAvailableSerials.filter(function(s) { return !q || s.toLowerCase().indexOf(q) !== -1; });
                var html = '';
                filtered.forEach(function(sn) {
                    var checked = w2wSelected.has(sn) ? 'checked' : '';
                    html += '<label style="display:flex; align-items:center; gap:8px; padding:5px 8px; border-bottom:1px solid #f3f4f6; cursor:pointer; font-family:monospace; font-size:.82rem;">'
                          + '<input type="checkbox" ' + checked + ' value="' + sn + '" onchange="w2wToggleSerial(this)"> ' + sn
                          + '</label>';
                });
                if (!filtered.length) html = '<div style="padding:10px; text-align:center; color:#94a3b8; font-size:.85rem;">No matching serials</div>';
                $('#w2wSerialList').html(html);
                w2wSync();
            };
            window.w2wToggleSerial = function(cb) {
                if (cb.checked) w2wSelected.add(cb.value); else w2wSelected.delete(cb.value);
                w2wSync();
            };
            window.w2wAutoPick = function() {
                var qty = parseInt($('#quantity').val(), 10) || 0;
                if (qty <= 0) { alert('Enter quantity first.'); return; }
                w2wSelected = new Set(w2wAvailableSerials.slice(0, qty));
                w2wRenderList();
            };
            window.w2wClearPicks = function() {
                w2wSelected = new Set();
                w2wRenderList();
            };
            function w2wSync() {
                $('#w2wForm input[name="serial_numbers[]"]').remove();
                w2wSelected.forEach(function(sn) {
                    $('#w2wForm').append('<input type="hidden" name="serial_numbers[]" value="' + sn + '">');
                });
                var qty = parseInt($('#quantity').val(), 10) || 0;
                $('#w2wSerialSummary').text(w2wSelected.size + ' selected' + (qty > 0 ? ' / ' + qty + ' needed' : ''))
                    .css('color', (qty > 0 && w2wSelected.size === qty) ? '#059669' : '#374151');
            }
            $('#quantity').on('input', w2wSync);

            const getWhProductsUrl = "{{ route('admin.warehouses.getWarehouseProducts') }}";
            let whProductsCache = [];

            $('#from_warehouse_id').on('change', function () {
                loadWarehouseProducts($(this).val());
                fetchWarehouseQty();
            });

            function loadWarehouseProducts(whId) {
                var $wrap = $('#whProdPanelWrap');
                var $body = $('#whProdTbody');
                var $empty = $('#whProdEmpty');
                var $loading = $('#whProdLoading');
                var $count = $('#whProdCount');

                if (!whId) { $wrap.hide(); return; }
                $wrap.show();
                $body.empty(); $empty.hide(); $loading.show(); $count.text('0 items');

                $.get(getWhProductsUrl, { warehouse_id: whId }, function (data) {
                    $loading.hide();
                    whProductsCache = data || [];
                    $count.text(whProductsCache.length + ' item' + (whProductsCache.length === 1 ? '' : 's'));

                    if (whProductsCache.length === 0) { $empty.show(); return; }

                    var html = '';
                    whProductsCache.forEach(function (p) {
                        var qtyCls = p.available_qty <= 5 ? 'low' : '';
                        html += '<tr data-pid="' + p.product_id + '" data-cat="' + (p.category_id || '') + '" data-sub="' + (p.sub_category_id || '') + '" data-price="' + (p.current_sale_price || 0) + '" data-qty="' + p.available_qty + '" data-name="' + escapeAttr(p.item_name) + '" data-code="' + escapeAttr(p.item_code || '') + '" data-catname="' + escapeAttr(p.category_name || '') + '" data-subname="' + escapeAttr(p.sub_category_name || '') + '">'
                            + '<td><strong>' + escapeHtml(p.item_name) + '</strong>' + (p.item_code ? ' <span style="color:#868e96; font-size:.72rem;">(' + escapeHtml(p.item_code) + ')</span>' : '') + '</td>'
                            + '<td>' + escapeHtml(p.category_name || '-') + '</td>'
                            + '<td>' + escapeHtml(p.sub_category_name || '-') + '</td>'
                            + '<td><span class="wh-qty-badge ' + qtyCls + '">' + p.available_qty + '</span></td>'
                            + '<td style="text-align:right;"><button type="button" class="wh-select-btn" onclick="pickWhProduct(this)"><i class="fas fa-check me-1"></i> Select</button></td>'
                            + '</tr>';
                    });
                    $body.html(html);
                });
            }

            $('#whProdSearch').on('input', function () {
                var q = $(this).val().toLowerCase().trim();
                $('#whProdTbody tr').each(function () {
                    var text = ($(this).text() || '').toLowerCase();
                    $(this).toggleClass('hidden', q.length > 0 && text.indexOf(q) === -1);
                });
            });

            window.pickWhProduct = function (btn) {
                var $tr = $(btn).closest('tr');
                var pid = $tr.data('pid');
                var cat = String($tr.data('cat') || '');
                var sub = String($tr.data('sub') || '');
                var price = $tr.data('price');
                var qty = parseInt($tr.data('qty'), 10) || 0;
                var name = $tr.data('name');

                $('#whProdTbody tr').removeClass('selected');
                $('#whProdTbody .wh-select-btn').removeClass('picked').html('<i class="fas fa-check me-1"></i> Select');
                $tr.addClass('selected');
                $(btn).addClass('picked').html('<i class="fas fa-check-circle me-1"></i> Picked');

                if (cat) {
                    if ($('#category_id option[value="' + cat + '"]').length === 0) {
                        $('#category_id').append('<option value="' + cat + '">' + $tr.data('catname') + '</option>');
                    }
                    $('#category_id').val(cat).trigger('change.select2');
                }

                $('#sub_category_id').empty().append('<option value="">Select Sub Category</option>');
                if (sub) {
                    $('#sub_category_id').append('<option value="' + sub + '" selected>' + $tr.data('subname') + '</option>').trigger('change.select2');
                }

                $('#product_id').empty().append('<option value="">Select Product</option>')
                    .append('<option value="' + pid + '" data-price="' + price + '" selected>' + name + '</option>')
                    .trigger('change'); // fires both select2 and regular change (loads serial picker)

                $('#unit_price').val(price || '');
                availableQty = qty;
                $('#available_qty_hint').text('Available in source warehouse: ' + qty);
                $('#quantity').attr('max', qty).val('').focus();
            };

            function escapeHtml(s) { return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) { return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]; }); }
            function escapeAttr(s) { return String(s == null ? '' : s).replace(/"/g, '&quot;'); }

            function fetchWarehouseQty() {
                var whId = $('#from_warehouse_id').val();
                var prodId = $('#product_id').val();
                if (!whId || !prodId) { resetQty(); return; }

                $.get(getWhQtyUrl, { warehouse_id: whId, product_id: prodId }, function (data) {
                    availableQty = parseInt(data?.available_qty ?? 0, 10) || 0;
                    $('#available_qty_hint').text('Available in source warehouse: ' + availableQty);
                    if (availableQty > 0) $('#quantity').attr('max', availableQty);
                });
            }

            $('#quantity').on('input', function () {
                var qty = parseInt($(this).val(), 10) || 0;
                if (availableQty !== null && qty > availableQty) {
                    this.setCustomValidity('Quantity exceeds available stock in source warehouse.');
                    $(this).addClass('is-invalid');
                } else {
                    this.setCustomValidity('');
                    $(this).removeClass('is-invalid');
                }
            });

            function resetQty() {
                $('#quantity').val('').removeAttr('max');
                $('#available_qty_hint').text('');
                availableQty = null;
            }
        });
    </script>
@endsection
