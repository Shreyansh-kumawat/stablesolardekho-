@extends('layouts.adminLayout')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('customerOrders') }}"
               class="inline-flex items-center gap-1.5 text-gray-500 hover:text-gray-800 transition-colors text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Orders
            </a>
            <span class="text-gray-300">/</span>
            <h1 class="text-xl font-bold text-gray-800">{{ $order->order_number }}</h1>
        </div>
        <div>
            @php
                $statusColors = [
                    'pending'   => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                    'confirmed' => 'bg-blue-100 text-blue-800 border-blue-200',
                    'shipped'   => 'bg-purple-100 text-purple-800 border-purple-200',
                    'delivered' => 'bg-green-100 text-green-800 border-green-200',
                    'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                ];
                $color = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold border {{ $color }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left: Order Items + Payment -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Payment Verification Card -->
            @if($order->payment_status === 'verification_pending' && $order->payment_screenshot)
            <div class="bg-orange-50 rounded-xl shadow-sm border-2 border-orange-300">
                <div class="px-6 py-4 border-b border-orange-200 flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                    <h2 class="font-bold text-orange-800">Payment Verification Required</h2>
                </div>
                <div class="px-6 py-4">
                    <p class="text-sm text-orange-700 mb-3">Customer has uploaded a payment screenshot. Please verify the payment and approve or reject.</p>
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Payment Screenshot</p>
                        <a href="{{ asset('storage/' . $order->payment_screenshot) }}" target="_blank">
                            <img src="{{ asset('storage/' . $order->payment_screenshot) }}" style="max-width:320px;border-radius:8px;border:1px solid #e5e7eb;box-shadow:0 1px 3px rgba(0,0,0,0.1);cursor:pointer;" alt="Payment Screenshot">
                        </a>
                    </div>
                    @if($order->payment_reference)
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Reference / UTR Number</p>
                        <p class="font-mono text-sm font-medium text-gray-800 mt-0.5">{{ $order->payment_reference }}</p>
                    </div>
                    @endif
                    <div style="display:flex;gap:12px;">
                        <form action="{{ route('admin.order.approvePayment', $order->id) }}" method="POST" style="flex:1;">
                            @csrf
                            <button type="submit" style="width:100%;padding:10px;background:#16a34a;color:#fff;font-size:14px;font-weight:700;border-radius:8px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;">
                                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                Approve Payment
                            </button>
                        </form>
                        <form action="{{ route('admin.order.rejectPayment', $order->id) }}" method="POST" style="flex:1;">
                            @csrf
                            <button type="submit" style="width:100%;padding:10px;background:#dc2626;color:#fff;font-size:14px;font-weight:700;border-radius:8px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;">
                                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                Reject Payment
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            <!-- Order Items & Fulfillment -->
            <style>
                .oi-card { background:#fff; border:1px solid #e5e7eb; border-radius:12px; margin-bottom:16px; overflow:hidden; }
                .oi-head { padding:14px 18px; background:#fafbfc; border-bottom:1px solid #eef0f2; display:flex; align-items:center; gap:14px; }
                .oi-thumb { width:52px; height:52px; object-fit:cover; border-radius:8px; border:1px solid #e5e7eb; flex-shrink:0; }
                .oi-thumb-ph { width:52px; height:52px; border-radius:8px; background:#f1f3f5; display:flex; align-items:center; justify-content:center; color:#adb5bd; flex-shrink:0; }
                .oi-title { font-size:.95rem; font-weight:600; color:#1f2937; }
                .oi-meta { font-size:.78rem; color:#6b7280; margin-top:2px; }
                .oi-price { margin-left:auto; text-align:right; }
                .oi-price .amt { font-size:1rem; font-weight:700; color:#111827; }
                .oi-price .ord { font-size:.75rem; color:#6b7280; margin-top:2px; }

                .stock-row { padding:12px 18px; background:#fff; border-bottom:1px solid #f1f3f5; }
                .stock-label { font-size:.68rem; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.06em; margin-bottom:8px; }
                .stock-pills { display:flex; flex-wrap:wrap; gap:6px; }
                .pill { display:inline-flex; align-items:center; gap:6px; padding:5px 10px; border-radius:16px; font-size:.75rem; font-weight:600; }
                .pill .dot { width:7px; height:7px; border-radius:50%; }
                .pill.main { background:#d1fae5; color:#047857; } .pill.main .dot { background:#10b981; }
                .pill.wh   { background:#e0e7ff; color:#4338ca; } .pill.wh .dot { background:#6366f1; }
                .pill.tot  { background:#f3f4f6; color:#374151; } .pill.tot .dot { background:#9ca3af; }
                .pill.zero { opacity:.55; }

                .ff-section { padding:14px 18px; background:#fefce8; border-top:1px solid #fde68a; }
                .ff-section.done { background:#f0fdf4; border-top-color:#bbf7d0; }
                .ff-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; }
                .ff-title { font-size:.82rem; font-weight:700; color:#78350f; }
                .ff-section.done .ff-title { color:#166534; }
                .ff-need { font-size:.78rem; font-weight:600; color:#78350f; }
                .ff-section.done .ff-need { color:#166534; }

                .src-row { display:flex; gap:8px; align-items:center; margin-bottom:8px; }
                .src-row select, .src-row input[type=number] { padding:7px 10px; border:1px solid #d1d5db; border-radius:6px; font-size:.85rem; }
                .src-row select { flex:1; }
                .src-row input[type=number] { width:110px; }
                .src-remove { background:#fee2e2; color:#b91c1c; border:none; width:32px; height:32px; border-radius:6px; cursor:pointer; font-weight:700; display:flex; align-items:center; justify-content:center; }
                .src-remove:hover { background:#fecaca; }
                .add-src-btn { background:#4f46e5; color:#fff; border:none; padding:6px 12px; border-radius:6px; font-size:.78rem; font-weight:600; cursor:pointer; }
                .add-src-btn:hover { background:#4338ca; }
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
            </style>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200" style="padding:16px 16px 8px;">
                <div style="padding:0 4px 12px;border-bottom:1px solid #f3f4f6;margin-bottom:14px;">
                    <h2 class="font-semibold text-gray-800">Order Items & Stock Allocation</h2>
                    <p style="font-size:.78rem;color:#6b7280;margin-top:2px;">Select the sources (Main Inventory / Warehouses) to fulfill each item.</p>
                </div>

                @foreach($order->items as $item)
                @php
                    $info = $stockInfo[$item->id] ?? null;
                    $mainStock = $info ? $info['main'] : 0;
                    $whList = $info ? $info['warehouses'] : collect();
                    $totalStock = $mainStock + ($whList->sum('available_qty'));
                    $done = $info && $info['remaining'] == 0 && $info['total_fulfilled'] >= $item->quantity;
                @endphp
                <div class="oi-card">
                    <div class="oi-head">
                        @if($item->product && $item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" class="oi-thumb" alt="">
                        @else
                            <div class="oi-thumb-ph">
                                <svg style="width:22px;height:22px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z"/></svg>
                            </div>
                        @endif
                        <div style="flex:1;min-width:0;">
                            <div class="oi-title">{{ $item->product_name }}</div>
                            <div class="oi-meta">Ordered: <strong>{{ $item->quantity }}</strong> &middot; &#8377;{{ number_format($item->price, 2) }} each</div>
                        </div>
                        <div class="oi-price">
                            <div class="amt">&#8377;{{ number_format($item->subtotal, 2) }}</div>
                        </div>
                    </div>

                    <div class="stock-row">
                        <div class="stock-label">Current Stock Breakdown</div>
                        <div class="stock-pills">
                            <span class="pill main {{ $mainStock == 0 ? 'zero' : '' }}"><span class="dot"></span> Main Inventory: {{ $mainStock }}</span>
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
                        <div style="font-size:.8rem;color:#166534;font-weight:600;">Total Fulfilled: {{ $info['total_fulfilled'] }} / {{ $item->quantity }}</div>
                    </div>
                    @endif

                    @if(!$done)
                    <div class="ff-section">
                        <div class="ff-header">
                            <div class="ff-title">Allocate Stock Sources</div>
                            <div class="ff-need">Needed: <strong>{{ $info ? $info['remaining'] : $item->quantity }}</strong></div>
                        </div>

                        @if($totalStock < ($info ? $info['remaining'] : $item->quantity))
                        <div class="alloc-status err" style="margin-bottom:10px;">
                            &#9888; Insufficient stock! Available total: {{ $totalStock }}, but need {{ $info ? $info['remaining'] : $item->quantity }}.
                        </div>
                        @endif

                        <form method="POST" action="{{ route('admin.order.fulfill', $order->id) }}" id="ffForm{{ $item->id }}">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->id }}">

                            <div id="srcRows{{ $item->id }}">
                                <div class="src-row">
                                    <select name="sources[0][source]" onchange="recalcAlloc({{ $item->id }})">
                                        <option value="">-- Select source --</option>
                                        @if($mainStock > 0)
                                            <option value="main" data-max="{{ $mainStock }}">Main Inventory ({{ $mainStock }} available)</option>
                                        @endif
                                        @foreach($warehouses as $wh)
                                            @php $whQ = optional($whList->get($wh->id))->available_qty ?? 0; @endphp
                                            @if($whQ > 0)
                                                <option value="wh:{{ $wh->id }}" data-max="{{ $whQ }}">{{ $wh->name }} ({{ $whQ }} available)</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <input type="number" name="sources[0][qty]" min="1" placeholder="Qty" oninput="recalcAlloc({{ $item->id }})">
                                </div>
                            </div>

                            <div style="display:flex;gap:8px;align-items:center;">
                                <button type="button" class="add-src-btn" onclick="addSrcRow({{ $item->id }})">+ Add another source</button>
                                <div class="alloc-status warn" id="allocStatus{{ $item->id }}" style="flex:1;text-align:right;margin-top:0;">Allocated: 0 / {{ $info ? $info['remaining'] : $item->quantity }}</div>
                            </div>

                            <button type="submit" class="ff-submit" id="ffBtn{{ $item->id }}" disabled>Deduct Stock &amp; Fulfill</button>
                        </form>
                    </div>
                    @else
                    <div class="ff-section done">
                        <div class="ff-title" style="text-align:center;">&#10003; Item fully fulfilled</div>
                    </div>
                    @endif
                </div>
                @endforeach

                <div style="padding:14px 18px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;margin-top:6px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-weight:600;color:#374151;">Grand Total</span>
                        <span style="font-size:1.2rem;font-weight:700;color:#4f46e5;">&#8377;{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <script>
                const srcRowCounts = {};
                const itemNeeds = {};
                @foreach($order->items as $item)
                    @php $rem = $stockInfo[$item->id]['remaining'] ?? $item->quantity; @endphp
                    srcRowCounts[{{ $item->id }}] = 1;
                    itemNeeds[{{ $item->id }}] = {{ $rem }};
                @endforeach

                function addSrcRow(itemId) {
                    const idx = srcRowCounts[itemId]++;
                    const wrap = document.getElementById('srcRows' + itemId);
                    const first = wrap.querySelector('.src-row select');
                    const optionsHtml = first ? first.innerHTML : '';
                    const row = document.createElement('div');
                    row.className = 'src-row';
                    row.innerHTML = '<select name="sources[' + idx + '][source]" onchange="recalcAlloc(' + itemId + ')">' + optionsHtml + '</select>'
                        + '<input type="number" name="sources[' + idx + '][qty]" min="1" placeholder="Qty" oninput="recalcAlloc(' + itemId + ')">'
                        + '<button type="button" class="src-remove" onclick="this.parentElement.remove(); recalcAlloc(' + itemId + ');">&times;</button>';
                    wrap.appendChild(row);
                }

                function recalcAlloc(itemId) {
                    const wrap = document.getElementById('srcRows' + itemId);
                    const rows = wrap.querySelectorAll('.src-row');
                    let total = 0;
                    let hasError = false;
                    const usedSources = {};
                    rows.forEach(function(row) {
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
                        } else {
                            qtyInput.style.borderColor = '#d1d5db';
                            sel.style.borderColor = '#d1d5db';
                        }
                    });
                    const need = itemNeeds[itemId];
                    const status = document.getElementById('allocStatus' + itemId);
                    const btn = document.getElementById('ffBtn' + itemId);
                    status.textContent = 'Allocated: ' + total + ' / ' + need;
                    status.className = 'alloc-status';
                    if (hasError) {
                        status.classList.add('err');
                        status.textContent += ' (fix errors)';
                        btn.disabled = true;
                    } else if (total === 0) {
                        status.classList.add('warn');
                        btn.disabled = true;
                    } else if (total < need) {
                        status.classList.add('warn');
                        status.textContent += ' (add more sources)';
                        btn.disabled = true;
                    } else if (total > need) {
                        status.classList.add('err');
                        status.textContent += ' (over-allocated)';
                        btn.disabled = true;
                    } else {
                        status.classList.add('ok');
                        btn.disabled = false;
                    }
                }
            </script>

            <!-- Payment Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-800">Payment Details</h2>
                </div>
                <div class="px-6 py-4 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Method</p>
                        <p class="font-medium text-gray-800 mt-0.5">Online Transfer</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Payment Status</p>
                        @php
                            $payBadges = [
                                'paid' => 'bg-green-100 text-green-800',
                                'verification_pending' => 'bg-orange-100 text-orange-800',
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'failed' => 'bg-red-100 text-red-800',
                            ];
                            $payLabels = [
                                'paid' => 'Verified',
                                'verification_pending' => 'Verification Pending',
                                'pending' => 'Not Paid',
                                'failed' => 'Rejected',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $payBadges[$order->payment_status] ?? 'bg-gray-100 text-gray-800' }} mt-0.5">
                            {{ $payLabels[$order->payment_status] ?? ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    @if($order->payment_reference)
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Reference / UTR</p>
                        <p class="font-mono text-xs text-gray-700 mt-0.5">{{ $order->payment_reference }}</p>
                    </div>
                    @endif
                    @if($order->payment_screenshot && $order->payment_status !== 'verification_pending')
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Screenshot</p>
                        <a href="{{ asset('storage/' . $order->payment_screenshot) }}" target="_blank" style="color:#4f46e5;font-size:12px;font-weight:500;text-decoration:underline;margin-top:2px;display:inline-block;">View Screenshot</a>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Order Date</p>
                        <p class="font-medium text-gray-800 mt-0.5">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right: Customer + Delivery + Status Update -->
        <div class="space-y-6">

            <!-- Customer Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-800">Customer</h2>
                </div>
                <div class="px-6 py-4 space-y-2">
                    <div class="flex items-center gap-2">
                        <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-semibold text-sm">
                            {{ strtoupper(substr($order->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $order->name }}</p>
                            <p class="text-xs text-gray-500">{{ $order->user->email ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-gray-100">
                        <p class="text-xs text-gray-500">Phone</p>
                        <p class="text-sm font-medium text-gray-700">{{ $order->phone }}</p>
                    </div>
                </div>
            </div>

            <!-- Delivery Address -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-800">Delivery Address</h2>
                </div>
                <div class="px-6 py-4">
                    <address class="not-italic text-sm text-gray-700 space-y-0.5">
                        <p>{{ $order->address }}</p>
                        <p>{{ $order->city }}@if($order->district), {{ $order->district }}@endif, {{ $order->state }} - {{ $order->pincode }}</p>
                    </address>
                    @if($order->notes)
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Notes</p>
                        <p class="text-sm text-gray-700 mt-0.5">{{ $order->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Update Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-800">Update Order Status</h2>
                </div>
                <div class="px-6 py-4">
                    <form action="{{ route('updateCustomerOrderStatus', $order->id) }}" method="POST">
                        @csrf
                        <div class="space-y-3">
                            @foreach(['pending','confirmed','shipped','delivered','cancelled'] as $st)
                            <label class="flex items-center gap-3 p-2.5 rounded-lg border cursor-pointer transition-colors
                                {{ $order->status === $st ? 'border-indigo-400 bg-indigo-50' : 'border-gray-200 hover:bg-gray-50' }}">
                                <input type="radio" name="status" value="{{ $st }}"
                                    {{ $order->status === $st ? 'checked' : '' }}
                                    class="text-indigo-600">
                                <span class="text-sm font-medium capitalize text-gray-700">{{ $st }}</span>
                                @php
                                    $dots = ['pending'=>'bg-yellow-400','confirmed'=>'bg-blue-400','shipped'=>'bg-purple-400','delivered'=>'bg-green-400','cancelled'=>'bg-red-400'];
                                @endphp
                                <span class="ml-auto w-2.5 h-2.5 rounded-full {{ $dots[$st] ?? 'bg-gray-400' }}"></span>
                            </label>
                            @endforeach
                        </div>
                        <button type="submit"
                            style="margin-top:16px;width:100%;padding:10px;background:#4f46e5;color:#fff;font-size:14px;font-weight:600;border-radius:8px;border:none;cursor:pointer;">
                            Update Status
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
