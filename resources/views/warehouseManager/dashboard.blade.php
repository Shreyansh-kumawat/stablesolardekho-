@extends('layouts.adminLayout')

@section('css')
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary-blue: #4A90E2; --primary-light: #f5f7fa; --text-primary: #2d3436; --text-secondary: #636e72; --border-color: #e1e8ed; --card-bg: #ffffff; }
        body { background: var(--primary-light); color: var(--text-primary); }
        .page-header { background: #ffffff; padding: 1.5rem; margin-bottom: 1.5rem; border-radius: 8px; border: 1px solid var(--border-color); }
        .page-header h1 { font-weight: 600; margin: 0; font-size: 1.25rem; }
        .page-header p { color: var(--text-secondary); margin: 0.35rem 0 0 0; font-size: 0.9rem; }
        .stat-strip { display: flex; gap: 12px; flex-wrap: wrap; }
        .stat-item { flex: 1; min-width: 140px; background: #fff; border: 1px solid var(--border-color); border-radius: 8px; padding: 0.75rem 1rem; display: flex; align-items: center; gap: 8px; }
        .stat-item .stat-num { font-size: 1.1rem; font-weight: 700; white-space: nowrap; }
        .stat-item .stat-label { font-size: 0.78rem; color: var(--text-secondary); }
        .card { border: 1px solid var(--border-color); border-radius: 8px; background: var(--card-bg); }
        .card-header { background: #fff; border-bottom: 1px solid var(--border-color); font-weight: 600; font-size: 0.95rem; padding: 0.9rem 1.15rem; }
        .table thead th { background: #f8f9fa; font-weight: 600; font-size: 0.78rem; text-transform: uppercase; }
        .table tbody td { font-size: 0.85rem; vertical-align: middle; }
        .badge-in { background: #d3f9d8; color: #2b8a3e; padding: 0.25rem 0.6rem; border-radius: 12px; font-weight: 600; font-size: 0.72rem; }
        .badge-out { background: #ffe3e3; color: #c92a2a; padding: 0.25rem 0.6rem; border-radius: 12px; font-weight: 600; font-size: 0.72rem; }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <h1><i class="fas fa-warehouse me-2"></i>{{ $warehouse->name }}</h1>
        <p>{{ $warehouse->city }}{{ $warehouse->state ? ', '.$warehouse->state : '' }}</p>
    </div>

    <div class="container-fluid">
        @if($lowStock->count())
        <div class="card mb-3">
            <div class="card-header" style="color:#e67700;"><i class="fas fa-exclamation-triangle me-1"></i> Low Stock Alerts (5 or less)</div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Product</th><th class="text-end">Available</th></tr></thead>
                    <tbody>
                        @foreach($lowStock as $item)
                        <tr>
                            <td>{{ $item->product->item_name ?? '-' }}</td>
                            <td class="text-end" style="color:#c92a2a;font-weight:600;">{{ $item->available_qty }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <div class="stat-strip mb-3">
            <div class="stat-item"><span class="stat-num" style="color:var(--primary-blue);">{{ $totalProducts }}</span> <span class="stat-label">Products in Stock</span></div>
            <div class="stat-item"><span class="stat-num" style="color:#e67700;">{{ $totalUnits }}</span> <span class="stat-label">Total Units</span></div>
            <div class="stat-item"><span class="stat-num" style="color:#2b8a3e;">{{ $totalIn }}</span> <span class="stat-label">Received (IN)</span></div>
            <div class="stat-item"><span class="stat-num" style="color:#c92a2a;">{{ $totalOut }}</span> <span class="stat-label">Dispatched (OUT)</span></div>
        </div>

        <div class="card">
            <div class="card-header">Recent Transactions</div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Date</th><th>Product</th><th>Type</th><th>Qty</th></tr></thead>
                    <tbody>
                        @forelse($recentTxns as $t)
                        <tr>
                            <td>{{ $t->created_at->format('d M, H:i') }}</td>
                            <td>{{ $t->product->item_name ?? '-' }}</td>
                            <td><span class="badge-{{ strtolower($t->transaction_type) }}">{{ $t->transaction_type }}</span></td>
                            <td>{{ $t->quantity }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">No transactions yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
