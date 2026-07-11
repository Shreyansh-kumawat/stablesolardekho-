@extends('layouts.adminLayout')
@section('css')
<link rel="stylesheet" href="/assets/css/bootstrap-icons.min.css">
<style>
    :root {
        --primary-color: #667eea;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --warning-color: #ffc107;
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

    .products-table table { margin-bottom: 0; width: 100%; border-collapse: collapse; }

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
        border-bottom: 1px solid var(--border-color);
    }

    .products-table tbody tr:hover { background: #f8f9fa; }

    .product-info { display: flex; flex-direction: column; gap: 6px; }
    .product-name { font-weight: 700; color: var(--text-dark); font-size: 15px; }
    .product-meta { font-size: 12px; color: var(--text-muted); }

    .badge { padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px; }
    .badge-status { padding: 10px 18px; border-radius: 25px; font-size: 12px; font-weight: 700; display: inline-block; text-transform: uppercase; letter-spacing: 0.5px; }
    .badge-pending { background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%); color: #856404; box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3); }
    .badge-completed { background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); color: #155724; box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3); }
    .badge-cancelled { background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%); color: #721c24; box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3); }

    .remarks-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        border: 1px solid var(--border-color);
    }

    .remarks-card h5 { color: var(--text-dark); margin-bottom: 15px; font-weight: 700; font-size: 16px; }
    .remarks-card textarea {
        width: 100%; padding: 12px 14px; border: 2px solid var(--border-color); border-radius: 8px;
        font-size: 14px; resize: vertical; min-height: 80px;
    }
    .remarks-card textarea:focus { border-color: var(--primary-color); outline: none; box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15); }

    .action-buttons { display: flex; gap: 12px; margin-top: 30px; flex-wrap: wrap; justify-content: flex-end; }
    .action-buttons .btn {
        padding: 12px 28px; font-weight: 600; border-radius: 8px; border: none;
        transition: all 0.3s ease; font-size: 14px; text-transform: uppercase;
        letter-spacing: 0.5px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); cursor: pointer;
        text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-approve { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; }
    .btn-approve:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(40, 167, 69, 0.3); color: white; }
    .btn-cancel-req { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; }
    .btn-cancel-req:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(220, 53, 69, 0.3); color: white; }

    .back-btn {
        display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px;
        background: white; color: var(--text-dark); border-radius: 8px; text-decoration: none;
        font-weight: 600; border: 2px solid var(--border-color); font-size: 13px;
    }
    .back-btn:hover { background: var(--light-bg); border-color: var(--primary-color); color: var(--primary-color); }

    .page-header { margin-bottom: 35px; }
    .page-header h1 { font-size: 28px; font-weight: 700; color: var(--text-dark); margin-bottom: 8px; }
    .page-header p { color: var(--text-muted); font-size: 15px; }

    @media (max-width: 768px) {
        .order-detail { grid-template-columns: 1fr; gap: 15px; }
        .action-buttons { flex-direction: column; }
        .action-buttons .btn { width: 100%; justify-content: center; }
        .products-table th, .products-table td { padding: 10px 8px; font-size: 12px; }
    }
</style>
@endsection

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header flex items-center justify-between mb-8">
            <div>
                <h1 class="flex items-center gap-3">
                    <i class="bi bi-file-earmark-check" style="color: var(--primary-color);"></i>Inventory Request Details
                </h1>
                <p>Review and take action on this inventory request</p>
            </div>
            <a href="{{ route('pendingOrders') }}" class="back-btn">
                <i class="bi bi-arrow-left"></i> Back to Requests
            </a>
        </div>

        @if($order)
        <div class="order-card">
            <div class="order-detail">
                <div class="order-detail-item">
                    <div class="order-detail-label">Request ID</div>
                    <div class="order-detail-value">{{ $order->order_id }}</div>
                </div>
                <div class="order-detail-item">
                    <div class="order-detail-label">Request Date</div>
                    <div class="order-detail-value">{{ \Carbon\Carbon::parse($order->order_date)->format('d M, Y') }}</div>
                </div>
                <div class="order-detail-item">
                    <div class="order-detail-label">Channel Partner</div>
                    <div class="order-detail-value">{{ $order->channelPartner->cp_name ?? 'N/A' }}</div>
                </div>
                <div class="order-detail-item">
                    <div class="order-detail-label">Status</div>
                    <div class="badge-status badge-{{ strtolower($order->status) }}">
                        {{ $order->status == 'completed' ? 'Approved' : ucfirst($order->status) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="products-table">
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 45%;">Product</th>
                        <th style="width: 15%; text-align: center;">UOM</th>
                        <th style="width: 15%; text-align: center;">Quantity</th>
                    </tr>
                </thead>
                <tbody>
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
                        <tr>
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
                                <strong>{{ $product['quantity'] ?? 0 }}</strong>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                <i class="bi bi-inbox" style="font-size: 36px; margin-bottom: 12px; display: block;"></i>
                                <p style="font-size: 15px;">No products in this request</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($order->order_notes)
        <div class="remarks-card">
            <h5><i class="bi bi-chat-dots"></i> CP Remarks</h5>
            <p style="color: var(--text-muted);">{{ $order->order_notes }}</p>
        </div>
        @endif

        @if($order->payment_screenshot)
        <div class="remarks-card" style="margin-top:20px;">
            <h5><i class="bi bi-receipt"></i> Payment Receipt</h5>
            <div style="display:flex;gap:1rem;align-items:flex-start;flex-wrap:wrap;">
                <div>
                    <a href="{{ asset('storage/' . $order->payment_screenshot) }}" target="_blank">
                        <img src="{{ asset('storage/' . $order->payment_screenshot) }}" alt="Payment Receipt" style="max-width:300px;max-height:250px;border-radius:8px;border:1px solid var(--border-color);cursor:pointer;">
                    </a>
                </div>
                <div>
                    <p style="margin:0 0 .5rem;"><strong>Payment Status:</strong>
                        @if($order->payment_status === 'verification_pending')
                            <span class="badge bg-warning text-dark">Verification Pending</span>
                        @elseif($order->payment_status === 'paid')
                            <span class="badge bg-success">Approved</span>
                        @elseif($order->payment_status === 'failed')
                            <span class="badge bg-danger">Rejected</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($order->payment_status ?? 'pending') }}</span>
                        @endif
                    </p>
                    @if($order->payment_reference)
                    <p style="margin:0 0 .5rem;"><strong>Reference:</strong> {{ $order->payment_reference }}</p>
                    @endif
                    @if($order->payment_status === 'verification_pending')
                    <div style="display:flex;gap:.5rem;margin-top:1rem;">
                        <form method="POST" action="{{ route('approveCpPayment', $order->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this payment?')">
                                <i class="bi bi-check-circle"></i> Approve Payment
                            </button>
                        </form>
                        <form method="POST" action="{{ route('rejectCpPayment', $order->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Reject this payment?')">
                                <i class="bi bi-x-circle"></i> Reject Payment
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if($order->status == 'pending')
        <div class="remarks-card">
            <h5><i class="bi bi-chat-left-text"></i> Admin Remarks (Optional)</h5>
            <textarea id="adminRemarks" placeholder="Add remarks for this request..."></textarea>
        </div>

        <div class="action-buttons">
            <form method="POST" action="{{ route('approveInventoryRequest', $order->id) }}" id="approveForm">
                @csrf
                <input type="hidden" name="admin_remarks" class="admin-remarks-input">
                <button type="submit" class="btn btn-approve" onclick="return confirmAction(this, 'approve')">
                    <i class="bi bi-check-circle"></i> Approve Request
                </button>
            </form>
            <form method="POST" action="{{ route('cancelInventoryRequest', $order->id) }}" id="cancelForm">
                @csrf
                <input type="hidden" name="admin_remarks" class="admin-remarks-input">
                <button type="submit" class="btn btn-cancel-req" onclick="return confirmAction(this, 'cancel')">
                    <i class="bi bi-x-circle"></i> Cancel Request
                </button>
            </form>
        </div>
        @endif

        @if($order->admin_remarks)
        <div class="remarks-card" style="margin-top: 20px;">
            <h5><i class="bi bi-shield-check"></i> Admin Remarks</h5>
            <p style="color: var(--text-muted);">{{ $order->admin_remarks }}</p>
        </div>
        @endif

        @else
        <div class="alert alert-warning" role="alert">
            <i class="bi bi-exclamation-triangle"></i> Request not found.
        </div>
        @endif
    </div>
</div>
@endsection

@section('js')
<script>
function confirmAction(btn, action) {
    var remarks = document.getElementById('adminRemarks') ? document.getElementById('adminRemarks').value : '';
    document.querySelectorAll('.admin-remarks-input').forEach(function(input) {
        input.value = remarks;
    });

    var msg = action === 'approve'
        ? 'Are you sure you want to APPROVE this inventory request?'
        : 'Are you sure you want to CANCEL this inventory request?';

    return confirm(msg);
}
</script>
@endsection
