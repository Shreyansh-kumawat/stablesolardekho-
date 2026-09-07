@extends('layouts.adminLayout')
@section('css')
<link rel="stylesheet" href="/assets/css/bootstrap-icons.min.css">
<style>
    :root {
        --primary-color: #2563eb;
        --success-color: #059669;
        --danger-color: #dc2626;
        --warning-color: #d97706;
        --light-bg: #f9fafb;
        --border-color: #e5e7eb;
        --text-dark: #1f2937;
        --text-muted: #6b7280;
    }

    .order-card {
        background: #fff; border: 1px solid var(--border-color); border-radius: 12px;
        padding: 20px; margin-bottom: 20px;
    }
    .order-card-head { padding-bottom: 14px; border-bottom: 1px solid #f3f4f6; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
    .order-card-head h2 { font-size: .85rem; font-weight: 700; color: #374151; margin: 0; text-transform: uppercase; letter-spacing: .04em; }
    .order-detail { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
    .order-detail-item { }
    .order-detail-label { font-size: .7rem; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 4px; }
    .order-detail-value { font-size: .9rem; font-weight: 600; color: var(--text-dark); }

    .products-table { background: white; border-radius: 12px; overflow: hidden; margin-bottom: 20px; border: 1px solid var(--border-color); }
    .products-table table { margin-bottom: 0; width: 100%; border-collapse: collapse; }
    .products-table thead { background: var(--light-bg); border-bottom: 1px solid var(--border-color); }
    .products-table th { padding: 10px 12px; font-weight: 700; color: var(--text-muted); border: none; font-size: .7rem; text-transform: uppercase; letter-spacing: .05em; }
    .products-table td { padding: 12px; vertical-align: middle; border-bottom: 1px solid #f3f4f6; }
    .products-table tbody tr:hover { background: var(--light-bg); }

    .product-info { display: flex; flex-direction: column; gap: 4px; }
    .product-name { font-weight: 700; color: var(--text-dark); font-size: .88rem; }
    .product-meta { font-size: .75rem; color: var(--text-muted); }

    .badge { padding: 4px 10px; border-radius: 20px; font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .03em; }
    .badge-status { padding: 4px 12px; border-radius: 20px; font-size: .75rem; font-weight: 700; display: inline-block; text-transform: uppercase; letter-spacing: .03em; }
    .badge-pending { background: #fef9c3; color: #92400e; }
    .badge-completed,.badge-confirmed { background: #dbeafe; color: #1e40af; }
    .badge-cancelled,.badge-rejected { background: #fee2e2; color: #991b1b; }
    .badge-delivered { background: #d1fae5; color: #065f46; }

    .remarks-card { background: white; border-radius: 12px; padding: 20px; margin-bottom: 16px; border: 1px solid var(--border-color); }
    .remarks-card h5 { color: var(--text-dark); margin-bottom: 12px; font-weight: 700; font-size: .9rem; }
    .remarks-card textarea { width: 100%; padding: 10px 12px; border: 1px solid var(--border-color); border-radius: 8px; font-size: .85rem; resize: vertical; min-height: 80px; }
    .remarks-card textarea:focus { border-color: var(--primary-color); outline: none; box-shadow: 0 0 0 3px rgba(37,99,235,0.12); }

    .action-buttons { display: flex; gap: 10px; margin-top: 16px; flex-wrap: wrap; justify-content: flex-end; }
    .action-buttons .btn { padding: 10px 22px; font-weight: 600; border-radius: 8px; border: none; font-size: .8rem; text-transform: uppercase; letter-spacing: .04em; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
    .btn-approve { background: #059669; color: white; }
    .btn-approve:hover { background: #047857; }
    .btn-cancel-req { background: #dc2626; color: white; }
    .btn-cancel-req:hover { background: #b91c1c; }
    .back-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 16px; background: white; color: var(--text-dark); border-radius: 8px; text-decoration: none; font-weight: 600; border: 1px solid var(--border-color); font-size: .8rem; transition: all .15s; }
    .back-btn:hover { border-color: var(--primary-color); color: var(--primary-color); }
    .page-header { margin-bottom: 24px; }
    .page-header h1 { font-size: 1.3rem; font-weight: 800; color: var(--text-dark); margin-bottom: 4px; }
    .page-header p { color: var(--text-muted); font-size: .8rem; }

    .price-input { width: 100px; padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; text-align: right; font-weight: 600; }
    .price-input:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 2px rgba(37,99,235,0.15); }
    .price-save-btn { background: #059669; color: #fff; border: none; padding: 4px 10px; border-radius: 5px; font-size: 11px; font-weight: 700; cursor: pointer; margin-left: 4px; }
    .price-saved { color: #059669; font-size: 11px; font-weight: 600; margin-left: 6px; }
    .cost-badge { display: inline-block; padding: 2px 8px; background: #fef3c7; color: #92400e; border-radius: 10px; font-size: 10px; font-weight: 600; margin-top: 4px; }
    .stock-badge { display: inline-block; padding: 2px 8px; background: #dbeafe; color: #1e40af; border-radius: 10px; font-size: 10px; font-weight: 600; }

    /* Fulfillment UI */
    .oi-card { background:#fff; border:1px solid #e5e7eb; border-radius:12px; margin-bottom:16px; overflow:hidden; }
    .oi-head { padding:14px 18px; background:#fafbfc; border-bottom:1px solid #eef0f2; display:flex; align-items:center; gap:14px; }
    .oi-title { font-size:.95rem; font-weight:600; color:#1f2937; }
    .oi-meta { font-size:.78rem; color:#6b7280; margin-top:2px; }
    .oi-price { margin-left:auto; text-align:right; }
    .oi-price .amt { font-size:1rem; font-weight:700; color:#111827; }
    .stock-row { padding:12px 18px; background:#fff; border-bottom:1px solid #f1f3f5; }
    .stock-label { font-size:.68rem; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.06em; margin-bottom:8px; }
    .stock-pills { display:flex; flex-wrap:wrap; gap:6px; }
    .pill { display:inline-flex; align-items:center; gap:6px; padding:5px 10px; border-radius:16px; font-size:.75rem; font-weight:600; }
    .pill .dot { width:7px; height:7px; border-radius:50%; }
    .pill.main { background:#d1fae5; color:#047857; } .pill.main .dot { background:#10b981; }
    .pill.wh { background:#e0e7ff; color:#4338ca; } .pill.wh .dot { background:#6366f1; }
    .pill.tot { background:#f3f4f6; color:#374151; } .pill.tot .dot { background:#9ca3af; }
    .pill.zero { opacity:.55; }

    .ff-section { padding:14px 18px; background:#fefce8; border-top:1px solid #fde68a; }
    .ff-section.done { background:#f0fdf4; border-top-color:#bbf7d0; }
    .ff-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; }
    .ff-title { font-size:.82rem; font-weight:700; color:#78350f; }
    .ff-section.done .ff-title { color:#166534; }
    .ff-need { font-size:.78rem; font-weight:600; color:#78350f; }

    .src-row { display:flex; gap:8px; align-items:center; margin-bottom:8px; }
    .src-row select, .src-row input[type=number] { padding:7px 10px; border:1px solid #d1d5db; border-radius:6px; font-size:.85rem; }
    .src-row select { flex:1; }
    .src-row input[type=number] { width:110px; }
    .src-remove { background:#fee2e2; color:#b91c1c; border:none; width:32px; height:32px; border-radius:6px; cursor:pointer; font-weight:700; display:flex; align-items:center; justify-content:center; }
    .add-src-btn { background:#4f46e5; color:#fff; border:none; padding:6px 12px; border-radius:6px; font-size:.78rem; font-weight:600; cursor:pointer; }
    .alloc-status { font-size:.78rem; font-weight:600; margin-top:8px; padding:6px 10px; border-radius:6px; }
    .alloc-status.ok { background:#d1fae5; color:#065f46; }
    .alloc-status.warn { background:#fef3c7; color:#92400e; }
    .alloc-status.err { background:#fee2e2; color:#991b1b; }
    .ff-submit { margin-top:10px; width:100%; padding:9px; background:#059669; color:#fff; border:none; border-radius:8px; font-weight:700; font-size:.85rem; cursor:pointer; }
    .ff-submit:hover:not(:disabled) { background:#047857; }
    .ff-submit:disabled { background:#a7f3d0; cursor:not-allowed; }

    .audit-box { padding:12px 18px; background:#f0fdf4; border-top:1px solid #bbf7d0; }
    .audit-title { font-size:.72rem; font-weight:700; color:#166534; text-transform:uppercase; letter-spacing:.06em; margin-bottom:8px; }
    .audit-log { background:#fff; border:1px solid #d1fae5; border-radius:6px; padding:8px 12px; margin-bottom:8px; }
    .audit-log-item { font-size:.78rem; color:#374151; padding:2px 0; }
    .audit-log-item strong { color:#065f46; }

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
        <div class="page-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;">
            <div style="display:flex;align-items:center;gap:.75rem;">
                <div style="width:40px;height:40px;background:var(--primary-color);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;">
                    <i class="bi bi-receipt"></i>
                </div>
                <div>
                    <h1>Inventory Request Details</h1>
                    <p>Review and take action on this request</p>
                </div>
            </div>
            <a href="{{ route('pendingOrders') }}" class="back-btn">
                <i class="bi bi-arrow-left"></i> Back to Requests
            </a>
        </div>

        @if(session('success'))
        <div style="margin-bottom:16px;padding:14px 18px;background:#d1fae5;border:1px solid #a7f3d0;color:#065f46;border-radius:10px;font-size:.88rem;font-weight:600;">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div style="margin-bottom:16px;padding:14px 18px;background:#fee2e2;border:1px solid #fecaca;color:#991b1b;border-radius:10px;font-size:.88rem;font-weight:600;">
            {{ session('error') }}
        </div>
        @endif

        @if($order)
        <div class="order-card">
            <div class="order-card-head">
                <i class="bi bi-info-circle" style="color:var(--primary-color);"></i>
                <h2>Order Information</h2>
            </div>
            <div class="order-detail">
                <div class="order-detail-item">
                    <div class="order-detail-label">Request ID</div>
                    <div class="order-detail-value" style="font-family:monospace;">{{ $order->order_id }}</div>
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
                    <div class="badge-status badge-{{ strtolower($order->status) }}">{{ ucfirst($order->status) }}</div>
                </div>
            </div>
        </div>

        {{-- Products Table with Price, Stock, Cost --}}
        @php
            $productsArr = $order->products;
            if (is_string($productsArr)) $productsArr = json_decode($productsArr, true);
            if (!is_array($productsArr)) $productsArr = [];
            $orderGrandTotal = 0;
        @endphp

        <div class="products-table">
            <table>
                <thead>
                    <tr>
                        <th style="width:5%;">#</th>
                        <th style="width:30%;">Product</th>
                        <th style="text-align:center;">UOM</th>
                        <th style="text-align:center;">Qty</th>
                        <th style="text-align:right;">Unit Price</th>
                        <th style="text-align:right;">Total</th>
                        <th style="text-align:center;">Stock Left</th>
                        <th style="text-align:right;">Cost Price</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productsArr as $pIdx => $product)
                        @php
                            $productDetails = \App\Models\Product::find($product['product_id'] ?? null);
                            $categoryDetails = \App\Models\ProductCategory::find($product['category_id'] ?? null);
                            $qty = (int)($product['quantity'] ?? 0);
                            $unitPrice = $product['price'] ?? ($productDetails ? $productDetails->current_sale_price : 0) ?? 0;
                            $lineTotal = $unitPrice * $qty;
                            $orderGrandTotal += $lineTotal;
                            $info = $stockInfo[$pIdx] ?? null;
                            $totalStock = $info ? ($info['main'] + ($info['warehouses']->sum('available_qty'))) : ($productDetails ? $productDetails->quantity : 0);
                            $costPrice = $info['cost_price'] ?? null;
                        @endphp
                        <tr>
                            <td><strong>{{ $pIdx + 1 }}</strong></td>
                            <td>
                                <div class="product-info">
                                    <span class="product-name">{{ $productDetails ? $productDetails->item_name : 'Unknown' }}</span>
                                    <span class="product-meta">
                                        <i class="bi bi-tag"></i> {{ $categoryDetails ? $categoryDetails->category_name : 'N/A' }}
                                        @if($productDetails && $productDetails->item_code)
                                            &middot; {{ $productDetails->item_code }}
                                        @endif
                                    </span>
                                </div>
                            </td>
                            <td style="text-align:center;">
                                <span class="badge bg-primary" style="padding:4px 10px;border-radius:12px;font-size:11px;">{{ $product['uom'] ?? ($productDetails->uom ?? 'N/A') }}</span>
                            </td>
                            <td style="text-align:center;"><strong>{{ $qty }}</strong></td>
                            <td style="text-align:right;">
                                <div class="price-edit-wrap" data-idx="{{ $pIdx }}">
                                    <input type="number" step="0.01" min="0" class="price-input" id="priceInput{{ $pIdx }}" value="{{ $unitPrice }}" onchange="savePrice({{ $pIdx }})">
                                    <span class="price-saved" id="priceSaved{{ $pIdx }}" style="display:none;">Saved!</span>
                                </div>
                            </td>
                            <td style="text-align:right;font-weight:700;color:#111827;" id="lineTotal{{ $pIdx }}">
                                &#8377;{{ number_format($lineTotal, 2) }}
                            </td>
                            <td style="text-align:center;">
                                <span class="stock-badge">{{ $totalStock }} left</span>
                            </td>
                            <td style="text-align:right;">
                                @if($costPrice)
                                    <span class="cost-badge">&#8377;{{ number_format($costPrice, 2) }}</span>
                                @else
                                    <span style="color:#9ca3af;font-size:11px;">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted);">
                                <i class="bi bi-inbox" style="font-size:36px;display:block;margin-bottom:12px;"></i>
                                No products in this request
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($productsArr) > 0)
                <tfoot>
                    <tr style="background:#f9fafb;">
                        <td colspan="5" style="text-align:right;font-weight:700;font-size:14px;padding:16px 12px;">Grand Total</td>
                        <td style="text-align:right;font-weight:800;font-size:16px;color:#4f46e5;padding:16px 12px;" id="grandTotalDisplay">
                            &#8377;{{ number_format($orderGrandTotal, 2) }}
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        {{-- Fulfillment Section --}}
        @if(in_array($order->status, ['confirmed', 'completed', 'pending']))
        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:15px;padding:20px;margin-bottom:30px;box-shadow:0 4px 12px rgba(0,0,0,0.08);">
            <div style="padding:0 4px 14px;border-bottom:1px solid #f3f4f6;margin-bottom:16px;">
                <h2 style="font-size:1rem;font-weight:700;color:#1f2937;">Stock Allocation & Fulfillment</h2>
                <p style="font-size:.78rem;color:#6b7280;margin-top:2px;">Select sources (Main Inventory / Warehouses) to fulfill each product. Serials are required for serial-tracked products.</p>
            </div>

            @foreach($productsArr as $pIdx => $prod)
            @php
                $productDetails = \App\Models\Product::find($prod['product_id'] ?? null);
                $info = $stockInfo[$pIdx] ?? null;
                $mainStock = $info ? $info['main'] : 0;
                $whList = $info ? $info['warehouses'] : collect();
                $totalStock = $mainStock + ($whList->sum('available_qty'));
                $qty = (int)($prod['quantity'] ?? 0);
                $done = $info && $info['remaining'] == 0 && $info['total_fulfilled'] >= $qty;
                $unitPrice = $prod['price'] ?? ($productDetails ? $productDetails->current_sale_price : 0) ?? 0;
            @endphp
            <div class="oi-card">
                <div class="oi-head">
                    <div style="flex:1;min-width:0;">
                        <div class="oi-title">{{ $productDetails ? $productDetails->item_name : 'Unknown' }}</div>
                        <div class="oi-meta">Ordered: <strong>{{ $qty }}</strong> &middot; &#8377;{{ number_format($unitPrice, 2) }} each</div>
                    </div>
                    <div class="oi-price">
                        <div class="amt">&#8377;{{ number_format($unitPrice * $qty, 2) }}</div>
                    </div>
                </div>

                <div class="stock-row">
                    <div class="stock-label">Current Stock Breakdown</div>
                    <div class="stock-pills">
                        <span class="pill main {{ $mainStock == 0 ? 'zero' : '' }}"><span class="dot"></span> Main: {{ $mainStock }}</span>
                        @foreach($warehouses as $wh)
                            @php $whQ = optional($whList->get($wh->id))->available_qty ?? 0; @endphp
                            <span class="pill wh {{ $whQ == 0 ? 'zero' : '' }}"><span class="dot"></span> {{ $wh->name }}: {{ $whQ }}</span>
                        @endforeach
                        <span class="pill tot"><span class="dot"></span> Total: {{ $totalStock }}</span>
                    </div>
                </div>

                @if($info && $info['total_fulfilled'] > 0)
                <div class="audit-box">
                    <div class="audit-title">Fulfillment Log</div>
                    <div class="audit-log">
                        @if($info['fulfilled_main'] > 0)
                            <div class="audit-log-item">&#10003; <strong>Main Inventory:</strong> {{ $info['fulfilled_main'] }} deducted</div>
                        @endif
                        @foreach($warehouses as $wh)
                            @if(($info['fulfilled_wh'][$wh->id] ?? 0) > 0)
                                <div class="audit-log-item">&#10003; <strong>{{ $wh->name }}:</strong> {{ $info['fulfilled_wh'][$wh->id] }} deducted</div>
                            @endif
                        @endforeach
                    </div>
                    <div style="font-size:.8rem;color:#166534;font-weight:600;">Fulfilled: {{ $info['total_fulfilled'] }} / {{ $qty }}</div>
                </div>
                @endif

                @if(!$done)
                <div class="ff-section">
                    <div class="ff-header">
                        <div class="ff-title">Allocate Stock Sources</div>
                        <div class="ff-need">Needed: <strong>{{ $info ? $info['remaining'] : $qty }}</strong></div>
                    </div>

                    @if($totalStock < ($info ? $info['remaining'] : $qty))
                    <div class="alloc-status err" style="margin-bottom:10px;">
                        &#9888; Insufficient stock! Available: {{ $totalStock }}, Need: {{ $info ? $info['remaining'] : $qty }}.
                    </div>
                    @endif

                    @if($info && ($info['is_serial_tracked'] ?? false))
                    <div style="margin-bottom:10px;padding:8px 12px;background:#f0f9ff;border:1px solid #bae6fd;border-radius:6px;font-size:.82rem;color:#0c4a6e;">
                        <strong>Serial-tracked product.</strong> Pick specific serial numbers for each source below.
                    </div>
                    @endif

                    <form method="POST" action="{{ route('admin.cpOrder.fulfill', $order->id) }}" id="ffForm{{ $pIdx }}" data-pidx="{{ $pIdx }}" data-is-serial="{{ $info && ($info['is_serial_tracked'] ?? false) ? 1 : 0 }}">
                        @csrf
                        <input type="hidden" name="product_index" value="{{ $pIdx }}">

                        <div id="srcRows{{ $pIdx }}">
                            <div class="src-row" data-idx="0">
                                <select name="sources[0][source]" onchange="onSrcChange({{ $pIdx }}, this, 0)">
                                    <option value="">-- Select source --</option>
                                    @if($mainStock > 0)
                                        <option value="main" data-max="{{ $mainStock }}">Main Inventory ({{ $mainStock }} avail)</option>
                                    @endif
                                    @foreach($warehouses as $wh)
                                        @php $whQ = optional($whList->get($wh->id))->available_qty ?? 0; @endphp
                                        @if($whQ > 0)
                                            <option value="wh:{{ $wh->id }}" data-max="{{ $whQ }}">{{ $wh->name }} ({{ $whQ }} avail)</option>
                                        @endif
                                    @endforeach
                                </select>
                                <input type="number" name="sources[0][qty]" min="1" placeholder="Qty" oninput="onQtyChange({{ $pIdx }}, 0)">
                            </div>
                        </div>

                        <div id="serialPickers{{ $pIdx }}" style="display:none;"></div>

                        <div style="display:flex;gap:8px;align-items:center;">
                            <button type="button" class="add-src-btn" onclick="addSrcRow({{ $pIdx }})">+ Add source</button>
                            <div class="alloc-status warn" id="allocStatus{{ $pIdx }}" style="flex:1;text-align:right;margin-top:0;">Allocated: 0 / {{ $info ? $info['remaining'] : $qty }}</div>
                        </div>

                        <button type="submit" class="ff-submit" id="ffBtn{{ $pIdx }}" disabled>Deduct Stock & Fulfill</button>
                    </form>
                </div>

                @if($info && ($info['is_serial_tracked'] ?? false) && count($info['assigned_serials']) > 0)
                <div style="margin:0 18px 14px;padding:10px 14px;background:#ecfdf5;border:1px solid #bbf7d0;border-radius:8px;">
                    <div style="font-size:.78rem;font-weight:700;color:#065f46;margin-bottom:6px;">Serials Allocated:</div>
                    <div style="display:flex;flex-wrap:wrap;gap:5px;">
                        @foreach($info['assigned_serials'] as $sn)
                        <span style="background:#fff;border:1px solid #86efac;color:#065f46;font-family:monospace;font-size:.72rem;padding:3px 8px;border-radius:4px;">{{ $sn }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                @else
                <div class="ff-section done">
                    <div class="ff-title" style="text-align:center;">&#10003; Product fully fulfilled</div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        @if($order->order_notes)
        <div class="remarks-card">
            <h5><i class="bi bi-chat-dots"></i> CP Remarks</h5>
            <p style="color: var(--text-muted);">{{ $order->order_notes }}</p>
        </div>
        @endif

        @if($order->payment_screenshot)
        <div class="remarks-card">
            <h5><i class="bi bi-receipt"></i> Payment Receipt</h5>
            <div style="display:flex;gap:1rem;align-items:flex-start;flex-wrap:wrap;">
                <div>
                    <a href="{{ url('serve/' . $order->payment_screenshot) }}" target="_blank">
                        <img src="{{ url('serve/' . $order->payment_screenshot) }}" alt="Payment Receipt" style="max-width:300px;max-height:250px;border-radius:8px;border:1px solid var(--border-color);cursor:pointer;">
                    </a>
                </div>
                <div>
                    <p style="margin:0 0 .5rem;"><strong>Payment Status:</strong>
                        @if($order->payment_status === 'verification_pending')
                            <span class="badge" style="background:#fff3cd;color:#856404;">Verification Pending</span>
                        @elseif($order->payment_status === 'paid')
                            <span class="badge" style="background:#d1fae5;color:#065f46;">Approved</span>
                        @elseif($order->payment_status === 'failed')
                            <span class="badge" style="background:#fee2e2;color:#991b1b;">Rejected</span>
                        @else
                            <span class="badge" style="background:#f3f4f6;color:#374151;">{{ ucfirst($order->payment_status ?? 'pending') }}</span>
                        @endif
                    </p>
                    @if($order->payment_reference)
                    <p style="margin:0 0 .5rem;"><strong>Reference:</strong> {{ $order->payment_reference }}</p>
                    @endif
                    @if($order->payment_status === 'verification_pending')
                    <div style="display:flex;gap:.5rem;margin-top:1rem;">
                        <form method="POST" action="{{ route('approveCpPayment', $order->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-approve" style="padding:8px 18px;font-size:12px;" onclick="return confirmPaymentApproval(event, this)">
                                <i class="bi bi-check-circle"></i> Approve
                            </button>
                        </form>
                        <form method="POST" action="{{ route('rejectCpPayment', $order->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-cancel-req" style="padding:8px 18px;font-size:12px;" onclick="return confirm('Reject this payment?')">
                                <i class="bi bi-x-circle"></i> Reject
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Bills Section --}}
        <div class="remarks-card">
            <h5><i class="bi bi-file-earmark-text"></i> Bills</h5>

            @php $bills = $order->bills()->with('uploadedBy')->orderByDesc('created_at')->get(); @endphp

            @if($bills->count() > 0)
            <div style="margin-bottom:16px;">
                @foreach($bills as $bill)
                <div style="display:flex;align-items:center;gap:12px;padding:10px 12px;background:var(--light-bg);border:1px solid var(--border-color);border-radius:8px;margin-bottom:8px;">
                    <i class="bi bi-file-earmark-pdf" style="font-size:1.4rem;color:var(--danger-color);"></i>
                    <div style="flex:1;min-width:0;">
                        <a href="{{ url('serve/' . $bill->file_path) }}" target="_blank" style="font-weight:600;color:var(--primary-color);font-size:.85rem;text-decoration:none;">
                            {{ $bill->file_name }}
                        </a>
                        <div style="font-size:.75rem;color:var(--text-muted);">
                            {{ $bill->uploadedBy->name ?? 'Admin' }} &middot; {{ $bill->created_at->format('d M Y, h:i A') }}
                            @if($bill->remarks) &middot; {{ $bill->remarks }} @endif
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.cpOrder.deleteBill', $bill->id) }}" style="margin:0;">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Delete this bill?')" style="background:none;border:none;color:var(--danger-color);cursor:pointer;font-size:1.1rem;padding:4px;" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('admin.cpOrder.uploadBill', $order->id) }}" enctype="multipart/form-data" id="billUploadForm">
                @csrf
                <label style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:var(--primary-color);color:#fff;border-radius:8px;font-size:.8rem;font-weight:600;cursor:pointer;">
                    <i class="bi bi-plus-circle"></i> Add Bill
                    <input type="file" name="bill_file" required style="display:none;" onchange="document.getElementById('billUploadForm').submit();">
                </label>
            </form>
        </div>

        @if($order->status == 'pending')
        <div class="remarks-card">
            <h5><i class="bi bi-chat-left-text"></i> Admin Remarks (Optional)</h5>
            <textarea id="adminRemarks" placeholder="Add remarks for this request..."></textarea>
        </div>

        <div class="action-buttons">
            <form method="POST" action="{{ route('approveInventoryRequest', $order->id) }}" id="approveForm">
                @csrf
                <input type="hidden" name="admin_remarks" class="admin-remarks-input">
                <button type="submit" class="btn btn-approve" onclick="return confirmAction(event, this, 'approve')">
                    <i class="bi bi-check-circle"></i> Approve Request
                </button>
            </form>
            <form method="POST" action="{{ route('cancelInventoryRequest', $order->id) }}" id="cancelForm">
                @csrf
                <input type="hidden" name="admin_remarks" class="admin-remarks-input">
                <button type="submit" class="btn btn-cancel-req" onclick="return confirmAction(event, this, 'cancel')">
                    <i class="bi bi-x-circle"></i> Cancel Request
                </button>
            </form>
        </div>
        @endif

        @if($order->status === 'confirmed')
        <div class="action-buttons">
            <form method="POST" action="{{ route('markCpOrderDelivered', $order->id) }}">
                @csrf
                <button type="submit" class="btn btn-approve" onclick="return confirm('Mark this order as delivered?')">
                    <i class="bi bi-check2-circle"></i> Mark as Delivered
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
        <div style="padding:40px;text-align:center;color:var(--text-muted);">
            <i class="bi bi-exclamation-triangle" style="font-size:36px;"></i>
            <p style="margin-top:12px;">Request not found.</p>
        </div>
        @endif
    </div>
</div>
@endsection

@section('js')
<script>
// Price editing
function savePrice(idx) {
    var input = document.getElementById('priceInput' + idx);
    var price = parseFloat(input.value) || 0;
    var saved = document.getElementById('priceSaved' + idx);

    fetch('{{ route("admin.cpOrder.updatePrice", $order->id ?? 0) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ product_index: idx, price: price })
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            saved.style.display = 'inline';
            setTimeout(function() { saved.style.display = 'none'; }, 2000);
            // Update line total
            var qty = parseInt(document.querySelectorAll('.products-table tbody tr')[idx].querySelector('td:nth-child(4) strong').textContent) || 0;
            document.getElementById('lineTotal' + idx).innerHTML = '&#8377;' + (price * qty).toLocaleString('en-IN', {minimumFractionDigits:2,maximumFractionDigits:2});
            // Update grand total
            if (data.grand_total !== undefined) {
                document.getElementById('grandTotalDisplay').innerHTML = '&#8377;' + parseFloat(data.grand_total).toLocaleString('en-IN', {minimumFractionDigits:2,maximumFractionDigits:2});
            }
        }
    });
}

// Fulfillment allocation JS
const srcRowCounts = {};
const itemNeeds = {};
@foreach($productsArr as $pIdx => $prod)
    @php $rem = isset($stockInfo[$pIdx]) ? $stockInfo[$pIdx]['remaining'] : (int)($prod['quantity'] ?? 0); @endphp
    srcRowCounts[{{ $pIdx }}] = 1;
    itemNeeds[{{ $pIdx }}] = {{ $rem }};
@endforeach

const availableSerialsByItem = {};
@foreach($productsArr as $pIdx => $prod)
    @if(isset($stockInfo[$pIdx]) && ($stockInfo[$pIdx]['is_serial_tracked'] ?? false))
    availableSerialsByItem[{{ $pIdx }}] = {
        @foreach($stockInfo[$pIdx]['available_serials'] as $srcKey => $ser)
        {!! json_encode((string)$srcKey) !!}: {!! json_encode($ser->values()->toArray()) !!},
        @endforeach
    };
    @endif
@endforeach

const selectedSerialsByRow = {};

function isSerialTrackedItem(pIdx) {
    var form = document.getElementById('ffForm' + pIdx);
    return form && form.getAttribute('data-is-serial') === '1';
}

function addSrcRow(pIdx) {
    const idx = srcRowCounts[pIdx]++;
    const wrap = document.getElementById('srcRows' + pIdx);
    const first = wrap.querySelector('.src-row select');
    const optionsHtml = first ? first.innerHTML : '';
    const row = document.createElement('div');
    row.className = 'src-row';
    row.setAttribute('data-idx', idx);
    row.innerHTML = '<select name="sources[' + idx + '][source]" onchange="onSrcChange(' + pIdx + ', this, ' + idx + ')">' + optionsHtml + '</select>'
        + '<input type="number" name="sources[' + idx + '][qty]" min="1" placeholder="Qty" oninput="onQtyChange(' + pIdx + ', ' + idx + ')">'
        + '<button type="button" class="src-remove" onclick="removeSrcRow(this, ' + pIdx + ', ' + idx + ')">&times;</button>';
    wrap.appendChild(row);
}

function removeSrcRow(btn, pIdx, idx) {
    btn.parentElement.remove();
    var picker = document.getElementById('srcSerials_' + pIdx + '_' + idx);
    if (picker) picker.remove();
    delete selectedSerialsByRow[pIdx + '_' + idx];
    recalcAlloc(pIdx);
    syncSerialInputs(pIdx);
}

function onSrcChange(pIdx, sel, idx) {
    recalcAlloc(pIdx);
    if (!isSerialTrackedItem(pIdx)) return;
    rebuildSerialPicker(pIdx, idx);
}
function onQtyChange(pIdx, idx) {
    recalcAlloc(pIdx);
    if (!isSerialTrackedItem(pIdx)) return;
    rebuildSerialPicker(pIdx, idx);
}

function rebuildSerialPicker(pIdx, idx) {
    var wrap = document.getElementById('serialPickers' + pIdx);
    var row = document.querySelector('#srcRows' + pIdx + ' .src-row[data-idx="' + idx + '"]');
    if (!row || !wrap) return;
    var sel = row.querySelector('select');
    var qtyInput = row.querySelector('input[type=number]');
    var src = sel.value;
    var qty = parseInt(qtyInput.value) || 0;

    var existing = document.getElementById('srcSerials_' + pIdx + '_' + idx);
    if (existing) existing.remove();
    delete selectedSerialsByRow[pIdx + '_' + idx];
    if (!src || qty <= 0) { wrap.style.display = wrap.children.length ? '' : 'none'; syncSerialInputs(pIdx); return; }

    var avail = (availableSerialsByItem[pIdx] || {})[src] || [];
    var preSelected = avail.slice(0, Math.min(qty, avail.length));
    selectedSerialsByRow[pIdx + '_' + idx] = new Set(preSelected);

    var srcLabel = sel.options[sel.selectedIndex].text.split(' (')[0];
    var block = document.createElement('div');
    block.id = 'srcSerials_' + pIdx + '_' + idx;
    block.style.cssText = 'margin-top:10px;padding:12px;background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;';
    block.innerHTML =
        '<div style="display:flex;justify-content:space-between;margin-bottom:8px;align-items:center;flex-wrap:wrap;gap:6px;">'
      + '<span style="font-weight:600;font-size:.84rem;color:#0c4a6e;">Serials from ' + srcLabel + ' (' + qty + ' needed)</span>'
      + '<div style="display:flex;gap:6px;">'
      + '<button type="button" onclick="autoPickSourceSerials(' + pIdx + ',' + idx + ')" style="background:#2563eb;color:#fff;border:none;padding:4px 10px;border-radius:5px;font-size:.75rem;font-weight:600;cursor:pointer;">Auto-Pick</button>'
      + '<button type="button" onclick="clearSourceSerials(' + pIdx + ',' + idx + ')" style="background:#f3f4f6;color:#374151;border:1px solid #d1d5db;padding:4px 10px;border-radius:5px;font-size:.75rem;font-weight:600;cursor:pointer;">Clear</button>'
      + '</div></div>'
      + '<div id="srcSerialList_' + pIdx + '_' + idx + '" style="max-height:180px;overflow-y:auto;background:#fff;border:1px solid #e5e7eb;border-radius:6px;padding:6px;"></div>'
      + '<div style="margin-top:6px;font-size:.78rem;color:#374151;font-weight:600;" id="srcSerialCount_' + pIdx + '_' + idx + '">0 / ' + qty + '</div>';
    wrap.appendChild(block);
    wrap.style.display = '';
    renderSerialCheckboxes(pIdx, idx);
}

function renderSerialCheckboxes(pIdx, idx) {
    var row = document.querySelector('#srcRows' + pIdx + ' .src-row[data-idx="' + idx + '"]');
    var sel = row.querySelector('select');
    var qty = parseInt(row.querySelector('input[type=number]').value) || 0;
    var avail = (availableSerialsByItem[pIdx] || {})[sel.value] || [];
    var selected = selectedSerialsByRow[pIdx + '_' + idx] || new Set();
    var listEl = document.getElementById('srcSerialList_' + pIdx + '_' + idx);
    if (!listEl) return;
    var html = '';
    avail.forEach(function(sn) {
        var checked = selected.has(sn) ? 'checked' : '';
        html += '<label style="display:flex;align-items:center;gap:8px;padding:4px 8px;border-bottom:1px solid #f3f4f6;cursor:pointer;font-family:monospace;font-size:.78rem;">'
              + '<input type="checkbox" ' + checked + ' value="' + sn + '" onchange="toggleSourceSerial(' + pIdx + ',' + idx + ',this)"> ' + sn
              + '</label>';
    });
    if (!avail.length) html = '<div style="padding:8px;text-align:center;color:#94a3b8;font-size:.82rem;">No serials at this source</div>';
    listEl.innerHTML = html;
    var countEl = document.getElementById('srcSerialCount_' + pIdx + '_' + idx);
    if (countEl) {
        countEl.textContent = selected.size + ' selected / ' + qty + ' needed';
        countEl.style.color = (qty > 0 && selected.size === qty) ? '#059669' : '#dc2626';
    }
    syncSerialInputs(pIdx);
    recalcAlloc(pIdx);
}

function toggleSourceSerial(pIdx, idx, cb) {
    var key = pIdx + '_' + idx;
    if (!selectedSerialsByRow[key]) selectedSerialsByRow[key] = new Set();
    if (cb.checked) selectedSerialsByRow[key].add(cb.value);
    else selectedSerialsByRow[key].delete(cb.value);
    renderSerialCheckboxes(pIdx, idx);
}
function autoPickSourceSerials(pIdx, idx) {
    var row = document.querySelector('#srcRows' + pIdx + ' .src-row[data-idx="' + idx + '"]');
    var sel = row.querySelector('select');
    var qty = parseInt(row.querySelector('input[type=number]').value) || 0;
    var avail = (availableSerialsByItem[pIdx] || {})[sel.value] || [];
    selectedSerialsByRow[pIdx + '_' + idx] = new Set(avail.slice(0, qty));
    renderSerialCheckboxes(pIdx, idx);
}
function clearSourceSerials(pIdx, idx) {
    selectedSerialsByRow[pIdx + '_' + idx] = new Set();
    renderSerialCheckboxes(pIdx, idx);
}

function syncSerialInputs(pIdx) {
    var form = document.getElementById('ffForm' + pIdx);
    if (!form) return;
    form.querySelectorAll('input.serial-hidden').forEach(function(el) { el.remove(); });
    Object.keys(selectedSerialsByRow).forEach(function(key) {
        if (!key.startsWith(pIdx + '_')) return;
        var idx = key.split('_')[1];
        selectedSerialsByRow[key].forEach(function(sn) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.className = 'serial-hidden';
            input.name = 'serials[' + idx + '][]';
            input.value = sn;
            form.appendChild(input);
        });
    });
}

function recalcAlloc(pIdx) {
    const wrap = document.getElementById('srcRows' + pIdx);
    const rows = wrap.querySelectorAll('.src-row');
    let total = 0, hasError = false, serialMismatch = false;
    const usedSources = {};
    const isSerial = isSerialTrackedItem(pIdx);
    rows.forEach(function(row) {
        const idx = row.getAttribute('data-idx');
        const sel = row.querySelector('select');
        const qtyInput = row.querySelector('input[type=number]');
        const src = sel.value;
        const qty = parseInt(qtyInput.value) || 0;
        if (src && qty > 0) {
            const opt = sel.options[sel.selectedIndex];
            const max = parseInt(opt.getAttribute('data-max')) || 0;
            if (qty > max) { hasError = true; qtyInput.style.borderColor = '#dc2626'; }
            else { qtyInput.style.borderColor = '#d1d5db'; }
            if (usedSources[src]) { hasError = true; sel.style.borderColor = '#dc2626'; }
            else { sel.style.borderColor = '#d1d5db'; usedSources[src] = true; }
            total += qty;
            if (isSerial) {
                var ss = selectedSerialsByRow[pIdx + '_' + idx];
                if (!ss || ss.size !== qty) serialMismatch = true;
            }
        } else {
            qtyInput.style.borderColor = '#d1d5db';
            sel.style.borderColor = '#d1d5db';
        }
    });
    const need = itemNeeds[pIdx];
    const status = document.getElementById('allocStatus' + pIdx);
    const btn = document.getElementById('ffBtn' + pIdx);
    status.textContent = 'Allocated: ' + total + ' / ' + need;
    status.className = 'alloc-status';
    if (hasError) { status.classList.add('err'); status.textContent += ' (fix errors)'; btn.disabled = true; }
    else if (total === 0) { status.classList.add('warn'); btn.disabled = true; }
    else if (total < need) { status.classList.add('warn'); status.textContent += ' (add more)'; btn.disabled = true; }
    else if (total > need) { status.classList.add('err'); status.textContent += ' (over-allocated)'; btn.disabled = true; }
    else if (isSerial && serialMismatch) { status.classList.add('warn'); status.textContent += ' (pick serials)'; btn.disabled = true; }
    else { status.classList.add('ok'); btn.disabled = false; }
}

// Approve/Cancel actions
function confirmAction(e, btn, action) {
    var remarks = document.getElementById('adminRemarks') ? document.getElementById('adminRemarks').value : '';
    document.querySelectorAll('.admin-remarks-input').forEach(function(input) { input.value = remarks; });
    if (action === 'cancel') return confirm('Cancel this inventory request?');
    var form = btn.closest('form');
    e.preventDefault();
    fetch('{{ route("checkCpOrderStock", $order->id ?? 0) }}', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.warnings && data.warnings.length > 0) {
            var msg = 'Stock Warning!\n\n';
            data.warnings.forEach(function(w) { msg += w.name + ': ' + w.available + ' available, ' + w.requested + ' requested.\n'; });
            msg += '\nApprove anyway?';
            if (confirm(msg)) form.submit();
        } else {
            if (confirm('Approve this order?')) form.submit();
        }
    })
    .catch(function() { if (confirm('Could not check stock. Approve anyway?')) form.submit(); });
    return false;
}

function confirmPaymentApproval(e, btn) {
    var form = btn.closest('form');
    e.preventDefault();
    fetch('{{ route("checkCpOrderStock", $order->id ?? 0) }}', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.warnings && data.warnings.length > 0) {
            var msg = 'Stock Warning!\n\n';
            data.warnings.forEach(function(w) { msg += w.name + ': ' + w.available + ' available, ' + w.requested + ' requested.\n'; });
            msg += '\nApprove payment anyway?';
            if (confirm(msg)) form.submit();
        } else {
            if (confirm('Approve this payment?')) form.submit();
        }
    })
    .catch(function() { if (confirm('Could not check stock. Approve payment anyway?')) form.submit(); });
    return false;
}
</script>
@endsection
