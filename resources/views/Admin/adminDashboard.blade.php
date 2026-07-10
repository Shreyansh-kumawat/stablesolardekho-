@extends('layouts.adminLayout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="p-6 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-gray-500 text-sm mt-0.5">Welcome back! Here's your business overview for today.</p>
        </div>
        <span class="text-xs text-gray-400">{{ now()->format('d M Y, h:i A') }}</span>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
        <!-- Total Revenue -->
        <div class="xl:col-span-2 bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-xl p-5 text-white shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-indigo-200 text-xs font-medium uppercase tracking-wide">Total Revenue</span>
                <div class="w-9 h-9 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold">₹{{ number_format($totalRevenue, 0) }}</p>
            <p class="text-indigo-200 text-xs mt-1">from paid orders</p>
        </div>

        <!-- Orders Today -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-gray-500 text-xs font-medium uppercase tracking-wide">Today's Orders</span>
                <div class="w-9 h-9 bg-amber-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $ordersToday }}</p>
            <p class="text-gray-400 text-xs mt-1">new orders</p>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-gray-500 text-xs font-medium uppercase tracking-wide">Pending</span>
                <div class="w-9 h-9 bg-yellow-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $pendingOrders }}</p>
            <p class="text-gray-400 text-xs mt-1">need attention</p>
        </div>

        <!-- Customers -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-gray-500 text-xs font-medium uppercase tracking-wide">Customers</span>
                <div class="w-9 h-9 bg-green-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $totalCustomers }}</p>
            <p class="text-gray-400 text-xs mt-1">registered</p>
        </div>

        <!-- Products -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-gray-500 text-xs font-medium uppercase tracking-wide">Products</span>
                <div class="w-9 h-9 bg-purple-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $totalProducts }}</p>
            <p class="text-gray-400 text-xs mt-1">in catalogue</p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Monthly Revenue Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <h2 class="font-semibold text-gray-800 mb-4">Monthly Revenue (Last 6 Months)</h2>
            <canvas id="revenueChart" height="90"></canvas>
        </div>

        <!-- Order Status Donut -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <h2 class="font-semibold text-gray-800 mb-4">Orders by Status</h2>
            <canvas id="statusChart" height="160"></canvas>
            <div class="mt-4 space-y-2">
                @php
                    $statusDots = ['pending'=>'bg-yellow-400','confirmed'=>'bg-blue-400','shipped'=>'bg-purple-400','delivered'=>'bg-green-400','cancelled'=>'bg-red-400'];
                @endphp
                @foreach($statusDots as $st => $dot)
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full {{ $dot }}"></span>
                        <span class="text-gray-600 capitalize">{{ $st }}</span>
                    </div>
                    <span class="font-semibold text-gray-800">{{ $orderStatusCounts[$st] ?? 0 }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Top Products -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-800">Top Selling Products</h2>
                <a href="{{ route('manageProducts') }}" class="text-xs text-indigo-600 hover:underline">View all</a>
            </div>
            @if($topProducts->count())
            <div class="space-y-3">
                @foreach($topProducts as $i => $p)
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">{{ $i+1 }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $p->product_name }}</p>
                        <div class="w-full bg-gray-100 rounded-full h-1.5 mt-1">
                            @php $maxQty = $topProducts->max('total_qty'); @endphp
                            <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ $maxQty > 0 ? round($p->total_qty/$maxQty*100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-xs font-semibold text-gray-800">{{ $p->total_qty }} units</p>
                        <p class="text-xs text-gray-400">₹{{ number_format($p->total_revenue, 0) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="py-8 text-center text-gray-400">
                <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5" />
                </svg>
                <p class="text-sm">No sales data yet</p>
            </div>
            @endif
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-800">Recent Orders</h2>
                <a href="{{ route('customerOrders') }}" class="text-xs text-indigo-600 hover:underline">View all</a>
            </div>
            @if($recentOrders->count())
            <div class="space-y-3">
                @foreach($recentOrders as $order)
                @php
                    $statusColors = ['pending'=>'bg-yellow-100 text-yellow-800','confirmed'=>'bg-blue-100 text-blue-800','shipped'=>'bg-purple-100 text-purple-800','delivered'=>'bg-green-100 text-green-800','cancelled'=>'bg-red-100 text-red-800'];
                    $color = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                @endphp
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-semibold text-xs shrink-0">
                        {{ strtoupper(substr($order->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $order->name }}</p>
                        <p class="text-xs text-gray-400 font-mono">{{ $order->order_number }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-sm font-semibold text-gray-800">₹{{ number_format($order->total_amount, 0) }}</p>
                        <span class="inline-flex px-1.5 py-0.5 rounded text-xs font-medium {{ $color }}">{{ ucfirst($order->status) }}</span>
                    </div>
                    <a href="{{ route('viewCustomerOrder', $order->id) }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </div>
                @endforeach
            </div>
            @else
            <div class="py-8 text-center text-gray-400">
                <p class="text-sm">No orders yet</p>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

@section('js')
<script src="/assets/js/chart.umd.min.js"></script>
<script>
    // Monthly Revenue Chart
    const revenueLabels = @json($monthlyRevenue->pluck('label'));
    const revenueData   = @json($monthlyRevenue->pluck('revenue'));

    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Revenue (₹)',
                data: revenueData,
                backgroundColor: 'rgba(99, 102, 241, 0.15)',
                borderColor: 'rgba(99, 102, 241, 0.9)',
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.04)' },
                    ticks: {
                        font: { size: 11 },
                        callback: v => '₹' + (v >= 1000 ? (v/1000).toFixed(0)+'k' : v)
                    }
                },
                x: { ticks: { font: { size: 11 } }, grid: { display: false } }
            }
        }
    });

    // Status Donut Chart
    const statusData = {
        pending:   {{ $orderStatusCounts['pending']   ?? 0 }},
        confirmed: {{ $orderStatusCounts['confirmed'] ?? 0 }},
        shipped:   {{ $orderStatusCounts['shipped']   ?? 0 }},
        delivered: {{ $orderStatusCounts['delivered'] ?? 0 }},
        cancelled: {{ $orderStatusCounts['cancelled'] ?? 0 }},
    };

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pending','Confirmed','Shipped','Delivered','Cancelled'],
            datasets: [{
                data: Object.values(statusData),
                backgroundColor: ['#fbbf24','#60a5fa','#a78bfa','#34d399','#f87171'],
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: { legend: { display: false } }
        }
    });
</script>
@endsection
