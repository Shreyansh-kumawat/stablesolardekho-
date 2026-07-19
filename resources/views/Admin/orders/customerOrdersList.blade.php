@extends('layouts.adminLayout')

@section('css')
<style>
    @media (max-width: 640px) {
        #ordersTable thead { display: none; }
        #ordersTable tbody tr {
            display: block;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 12px;
            padding: 10px;
            background: white;
        }
        #ordersTable tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 4px;
            border: none;
            border-bottom: 1px solid #f3f4f6;
            font-size: 13px;
        }
        #ordersTable tbody td:last-child { border-bottom: none; }
        #ordersTable tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #6b7280;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            flex-shrink: 0;
            margin-right: 8px;
        }
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            text-align: left;
        }
    }
</style>
@endsection

@section('content')
<div class="p-3 sm:p-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4 sm:mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">E-commerce Orders</h1>
            <p class="text-gray-500 text-sm mt-0.5">All customer orders from the online store</p>
        </div>
        <span class="inline-flex items-center self-start sm:self-auto px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
            Total: {{ $orders->count() }}
        </span>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
    @endif

    {{-- Stats Row --}}
    <div class="grid grid-cols-3 sm:grid-cols-5 gap-2 sm:gap-4 mb-4 sm:mb-6">
        @php
            $statuses = ['pending','confirmed','shipped','delivered','cancelled'];
            $colors   = ['yellow','blue','purple','green','red'];
        @endphp
        @foreach($statuses as $i => $st)
        <div class="bg-white rounded-xl border border-gray-200 p-3 sm:p-4 text-center shadow-sm">
            <p class="text-xl sm:text-2xl font-bold text-{{ $colors[$i] }}-600">{{ $orders->where('status', $st)->count() }}</p>
            <p class="text-xs text-gray-500 capitalize mt-1">{{ $st }}</p>
        </div>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="ordersTable" class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider whitespace-nowrap">Order #</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider whitespace-nowrap">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Payment</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider whitespace-nowrap">Pay Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider whitespace-nowrap">Order Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-mono text-xs font-medium text-gray-800" data-label="Order #">{{ $order->order_number }}</td>
                        <td class="px-4 py-3" data-label="Customer">
                            <div class="font-medium text-gray-800">{{ $order->name }}</div>
                            <div class="text-xs text-gray-500">{{ $order->user->email ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-3 font-semibold text-gray-800 whitespace-nowrap" data-label="Total">₹{{ number_format($order->total_amount, 2) }}</td>
                        <td class="px-4 py-3 capitalize text-gray-600" data-label="Payment">{{ $order->payment_method }}</td>
                        <td class="px-4 py-3" data-label="Pay Status">
                            @if($order->payment_status === 'paid')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Verified</span>
                            @elseif($order->payment_status === 'verification_pending')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Verify</span>
                            @elseif($order->payment_status === 'pending')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Not Paid</span>
                            @elseif($order->payment_status === 'failed')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Rejected</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($order->payment_status) }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3" data-label="Order Status">
                            @php
                                $statusColors = [
                                    'pending'   => 'bg-yellow-100 text-yellow-800',
                                    'confirmed' => 'bg-blue-100 text-blue-800',
                                    'shipped'   => 'bg-purple-100 text-purple-800',
                                    'delivered' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                                $color = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap" data-label="Date">{{ $order->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3" data-label="Action">
                            <div style="display:flex;align-items:center;gap:8px;">
                                <a href="{{ route('viewCustomerOrder', $order->id) }}"
                                   style="display:inline-flex;align-items:center;gap:4px;padding:6px 12px;background:#4f46e5;color:#fff;font-size:12px;font-weight:600;border-radius:8px;text-decoration:none;white-space:nowrap;">
                                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    View
                                </a>
                                <form action="{{ route('admin.order.delete', $order->id) }}" method="POST" class="delete-order-form" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="delete-order-btn"
                                        style="display:inline-flex;align-items:center;gap:4px;padding:6px 12px;background:#dc2626;color:#fff;font-size:12px;font-weight:600;border-radius:8px;border:none;cursor:pointer;white-space:nowrap;"
                                        data-number="{{ $order->order_number }}">
                                        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
    $('#ordersTable').DataTable({
        order: [[6, 'desc']],
        pageLength: 25,
        language: {
            emptyTable: 'No customer orders yet',
            search: '',
            searchPlaceholder: 'Search orders...',
        }
    });

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
