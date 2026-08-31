@extends('layouts.adminLayout')

@section('content')
<style>
    @media (max-width: 1024px) {
        .order-layout-grid { grid-template-columns: 1fr !important; }
    }
</style>
<div class="p-6" style="max-width: 1400px; margin: 0 auto;">
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

    <div style="display:grid; grid-template-columns: minmax(0, 2fr) minmax(320px, 1fr); gap: 20px; align-items: start;" class="order-layout-grid">

        <!-- Left: Order Items only -->
        <div class="space-y-4" style="min-width:0;">

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

                        @if($info && ($info['is_serial_tracked'] ?? false))
                        <div style="margin-bottom:10px; padding:8px 12px; background:#f0f9ff; border:1px solid #bae6fd; border-radius:6px; font-size:.82rem; color:#0c4a6e;">
                            <i class="fas fa-barcode me-1"></i> <strong>This product is serial-tracked.</strong> Pick specific serial numbers for each source below (or use Auto-Pick).
                        </div>
                        @endif

                        <form method="POST" action="{{ route('admin.order.fulfill', $order->id) }}" id="ffForm{{ $item->id }}" data-item-id="{{ $item->id }}" data-is-serial="{{ $info && ($info['is_serial_tracked'] ?? false) ? 1 : 0 }}">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->id }}">

                            <div id="srcRows{{ $item->id }}">
                                <div class="src-row" data-idx="0">
                                    <select name="sources[0][source]" onchange="onSrcChange({{ $item->id }}, this, 0)">
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
                                    <input type="number" name="sources[0][qty]" min="1" placeholder="Qty" oninput="onQtyChange({{ $item->id }}, 0)">
                                </div>
                            </div>

                            <div id="serialPickers{{ $item->id }}" style="display:none;"></div>

                            <div style="display:flex;gap:8px;align-items:center;">
                                <button type="button" class="add-src-btn" onclick="addSrcRow({{ $item->id }})">+ Add another source</button>
                                <div class="alloc-status warn" id="allocStatus{{ $item->id }}" style="flex:1;text-align:right;margin-top:0;">Allocated: 0 / {{ $info ? $info['remaining'] : $item->quantity }}</div>
                            </div>

                            <button type="submit" class="ff-submit" id="ffBtn{{ $item->id }}" disabled>Deduct Stock &amp; Fulfill</button>
                        </form>
                    </div>
                    @if($info && ($info['is_serial_tracked'] ?? false) && count($info['assigned_serials']) > 0)
                    <div style="margin-top:10px; padding:10px 14px; background:#ecfdf5; border:1px solid #bbf7d0; border-radius:8px;">
                        <div style="font-size:.78rem; font-weight:700; color:#065f46; margin-bottom:6px;">
                            <i class="fas fa-check-circle me-1"></i> Serials Delivered to Customer:
                        </div>
                        <div style="display:flex; flex-wrap:wrap; gap:5px;">
                            @foreach($info['assigned_serials'] as $sn)
                            <span style="background:#fff; border:1px solid #86efac; color:#065f46; font-family:monospace; font-size:.72rem; padding:3px 8px; border-radius:4px;">{{ $sn }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
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

                // Available serials per item, keyed by source (main | wh:ID)
                const availableSerialsByItem = {};
                @foreach($order->items as $item)
                    @if(isset($stockInfo[$item->id]) && ($stockInfo[$item->id]['is_serial_tracked'] ?? false))
                    availableSerialsByItem[{{ $item->id }}] = {
                        @foreach($stockInfo[$item->id]['available_serials'] as $srcKey => $ser)
                        {!! json_encode((string)$srcKey) !!}: {!! json_encode($ser->values()->toArray()) !!},
                        @endforeach
                    };
                    @endif
                @endforeach

                // Selected serials per (itemId, rowIdx)
                const selectedSerialsByRow = {};

                function isSerialTrackedItem(itemId) {
                    var form = document.getElementById('ffForm' + itemId);
                    return form && form.getAttribute('data-is-serial') === '1';
                }

                function addSrcRow(itemId) {
                    const idx = srcRowCounts[itemId]++;
                    const wrap = document.getElementById('srcRows' + itemId);
                    const first = wrap.querySelector('.src-row select');
                    const optionsHtml = first ? first.innerHTML : '';
                    const row = document.createElement('div');
                    row.className = 'src-row';
                    row.setAttribute('data-idx', idx);
                    row.innerHTML = '<select name="sources[' + idx + '][source]" onchange="onSrcChange(' + itemId + ', this, ' + idx + ')">' + optionsHtml + '</select>'
                        + '<input type="number" name="sources[' + idx + '][qty]" min="1" placeholder="Qty" oninput="onQtyChange(' + itemId + ', ' + idx + ')">'
                        + '<button type="button" class="src-remove" onclick="removeSrcRow(this, ' + itemId + ', ' + idx + ')">&times;</button>';
                    wrap.appendChild(row);
                }

                function removeSrcRow(btn, itemId, idx) {
                    btn.parentElement.remove();
                    var picker = document.getElementById('srcSerials_' + itemId + '_' + idx);
                    if (picker) picker.remove();
                    delete selectedSerialsByRow[itemId + '_' + idx];
                    recalcAlloc(itemId);
                    syncSerialInputs(itemId);
                }

                function onSrcChange(itemId, sel, idx) {
                    recalcAlloc(itemId);
                    if (!isSerialTrackedItem(itemId)) return;
                    rebuildSerialPicker(itemId, idx);
                }
                function onQtyChange(itemId, idx) {
                    recalcAlloc(itemId);
                    if (!isSerialTrackedItem(itemId)) return;
                    rebuildSerialPicker(itemId, idx);
                }

                function rebuildSerialPicker(itemId, idx) {
                    var wrap = document.getElementById('serialPickers' + itemId);
                    var row = document.querySelector('#srcRows' + itemId + ' .src-row[data-idx="' + idx + '"]');
                    if (!row || !wrap) return;
                    var sel = row.querySelector('select');
                    var qtyInput = row.querySelector('input[type=number]');
                    var src = sel.value;
                    var qty = parseInt(qtyInput.value) || 0;

                    var existing = document.getElementById('srcSerials_' + itemId + '_' + idx);
                    if (existing) existing.remove();
                    delete selectedSerialsByRow[itemId + '_' + idx];
                    if (!src || qty <= 0) { wrap.style.display = wrap.children.length ? '' : 'none'; syncSerialInputs(itemId); return; }

                    var avail = (availableSerialsByItem[itemId] || {})[src] || [];
                    // Auto-select first N by default
                    var preSelected = avail.slice(0, Math.min(qty, avail.length));
                    selectedSerialsByRow[itemId + '_' + idx] = new Set(preSelected);

                    var srcLabel = sel.options[sel.selectedIndex].text.split(' (')[0];
                    var block = document.createElement('div');
                    block.id = 'srcSerials_' + itemId + '_' + idx;
                    block.style.cssText = 'margin-top:10px; padding:12px; background:#f0f9ff; border:1px solid #bae6fd; border-radius:8px;';
                    block.innerHTML =
                        '<div style="display:flex; justify-content:space-between; margin-bottom:8px; align-items:center; flex-wrap:wrap; gap:6px;">'
                      + '<span style="font-weight:600; font-size:.84rem; color:#0c4a6e;"><i class="fas fa-barcode me-1"></i> Serials from ' + srcLabel + ' (' + qty + ' needed)</span>'
                      + '<div style="display:flex; gap:6px;">'
                      + '<button type="button" onclick="autoPickSourceSerials(' + itemId + ',' + idx + ')" style="background:#2563eb;color:#fff;border:none;padding:4px 10px;border-radius:5px;font-size:.75rem;font-weight:600;cursor:pointer;">Auto-Pick First ' + qty + '</button>'
                      + '<button type="button" onclick="clearSourceSerials(' + itemId + ',' + idx + ')" style="background:#f3f4f6;color:#374151;border:1px solid #d1d5db;padding:4px 10px;border-radius:5px;font-size:.75rem;font-weight:600;cursor:pointer;">Clear</button>'
                      + '</div>'
                      + '</div>'
                      + '<div id="srcSerialList_' + itemId + '_' + idx + '" style="max-height:180px; overflow-y:auto; background:#fff; border:1px solid #e5e7eb; border-radius:6px; padding:6px;"></div>'
                      + '<div style="margin-top:6px; font-size:.78rem; color:#374151; font-weight:600;" id="srcSerialCount_' + itemId + '_' + idx + '">0 selected / ' + qty + ' needed</div>';
                    wrap.appendChild(block);
                    wrap.style.display = '';
                    renderSerialCheckboxes(itemId, idx);
                }

                function renderSerialCheckboxes(itemId, idx) {
                    var row = document.querySelector('#srcRows' + itemId + ' .src-row[data-idx="' + idx + '"]');
                    var sel = row.querySelector('select');
                    var qty = parseInt(row.querySelector('input[type=number]').value) || 0;
                    var avail = (availableSerialsByItem[itemId] || {})[sel.value] || [];
                    var selected = selectedSerialsByRow[itemId + '_' + idx] || new Set();
                    var listEl = document.getElementById('srcSerialList_' + itemId + '_' + idx);
                    if (!listEl) return;
                    var html = '';
                    avail.forEach(function(sn) {
                        var checked = selected.has(sn) ? 'checked' : '';
                        html += '<label style="display:flex; align-items:center; gap:8px; padding:4px 8px; border-bottom:1px solid #f3f4f6; cursor:pointer; font-family:monospace; font-size:.78rem;">'
                              + '<input type="checkbox" ' + checked + ' value="' + sn + '" onchange="toggleSourceSerial(' + itemId + ',' + idx + ',this)"> ' + sn
                              + '</label>';
                    });
                    if (!avail.length) html = '<div style="padding:8px; text-align:center; color:#94a3b8; font-size:.82rem;">No serials available at this source</div>';
                    listEl.innerHTML = html;
                    var countEl = document.getElementById('srcSerialCount_' + itemId + '_' + idx);
                    if (countEl) {
                        countEl.textContent = selected.size + ' selected / ' + qty + ' needed';
                        countEl.style.color = (qty > 0 && selected.size === qty) ? '#059669' : '#dc2626';
                    }
                    syncSerialInputs(itemId);
                }

                function toggleSourceSerial(itemId, idx, cb) {
                    var key = itemId + '_' + idx;
                    if (!selectedSerialsByRow[key]) selectedSerialsByRow[key] = new Set();
                    if (cb.checked) selectedSerialsByRow[key].add(cb.value);
                    else selectedSerialsByRow[key].delete(cb.value);
                    renderSerialCheckboxes(itemId, idx);
                    recalcAlloc(itemId);
                }
                function autoPickSourceSerials(itemId, idx) {
                    var row = document.querySelector('#srcRows' + itemId + ' .src-row[data-idx="' + idx + '"]');
                    var sel = row.querySelector('select');
                    var qty = parseInt(row.querySelector('input[type=number]').value) || 0;
                    var avail = (availableSerialsByItem[itemId] || {})[sel.value] || [];
                    selectedSerialsByRow[itemId + '_' + idx] = new Set(avail.slice(0, qty));
                    renderSerialCheckboxes(itemId, idx);
                    recalcAlloc(itemId);
                }
                function clearSourceSerials(itemId, idx) {
                    selectedSerialsByRow[itemId + '_' + idx] = new Set();
                    renderSerialCheckboxes(itemId, idx);
                    recalcAlloc(itemId);
                }

                function syncSerialInputs(itemId) {
                    var form = document.getElementById('ffForm' + itemId);
                    if (!form) return;
                    form.querySelectorAll('input.serial-hidden').forEach(function(el) { el.remove(); });
                    Object.keys(selectedSerialsByRow).forEach(function(key) {
                        if (!key.startsWith(itemId + '_')) return;
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

                function recalcAlloc(itemId) {
                    const wrap = document.getElementById('srcRows' + itemId);
                    const rows = wrap.querySelectorAll('.src-row');
                    let total = 0;
                    let hasError = false;
                    let serialMismatch = false;
                    const usedSources = {};
                    const isSerial = isSerialTrackedItem(itemId);
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
                                var sel_set = selectedSerialsByRow[itemId + '_' + idx];
                                if (!sel_set || sel_set.size !== qty) serialMismatch = true;
                            }
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
                    } else if (isSerial && serialMismatch) {
                        status.classList.add('warn');
                        status.textContent += ' (pick serial numbers)';
                        btn.disabled = true;
                    } else {
                        status.classList.add('ok');
                        btn.disabled = false;
                    }
                }
            </script>

        </div>

        <!-- Right: Sticky sidebar with Status Action + Customer/Delivery combined -->
        <div class="space-y-4" style="position: sticky; top: 20px; align-self: flex-start; height: fit-content;">

            <!-- Update Status (moved to top for prominence) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-blue-50">
                    <h2 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        Order Status
                    </h2>
                </div>
                <div class="px-5 py-4">
                    <form action="{{ route('updateCustomerOrderStatus', $order->id) }}" method="POST">
                        @csrf
                        @php
                            $stColors = [
                                'pending'   => ['dot'=>'#facc15','bg'=>'#fefce8','br'=>'#facc15'],
                                'confirmed' => ['dot'=>'#3b82f6','bg'=>'#eff6ff','br'=>'#3b82f6'],
                                'shipped'   => ['dot'=>'#a855f7','bg'=>'#faf5ff','br'=>'#a855f7'],
                                'delivered' => ['dot'=>'#22c55e','bg'=>'#f0fdf4','br'=>'#22c55e'],
                                'cancelled' => ['dot'=>'#ef4444','bg'=>'#fef2f2','br'=>'#ef4444'],
                            ];
                        @endphp
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                            @foreach(['pending','confirmed','shipped','delivered','cancelled'] as $st)
                                @php $c = $stColors[$st]; $active = $order->status === $st; @endphp
                                <label style="display:flex;align-items:center;gap:8px;padding:10px 12px;border:2px solid {{ $active ? $c['br'] : '#e5e7eb' }};background:{{ $active ? $c['bg'] : '#fff' }};border-radius:8px;cursor:pointer;font-size:12px;font-weight:600;color:{{ $active ? '#111827' : '#6b7280' }};transition:all 0.15s;{{ $st === 'cancelled' ? 'grid-column:1/-1;' : '' }}">
                                    <input type="radio" name="status" value="{{ $st }}" {{ $active ? 'checked' : '' }} style="display:none;">
                                    <span style="width:8px;height:8px;border-radius:50%;background:{{ $c['dot'] }};flex-shrink:0;"></span>
                                    <span style="text-transform:capitalize;">{{ $st }}</span>
                                </label>
                            @endforeach
                        </div>
                        <button type="submit" style="margin-top:14px;width:100%;padding:11px;background:linear-gradient(135deg,#4f46e5,#4338ca);color:#fff;font-size:13px;font-weight:700;border-radius:8px;border:none;cursor:pointer;box-shadow:0 4px 10px rgba(79,70,229,0.25);">
                            <i class="fas fa-check me-1"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Combined Customer + Delivery + Notes card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                    <h2 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Customer
                    </h2>
                </div>
                <div class="px-5 py-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-blue-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($order->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-gray-800 text-sm truncate">{{ $order->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $order->user->email ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <a href="tel:{{ $order->phone }}" class="text-gray-700 hover:text-indigo-600 font-medium">{{ $order->phone }}</a>
                        </div>
                        <div class="flex items-start gap-2 pt-3 border-t border-gray-100">
                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <div class="text-gray-700 leading-relaxed">
                                <div>{{ $order->address }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $order->city }}@if($order->district), {{ $order->district }}@endif, {{ $order->state }} - {{ $order->pincode }}</div>
                            </div>
                        </div>
                        @if($order->notes)
                        <div class="pt-3 border-t border-gray-100 bg-amber-50 -mx-5 -mb-4 px-5 py-3">
                            <p class="text-xs font-semibold text-amber-700 uppercase tracking-wide mb-1">
                                <i class="fas fa-sticky-note me-1"></i> Customer Note
                            </p>
                            <p class="text-sm text-amber-900 italic">"{{ $order->notes }}"</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                    <h2 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        Payment
                    </h2>
                </div>
                <div class="px-5 py-4" style="display:grid;grid-template-columns:1fr 1fr;gap:14px;font-size:13px;">
                    @php
                        $payColors = [
                            'paid' => ['bg'=>'#d1fae5','color'=>'#065f46'],
                            'verification_pending' => ['bg'=>'#ffedd5','color'=>'#9a3412'],
                            'pending' => ['bg'=>'#fef9c3','color'=>'#854d0e'],
                            'failed' => ['bg'=>'#fee2e2','color'=>'#991b1b'],
                        ];
                        $payLabels = [
                            'paid' => 'Verified',
                            'verification_pending' => 'Verification Pending',
                            'pending' => 'Not Paid',
                            'failed' => 'Rejected',
                        ];
                        $pc = $payColors[$order->payment_status] ?? ['bg'=>'#f3f4f6','color'=>'#374151'];
                    @endphp
                    <div>
                        <p style="font-size:11px;color:#6b7280;text-transform:uppercase;letter-spacing:.04em;margin-bottom:3px;">Method</p>
                        <p style="font-weight:600;color:#111827;">Online</p>
                    </div>
                    <div>
                        <p style="font-size:11px;color:#6b7280;text-transform:uppercase;letter-spacing:.04em;margin-bottom:3px;">Status</p>
                        <span style="display:inline-block;padding:2px 10px;border-radius:12px;font-size:11px;font-weight:700;background:{{ $pc['bg'] }};color:{{ $pc['color'] }};">{{ $payLabels[$order->payment_status] ?? ucfirst($order->payment_status) }}</span>
                    </div>
                    @if($order->payment_reference)
                    <div style="grid-column:1/-1;">
                        <p style="font-size:11px;color:#6b7280;text-transform:uppercase;letter-spacing:.04em;margin-bottom:3px;">Reference / UTR</p>
                        <p style="font-family:monospace;font-size:12px;color:#374151;">{{ $order->payment_reference }}</p>
                    </div>
                    @endif
                    <div style="grid-column:1/-1;">
                        <p style="font-size:11px;color:#6b7280;text-transform:uppercase;letter-spacing:.04em;margin-bottom:3px;">Order Date</p>
                        <p style="font-weight:600;color:#111827;">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    @if($order->payment_screenshot && $order->payment_status !== 'verification_pending')
                    <div style="grid-column:1/-1;">
                        <a href="{{ asset('storage/' . $order->payment_screenshot) }}" target="_blank" style="color:#4f46e5;font-size:12px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                            <i class="fas fa-image"></i> View Payment Screenshot
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- WhatsApp quick action -->
            <a href="https://wa.me/91{{ preg_replace('/\D/', '', $order->phone) }}?text={{ urlencode('Hi ' . $order->name . ', regarding your order ' . $order->order_number) }}"
               target="_blank"
               class="flex items-center justify-center gap-2 w-full py-2.5 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg transition-colors text-sm shadow-sm">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.297-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.71.306 1.263.489 1.694.625.712.227 1.36.194 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Message Customer on WhatsApp
            </a>

        </div>
    </div>
</div>
@endsection
