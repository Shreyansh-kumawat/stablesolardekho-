@extends('layouts.adminLayout')

@section('css')
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary-blue: #4A90E2; --primary-light: #f5f7fa; --text-primary: #2d3436; --text-secondary: #636e72; --border-color: #e1e8ed; --card-bg: #ffffff; }
        body { background: var(--primary-light); color: var(--text-primary); }
        .page-header { background: #ffffff; padding: 1.5rem; margin-bottom: 1.5rem; border-radius: 8px; border: 1px solid var(--border-color); }
        .page-header h1 { font-weight: 600; margin: 0; font-size: 1.25rem; }
        .page-header p { color: var(--text-secondary); margin: 0.35rem 0 0 0; font-size: 0.9rem; }
        .stat-card { background: #fff; border: 1px solid var(--border-color); border-radius: 8px; padding: 1.25rem; }
        .stat-card .label { color: var(--text-secondary); font-size: 0.78rem; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; }
        .stat-card .value { font-size: 1.75rem; font-weight: 700; margin-top: 0.35rem; color: var(--primary-blue); }
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
        <div class="row g-3 mb-4">
            <div class="col-md-3"><div class="stat-card"><div class="label">Products in Stock</div><div class="value">{{ $totalProducts }}</div></div></div>
            <div class="col-md-3"><div class="stat-card"><div class="label">Total Units</div><div class="value">{{ $totalUnits }}</div></div></div>
            <div class="col-md-3"><div class="stat-card"><div class="label">Total Received (IN)</div><div class="value" style="color:#2b8a3e;">{{ $totalIn }}</div></div></div>
            <div class="col-md-3"><div class="stat-card"><div class="label">Total Dispatched (OUT)</div><div class="value" style="color:#c92a2a;">{{ $totalOut }}</div></div></div>
        </div>

        <div class="row g-3">
            <div class="col-md-7">
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

            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">Low Stock Alert (&le; 5)</div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead><tr><th>Product</th><th class="text-end">Available</th></tr></thead>
                            <tbody>
                                @forelse($lowStock as $item)
                                <tr>
                                    <td>{{ $item->product->item_name ?? '-' }}</td>
                                    <td class="text-end" style="color:#c92a2a;font-weight:600;">{{ $item->available_qty }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="2" class="text-center text-muted py-4">All stock levels healthy</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
