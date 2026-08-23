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
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="container-fluid">
            <h1><i class="fas fa-arrow-right me-2"></i>Transfer to Warehouse</h1>
            <p>Transfer products from main inventory to a warehouse</p>
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
                <form id="transferForm" action="{{ route('admin.warehouses.storeTransfer') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Warehouse *</label>
                            <select id="warehouse_id" name="warehouse_id" class="form-select select2" required>
                                <option value="">Select Warehouse</option>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->id }}">{{ $wh->name }}{{ $wh->city ? ' ('.$wh->city.')' : '' }}</option>
                                @endforeach
                            </select>
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
                            <input type="number" step="0.01" min="0" name="unit_price" class="form-control" placeholder="Enter unit price">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Invoice Number</label>
                            <input type="text" name="invoice_number" class="form-control" placeholder="Invoice number">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Invoice Date</label>
                            <input type="date" name="invoice_date" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">UOM</label>
                            <input type="text" id="uom" class="form-control" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Serial Required</label>
                            <input type="text" id="serial_required" class="form-control" readonly>
                            <input type="hidden" id="serial_required_hidden" name="is_serialNumber_required">
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
                    </div>

                    <div class="mt-3" id="serial_container"></div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Transfer to Warehouse</button>
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
            const getAvailableQtyUrl = "{{ route('getProductAvailableQty') }}";
            const getAvailableSerialUrl = "{{ route('getAvailableSerial') }}";

            let serialRequired = false;
            let availableQty = null;

            $('#category_id').on('change', function () {
                $('#sub_category_id').empty().append('<option value="">Select Sub Category</option>');
                $('#product_id').empty().append('<option value="">Select Product</option>');
                resetFields();
                if (!$(this).val()) return;

                $.get(subCategoryUrl, { category_id: $(this).val() }, function (data) {
                    data.forEach(item => {
                        $('#sub_category_id').append(`<option value="${item.id}">${item.sub_category_name}</option>`);
                    });
                    $('#sub_category_id').trigger('change.select2');
                });
            });

            $('#sub_category_id').on('change', function () {
                $('#product_id').empty().append('<option value="">Select Product</option>');
                resetFields();
                if (!$(this).val()) return;

                $.get(productUrl, { sub_category_id: $(this).val() }, function (data) {
                    data.forEach(item => {
                        $('#product_id').append(
                            `<option value="${item.id}" data-uom="${item.uom || ''}" data-serial="${item.is_serialNumber_required || 0}">${item.item_name}</option>`
                        );
                    });
                    $('#product_id').trigger('change.select2');
                });
            });

            $('#product_id').on('change', function () {
                const sel = $(this).find('option:selected');
                const uom = sel.data('uom') || '';
                const serialFlag = String(sel.data('serial')) === '1';

                serialRequired = serialFlag;
                $('#uom').val(uom);
                $('#serial_required').val(serialFlag ? 'Yes' : 'No');
                $('#serial_required_hidden').val(serialFlag ? 1 : 0);
                $('#quantity').val('');
                $('#serial_container').empty();

                if (!$(this).val()) { resetFields(); return; }

                $.get(getAvailableQtyUrl, { product_id: $(this).val() }, function (data) {
                    availableQty = parseInt(data?.available_qty ?? data ?? 0, 10) || 0;
                    $('#available_qty_hint').text(`Available in main inventory: ${availableQty}`);
                    if (availableQty > 0) $('#quantity').attr('max', availableQty);
                });
            });

            $('#quantity').on('input', function () {
                const qty = parseInt($(this).val(), 10) || 0;
                $('#serial_container').empty();

                if (availableQty !== null && qty > availableQty) {
                    this.setCustomValidity('Quantity exceeds available stock.');
                    $(this).addClass('is-invalid');
                    return;
                }
                this.setCustomValidity('');
                $(this).removeClass('is-invalid');

                if (serialRequired && qty > 0) {
                    const productId = $('#product_id').val();
                    $.get(getAvailableSerialUrl, { product_id: productId }, function (data) {
                        const serials = Array.isArray(data?.available_serials) ? data.available_serials : (Array.isArray(data) ? data : []);
                        let options = '';
                        serials.forEach(sn => {
                            const val = sn.serial_number ?? sn;
                            options += `<option value="${val}">${val}</option>`;
                        });
                        $('#serial_container').html(`
                            <label class="form-label mt-3">Select Serial Numbers</label>
                            <select id="serial_numbers" name="serial_numbers[]" class="form-select select2" multiple required>${options}</select>
                        `);
                        $('#serial_numbers').select2({ width: '100%' });
                        const firstN = serials.slice(0, qty).map(sn => sn.serial_number ?? sn);
                        $('#serial_numbers').val(firstN).trigger('change');
                        $('#serial_numbers').on('change', function () {
                            const selected = $(this).val() || [];
                            if (selected.length > qty) {
                                selected.splice(qty);
                                $(this).val(selected).trigger('change.select2');
                            }
                        });
                    });
                }
            });

            function resetFields() {
                $('#uom').val('');
                $('#serial_required').val('');
                $('#serial_required_hidden').val('');
                $('#quantity').val('').removeAttr('max');
                $('#available_qty_hint').text('');
                $('#serial_container').empty();
                serialRequired = false;
                availableQty = null;
            }
        });
    </script>
@endsection
