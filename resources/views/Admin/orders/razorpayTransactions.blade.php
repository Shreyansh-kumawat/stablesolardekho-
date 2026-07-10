@extends('layouts.adminLayout')

@section('content')
<div class="p-6 space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-800">Razorpay Transaction History</h1>
        <p class="text-gray-500 text-sm mt-0.5">All online payment transactions processed via Razorpay.</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Total Collected</p>
            <p class="text-2xl font-bold text-green-600 mt-1">₹{{ number_format($paidTotal, 0) }}</p>
            <p class="text-xs text-gray-400 mt-1">From {{ $paidCount }} successful payments</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Successful</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $paidCount }}</p>
            <p class="text-xs text-gray-400 mt-1">Payments verified &amp; captured</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Failed</p>
            <p class="text-2xl font-bold text-red-500 mt-1">{{ $failedCount }}</p>
            <p class="text-xs text-gray-400 mt-1">Signature mismatch or dropped</p>
        </div>
    </div>

    {{-- Transactions Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">All Razorpay Payments</h2>
            <span class="text-xs text-gray-400">{{ $transactions->count() }} records</span>
        </div>

        @if($transactions->count())
        <div class="overflow-x-auto">
            <table id="rzpTable" class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 text-left">Order #</th>
                        <th class="px-4 py-3 text-left">Customer</th>
                        <th class="px-4 py-3 text-left">Razorpay Order ID</th>
                        <th class="px-4 py-3 text-left">Razorpay Payment ID</th>
                        <th class="px-4 py-3 text-right">Amount</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($transactions as $txn)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-mono text-xs text-gray-700 font-semibold">{{ $txn->order_number }}</td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-gray-800 text-xs">{{ $txn->user?->name ?? $txn->name }}</p>
                            <p class="text-gray-400 text-xs">{{ $txn->user?->email ?? '—' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-mono text-xs text-gray-600 bg-gray-100 px-2 py-0.5 rounded">
                                {{ $txn->razorpay_order_id ?? '—' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($txn->razorpay_payment_id)
                            <span class="font-mono text-xs text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">
                                {{ $txn->razorpay_payment_id }}
                            </span>
                            @else
                            <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-gray-800">₹{{ number_format($txn->total_amount, 0) }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($txn->payment_status === 'paid')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Paid
                                </span>
                            @elseif($txn->payment_status === 'failed')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                                    Failed
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $txn->created_at->format('d M Y, h:i A') }}</td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('viewCustomerOrder', $txn->id) }}"
                                class="text-xs font-medium text-indigo-600 hover:text-indigo-800">View Order</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-12 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
            </svg>
            <p class="font-medium">No Razorpay transactions yet</p>
            <p class="text-sm mt-1">Online payments will appear here once customers checkout via Razorpay.</p>
        </div>
        @endif
    </div>

</div>
@endsection

@section('js')
<script>
$(document).ready(function () {
    if ($('#rzpTable tbody tr').length > 1) {
        $('#rzpTable').DataTable({
            order: [[6, 'desc']],
            pageLength: 25,
            columnDefs: [{ orderable: false, targets: [7] }]
        });
    }
});
</script>
@endsection
