@extends('layouts.adminLayout')
@section('title', 'CP Orders')
@section('page_title', 'CP Orders')
@section('css')
<style>
    .po-wrap { padding: 1.25rem; }
    .po-header { display: flex; align-items: center; gap: 12px; margin-bottom: 1.25rem; }
    .po-icon { width: 40px; height: 40px; background: #2563eb; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .po-header h1 { font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 0; }
    .po-header p { font-size: 0.78rem; color: #64748b; margin: 2px 0 0; }
    .po-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .po-controls { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 16px; border-bottom: 1px solid #f1f5f9; flex-wrap: wrap; }
    .po-controls label { font-size: 0.78rem; font-weight: 600; color: #475569; }
    .po-controls select { border: 1px solid #e2e8f0; border-radius: 6px; padding: 4px 8px; font-size: 0.8rem; }
    .po-controls input[type="text"] { border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px 10px; font-size: 0.8rem; min-width: 200px; }
    .po-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
    .po-table thead { background: #f8fafc; }
    .po-table th { padding: 10px 14px; font-weight: 700; color: #374151; text-transform: uppercase; font-size: 0.72rem; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; text-align: left; white-space: nowrap; }
    .po-table td { padding: 10px 14px; border-bottom: 1px solid #f1f5f9; color: #374151; vertical-align: middle; }
    .po-table tbody tr:hover { background: #f8fafc; }
    .po-badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; }
    .po-badge-pending { background: #fef3c7; color: #92400e; }
    .po-badge-approved { background: #d1fae5; color: #065f46; }
    .po-badge-completed { background: #dbeafe; color: #1e40af; }
    .po-badge-rejected { background: #fee2e2; color: #991b1b; }
    .po-badge-confirmed { background: #dbeafe; color: #1e40af; }
    .po-badge-delivered { background: #d1fae5; color: #065f46; }
    .po-badge-shipped { background: #ede9fe; color: #6d28d9; }
    .po-badge-receipt { background: #e0e7ff; color: #3730a3; }
    .po-badge-no-receipt { background: #f1f5f9; color: #64748b; }
    .po-btn { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: all 0.15s; }
    .po-btn-review { background: #2563eb; color: #fff; }
    .po-btn-review:hover { background: #1d4ed8; color: #fff; }
    .po-btn-deliver { background: #059669; color: #fff; }
    .po-btn-deliver:hover { background: #047857; color: #fff; }
    .po-empty { text-align: center; padding: 3rem 1rem; color: #94a3b8; }
    .po-empty svg { margin: 0 auto 12px; display: block; }
    .po-footer { padding: 10px 16px; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; font-size: 0.75rem; color: #64748b; flex-wrap: wrap; gap: 8px; }
    .po-pagination { display: flex; gap: 4px; }
    .po-pagination button { padding: 4px 10px; border: 1px solid #e2e8f0; border-radius: 4px; background: #fff; font-size: 0.75rem; cursor: pointer; color: #374151; }
    .po-pagination button.active { background: #2563eb; color: #fff; border-color: #2563eb; }
    .po-pagination button:disabled { opacity: 0.4; cursor: default; }
    .po-order-id { font-weight: 700; color: #1e293b; font-family: monospace; font-size: 0.8rem; }
    .po-cp-name { font-weight: 600; color: #334155; }
    .po-date { color: #64748b; }
    .po-amount { font-weight: 700; color: #059669; }
    .po-search { display: flex; align-items: center; gap: 8px; }
    @media(max-width:768px) {
        .po-controls { flex-direction: column; align-items: stretch; }
        .po-table { font-size: 0.75rem; }
        .po-table th, .po-table td { padding: 8px 10px; }
        .po-footer { flex-direction: column; align-items: center; }
    }
</style>
@endsection

@section('content')
<div class="po-wrap">
    <div class="po-header">
        <div class="po-icon">
            <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <h1>CP Orders</h1>
            <p>Review, confirm and deliver inventory requests from channel partners</p>
        </div>
    </div>

    <div class="po-card">
        <div class="po-controls">
            <div style="display:flex;align-items:center;gap:6px;">
                <label>Show</label>
                <select id="poPerPage">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="-1">All</option>
                </select>
                <label>entries</label>
            </div>
            <div class="po-search">
                <svg width="14" height="14" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                <input type="text" id="poSearch" placeholder="Search orders...">
            </div>
        </div>

        @if($orders->count() > 0)
        <div style="overflow-x:auto;">
            <table class="po-table" id="poTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Channel Partner</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <span class="po-order-id">{{ $order->order_id }}</span>
                            @if($order->type === 'customer_order')
                                <span class="po-badge po-badge-confirmed" style="font-size:.6rem;margin-left:4px;">Shop</span>
                            @endif
                        </td>
                        <td><span class="po-cp-name">{{ $order->cp_name }}</span></td>
                        <td><span class="po-date">{{ \Carbon\Carbon::parse($order->date)->format('d M Y') }}</span></td>
                        <td>{{ $order->items }} {{ $order->items === 1 ? 'item' : 'items' }}</td>
                        <td>
                            @if($order->payment_screenshot)
                                <span class="po-badge po-badge-receipt">Receipt Uploaded</span>
                            @else
                                <span class="po-badge po-badge-no-receipt">No Receipt</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusClass = match($order->status) {
                                    'confirmed' => 'po-badge-confirmed',
                                    'shipped' => 'po-badge-shipped',
                                    'delivered' => 'po-badge-delivered',
                                    'approved' => 'po-badge-approved',
                                    'completed' => 'po-badge-completed',
                                    'rejected' => 'po-badge-rejected',
                                    'cancelled' => 'po-badge-rejected',
                                    default => 'po-badge-pending',
                                };
                            @endphp
                            <span class="po-badge {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td style="display:flex;gap:6px;align-items:center;">
                            @if($order->type === 'customer_order')
                            <a href="{{ route('viewCustomerOrder', $order->id) }}" class="po-btn po-btn-review">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Review
                            </a>
                            @else
                            <a href="{{ route('viewSingleOrder', ['id' => $order->id]) }}" class="po-btn po-btn-review">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Review
                            </a>
                            @if($order->status === 'confirmed')
                            <form method="POST" action="{{ route('markCpOrderDelivered', $order->id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="po-btn po-btn-deliver" onclick="return confirm('Mark this order as delivered?')">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    Deliver
                                </button>
                            </form>
                            @endif
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="po-footer" id="poFooter">
            <span id="poInfo"></span>
            <div class="po-pagination" id="poPagination"></div>
        </div>
        @else
        <div class="po-empty">
            <svg width="48" height="48" fill="none" stroke="#cbd5e1" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
            <p style="font-weight:600;font-size:0.9rem;margin:0;">No pending orders</p>
            <p style="font-size:0.78rem;margin:4px 0 0;">All orders have been processed</p>
        </div>
        @endif
    </div>
</div>
@endsection

@section('js')
<script>
(function() {
    var rows = document.querySelectorAll('#poTable tbody tr');
    if (!rows.length) return;
    var allRows = Array.from(rows);
    var perPage = 10;
    var currentPage = 1;
    var filtered = allRows.slice();

    function render() {
        var start = (currentPage - 1) * perPage;
        var end = perPage === -1 ? filtered.length : start + perPage;
        var visible = filtered.slice(start, end);

        allRows.forEach(function(r) { r.style.display = 'none'; });
        visible.forEach(function(r) { r.style.display = ''; });

        var total = filtered.length;
        var showing = perPage === -1 ? total : Math.min(end, total);
        document.getElementById('poInfo').textContent = 'Showing ' + (total ? start + 1 : 0) + ' to ' + showing + ' of ' + total + ' entries';

        var pages = perPage === -1 ? 1 : Math.ceil(total / perPage);
        var pag = document.getElementById('poPagination');
        pag.innerHTML = '';
        if (pages > 1) {
            var prev = document.createElement('button');
            prev.textContent = 'Prev';
            prev.disabled = currentPage === 1;
            prev.onclick = function() { currentPage--; render(); };
            pag.appendChild(prev);
            for (var i = 1; i <= pages; i++) {
                var btn = document.createElement('button');
                btn.textContent = i;
                if (i === currentPage) btn.className = 'active';
                btn.onclick = (function(p) { return function() { currentPage = p; render(); }; })(i);
                pag.appendChild(btn);
            }
            var next = document.createElement('button');
            next.textContent = 'Next';
            next.disabled = currentPage === pages;
            next.onclick = function() { currentPage++; render(); };
            pag.appendChild(next);
        }
    }

    document.getElementById('poPerPage').addEventListener('change', function() {
        perPage = parseInt(this.value);
        currentPage = 1;
        render();
    });

    document.getElementById('poSearch').addEventListener('input', function() {
        var q = this.value.toLowerCase();
        filtered = allRows.filter(function(r) {
            return r.textContent.toLowerCase().indexOf(q) !== -1;
        });
        currentPage = 1;
        render();
    });

    render();
})();
</script>
@endsection
