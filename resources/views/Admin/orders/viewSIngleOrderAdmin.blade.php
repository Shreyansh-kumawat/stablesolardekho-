@extends('layouts.adminLayout')
@section('css')
<link rel="stylesheet" href="/assets/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="/assets/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="{{ asset('stable/css/datatableListCss.css') }}">
<link rel="stylesheet" href="/assets/css/bootstrap-icons.min.css">
<style>
    :root {
        --primary-color: #667eea;
        --secondary-color: #764ba2;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --warning-color: #ffc107;
        --info-color: #17a2b8;
        --light-bg: #f8f9fa;
        --border-color: #dee2e6;
        --text-dark: #212529;
        --text-muted: #6c757d;
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .order-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
        border: none;
    }

    .order-detail {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 25px;
    }

    .order-detail-item {
        padding: 15px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        backdrop-filter: blur(10px);
    }

    .order-detail-label {
        font-weight: 600;
        opacity: 0.9;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .order-detail-value {
        font-size: 18px;
        font-weight: 700;
    }

    .products-table {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        border: 1px solid var(--border-color);
    }

    .products-table table {
        margin-bottom: 0;
    }

    .products-table thead {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 2px solid var(--border-color);
    }

    .products-table th {
        padding: 18px 15px;
        font-weight: 700;
        color: var(--text-dark);
        border: none;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .products-table td {
        padding: 18px 15px;
        vertical-align: middle;
        border: 1px solid var(--border-color);
    }

    .products-table tbody tr:hover {
        background: #f8f9fa;
        transition: background 0.3s ease;
    }

    .unit-rate-input, .gst-rate-input, .gst-input {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .unit-rate-input:focus, .gst-rate-input:focus, .gst-input:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        background: #f8fbff;
    }

    .unit-rate-input.is-invalid, .gst-rate-input.is-invalid {
        border-color: var(--danger-color);
        background: #fff5f5;
    }

    .unit-rate-input.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.15);
    }

    .line-total {
        font-weight: 700;
        color: var(--primary-color);
        text-align: right;
        font-size: 15px;
    }

    .summary-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
        border: 1px solid var(--border-color);
    }

    .summary-card h5 {
        color: var(--text-dark);
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 16px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 15px;
    }

    .summary-row:last-child {
        border-bottom: none;
    }

    .summary-label {
        font-weight: 600;
        color: var(--text-muted);
    }

    .summary-value {
        font-weight: 700;
        color: var(--text-dark);
        text-align: right;
        min-width: 150px;
    }

    .summary-row.total {
        padding: 20px 0;
        border-top: 2px solid var(--border-color);
        border-bottom: none;
        font-size: 18px;
        color: var(--primary-color);
    }

    .summary-row.total .summary-value {
        color: var(--primary-color);
        font-size: 22px;
    }

    .gst-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 25px;
        border: 2px dashed var(--border-color);
    }

    .gst-section h5 {
        color: var(--text-dark);
        margin-bottom: 20px;
        font-weight: 700;
        font-size: 15px;
    }

    .gst-input-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 0;
    }

    .gst-input-group > div {
        display: flex;
        flex-direction: column;
    }

    .gst-input-group label {
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--text-dark);
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Other Remarks and Date Section */
    .other-remarks {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-top: 25px;
        padding-top: 25px;
        border-top: 2px solid var(--border-color);
    }

    .other-remarks .form-control {
        padding: 12px 14px;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .other-remarks .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        background: #f8fbff;
    }

    .other-remarks label {
        display: block;
        font-weight: 600;
        color: var(--text-dark);
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
    }

    .other-remarks .form-group {
        display: flex;
        flex-direction: column;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 30px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .action-buttons .btn {
        padding: 12px 28px;
        font-weight: 600;
        border-radius: 8px;
        border: none;
        transition: all 0.3s ease;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        cursor: pointer;
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(40, 167, 69, 0.3);
        color: white;
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        color: white;
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(108, 117, 125, 0.3);
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(220, 53, 69, 0.3);
        color: white;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }

    .action-buttons .btn i {
        margin-right: 6px;
    }

    /* Badge Styles */
    .badge-status {
        padding: 10px 18px;
        border-radius: 25px;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-pending {
        background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
        color: #856404;
        box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
    }

    .badge-approved {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }

    .badge-rejected {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    }

    .product-info {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .product-name {
        font-weight: 700;
        color: var(--text-dark);
        font-size: 15px;
    }

    .product-meta {
        font-size: 12px;
        color: var(--text-muted);
    }

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    /* Header Section */
    .page-header {
        margin-bottom: 35px;
    }

    .page-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .page-header p {
        color: var(--text-muted);
        font-size: 15px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: white;
        color: var(--text-dark);
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid var(--border-color);
        font-size: 13px;
    }

    .back-btn:hover {
        background: var(--light-bg);
        border-color: var(--primary-color);
        color: var(--primary-color);
        transform: translateX(-4px);
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px 12px 0 0;
        border: none;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .order-detail {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .gst-input-group {
            grid-template-columns: 1fr;
        }

        .other-remarks {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
            justify-content: stretch;
        }

        .action-buttons .btn {
            width: 100%;
        }

        .products-table {
            font-size: 12px;
        }

        .products-table th,
        .products-table td {
            padding: 10px 8px;
        }
    }

    /* Confirmation Modal */
    .confirm-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        z-index: 1050;
        align-items: center;
        justify-content: center;
    }

    .confirm-modal.show {
        display: flex;
    }

    .confirm-content {
        background: white;
        border-radius: 15px;
        padding: 35px;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .confirm-icon {
        font-size: 48px;
        margin-bottom: 20px;
        display: inline-block;
    }

    .confirm-icon.success {
        color: var(--success-color);
    }

    .confirm-icon.warning {
        color: var(--warning-color);
    }

    .confirm-icon.danger {
        color: var(--danger-color);
    }

    .confirm-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 12px;
    }

    .confirm-message {
        color: var(--text-muted);
        margin-bottom: 25px;
        line-height: 1.6;
    }

    .confirm-details {
        background: var(--light-bg);
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 25px;
        max-height: 200px;
        overflow-y: auto;
    }

    .confirm-detail-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid var(--border-color);
        font-size: 13px;
    }

    .confirm-detail-row:last-child {
        border-bottom: none;
    }

    .confirm-detail-label {
        color: var(--text-muted);
        font-weight: 600;
    }

    .confirm-detail-value {
        color: var(--text-dark);
        font-weight: 700;
    }

    .confirm-buttons {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .confirm-buttons .btn {
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
    }

    .confirm-buttons .btn-confirm {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .confirm-buttons .btn-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(40, 167, 69, 0.3);
    }

    .confirm-buttons .btn-cancel {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        color: white;
    }

    .confirm-buttons .btn-cancel:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(108, 117, 125, 0.3);
    }
</style>
@endsection

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="page-header flex items-center justify-between mb-8">
            <div>
                <h1 class="flex items-center gap-3">
                    <i class="bi bi-file-earmark-check" style="color: var(--primary-color);"></i>Order Details
                </h1>
                <p>View and process order information</p>
            </div>
            <a href="{{ route('pendingOrders') }}" class="back-btn">
                <i class="bi bi-arrow-left"></i> Back to Orders
            </a>
        </div>

        @if($order)
        <!-- Order Header Card -->
        <div class="order-card">
            <div class="order-detail">
                <div class="order-detail-item">
                    <div class="order-detail-label">Order ID</div>
                    <div class="order-detail-value">{{ $order->order_id }}</div>
                </div>
                <div class="order-detail-item">
                    <div class="order-detail-label">Order Date</div>
                    <div class="order-detail-value">{{ \Carbon\Carbon::parse($order->order_date)->format('d M, Y') }}</div>
                </div>
                <div class="order-detail-item">
                    <div class="order-detail-label">Channel Partner</div>
                    <div class="order-detail-value">{{ $order->channelPartner->cp_name ?? 'N/A' }}</div>
                </div>
                <div class="order-detail-item">
                    <div class="order-detail-label">Status</div>
                    <div class="badge-status badge-{{ strtolower($order->status) }}">{{ ucfirst($order->status) }}</div>
                </div>
            </div>
        </div>

        <form id="orderForm" method="POST" action="{{ route('save_order_pricing') }}">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <!-- Products Table -->
            <div class="products-table">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 28%;">Product Name</th>
                            <th style="width: 10%; text-align: center;">UOM</th>
                            <th style="width: 12%; text-align: center;">Quantity</th>
                            <th style="width: 16%;">Unit Rate (₹)</th>
                            <th style="width: 12%;">GST Rate (%)</th>
                            <th style="width: 12%; text-align: right;">Total (₹)</th>
                            <th style="width: 5%; text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="productsTableBody">
                        @php
                            $products = is_array($order->products) ? $order->products : json_decode($order->products, true);
                            $productIndex = 0;
                        @endphp

                        @forelse($products as $product)
                            @php 
                                $productIndex++;
                                $productDetails = \App\Models\Product::find($product['product_id']);
                                $categoryDetails = \App\Models\ProductCategory::find($product['category_id']);
                                $subcategoryDetails = \App\Models\ProductSubCategory::find($product['subcategory_id']);
                            @endphp
                            <tr class="product-row" data-index="{{ $productIndex }}">
                                <td><strong>{{ $productIndex }}</strong></td>
                                <td>
                                    <div class="product-info">
                                        <span class="product-name">{{ $productDetails->item_name ?? 'Unknown Product' }}</span>
                                        <span class="product-meta">
                                            <i class="bi bi-tag"></i> {{ $categoryDetails->category_name ?? 'N/A' }} 
                                            @if($subcategoryDetails)
                                                / {{ $subcategoryDetails->sub_category_name }}
                                            @endif
                                        </span>
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge bg-primary">{{ $product['uom'] ?? 'N/A' }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <strong class="product-quantity">{{ $product['quantity'] ?? 0 }}</strong>
                                </td>
                                <td>
                                    <input type="number" 
                                           class="unit-rate-input" 
                                           name="unit_rate[{{ $productIndex }}]"
                                           placeholder="0.00"
                                           step="0.01"
                                           min="0"
                                           data-index="{{ $productIndex }}"
                                           value="">
                                </td>
                                <td>
                                    <input type="number" 
                                           class="gst-rate-input" 
                                           name="gst_rate[{{ $productIndex }}]"
                                           placeholder="0"
                                           step="0.01"
                                           min="0"
                                           data-index="{{ $productIndex }}"
                                           value="">
                                    <input type="hidden" name="product_id[{{ $productIndex }}]" value="{{ $product['product_id'] ?? '' }}">
                                    <input type="hidden" name="category_id[{{ $productIndex }}]" value="{{ $product['category_id'] ?? '' }}">
                                    <input type="hidden" name="subcategory_id[{{ $productIndex }}]" value="{{ $product['subcategory_id'] ?? '' }}">
                                    <input type="hidden" name="uom[{{ $productIndex }}]" value="{{ $product['uom'] ?? '' }}">
                                    <input type="hidden" name="quantity[{{ $productIndex }}]" value="{{ $product['quantity'] ?? 0 }}">
                                </td>
                                <td class="line-total" data-index="{{ $productIndex }}">₹ 0.00</td>
                                <td style="text-align: center;">
                                    <button type="button" class="btn btn-sm btn-danger delete-product" data-index="{{ $productIndex }}" title="Remove product">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                    <i class="bi bi-inbox" style="font-size: 36px; margin-bottom: 12px; display: block;"></i>
                                    <p style="font-size: 15px;">No products in this order</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Summary Card -->
            <div class="summary-card">
                <h5>
                    <i class="bi bi-receipt"></i> Order Summary
                </h5>
                
                <div class="summary-row">
                    <span class="summary-label">Subtotal</span>
                    <span class="summary-value" id="subtotal">₹ 0.00</span>
                </div>

                <div class="summary-row">
                    <span class="summary-label">GST Amount</span>
                    <span class="summary-value" id="gstAmount">₹ 0.00</span>
                </div>

                <div class="summary-row total">
                    <span><i class="bi bi-cash-stack"></i> Grand Total</span>
                    <span id="grandTotal">₹ 0.00</span>
                </div>

                <!-- Other Remarks and Date Section -->
                <div class="other-remarks">
                    <div class="form-group">
                        <label for="otherRemarks">
                            <i class="bi bi-chat-dots"></i> Additional Remarks
                        </label>
                        <textarea name="other_remarks" 
                                  id="otherRemarks"
                                  class="form-control" 
                                  placeholder="Enter any additional remarks or notes (optional)"
                                  rows="3"
                                  maxlength="500">{{ $order->other_remarks ?? '' }}</textarea>
                        <small class="text-muted mt-2" id="charCount">0/500 characters</small>
                    </div>
                    <div class="form-group">
                        <label for="quoteValidity">
                            <i class="bi bi-calendar-check"></i> Quote Validity Date
                        </label>
                        <input type="date" 
                               name="quote_validity_date" 
                               id="quoteValidity"
                               class="form-control"
                               value="{{ $order->expected_delivery_date ?? '' }}">
                        <small class="text-muted mt-2">Select the date when this quote expires</small>
                    </div>
                </div>

                <!-- Hidden fields for submission -->
                <input type="hidden" name="subtotal" id="subtotalInput">
                <input type="hidden" name="gst_amount" id="gstAmountInput">
                <input type="hidden" name="igst_amount" id="igstAmountInput">
                <input type="hidden" name="grand_total" id="grandTotalInput">
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button type="submit" class="btn btn-success" id="submitBtn">
                    <i class="bi bi-check-circle"></i> Save & Approve
                </button>
                <button type="button" class="btn btn-secondary" id="saveAsDraftBtn">
                    <i class="bi bi-file-earmark-text"></i> Save as Draft
                </button>
                <button type="button" class="btn btn-danger" id="rejectBtn">
                    <i class="bi bi-x-circle"></i> Reject Order
                </button>
            </div>
        </form>

        @else
        <div class="alert alert-warning" role="alert">
            <i class="bi bi-exclamation-triangle"></i> Order not found.
        </div>
        @endif
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="confirm-modal">
    <div class="confirm-content">
        <div class="confirm-icon success" id="confirmIcon">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="confirm-title" id="confirmTitle">Confirm Action</div>
        <div class="confirm-message" id="confirmMessage">Are you sure you want to proceed with this action?</div>
        
        <div class="confirm-details" id="confirmDetails" style="display: none;">
        </div>

        <div class="confirm-buttons">
            <button type="button" class="btn btn-cancel" id="confirmCancel">Cancel</button>
            <button type="button" class="btn btn-confirm" id="confirmSubmit">Confirm</button>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    let confirmAction = null;
    let confirmData = null;
    let isFormConfirmed = false;

    // Calculate line totals when unit rate changes
    $(document).on('input', '.unit-rate-input', function() {
        const index = $(this).data('index');
        const quantity = parseFloat($(`input[name="quantity[${index}]"]`).val()) || 0;
        const unitRate = parseFloat($(this).val()) || 0;
        const gstRate = parseFloat($(`input[name="gst_rate[${index}]"]`).val()) || 0;
        const lineTotal = (quantity * unitRate * (1 + gstRate / 100)).toFixed(2);
        
        $(`.line-total[data-index="${index}"]`).text('₹ ' + parseFloat(lineTotal).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        
        calculateTotals();
    });

    $(document).on('input', '.gst-rate-input', function() {
        const index = $(this).data('index');
        const quantity = parseFloat($(`input[name="quantity[${index}]"]`).val()) || 0;
        const unitRate = parseFloat($(`input[name="unit_rate[${index}]"]`).val()) || 0;
        const gstRate = parseFloat($(this).val()) || 0;
        const lineTotal = (quantity * unitRate * (1 + gstRate / 100)).toFixed(2);
        
        $(`.line-total[data-index="${index}"]`).text('₹ ' + parseFloat(lineTotal).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        
        calculateTotals();
    });

    // Character count for remarks
    $('#otherRemarks').on('input', function() {
        const count = $(this).val().length;
        $('#charCount').text(count + '/500 characters');
    });

    // Delete product row
    $(document).on('click', '.delete-product', function() {
        const index = $(this).data('index');
        if (confirm('Are you sure you want to remove this product from the order?')) {
            $(`.product-row[data-index="${index}"]`).remove();
            calculateTotals();
            
            if ($('.product-row').length === 0) {
                $('#productsTableBody').html(`
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: var(--text-muted);">
                            <i class="bi bi-inbox" style="font-size: 36px; margin-bottom: 12px; display: block;"></i>
                            <p style="font-size: 15px;">No products in this order</p>
                        </td>
                    </tr>
                `);
            }
        }
    });

    // Calculate all totals
    function calculateTotals() {
        let subtotal = 0;
        let totalGst = 0;

        $('.product-row').each(function() {
            const index = $(this).data('index');
            const quantity = parseFloat($(`input[name="quantity[${index}]"]`).val()) || 0;
            const unitRate = parseFloat($(`input[name="unit_rate[${index}]"]`).val()) || 0;
            const gstRate = parseFloat($(`input[name="gst_rate[${index}]"]`).val()) || 0;
            subtotal += (quantity * unitRate);
            totalGst += (quantity * unitRate * (gstRate / 100));
        });

        const grandTotal = subtotal + totalGst;

        $('#subtotal').text('₹ ' + parseFloat(subtotal).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        $('#gstAmount').text('₹ ' + parseFloat(totalGst).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        $('#grandTotal').text('₹ ' + parseFloat(grandTotal).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}));

        $('#subtotalInput').val(subtotal.toFixed(2));
        $('#gstAmountInput').val(totalGst.toFixed(2));
        $('#igstAmountInput').val('0.00');
        $('#grandTotalInput').val(grandTotal.toFixed(2));
    }

    // Show confirmation modal
    function showConfirmModal(title, message, icon, details = null) {
        $('#confirmTitle').text(title);
        $('#confirmMessage').text(message);
        
        $('#confirmIcon').attr('class', 'confirm-icon ' + icon).html(
            icon === 'success' ? '<i class="bi bi-check-circle"></i>' :
            icon === 'warning' ? '<i class="bi bi-exclamation-circle"></i>' :
            '<i class="bi bi-trash-fill"></i>'
        );

        if (details) {
            let detailsHtml = '';
            for (let [key, value] of Object.entries(details)) {
                detailsHtml += `
                    <div class="confirm-detail-row">
                        <span class="confirm-detail-label">${key}:</span>
                        <span class="confirm-detail-value">${value}</span>
                    </div>
                `;
            }
            $('#confirmDetails').html(detailsHtml).show();
        } else {
            $('#confirmDetails').hide();
        }

        $('#confirmModal').addClass('show');
    }

    // Hide confirmation modal
    function hideConfirmModal() {
        $('#confirmModal').removeClass('show');
        confirmAction = null;
        confirmData = null;
    }

    // Form submission
    $('#orderForm').on('submit', function(e) {
        // If form is already confirmed, allow submission
        if (isFormConfirmed) {
            isFormConfirmed = false;
            return true;
        }

        e.preventDefault();
        
        if ($('.product-row').length === 0) {
            alert('Cannot process order with no products.');
            return false;
        }

        let allFilled = true;
        $('.product-row').each(function() {
            const index = $(this).data('index');
            const unitRateInput = $(`input[name="unit_rate[${index}]"]`);
            const unitRate = parseFloat(unitRateInput.val());
            
            if (!unitRateInput.val() || unitRate <= 0) {
                unitRateInput.addClass('is-invalid');
                allFilled = false;
            } else {
                unitRateInput.removeClass('is-invalid');
            }
        });

        if (!allFilled) {
            alert('Please enter valid unit rates (greater than 0) for all products.');
            return false;
        }

        const subtotal = $('#subtotalInput').val();
        const gstAmount = $('#gstAmountInput').val();
        const grandTotal = $('#grandTotalInput').val();

        const details = {
            'Subtotal': '₹ ' + parseFloat(subtotal).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}),
            'GST Amount': '₹ ' + parseFloat(gstAmount).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}),
            'Grand Total': '₹ ' + parseFloat(grandTotal).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})
        };

        confirmAction = 'approve';
        confirmData = { action: 'approve' };

        showConfirmModal(
            'Approve Order',
            'Are you sure you want to approve this order with the entered pricing?',
            'success',
            details
        );
    });

    // Save as draft
    $('#saveAsDraftBtn').on('click', function(e) {
        e.preventDefault();
        
        // Validate before showing modal
        if ($('.product-row').length === 0) {
            alert('Cannot save order with no products.');
            return false;
        }

        let allFilled = true;
        $('.product-row').each(function() {
            const index = $(this).data('index');
            const unitRateInput = $(`input[name="unit_rate[${index}]"]`);
            const unitRate = parseFloat(unitRateInput.val());
            
            if (!unitRateInput.val() || unitRate <= 0) {
                unitRateInput.addClass('is-invalid');
                allFilled = false;
            } else {
                unitRateInput.removeClass('is-invalid');
            }
        });

        if (!allFilled) {
            alert('Please enter valid unit rates (greater than 0) for all products.');
            return false;
        }

        confirmAction = 'draft';
        confirmData = { action: 'draft' };

        showConfirmModal(
            'Save as Draft',
            'Save this order as draft? You can edit it later.',
            'warning'
        );
    });

    // Reject order
    $('#rejectBtn').on('click', function(e) {
        e.preventDefault();
        confirmAction = 'reject';
        confirmData = { action: 'reject' };

        showConfirmModal(
            'Reject Order',
            'Are you sure you want to reject this order? This action cannot be undone.',
            'danger'
        );
    });

    // Confirm submit - Now properly adds action and submits
    $('#confirmSubmit').on('click', function() {
        // Store action value BEFORE hiding modal
        const actionValue = confirmAction;
        
        hideConfirmModal();

        // Build products JSON array
        let productsData = [];
        $('.product-row').each(function() {
            const index = $(this).data('index');
            const quantity = parseFloat($(`input[name="quantity[${index}]"]`).val()) || 0;
            const unitRate = parseFloat($(`input[name="unit_rate[${index}]"]`).val()) || 0;
            const gstRate = parseFloat($(`input[name="gst_rate[${index}]"]`).val()) || 0;
            const gstAmount = (quantity * unitRate * (gstRate / 100)).toFixed(2);
            const totalAmount = (quantity * unitRate + parseFloat(gstAmount)).toFixed(2);

            productsData.push({
                uom: $(`input[name="uom[${index}]"]`).val(),
                quantity: quantity,
                product_id: $(`input[name="product_id[${index}]"]`).val(),
                category_id: $(`input[name="category_id[${index}]"]`).val(),
                subcategory_id: $(`input[name="subcategory_id[${index}]"]`).val(),
                unit_rate: unitRate.toFixed(2),
                gst_rate: gstRate.toFixed(2),
                gst_amount: gstAmount,
                total_amount: totalAmount
            });
        });

        // Remove any existing action input
        $('input[name="action"]').remove();
        $('input[name="products_json"]').remove();

        // Create action input
        const actionInput = $('<input type="hidden" name="action">');
        actionInput.val(actionValue);
        $('#orderForm').append(actionInput);

        // Create products JSON input
        const productsInput = $('<input type="hidden" name="products_json">');
        productsInput.val(JSON.stringify(productsData));
        $('#orderForm').append(productsInput);

        // Set flag to bypass validation
        isFormConfirmed = true;
        
        // Submit the form
        $('#orderForm').submit();
    });

    // Cancel confirmation
    $('#confirmCancel').on('click', function() {
        hideConfirmModal();
    });

    // Close modal when clicking outside
    $('#confirmModal').on('click', function(e) {
        if (e.target === this) {
            hideConfirmModal();
        }
    });

    calculateTotals();
});
</script>
@endsection