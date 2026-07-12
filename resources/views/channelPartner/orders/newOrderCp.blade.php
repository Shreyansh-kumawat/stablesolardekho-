@extends('layouts.adminLayout')
@section('css')
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/select2.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary-blue: #4A90E2;
            --primary-light: #f8f9fa;
            --text-primary: #2d3436;
            --text-secondary: #636e72;
            --border-color: #dee2e6;
        }

        body {
            background: var(--primary-light);
            font-size: 0.875rem;
        }

        .page-header {
            background: #fff;
            padding: 0.75rem 0;
            margin-bottom: 1rem;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .page-header h1 {
            font-size: 1.125rem;
            font-weight: 600;
            margin: 0;
        }

        .page-header p {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin: 0.25rem 0 0 0;
        }

        .product-row {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 0.75rem !important;
            margin-bottom: 0.75rem !important;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.25rem;
            font-size: 0.8rem;
            color: var(--text-primary);
        }

        .form-control,
        .form-select {
            border-radius: 4px;
            border: 1px solid var(--border-color);
            padding: 0.375rem 0.5rem;
            font-size: 0.875rem;
            height: calc(1.5em + 0.75rem + 2px);
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 0.15rem rgba(74, 144, 226, 0.15);
            border-color: var(--primary-blue);
        }

        .select2-container--default .select2-selection--single {
            border-radius: 4px !important;
            border: 1px solid var(--border-color) !important;
            height: calc(1.5em + 0.75rem + 2px) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: calc(1.5em + 0.75rem) !important;
            padding-left: 0.5rem !important;
            font-size: 0.875rem !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(1.5em + 0.75rem) !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: var(--primary-blue) !important;
            box-shadow: 0 0 0 0.15rem rgba(74, 144, 226, 0.15) !important;
        }

        .btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 4px;
            font-weight: 500;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }

        .btn-primary {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .btn-primary:hover {
            background: #3b7dc4;
            border-color: #3b7dc4;
        }

        .btn-success {
            background: #28a745;
            border-color: #28a745;
        }

        .btn-danger {
            background: #dc3545;
            border-color: #dc3545;
        }

        .form-section {
            background: #fff;
            padding: 1rem;
            border-radius: 6px;
            border: 1px solid var(--border-color);
        }

        .section-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--text-primary);
        }

        textarea.form-control {
            font-size: 0.875rem;
        }

        .btn-group-compact {
            display: flex;
            gap: 0.5rem;
            margin: 0.75rem 0;
        }

        @media (max-width: 768px) {
            .product-row {
                padding: 0.5rem !important;
            }
            .form-label {
                font-size: 0.75rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="container-fluid">
            <h1><i class="fas fa-box me-2"></i>New Order</h1>
            <p>Select products, quantities and upload payment proof to place an order</p>
        </div>
    </div>

    <div class="container-fluid">
        <form id="orderForm" class="form-section" enctype="multipart/form-data">
            @csrf

            <h6 class="section-title">Product Details</h6>

            <div id="productRows">
                <div class="product-row" data-row-index="0">
                    <div class="row g-2">
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-control category-select" name="products[0][category_id]" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">Subcategory <span class="text-danger">*</span></label>
                            <select class="form-control subcategory-select" name="products[0][subcategory_id]" required disabled>
                                <option value="">Select Subcategory</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label">Product <span class="text-danger">*</span></label>
                            <select class="form-control product-select" name="products[0][product_id]" required disabled>
                                <option value="">Select Product</option>
                            </select>
                        </div>
                        <div class="col-lg-1 col-md-4">
                            <label class="form-label">Qty <span class="text-danger">*</span></label>
                            <input type="number" class="form-control quantity-input" name="products[0][quantity]" min="1" value="1" required disabled>
                            <small class="stock-info text-muted" style="font-size:0.72rem;"></small>
                        </div>
                        <div class="col-lg-1 col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-row w-100 d-none">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="btn-group-compact">
                <button type="button" class="btn btn-primary btn-sm" id="addRow">
                    <i class="fas fa-plus me-1"></i> Add Row
                </button>
            </div>

            <div class="mt-3">
                <label class="form-label">Additional Remarks</label>
                <textarea class="form-control" name="remarks" rows="3" placeholder="Enter any additional remarks..."></textarea>
            </div>

            <div class="mt-3" style="background:#f0f7ff;border:1px solid #b3d4fc;border-radius:6px;padding:1rem;">
                <h6 class="section-title" style="color:#1a56db;margin-bottom:0.5rem;"><i class="fas fa-camera me-1"></i> Payment Proof</h6>
                <p style="font-size:0.78rem;color:#555;margin:0 0 0.5rem;">Upload a screenshot of the payment (UPI, bank transfer, etc.)</p>
                <input type="file" class="form-control" name="payment_screenshot" id="paymentScreenshot" accept="image/*" required>
                <div id="paymentPreview" style="margin-top:0.5rem;display:none;">
                    <img id="paymentPreviewImg" style="max-width:200px;max-height:150px;border-radius:4px;border:1px solid #ddd;" />
                </div>
                <div class="mt-2">
                    <label class="form-label">Payment Reference / UTR Number</label>
                    <input type="text" class="form-control" name="payment_reference" placeholder="Enter UTR / Transaction ID (optional)">
                </div>
            </div>

            <div class="btn-group-compact mt-3">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check me-1"></i> Submit Order
                </button>
                <a href="{{ route('orderReportCp') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </form>
    </div>
@endsection

@section('js')
<script src="/assets/js/jquery-3.7.1.min.js"></script>
<script src="/assets/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/select2.min.js"></script>

<script>
let rowIndex = 1;

$(document).ready(function() {
    initializeSelects();
    attachCategoryChangeEvent();

    $('#addRow').click(function() {
        const newRow = `
            <div class="product-row" data-row-index="${rowIndex}">
                <div class="row g-2">
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-control category-select" name="products[${rowIndex}][category_id]" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Subcategory <span class="text-danger">*</span></label>
                        <select class="form-control subcategory-select" name="products[${rowIndex}][subcategory_id]" required disabled>
                            <option value="">Select Subcategory</option>
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Product <span class="text-danger">*</span></label>
                        <select class="form-control product-select" name="products[${rowIndex}][product_id]" required disabled>
                            <option value="">Select Product</option>
                        </select>
                    </div>
                    <div class="col-lg-1 col-md-4">
                        <label class="form-label">Qty <span class="text-danger">*</span></label>
                        <input type="number" class="form-control quantity-input" name="products[${rowIndex}][quantity]" min="1" value="1" required disabled>
                        <small class="stock-info text-muted" style="font-size:0.72rem;"></small>
                    </div>
                    <div class="col-lg-1 col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-row w-100">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

        $('#productRows').append(newRow);
        initializeSelects($(`[data-row-index="${rowIndex}"]`));
        attachCategoryChangeEvent();
        rowIndex++;
        updateRemoveButtons();
    });

    $(document).on('click', '.remove-row', function(e) {
        e.preventDefault();
        $(this).closest('.product-row').remove();
        updateRemoveButtons();
    });

    $(document).on('change', '.subcategory-select', function() {
        const row = $(this).closest('.product-row');
        const subcategoryId = $(this).val();
        const productSelect = row.find('.product-select');

        productSelect.html('<option value="">Select Product</option>').prop('disabled', true);

        if (subcategoryId) {
            loadProducts(subcategoryId, productSelect);
        }
    });

    $(document).on('input', '.quantity-input', function() {
        var max = parseInt($(this).attr('max')) || 0;
        var val = parseInt($(this).val()) || 0;
        var stockInfo = $(this).closest('.product-row').find('.stock-info');

        if (max > 0) {
            var remaining = max - val;
            if (val > max) {
                $(this).val(max);
                remaining = 0;
                stockInfo.html('<span style="color:#dc2626;font-weight:600;">Max ' + max + ' available</span>');
            } else if (val < 1) {
                stockInfo.html('<span style="color:#059669;font-weight:600;">' + max + ' left</span>');
            } else {
                stockInfo.html('<span style="color:#059669;font-weight:600;">' + remaining + ' left</span>');
            }
        }
    });

    $('#paymentScreenshot').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#paymentPreviewImg').attr('src', e.target.result);
                $('#paymentPreview').show();
            };
            reader.readAsDataURL(file);
        } else {
            $('#paymentPreview').hide();
        }
    });

    $('#orderForm').submit(function(e) {
        e.preventDefault();

        const products = [];
        let isValid = true;

        $('.product-row').each(function() {
            const categoryId = $(this).find('.category-select').val();
            const subcategoryId = $(this).find('.subcategory-select').val();
            const productId = $(this).find('.product-select').val();
            const quantity = $(this).find('.quantity-input').val();

            if (!categoryId || !subcategoryId || !productId || !quantity) {
                isValid = false;
                return false;
            }

            products.push({
                category_id: categoryId,
                subcategory_id: subcategoryId,
                product_id: productId,
                quantity: parseInt(quantity)
            });
        });

        if (!isValid) {
            alert('Please fill in all required fields');
            return;
        }

        const paymentFile = $('#paymentScreenshot')[0].files[0];
        if (!paymentFile) {
            alert('Please upload payment proof');
            return;
        }

        const formData = new FormData();
        formData.append('products', JSON.stringify(products));
        formData.append('remarks', $('textarea[name="remarks"]').val());
        formData.append('payment_screenshot', paymentFile);
        formData.append('payment_reference', $('input[name="payment_reference"]').val());
        formData.append('_token', $('input[name="_token"]').val());

        $.ajax({
            url: '{{ route("storeNewOrderRequest") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert('Order submitted successfully!');
                window.location.href = '{{ route("orderReportCp") }}';
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || 'Failed to submit order'));
            }
        });
    });
});

function initializeSelects(container) {
    const selectElements = container ? 
        container.find('.category-select, .subcategory-select, .product-select') : 
        $('.category-select, .subcategory-select, .product-select');
    
    selectElements.each(function() {
        if (!$(this).hasClass('select2-hidden-accessible')) {
            $(this).select2({
                width: '100%',
                minimumResultsForSearch: 5,
                placeholder: $(this).find('option:first').text()
            });
        }
    });
}

function attachCategoryChangeEvent() {
    $(document).off('select2:select', '.category-select').on('select2:select', '.category-select', function(e) {
        const row = $(this).closest('.product-row');
        const categoryId = $(this).val();
        const subcategorySelect = row.find('.subcategory-select');
        const productSelect = row.find('.product-select');

        subcategorySelect.html('<option value="">Select Subcategory</option>').prop('disabled', true).trigger('change.select2');
        productSelect.html('<option value="">Select Product</option>').prop('disabled', true).trigger('change.select2');

        if (categoryId) {
            loadSubcategories(categoryId, subcategorySelect);
        }
    });
}

function updateRemoveButtons() {
    const rowCount = $('.product-row').length;
    $('.remove-row').toggleClass('d-none', rowCount === 1);
}

function loadSubcategories(categoryId, selectElement) {
    $.ajax({
        url: "{{ route('cp.getSubCategory') }}",
        method: 'GET',
        data: { category_id: categoryId },
        success: function(data) {
            // Destroy select2 first
            if (selectElement.hasClass('select2-hidden-accessible')) {
                selectElement.select2('destroy');
            }
            
            selectElement.html('<option value="">Select Subcategory</option>');
            
            if (data && data.length > 0) {
                data.forEach(function(subcategory) {
                    selectElement.append(`<option value="${subcategory.id}">${subcategory.subcategory_name || subcategory.sub_category_name || subcategory.name}</option>`);
                });
            }
            
            // Re-initialize select2
            selectElement.select2({
                width: '100%',
                minimumResultsForSearch: 5,
                placeholder: 'Select Subcategory'
            }).prop('disabled', false);
        },
        error: function(xhr) {
            console.error('Error:', xhr.responseText);
        }
    });
}

function loadProducts(subcategoryId, selectElement) {
    $.ajax({
        url: "{{ route('cp.getProducts') }}",
        method: 'GET',
        data: { sub_category_id: subcategoryId },
        success: function(data) {
            if (selectElement.hasClass('select2-hidden-accessible')) {
                selectElement.select2('destroy');
            }

            selectElement.html('<option value="">Select Product</option>');

            if (data && data.length > 0) {
                data.forEach(function(product) {
                    var qty = product.quantity || 0;
                    var name = product.product_name || product.item_name || product.name;
                    selectElement.append('<option value="' + product.id + '" data-stock="' + qty + '">' + name + ' (' + qty + ' available)</option>');
                });
            }

            selectElement.select2({
                width: '100%',
                minimumResultsForSearch: 5,
                placeholder: 'Select Product'
            }).prop('disabled', false);

            selectElement.off('select2:select').on('select2:select', function() {
                var row = $(this).closest('.product-row');
                var stock = parseInt($(this).find('option:selected').data('stock')) || 0;
                var qtyInput = row.find('.quantity-input');
                var stockInfo = row.find('.stock-info');

                if (stock > 0) {
                    qtyInput.attr('max', stock).val(1).prop('disabled', false);
                    stockInfo.html('<span style="color:#059669;font-weight:600;">' + stock + ' left</span>');
                } else {
                    qtyInput.val(0).prop('disabled', true);
                    stockInfo.html('<span style="color:#dc2626;font-weight:600;">Out of stock</span>');
                }
            });
        },
        error: function(xhr) {
            console.error('Error:', xhr.responseText);
        }
    });
}
</script>
@endsection