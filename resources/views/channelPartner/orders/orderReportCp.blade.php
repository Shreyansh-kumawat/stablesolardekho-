@extends('layouts.adminLayout')
@section('title', 'My Orders')

@section('css')
<style>
    .cpo-wrap { max-width: 1100px; margin: 0 auto; padding: 1.5rem 1rem; }
    .cpo-header { margin-bottom: 1.25rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .75rem; }
    .cpo-header-left { display: flex; align-items: center; gap: .75rem; }
    .cpo-header-left h1 { font-size: 1.3rem; font-weight: 800; color: #1f2937; margin: 0; }
    .cpo-header-left p { font-size: .8rem; color: #6b7280; margin: .15rem 0 0; }
    .cpo-icon-box { width: 40px; height: 40px; background: #2563eb; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; }
    .cpo-badge { padding: 5px 14px; border-radius: 20px; font-size: .78rem; font-weight: 600; background: #eff6ff; color: #1d4ed8; }

    .cpo-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
    .cpo-card .dataTables_wrapper { padding: 12px 16px; }
    .cpo-card .dataTables_filter input {
        border: 1px solid #e5e7eb; border-radius: 6px; padding: 6px 10px; font-size: .85rem; outline: none;
    }
    .cpo-card .dataTables_filter input:focus { border-color: #2563eb; box-shadow: 0 0 0 2px rgba(37,99,235,.1); }
    .cpo-card .dataTables_length select { border: 1px solid #e5e7eb; border-radius: 6px; padding: 4px 8px; font-size: .85rem; }

    #cpoTable { width: 100%; border-collapse: collapse; }
    #cpoTable thead th {
        background: #f9fafb; padding: 10px 14px;
        font-size: .7rem; font-weight: 700; color: #6b7280;
        text-transform: uppercase; letter-spacing: .05em;
        text-align: left; border-bottom: 1px solid #e5e7eb; white-space: nowrap;
    }
    #cpoTable tbody td {
        padding: 10px 14px; font-size: .84rem; color: #374151;
        border-bottom: 1px solid #f3f4f6; vertical-align: middle;
    }
    #cpoTable tbody tr:hover { background: #f9fafb; }

    .cpo-pill { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: .72rem; font-weight: 600; white-space: nowrap; }
    .cpo-pill-yellow { background: #fef9c3; color: #92400e; }
    .cpo-pill-green { background: #dcfce7; color: #166534; }
    .cpo-pill-red { background: #fee2e2; color: #991b1b; }
    .cpo-pill-blue { background: #dbeafe; color: #1e40af; }
    .cpo-pill-gray { background: #f3f4f6; color: #374151; }

    .cpo-amount { font-weight: 700; color: #1f2937; white-space: nowrap; }
    .cpo-date { font-size: .78rem; color: #9ca3af; white-space: nowrap; }
    .cpo-order-id { font-family: monospace; font-weight: 600; font-size: .8rem; color: #1f2937; }

    .cpo-btn { display: inline-flex; align-items: center; gap: 4px; padding: 5px 12px; font-size: .75rem; font-weight: 600; border-radius: 6px; text-decoration: none; white-space: nowrap; transition: background .15s; }
    .cpo-btn-view { background: #2563eb; color: #fff; }
    .cpo-btn-view:hover { background: #1d4ed8; color: #fff; }
    .cpo-btn-pay { background: #dcfce7; color: #166534; }
    .cpo-btn-pay:hover { background: #bbf7d0; color: #166534; }
    .cpo-actions { display: flex; align-items: center; gap: 6px; }

    @media (max-width: 768px) {
        .cpo-wrap { padding: 12px; }
        #cpoTable thead { display: none; }
        #cpoTable tbody tr {
            display: block; background: #fff; border: 1px solid #e5e7eb;
            border-radius: 10px; margin-bottom: 10px; padding: 12px;
        }
        #cpoTable tbody td {
            display: flex; justify-content: space-between; align-items: center;
            padding: 5px 0; border-bottom: 1px solid #f3f4f6;
        }
        #cpoTable tbody td:last-child { border-bottom: none; }
        #cpoTable tbody td::before {
            content: attr(data-label); font-weight: 700; color: #9ca3af;
            font-size: .68rem; text-transform: uppercase; letter-spacing: .05em;
            flex-shrink: 0; margin-right: 10px;
        }
    }
</style>
@endsection

@section('content')
<div class="cpo-wrap">
    <div class="cpo-header">
        <div class="cpo-header-left">
            <div class="cpo-icon-box">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
            </div>
            <div>
                <h1>My Orders</h1>
                <p>Track your orders and their status</p>
            </div>
        </div>
        <span class="cpo-badge">Total: {{ $orders->count() }}</span>
    </div>

    <div class="cpo-card">
        <div style="overflow-x:auto;">
            <table id="cpoTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td data-label="#">{{ $loop->iteration }}</td>
                        <td data-label="Order ID">
                            <span class="cpo-order-id">{{ $order->order_id }}</span>
                            @if($order->type === 'customer_order')
                                <span class="cpo-pill cpo-pill-blue" style="font-size:.6rem;margin-left:4px;">Shop</span>
                            @endif
                        </td>
                        <td data-label="Date">
                            <span class="cpo-date">{{ \Carbon\Carbon::parse($order->date)->format('d M Y') }}</span>
                        </td>
                        <td data-label="Amount">
                            @if($order->amount)
                                <span class="cpo-amount">&#8377;{{ number_format($order->amount, 0) }}</span>
                            @else
                                <span style="color:#9ca3af;font-size:.78rem;">Pending</span>
                            @endif
                        </td>
                        <td data-label="Status">
                            @if($order->status == 'pending')
                                <span class="cpo-pill cpo-pill-yellow">Pending</span>
                            @elseif($order->status == 'confirmed')
                                <span class="cpo-pill cpo-pill-blue">Confirmed</span>
                            @elseif($order->status == 'shipped')
                                <span class="cpo-pill cpo-pill-blue">Shipped</span>
                            @elseif($order->status == 'delivered')
                                <span class="cpo-pill cpo-pill-green">Delivered</span>
                            @elseif($order->status == 'completed')
                                <span class="cpo-pill cpo-pill-green">Completed</span>
                            @elseif($order->status == 'cancelled')
                                <span class="cpo-pill cpo-pill-red">Cancelled</span>
                            @else
                                <span class="cpo-pill cpo-pill-gray">{{ ucfirst($order->status) }}</span>
                            @endif
                        </td>
                        <td data-label="Payment">
                            @if($order->payment_status === 'verification_pending')
                                <span class="cpo-pill cpo-pill-blue">Receipt Uploaded</span>
                            @elseif($order->payment_status === 'paid')
                                <span class="cpo-pill cpo-pill-green">Verified</span>
                            @elseif($order->payment_status === 'failed')
                                <span class="cpo-pill cpo-pill-red">Rejected</span>
                            @else
                                <span class="cpo-pill cpo-pill-gray">Unpaid</span>
                            @endif
                        </td>
                        <td data-label="Action">
                            <div class="cpo-actions">
                                @if($order->type === 'customer_order')
                                <a href="{{ route('user.order.detail', $order->id) }}" class="cpo-btn cpo-btn-view">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    View
                                </a>
                                @else
                                <a href="{{ route('viewSingleOrderCp', $order->id) }}" class="cpo-btn cpo-btn-view">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    View
                                </a>
                                @if(!in_array($order->payment_status, ['paid', 'verification_pending']))
                                <a href="{{ route('cpOrderPayment', $order->id) }}" class="cpo-btn cpo-btn-pay">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                    Pay
                                </a>
                                @endif
                                @endif
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
    if ($('#cpoTable tbody tr').length > 0) {
        $('#cpoTable').DataTable({
            order: [[2, 'desc']],
            pageLength: 25,
            language: {
                emptyTable: 'No orders yet',
                search: '',
                searchPlaceholder: 'Search orders...',
            }
        });
    }
});
</script>
@endsection
