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

    .po-section-title { font-size: 0.9rem; font-weight: 700; color: #1e293b; margin: 0 0 0.75rem; display: flex; align-items: center; gap: 8px; }
    .po-section-count { font-size: 0.72rem; font-weight: 700; color: #fff; background: #2563eb; padding: 2px 8px; border-radius: 10px; }
    .po-section-count-muted { background: #64748b; }
    .po-divider { border: none; border-top: 2px solid #e2e8f0; margin: 1.5rem 0; }

    .po-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); margin-bottom: 1rem; }
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
    .po-badge-cancelled { background: #fee2e2; color: #991b1b; }
    .po-badge-receipt { background: #e0e7ff; color: #3730a3; }
    .po-badge-no-receipt { background: #f1f5f9; color: #64748b; }
    .po-btn { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: all 0.15s; }
    .po-btn-review { background: #2563eb; color: #fff; }
    .po-btn-review:hover { background: #1d4ed8; color: #fff; }
    .po-btn-deliver { background: #059669; color: #fff; }
    .po-btn-deliver:hover { background: #047857; color: #fff; }
    .po-empty { text-align: center; padding: 2rem 1rem; color: #94a3b8; font-size: 0.85rem; }
    .po-order-id { font-weight: 700; color: #1e293b; font-family: monospace; font-size: 0.8rem; }
    .po-cp-name { font-weight: 600; color: #334155; }
    .po-date { color: #64748b; }
    .po-amount { font-weight: 700; color: #059669; }
    @media(max-width:768px) {
        .po-table { font-size: 0.75rem; }
        .po-table th, .po-table td { padding: 8px 10px; }
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
            <p>Review, confirm and deliver orders from channel partners</p>
        </div>
    </div>

    <div class="po-section-title">
        Pending Orders
        <span class="po-section-count">{{ $pendingOrders->count() }}</span>
    </div>

    @if($pendingOrders->count() > 0)
    <div class="po-card">
        <div style="overflow-x:auto;">
            <table class="po-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Channel Partner</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingOrders as $order)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <span class="po-order-id">{{ $order->order_id }}</span>
                            @if(str_starts_with($order->order_id, 'REORD'))
                                <span class="po-badge" style="font-size:.6rem;margin-left:4px;background:#fef3c7;color:#92400e;">Re-Order</span>
                            @endif
                        </td>
                        <td><span class="po-cp-name">{{ $order->cp_name }}</span></td>
                        <td><span class="po-date">{{ \Carbon\Carbon::parse($order->date)->format('d M Y') }}</span></td>
                        <td>{{ $order->items }} {{ $order->items === 1 ? 'item' : 'items' }}</td>
                        <td><span class="po-amount">{{ $order->amount ? '₹' . number_format($order->amount, 2) : '-' }}</span></td>
                        <td>
                            @if($order->payment_screenshot)
                                <span class="po-badge po-badge-receipt">Receipt</span>
                            @else
                                <span class="po-badge po-badge-no-receipt">No Receipt</span>
                            @endif
                        </td>
                        <td><span class="po-badge po-badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                        <td style="display:flex;gap:6px;align-items:center;">
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
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="po-card">
        <div class="po-empty">No pending orders right now.</div>
    </div>
    @endif

    <hr class="po-divider">

    <div class="po-section-title">
        Past Orders
        <span class="po-section-count po-section-count-muted">{{ $pastOrders->count() }}</span>
    </div>

    @if($pastOrders->count() > 0)
    <div class="po-card">
        <div style="overflow-x:auto;">
            <table class="po-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Channel Partner</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pastOrders as $order)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="po-order-id">{{ $order->order_id }}</span></td>
                        <td><span class="po-cp-name">{{ $order->cp_name }}</span></td>
                        <td><span class="po-date">{{ \Carbon\Carbon::parse($order->date)->format('d M Y') }}</span></td>
                        <td>{{ $order->items }} {{ $order->items === 1 ? 'item' : 'items' }}</td>
                        <td><span class="po-amount">{{ $order->amount ? '₹' . number_format($order->amount, 2) : '-' }}</span></td>
                        <td>
                            @php
                                $statusClass = match($order->status) {
                                    'delivered' => 'po-badge-delivered',
                                    'completed' => 'po-badge-completed',
                                    'rejected' => 'po-badge-rejected',
                                    'cancelled' => 'po-badge-cancelled',
                                    default => 'po-badge-pending',
                                };
                            @endphp
                            <span class="po-badge {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('viewSingleOrder', ['id' => $order->id]) }}" class="po-btn po-btn-review">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="po-card">
        <div class="po-empty">No past orders yet.</div>
    </div>
    @endif
</div>
@endsection
