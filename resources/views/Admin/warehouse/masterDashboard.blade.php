@extends('layouts.adminLayout')

@section('css')
    <style>
        :root { --primary-blue: #4A90E2; --primary-light: #f5f7fa; --text-primary: #2d3436; --text-secondary: #636e72; --border-color: #e1e8ed; --hover-bg: #f1f3f5; --card-bg: #ffffff; }
        body { background: var(--primary-light); color: var(--text-primary); }
        .page-header { background: #ffffff; padding: 1.5rem 0; margin-bottom: 1.5rem; border-radius: 8px; border: 1px solid var(--border-color); box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .page-header h1 { color: var(--text-primary); font-weight: 600; margin: 0; font-size: 1.25rem; }
        .page-header p { color: var(--text-secondary); margin: 0.35rem 0 0 0; font-size: 0.9rem; }
        .card { border: 1px solid var(--border-color); border-radius: 8px; background: var(--card-bg); box-shadow: 0 1px 3px rgba(0,0,0,0.04); margin-bottom: 1rem; }
        .card-body { padding: 1.5rem; }
        .card-title { font-weight: 600; font-size: 0.95rem; margin-bottom: 1rem; color: var(--text-primary); }
        .stat-card { background: #fff; border: 1px solid var(--border-color); border-radius: 8px; padding: 1.25rem; text-align: center; }
        .stat-card .stat-num { font-size: 1.5rem; font-weight: 700; }
        .stat-card .stat-label { font-size: 0.78rem; color: var(--text-secondary); margin-top: 2px; }
        .table thead th { background: #f8f9fa; color: var(--text-primary); font-weight: 600; border-bottom: 1px solid var(--border-color); padding: 0.75rem; font-size: 0.8rem; text-transform: uppercase; }
        .table tbody td { padding: 0.7rem 0.75rem; vertical-align: middle; border-color: var(--border-color); font-size: 0.85rem; }
        .table tbody tr:hover { background-color: var(--hover-bg); }
        .badge-in { background: #d3f9d8; color: #2b8a3e; padding: 0.25rem 0.55rem; border-radius: 12px; font-weight: 600; font-size: 0.73rem; }
        .badge-out { background: #fff5f5; color: #c92a2a; padding: 0.25rem 0.55rem; border-radius: 12px; font-weight: 600; font-size: 0.73rem; }
        .wh-card { background: #fff; border: 1px solid var(--border-color); border-radius: 8px; padding: 1rem 1.25rem; transition: border-color 0.15s; text-decoration: none; display: block; color: inherit; }
        .wh-card:hover { border-color: var(--primary-blue); }
        .wh-card .wh-name { font-weight: 700; font-size: 0.95rem; color: var(--text-primary); margin: 0 0 8px; }
        .wh-card .wh-stats { display: flex; gap: 16px; flex-wrap: wrap; }
        .wh-card .wh-stat { text-align: center; }
        .wh-card .wh-stat .num { font-weight: 700; font-size: 1.1rem; color: var(--primary-blue); }
        .wh-card .wh-stat .lbl { font-size: 0.7rem; color: var(--text-secondary); }
        .alert-low { background: #fff8e1; border: 1px solid #ffd54f; border-radius: 8px; padding: 0.6rem 1rem; display: flex; align-items: center; gap: 8px; margin-bottom: 6px; font-size: 0.82rem; }
        .alert-low .qty { font-weight: 700; color: #c92a2a; }
        .quick-link { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; border: 1px solid var(--border-color); background: #fff; color: var(--text-primary); text-decoration: none; transition: all 0.15s; }
        .quick-link:hover { background: var(--primary-blue); color: #fff; border-color: var(--primary-blue); }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="fas fa-th-large me-2"></i>Warehouse Master Dashboard</h1>
                <p>Cross-warehouse overview and alerts</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.warehouses.w2wTransfer') }}" class="quick-link"><i class="fas fa-exchange-alt"></i> W2W Transfer</a>
                <a href="{{ route('admin.warehouses.transfer') }}" class="quick-link"><i class="fas fa-arrow-right"></i> Main to WH</a>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-num" style="color:var(--primary-blue);">{{ $totalWarehouses }}</div>
                    <div class="stat-label">Active Warehouses</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-num" style="color:#e67700;">{{ $grandTotalStock }}</div>
                    <div class="stat-label">Total Stock (All WH)</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-num" style="color:#2b8a3e;">Rs {{ number_format($grandTotalIn, 2) }}</div>
                    <div class="stat-label">Total IN Value</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-num" style="color:#c92a2a;">Rs {{ number_format($grandTotalOut, 2) }}</div>
                    <div class="stat-label">Total OUT Value</div>
                </div>
            </div>
        </div>

        @if($lowStockItems->count())
        <div class="card">
            <div class="card-body">
                <h5 class="card-title" style="color:#e67700;"><i class="fas fa-exclamation-triangle me-1"></i> Low Stock Alerts (5 or less)</h5>
                @foreach($lowStockItems as $item)
                <div class="alert-low">
                    <i class="fas fa-exclamation-circle" style="color:#f59e0b;"></i>
                    <span><strong>{{ $item->warehouse_name }}</strong> &rarr; {{ $item->item_name }} ({{ $item->item_code }})</span>
                    <span class="qty">Only {{ $item->available_qty }} left</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-warehouse me-1"></i> All Warehouses</h5>
                <div class="row g-3">
                    @foreach($warehouses as $wh)
                    <div class="col-md-4">
                        <a href="{{ route('admin.warehouses.dashboard', $wh->id) }}" class="wh-card">
                            <p class="wh-name"><i class="fas fa-warehouse me-1" style="color:var(--primary-blue);"></i> {{ $wh->name }}</p>
                            <div class="wh-stats">
                                <div class="wh-stat">
                                    <div class="num">{{ $wh->inventories_count }}</div>
                                    <div class="lbl">Products</div>
                                </div>
                                <div class="wh-stat">
                                    <div class="num" style="color:#e67700;">{{ $wh->total_stock }}</div>
                                    <div class="lbl">Stock</div>
                                </div>
                                <div class="wh-stat">
                                    <div class="num" style="color:#2b8a3e;">{{ number_format($wh->total_in_value) }}</div>
                                    <div class="lbl">IN Value</div>
                                </div>
                                <div class="wh-stat">
                                    <div class="num" style="color:#c92a2a;">{{ number_format($wh->total_out_value) }}</div>
                                    <div class="lbl">OUT Value</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-clock me-1"></i> Recent Transactions (All Warehouses)</h5>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Warehouse</th>
                                <th>Product</th>
                                <th>Type</th>
                                <th>Qty</th>
                                <th>Transfer</th>
                                <th>By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTxns as $txn)
                            <tr>
                                <td style="white-space:nowrap;">{{ $txn->created_at->format('d M, h:i A') }}</td>
                                <td style="font-weight:600;">{{ $txn->warehouse->name ?? '-' }}</td>
                                <td>{{ $txn->product->item_name ?? '-' }}</td>
                                <td><span class="{{ $txn->transaction_type === 'IN' ? 'badge-in' : 'badge-out' }}">{{ $txn->transaction_type }}</span></td>
                                <td>{{ $txn->quantity }}</td>
                                <td>{{ $txn->transfer_type ?? '-' }}</td>
                                <td>{{ $txn->performer->name ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center text-muted py-3">No transactions yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
