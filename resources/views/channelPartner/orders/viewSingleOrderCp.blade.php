@extends('layouts.adminLayout')
@section('css')
    <link rel="stylesheet" href="/assets/css/bootstrap-icons.min.css">
    <style>
        .sov-wrap { max-width: 1000px; margin: 0 auto; padding: 1.5rem 1rem; }

        .sov-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .75rem; margin-bottom: 1.5rem; }
        .sov-header-left { display: flex; align-items: center; gap: .75rem; }
        .sov-header-left h1 { font-size: 1.3rem; font-weight: 800; color: #1f2937; margin: 0; }
        .sov-header-left p { font-size: .8rem; color: #6b7280; margin: .15rem 0 0; }
        .sov-icon-box { width: 40px; height: 40px; background: #2563eb; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.1rem; }
        .sov-back { display: inline-flex; align-items: center; gap: 6px; padding: 7px 16px; font-size: .8rem; font-weight: 600; color: #374151; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; text-decoration: none; transition: all .15s; }
        .sov-back:hover { border-color: #2563eb; color: #2563eb; }

        .sov-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; margin-bottom: 1.25rem; }
        .sov-card-head { padding: 14px 20px; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; gap: 8px; }
        .sov-card-head h2 { font-size: .85rem; font-weight: 700; color: #374151; margin: 0; text-transform: uppercase; letter-spacing: .04em; }
        .sov-card-body { padding: 20px; }

        .sov-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
        .sov-field label { display: block; font-size: .7rem; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 4px; }
        .sov-field span { font-size: .9rem; font-weight: 600; color: #1f2937; }

        .sov-pill { display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 20px; font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .03em; }
        .sov-pill-pending { background: #fef9c3; color: #92400e; }
        .sov-pill-approved { background: #dcfce7; color: #166534; }
        .sov-pill-completed { background: #dcfce7; color: #166534; }
        .sov-pill-rejected { background: #fee2e2; color: #991b1b; }
        .sov-pill-confirmed { background: #dbeafe; color: #1e40af; }
        .sov-pill-delivered { background: #d1fae5; color: #065f46; }
        .sov-pill-cancelled { background: #f3f4f6; color: #6b7280; }

        .sov-table { width: 100%; border-collapse: collapse; }
        .sov-table thead th { background: #f9fafb; padding: 10px 14px; font-size: .7rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: .05em; text-align: left; border-bottom: 1px solid #e5e7eb; white-space: nowrap; }
        .sov-table tbody td { padding: 12px 14px; font-size: .84rem; color: #374151; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
        .sov-table tbody tr:hover { background: #f9fafb; }
        .sov-table .text-right { text-align: right; }
        .sov-table .text-center { text-align: center; }

        .sov-prod-name { font-weight: 700; color: #1f2937; font-size: .88rem; }
        .sov-prod-cat { font-size: .75rem; color: #9ca3af; margin-top: 2px; }
        .sov-prod-price { font-weight: 700; color: #2563eb; }
        .sov-amount { font-weight: 700; color: #1f2937; }

        .sov-summary { display: flex; flex-direction: column; gap: 8px; max-width: 340px; margin-left: auto; }
        .sov-summary-row { display: flex; justify-content: space-between; align-items: center; padding: 6px 0; font-size: .85rem; }
        .sov-summary-row.total { border-top: 2px solid #e5e7eb; padding-top: 12px; margin-top: 4px; }
        .sov-summary-label { color: #6b7280; font-weight: 600; }
        .sov-summary-val { font-weight: 700; color: #1f2937; }
        .sov-summary-row.total .sov-summary-val { color: #2563eb; font-size: 1.05rem; }

        .sov-empty { text-align: center; padding: 40px 20px; color: #9ca3af; }
        .sov-empty i { font-size: 2rem; display: block; margin-bottom: 8px; }

        @media (max-width: 640px) {
            .sov-grid { grid-template-columns: 1fr 1fr; }
            .sov-table { font-size: .78rem; }
            .sov-table thead th, .sov-table tbody td { padding: 8px 10px; }
            .sov-summary { max-width: 100%; }
        }
    </style>
@endsection
@section('content')
    @php
        if (!function_exists('indNum')) {
            function indNum($n, $dec = 0) {
                $n = round((float) $n, $dec);
                $neg = $n < 0; $n = abs($n);
                $p = explode('.', number_format($n, $dec, '.', ''));
                $w = $p[0]; $d = isset($p[1]) ? '.' . $p[1] : '';
                if (strlen($w) > 3) {
                    $last3 = substr($w, -3);
                    $rest = substr($w, 0, -3);
                    $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);
                    $w = $rest . ',' . $last3;
                }
                return ($neg ? '-' : '') . $w . $d;
            }
        }
    @endphp

    <div class="sov-wrap">
        <!-- Header -->
        <div class="sov-header">
            <div class="sov-header-left">
                <div class="sov-icon-box"><i class="bi bi-receipt"></i></div>
                <div>
                    <h1>Order Details</h1>
                    <p>View your order information</p>
                </div>
            </div>
            <a href="{{ route('orderReportCp') }}" class="sov-back">
                <i class="bi bi-arrow-left"></i> Back to Orders
            </a>
        </div>

        @if ($order)
            @php
                $status = strtolower($order->status);
                $pillClass = match($status) {
                    'pending' => 'sov-pill-pending',
                    'approved', 'completed' => 'sov-pill-completed',
                    'rejected' => 'sov-pill-rejected',
                    'confirmed' => 'sov-pill-confirmed',
                    'delivered' => 'sov-pill-delivered',
                    default => 'sov-pill-cancelled',
                };
            @endphp

            <!-- Order Info -->
            <div class="sov-card">
                <div class="sov-card-head">
                    <i class="bi bi-info-circle" style="color:#2563eb;"></i>
                    <h2>Order Information</h2>
                </div>
                <div class="sov-card-body">
                    <div class="sov-grid">
                        <div class="sov-field">
                            <label>Request ID</label>
                            <span style="font-family:monospace;">{{ $order->order_id }}</span>
                        </div>
                        <div class="sov-field">
                            <label>Request Date</label>
                            <span>{{ \Carbon\Carbon::parse($order->order_date)->format('d M, Y') }}</span>
                        </div>
                        <div class="sov-field">
                            <label>Channel Partner</label>
                            <span>{{ $order->channelPartner->cp_name ?? 'N/A' }}</span>
                        </div>
                        <div class="sov-field">
                            <label>Status</label>
                            <span class="sov-pill {{ $pillClass }}">{{ ucfirst($order->status) }}</span>
                        </div>
                        @if ($status == 'completed')
                            <div class="sov-field">
                                <label>Quote Date</label>
                                <span>{{ \Carbon\Carbon::parse($order->quote_date)->format('d M, Y') }}</span>
                            </div>
                            <div class="sov-field">
                                <label>Quote Validity</label>
                                <span>{{ \Carbon\Carbon::parse($order->quote_validity_date)->format('d M, Y') }}</span>
                            </div>
                            <div class="sov-field">
                                <label>Total Amount</label>
                                <span style="color:#2563eb;">&#8377;{{ indNum($order->quote_amount, 2) }}</span>
                            </div>
                        @endif
                        @if ($order->admin_remark && in_array($status, ['completed', 'rejected']))
                            <div class="sov-field" style="grid-column: 1 / -1;">
                                <label>Admin Remarks</label>
                                <span style="font-weight:500;">{{ $order->admin_remark }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Products -->
            <div class="sov-card">
                <div class="sov-card-head">
                    <i class="bi bi-box-seam" style="color:#2563eb;"></i>
                    <h2>Products</h2>
                </div>
                <div style="overflow-x:auto;">
                    @php
                        $products = $order->products;
                        if (is_string($products)) $products = json_decode($products, true);
                        if (!is_array($products)) $products = [];

                        $hasQuote = $status == 'completed' && $order->quote_amount > 0;
                    @endphp

                    <table class="sov-table">
                        <thead>
                            <tr>
                                <th style="width:40px;">#</th>
                                <th>Product</th>
                                <th class="text-center">UOM</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Price</th>
                                @if ($hasQuote)
                                    <th class="text-right">Unit Rate</th>
                                    <th class="text-center">GST %</th>
                                    <th class="text-right">Tax</th>
                                    <th class="text-right">Total</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $i => $product)
                                @php
                                    $prod = \App\Models\Product::find($product['product_id'] ?? null);
                                    $cat = \App\Models\ProductCategory::find($product['category_id'] ?? null);
                                    $sub = !empty($product['subcategory_id']) ? \App\Models\ProductSubCategory::find($product['subcategory_id']) : null;
                                    $price = $prod ? $prod->current_sale_price : null;
                                @endphp
                                <tr>
                                    <td><strong>{{ $i + 1 }}</strong></td>
                                    <td>
                                        <div class="sov-prod-name">{{ $prod ? $prod->item_name : 'Unknown Product' }}</div>
                                        <div class="sov-prod-cat">
                                            {{ $cat ? $cat->category_name : 'N/A' }}{{ $sub ? ' / ' . $sub->sub_category_name : '' }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span style="background:#eff6ff;color:#1d4ed8;padding:2px 8px;border-radius:12px;font-size:.75rem;font-weight:600;">{{ $product['uom'] ?? 'N/A' }}</span>
                                    </td>
                                    <td class="text-center"><strong>{{ $product['quantity'] ?? 0 }}</strong></td>
                                    <td class="text-right">
                                        @if ($price)
                                            <span class="sov-prod-price">&#8377;{{ indNum($price, 2) }}</span>
                                        @else
                                            <span style="color:#9ca3af;">N/A</span>
                                        @endif
                                    </td>
                                    @if ($hasQuote)
                                        <td class="text-right sov-amount">&#8377;{{ indNum($product['unit_rate'] ?? 0, 2) }}</td>
                                        <td class="text-center">{{ $product['gst_rate'] ?? 0 }}%</td>
                                        <td class="text-right">&#8377;{{ indNum($product['gst_amount'] ?? 0, 2) }}</td>
                                        <td class="text-right sov-prod-price">&#8377;{{ indNum($product['total_amount'] ?? 0, 2) }}</td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $hasQuote ? 9 : 5 }}">
                                        <div class="sov-empty">
                                            <i class="bi bi-inbox"></i>
                                            No products in this order
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($hasQuote && count($products) > 0)
                    <div class="sov-card-body" style="border-top:1px solid #f3f4f6;">
                        <div class="sov-summary">
                            <div class="sov-summary-row">
                                <span class="sov-summary-label">Subtotal</span>
                                <span class="sov-summary-val">&#8377;{{ indNum($order->subtotal ?? 0, 2) }}</span>
                            </div>
                            @if (($order->gst_amount ?? 0) > 0)
                                <div class="sov-summary-row">
                                    <span class="sov-summary-label">GST</span>
                                    <span class="sov-summary-val">&#8377;{{ indNum($order->gst_amount, 2) }}</span>
                                </div>
                            @endif
                            @if (($order->igst_amount ?? 0) > 0)
                                <div class="sov-summary-row">
                                    <span class="sov-summary-label">IGST</span>
                                    <span class="sov-summary-val">&#8377;{{ indNum($order->igst_amount, 2) }}</span>
                                </div>
                            @endif
                            <div class="sov-summary-row total">
                                <span class="sov-summary-label">Grand Total</span>
                                <span class="sov-summary-val">&#8377;{{ indNum($order->grand_total ?? $order->quote_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="sov-card">
                <div class="sov-card-body sov-empty">
                    <i class="bi bi-exclamation-triangle"></i>
                    Order not found.
                </div>
            </div>
        @endif
    </div>
@endsection
