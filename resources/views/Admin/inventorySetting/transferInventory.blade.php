@extends('layouts.adminLayout')

@section('css')
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="/assets/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link href="/assets/css/select2.min.css" rel="stylesheet" />
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

        .dt-button {
            background: var(--primary-blue) !important;
            border: 1px solid var(--primary-blue) !important;
            border-radius: 6px !important;
            padding: 0.45rem 0.8rem !important;
            font-weight: 600 !important;
            color: #fff !important;
            font-size: 0.8rem !important;
            box-shadow: none !important;
        }

        .dt-button:hover {
            background: #3b7dc4 !important;
            border-color: #3b7dc4 !important;
        }

        .badge-yes {
            background: #e7f5ff;
            color: #1c7ed6;
            padding: 0.35rem 0.7rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .badge-no {
            background: #fff5f5;
            color: #c92a2a;
            padding: 0.35rem 0.7rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .text-muted-custom {
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        .modal-content.glass-modal {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .modal-header {
            background: #f8f9fa;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.25rem;
        }

        .modal-title {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 1rem;
        }

        .modal-footer {
            background: #f8f9fa;
            border-top: 1px solid var(--border-color);
            padding: 1rem 1.25rem;
        }

        .empty-state {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 2rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
            opacity: 0.6;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }

            .table {
                font-size: 0.85rem;
            }
        }
    </style>

    <style>
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 38px !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 6px !important;
            padding: 0.35rem 0.75rem !important;
            display: flex !important;
            align-items: center !important;
            background: #fff !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.5 !important;
            padding-left: 0 !important;
            color: var(--text-primary) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
            right: 6px !important;
        }

        .select2-container--default .select2-selection--single.is-invalid {
            border-color: #dc3545 !important;
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container-fluid">
            <h1><i class="fas fa-box me-2"></i>Transfer Inventory</h1>
            <p>Transfer products to another location or partner or user</p>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form id="inventoryForm" action="{{ route('storeTransferInventory') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Category</label>
                            <select id="category_id" name="category_id" class="form-select select2" required>
                                <option value="">Select Category</option>
                                @foreach(($categories ?? []) as $category)
                                    <option value="{{ data_get($category, 'id', '') }}">
                                        {{ data_get($category, 'category_name', '') }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Category is required.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Sub Category</label>
                            <select id="sub_category_id" name="sub_category_id" class="form-select select2" required>
                                <option value="">Select Sub Category</option>
                            </select>
                            <div class="invalid-feedback">Sub Category is required.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Product</label>
                            <select id="product_id" name="product_id" class="form-select select2" required>
                                <option value="">Select Product</option>
                            </select>
                            <div class="invalid-feedback">Product is required.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Transfer - Sold To</label>
                            <select id="sold_type" name="sold_type" class="form-select select2" required>
                                <option value="">Select Transfer Type</option>
                                @foreach(($cp_roles ?? []) as $role)
                                    <option value="{{ data_get($role, 'id', '') }}">
                                        {{ data_get($role, 'role_name', data_get($role, 'role_name', '')) }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Select Type is Required.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Transfer - Sold To</label>
                            <select name="sold_to" id="sold_to" class="form-select select2" required></select>
                            <div class="invalid-feedback">Select Type is Required.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Sale Price (Unit Price)</label>
                            <input type="number" step="0.01" min="0" id="unit_price" name="unit_price" class="form-control"
                                placeholder="Enter unit price" required>
                            <div class="invalid-feedback">Unit price is required.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Invoice Number</label>
                            <input type="text" id="invoice_number" name="invoice_number" class="form-control"
                                placeholder="Enter invoice number" required>
                            <div class="invalid-feedback">Invoice number is required.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Invoice Date</label>
                            <input type="date" id="invoice_date" name="invoice_date" class="form-control" required>
                            <div class="invalid-feedback">Invoice date is required.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">UOM</label>
                            <input type="text" id="uom" class="form-control" readonly>
                            <input type="hidden" id="uom_hidden" name="uom">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Serial Required</label>
                            <input type="text" id="serial_required" class="form-control" readonly>
                            <input type="hidden" id="serial_required_hidden" name="is_serialNumber_required">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Quantity</label>
                            <input type="number" step="0.00" min="1" max="50" id="quantity" name="quantity" class="form-control"
                                placeholder="Enter quantity" required>
                            <div class="invalid-feedback">Quantity is required.</div>
                            <small class="text-muted-custom" id="available_qty_hint"></small>
                        </div>
                    </div>

                    <div class="mt-3" id="serial_container"></div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Save Inventory</button>
                        <button type="reset" class="btn btn-secondary">Clear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/js/jquery.dataTables.min.js"></script>
    <script src="/assets/js/dataTables.bootstrap5.min.js"></script>
    <script src="/assets/js/dataTables.buttons.min.js"></script>
    <script src="/assets/js/buttons.bootstrap5.min.js"></script>
    <script src="/assets/js/jszip.min.js"></script>
    <script src="/assets/js/buttons.html5.min.js"></script>
    <script src="/assets/js/buttons.print.min.js"></script>
    <script src="/assets/js/select2.min.js"></script>

    <script>
        $(function () {
            $('.select2').select2({ width: '100%' });

            const subCategoryUrl = "{{ route('getSubCategory') }}";
            const productUrl = "{{ route('getProducts') }}";
            const getSoldToUrl = "{{ route('getChannelPartnerByRole') }}";
            const getAvailableQtyUrl = "{{ route('getProductAvailableQty') }}";
            const getAvailableSerialUrl = "{{ route('getAvailableSerial') }}";

            let serialRequired = false;
            let availableQty = null;

            function clearValidation() {
                $('#inventoryForm .is-invalid').removeClass('is-invalid');
            }

            function resetQtyAndSerials() {
                $('#quantity').val('');
                $('#quantity')[0].setCustomValidity('');
                $('#quantity').removeClass('is-invalid');
                $('#serial_container').empty();
                $('#available_qty_hint').text('');
                availableQty = null;
            }

            function loadAvailableQty() {
                const productId = $('#product_id').val();
                if (!productId) return;

                $.get(getAvailableQtyUrl, { product_id: productId }, function (data) {
                    availableQty = parseInt(data?.available_qty ?? data ?? 0, 10) || 0;
                    $('#available_qty_hint').text(`Available Qty: ${availableQty}`);

                    if (availableQty > 0) {
                        $('#quantity').attr('max', availableQty);
                    } else {
                        $('#quantity').removeAttr('max');
                    }
                });
            }

            function renderSerialSelector(qty) {
                const productId = $('#product_id').val();
                if (!productId || qty <= 0) return;

                $.get(getAvailableSerialUrl, { product_id: productId }, function (data) {
                    const serials = Array.isArray(data?.available_serials)
                        ? data.available_serials
                        : (Array.isArray(data) ? data : []);

                    let options = '';

                    serials.forEach((sn) => {
                        const value = sn.serial_number ?? sn;
                        options += `<option value="${value}">${value}</option>`;
                    });

                    const html = `
                                    <label class="form-label mt-3">Select Serial Numbers</label>
                                    <select id="serial_numbers" name="serial_numbers[]" class="form-select select2" multiple required>
                                        ${options}
                                    </select>
                                `;
                    $('#serial_container').html(html);

                    $('#serial_numbers').select2({ width: '100%' });

                    // Auto-select first N serial numbers based on quantity
                    const firstNSerials = serials.slice(0, qty).map(sn => sn.serial_number ?? sn);
                    $('#serial_numbers').val(firstNSerials).trigger('change');

                    // Enforce max selection = qty
                    $('#serial_numbers').on('change', function () {
                        const selected = $(this).val() || [];
                        if (selected.length > qty) {
                            selected.splice(qty);
                            $(this).val(selected).trigger('change.select2');
                        }
                    });
                });
            }

            $('#category_id').on('change', function () {
                const categoryId = $(this).val();
                $('#sub_category_id').empty().append('<option value="">Select Sub Category</option>');
                $('#product_id').empty().append('<option value="">Select Product</option>');
                $('#uom').val('');
                $('#serial_required').val('');
                $('#quantity').val('');
                $('#serial_container').empty();
                clearValidation();

                if (!categoryId) return;

                $.get(subCategoryUrl, { category_id: categoryId }, function (data) {
                    data.forEach(item => {
                        $('#sub_category_id').append(`<option value="${item.id}">${item.sub_category_name}</option>`);
                    });
                    $('#sub_category_id').trigger('change.select2');
                });
            });

            $('#sub_category_id').on('change', function () {
                const subCategoryId = $(this).val();
                $('#product_id').empty().append('<option value="">Select Product</option>');
                $('#uom').val('');
                $('#serial_required').val('');
                $('#quantity').val('');
                $('#serial_container').empty();
                clearValidation();

                if (!subCategoryId) return;

                $.get(productUrl, { sub_category_id: subCategoryId }, function (data) {
                    data.forEach(item => {
                        $('#product_id').append(
                            `<option value="${item.id}" data-uom="${item.uom || ''}" data-serial="${item.is_serialNumber_required || 0}">
                                                        ${item.item_name}
                                                     </option>`
                        );
                    });
                    $('#product_id').trigger('change.select2');
                });
            });

            $('#product_id').on('change', function () {
                const selected = $(this).find('option:selected');
                const uom = selected.data('uom') || '';
                const serialFlag = String(selected.data('serial')) === '1';

                serialRequired = serialFlag;
                $('#uom').val(uom);
                $('#uom_hidden').val(uom);
                $('#serial_required').val(serialFlag ? 'Yes' : 'No');
                $('#serial_required_hidden').val(serialFlag ? 1 : 0);

                $('#quantity').removeAttr('max');
                resetQtyAndSerials();
                loadAvailableQty();
                clearValidation();
            });


            $('#sold_type').on('change', function () {
                const roleId = $(this).val();
                $('#sold_to').empty().append('<option value="">Select Sold To</option>').trigger('change.select2');

                if (!roleId) return;

                $.get(getSoldToUrl, { role_id: roleId }, function (data) {
                    data.forEach(item => {
                        $('#sold_to').append(
                            `<option value="${item.id}">${item.cp_name || item.name || ''}</option>`
                        );
                    });
                    $('#sold_to').trigger('change.select2');
                });
            });

            $('#quantity').on('input', function () {
                const qty = parseInt($(this).val(), 10) || 0;

                if (availableQty !== null && qty > availableQty) {
                    this.setCustomValidity('Quantity cannot exceed available quantity.');
                    $(this).addClass('is-invalid');
                } else {
                    this.setCustomValidity('');
                    $(this).removeClass('is-invalid');
                }

                $('#serial_container').empty();
                if (serialRequired && qty > 0 && (availableQty === null || qty <= availableQty)) {
                    renderSerialSelector(qty);
                }
            });

            $('#inventoryForm').on('submit', function (e) {
                clearValidation();

                let valid = true;
                const qty = parseInt($('#quantity').val(), 10);

                if (!qty || qty < 1 || (availableQty !== null && qty > availableQty)) {
                    $('#quantity').addClass('is-invalid');
                    valid = false;
                }

                if (serialRequired) {
                    const selectedCount = ($('#serial_numbers').val() || []).length;
                    if (selectedCount !== qty) {
                        valid = false;
                        $('#serial_numbers').addClass('is-invalid');
                    }
                }

                if (!valid) e.preventDefault();
            });
        });
    </script>
@endsection