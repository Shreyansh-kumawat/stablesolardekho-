@extends('layouts.adminLayout')

@section('css')
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/select2.min.css" rel="stylesheet" />
    <style>
        :root { --primary-blue: #4A90E2; --primary-light: #f5f7fa; --text-primary: #2d3436; --text-secondary: #636e72; --border-color: #e1e8ed; --card-bg: #ffffff; }
        body { background: var(--primary-light); color: var(--text-primary); }
        .page-header { background: #fff; padding: 1.5rem; margin-bottom: 1.5rem; border-radius: 8px; border: 1px solid var(--border-color); }
        .page-header h1 { font-weight: 600; margin: 0; font-size: 1.25rem; }
        .page-header p { color: var(--text-secondary); margin: 0.35rem 0 0 0; font-size: 0.9rem; }
        .card { border: 1px solid var(--border-color); border-radius: 8px; background: var(--card-bg); }
        .card-body { padding: 1.5rem; }
        .btn-primary { background: var(--primary-blue); border: 1px solid var(--primary-blue); color: #fff; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; }
        .btn-secondary { padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; border: 1px solid var(--border-color); background: #fff; color: var(--text-primary); }
        .form-control, .form-select { border-radius: 6px; border: 1px solid var(--border-color); padding: 0.55rem 0.75rem; font-size: 0.9rem; }
        .form-label { font-weight: 600; margin-bottom: 0.5rem; font-size: 0.85rem; }
        .text-muted-custom { color: var(--text-secondary); font-size: 0.85rem; }
        .select2-container { width: 100% !important; }
        .select2-container--default .select2-selection--single { height: 38px !important; border: 1px solid var(--border-color) !important; border-radius: 6px !important; padding: 0.35rem 0.75rem !important; display: flex !important; align-items: center !important; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px !important; }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <h1><i class="fas fa-arrow-right me-2"></i>Transfer Stock</h1>
        <p>From: <strong>{{ $warehouse->name }}</strong> &rarr; to Main Inventory or another Warehouse</p>
    </div>

    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('wh.manager.storeTransfer') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Destination *</label>
                            <select name="to_warehouse_id" class="form-select select2" required>
                                <option value="">Select Destination</option>
                                <option value="main">&#127968; Main Inventory (Head Office)</option>
                                @if($otherWarehouses->count())
                                    <optgroup label="Warehouses">
                                        @foreach($otherWarehouses as $wh)
                                            <option value="{{ $wh->id }}">{{ $wh->name }}{{ $wh->city ? ' ('.$wh->city.')' : '' }}</option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Category *</label>
                            <select id="category_id" class="form-select select2" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Sub Category *</label>
                            <select id="sub_category_id" class="form-select select2" required>
                                <option value="">Select Sub Category</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Product *</label>
                            <select id="product_id" name="product_id" class="form-select select2" required>
                                <option value="">Select Product</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Unit Price</label>
                            <input type="number" step="0.01" min="0" id="unit_price" name="unit_price" class="form-control" placeholder="Auto-filled">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Quantity *</label>
                            <input type="number" min="1" id="quantity" name="quantity" class="form-control" required>
                            <small class="text-muted-custom" id="available_qty_hint"></small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Remarks</label>
                            <input type="text" name="remarks" class="form-control" placeholder="Optional">
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-exchange-alt me-1"></i> Transfer</button>
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

            const subCategoryUrl = "{{ route('wh.manager.getSubCategory') }}";
            const productUrl = "{{ route('wh.manager.getProducts') }}";
            const getWhQtyUrl = "{{ route('wh.manager.getWarehouseQty') }}";
            let availableQty = null;

            $('#category_id').on('change', function () {
                $('#sub_category_id').empty().append('<option value="">Select Sub Category</option>');
                $('#product_id').empty().append('<option value="">Select Product</option>');
                reset();
                if (!$(this).val()) return;
                $.get(subCategoryUrl, { category_id: $(this).val() }, function (data) {
                    data.forEach(item => $('#sub_category_id').append('<option value="'+item.id+'">'+item.sub_category_name+'</option>'));
                    $('#sub_category_id').trigger('change.select2');
                });
            });

            $('#sub_category_id').on('change', function () {
                $('#product_id').empty().append('<option value="">Select Product</option>');
                reset();
                if (!$(this).val()) return;
                $.get(productUrl, { sub_category_id: $(this).val() }, function (data) {
                    data.forEach(item => {
                        var price = item.current_sale_price || item.sale_price || 0;
                        $('#product_id').append('<option value="'+item.id+'" data-price="'+price+'">'+item.item_name+'</option>');
                    });
                    $('#product_id').trigger('change.select2');
                });
            });

            $('#product_id').on('change', function () {
                var price = $(this).find('option:selected').data('price') || '';
                $('#unit_price').val(price);
                if (!$(this).val()) { reset(); return; }
                $.get(getWhQtyUrl, { product_id: $(this).val() }, function (data) {
                    availableQty = parseInt(data.available_qty || 0, 10) || 0;
                    $('#available_qty_hint').text('Available in your warehouse: ' + availableQty);
                    if (availableQty > 0) $('#quantity').attr('max', availableQty);
                });
            });

            $('#quantity').on('input', function () {
                var qty = parseInt($(this).val(), 10) || 0;
                if (availableQty !== null && qty > availableQty) {
                    this.setCustomValidity('Quantity exceeds available stock.');
                    $(this).addClass('is-invalid');
                } else {
                    this.setCustomValidity('');
                    $(this).removeClass('is-invalid');
                }
            });

            function reset() {
                $('#quantity').val('').removeAttr('max');
                $('#unit_price').val('');
                $('#available_qty_hint').text('');
                availableQty = null;
            }
        });
    </script>
@endsection
