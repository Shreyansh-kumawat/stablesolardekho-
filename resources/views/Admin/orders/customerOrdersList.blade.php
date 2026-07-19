@extends('layouts.adminLayout')

@section('css')
<style>
    .co-page { padding: 20px; }

    /* Header */
    .co-header {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 10px; margin-bottom: 20px;
    }
    .co-header h1 { font-size: 1.3rem; font-weight: 700; color: #1f2937; margin: 0; }
    .co-header p { font-size: 0.82rem; color: #6b7280; margin: 2px 0 0; }
    .co-badge {
        display: inline-flex; align-items: center;
        padding: 5px 14px; border-radius: 20px;
        font-size: 0.8rem; font-weight: 600;
        background: #eff6ff; color: #1d4ed8;
    }

    /* Alert */
    .co-alert {
        padding: 12px 16px; border-radius: 8px;
        font-size: 0.85rem; margin-bottom: 16px;
    }
    .co-alert-ok { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
    .co-alert-err { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }

    /* Stats */
    .co-stats {
        display: flex; gap: 10px; margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .co-stat {
        flex: 1; min-width: 100px;
        background: #fff; border: 1px solid #e5e7eb;
        border-radius: 10px; padding: 14px 10px;
        text-align: center;
    }
    .co-stat-num { font-size: 1.5rem; font-weight: 800; line-height: 1; }
    .co-stat-label {
        font-size: 0.7rem; font-weight: 600; color: #6b7280;
        text-transform: uppercase; letter-spacing: 0.04em; margin-top: 4px;
    }
    .co-stat-pending .co-stat-num { color: #d97706; }
    .co-stat-confirmed .co-stat-num { color: #2563eb; }
    .co-stat-shipped .co-stat-num { color: #7c3aed; }
    .co-stat-delivered .co-stat-num { color: #16a34a; }
    .co-stat-cancelled .co-stat-num { color: #dc2626; }

    /* Table card */
    .co-table-wrap {
        background: #fff; border: 1px solid #e5e7eb;
        border-radius: 10px; overflow: hidden;
    }
    .co-table-wrap .dataTables_wrapper { padding: 12px 16px; }
    .co-table-wrap .dataTables_filter input {
        border: 1px solid #e5e7eb; border-radius: 6px;
        padding: 6px 10px; font-size: 0.85rem; outline: none;
    }
    .co-table-wrap .dataTables_filter input:focus {
        border-color: #4f46e5; box-shadow: 0 0 0 2px rgba(79,70,229,0.1);
    }
    .co-table-wrap .dataTables_length select {
        border: 1px solid #e5e7eb; border-radius: 6px;
        padding: 4px 8px; font-size: 0.85rem;
    }

    #ordersTable { width: 100%; border-collapse: collapse; }
    #ordersTable thead th {
        background: #f9fafb; padding: 10px 14px;
        font-size: 0.7rem; font-weight: 700; color: #6b7280;
        text-transform: uppercase; letter-spacing: 0.05em;
        text-align: left; border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }
    #ordersTable tbody td {
        padding: 10px 14px; font-size: 0.84rem;
        color: #374151; border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    #ordersTable tbody tr:hover { background: #f9fafb; }

    /* Badges */
    .co-pill {
        display: inline-flex; align-items: center;
        padding: 3px 10px; border-radius: 20px;
        font-size: 0.72rem; font-weight: 600;
        white-space: nowrap;
    }
    .co-pill-yellow { background: #fef9c3; color: #92400e; }
    .co-pill-blue { background: #dbeafe; color: #1e40af; }
    .co-pill-purple { background: #ede9fe; color: #6d28d9; }
    .co-pill-green { background: #dcfce7; color: #166534; }
    .co-pill-red { background: #fee2e2; color: #991b1b; }
    .co-pill-orange { background: #ffedd5; color: #9a3412; }
    .co-pill-gray { background: #f3f4f6; color: #374151; }

    /* Action buttons */
    .co-btn-view {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 5px 12px; background: #4f46e5; color: #fff;
        font-size: 0.75rem; font-weight: 600; border-radius: 6px;
        text-decoration: none; white-space: nowrap;
        transition: background 0.15s;
    }
    .co-btn-view:hover { background: #4338ca; color: #fff; }
    .co-btn-del {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 5px 12px; background: #fee2e2; color: #dc2626;
        font-size: 0.75rem; font-weight: 600; border-radius: 6px;
        border: none; cursor: pointer; white-space: nowrap;
        transition: background 0.15s;
    }
    .co-btn-del:hover { background: #fecaca; }

    .co-customer-name { font-weight: 600; color: #1f2937; }
    .co-customer-email { font-size: 0.75rem; color: #9ca3af; }
    .co-order-num { font-family: monospace; font-weight: 600; font-size: 0.8rem; color: #1f2937; }
    .co-amount { font-weight: 700; color: #1f2937; white-space: nowrap; }
    .co-date { font-size: 0.78rem; color: #9ca3af; white-space: nowrap; }
    .co-payment { text-transform: capitalize; color: #6b7280; font-size: 0.82rem; }
    .co-actions { display: flex; align-items: center; gap: 6px; }

    /* Responsive: cards on mobile */
    @media (max-width: 768px) {
        .co-page { padding: 12px; }
        .co-stats { gap: 6px; }
        .co-stat { min-width: 60px; padding: 10px 6px; }
        .co-stat-num { font-size: 1.2rem; }
        .co-stat-label { font-size: 0.62rem; }

        #ordersTable thead { display: none; }
        #ordersTable tbody tr {
            display: block; background: #fff;
            border: 1px solid #e5e7eb; border-radius: 10px;
            margin-bottom: 10px; padding: 12px;
        }
        #ordersTable tbody td {
            display: flex; justify-content: space-between;
            align-items: center; padding: 5px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        #ordersTable tbody td:last-child { border-bottom: none; }
        #ordersTable tbody td::before {
            content: attr(data-label);
            font-weight: 700; color: #9ca3af;
            font-size: 0.68rem; text-transform: uppercase;
            letter-spacing: 0.05em; flex-shrink: 0;
            margin-right: 10px;
        }
        .co-actions { flex-wrap: wrap; }
    }
    @media (max-width: 480px) {
        .co-header h1 { font-size: 1.1rem; }
        .co-stats { flex-wrap: wrap; }
        .co-stat { min-width: calc(33% - 6px); }
    }
</style>
@endsection

@section('content')
<div class="co-page">

    {{-- Header --}}
    <div class="co-header">
        <div>
            <h1>Customer Orders</h1>
            <p>All orders from the online store</p>
        </div>
        <span class="co-badge">Total: {{ $orders->count() }}</span>
    </div>

    @if(session('success'))
        <div class="co-alert co-alert-ok">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="co-alert co-alert-err">{{ session('error') }}</div>
    @endif

    {{-- Stats --}}
    @php
        $statuses = [
            'pending'   => 'co-stat-pending',
            'confirmed' => 'co-stat-confirmed',
            'shipped'   => 'co-stat-shipped',
            'delivered' => 'co-stat-delivered',
            'cancelled' => 'co-stat-cancelled',
        ];
    @endphp
    <div class="co-stats">
        @foreach($statuses as $st => $cls)
        <div class="co-stat {{ $cls }}">
            <div class="co-stat-num">{{ $orders->where('status', $st)->count() }}</div>
            <div class="co-stat-label">{{ $st }}</div>
        </div>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="co-table-wrap">
        <div style="overflow-x:auto;">
            <table id="ordersTable">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Pay Status</th>
                        <th>Order Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td data-label="Order #">
                            <span class="co-order-num">{{ $order->order_number }}</span>
                        </td>
                        <td data-label="Customer">
                            <div class="co-customer-name">{{ $order->name }}</div>
                            <div class="co-customer-email">{{ $order->user->email ?? '-' }}</div>
                        </td>
                        <td data-label="Total">
                            <span class="co-amount">₹{{ number_format($order->total_amount, 2) }}</span>
                        </td>
                        <td data-label="Payment">
                            <span class="co-payment">{{ $order->payment_method }}</span>
                        </td>
                        <td data-label="Pay Status">
                            @if($order->payment_status === 'paid')
                                <span class="co-pill co-pill-green">Verified</span>
                            @elseif($order->payment_status === 'verification_pending')
                                <span class="co-pill co-pill-orange">Verify</span>
                            @elseif($order->payment_status === 'pending')
                                <span class="co-pill co-pill-yellow">Not Paid</span>
                            @elseif($order->payment_status === 'failed')
                                <span class="co-pill co-pill-red">Rejected</span>
                            @else
                                <span class="co-pill co-pill-gray">{{ ucfirst($order->payment_status) }}</span>
                            @endif
                        </td>
                        <td data-label="Order Status">
                            @php
                                $pillMap = [
                                    'pending'   => 'co-pill-yellow',
                                    'confirmed' => 'co-pill-blue',
                                    'shipped'   => 'co-pill-purple',
                                    'delivered' => 'co-pill-green',
                                    'cancelled' => 'co-pill-red',
                                ];
                                $pillCls = $pillMap[$order->status] ?? 'co-pill-gray';
                            @endphp
                            <span class="co-pill {{ $pillCls }}">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td data-label="Date">
                            <span class="co-date">{{ $order->created_at->format('d M Y') }}</span>
                        </td>
                        <td data-label="Action">
                            <div class="co-actions">
                                <a href="{{ route('viewCustomerOrder', $order->id) }}" class="co-btn-view">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    View
                                </a>
                                <form action="{{ route('admin.order.delete', $order->id) }}" method="POST" class="delete-order-form" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="co-btn-del delete-order-btn" data-number="{{ $order->order_number }}">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function () {
    if ($('#ordersTable tbody tr').length > 0) {
        $('#ordersTable').DataTable({
            order: [[6, 'desc']],
            pageLength: 25,
            language: {
                emptyTable: 'No customer orders yet',
                search: '',
                searchPlaceholder: 'Search orders...',
            }
        });
    }

    $(document).on('click', '.delete-order-btn', function () {
        var btn = $(this);
        var orderNum = btn.data('number');
        Swal.fire({
            title: 'Delete Order?',
            text: 'Are you sure you want to delete order "' + orderNum + '"? This cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel'
        }).then(function (result) {
            if (result.isConfirmed) {
                btn.closest('.delete-order-form').submit();
            }
        });
    });
});
</script>
@endsection
