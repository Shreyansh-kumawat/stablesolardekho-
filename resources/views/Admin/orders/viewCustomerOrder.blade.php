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

            <!-- Order Items -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-800">Order Items</h2>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach($order->items as $item)
                    <div class="px-6 py-4 flex items-center gap-4">
                        @if($item->product && $item->product->image)
                        <img src="{{ asset('storage/' . $item->product->image) }}" style="width:56px;height:56px;object-fit:cover;border-radius:8px;border:1px solid #e5e7eb;" alt="{{ $item->product_name }}">
                        @else
                        <div class="w-14 h-14 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                        </div>
                        @endif
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">{{ $item->product_name }}</p>
                            <p class="text-sm text-gray-500">Qty: {{ $item->quantity }} x ₹{{ number_format($item->price, 2) }}</p>
                        </div>
                        <p class="font-semibold text-gray-800">₹{{ number_format($item->subtotal, 2) }}</p>
                    </div>
                    @endforeach
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-700">Grand Total</span>
                        <span class="text-xl font-bold text-indigo-600">₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

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
