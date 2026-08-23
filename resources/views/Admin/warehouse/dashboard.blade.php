@extends('layouts.adminLayout')

@section('css')
    <style>
        :root { --primary-blue: #4A90E2; --primary-light: #f5f7fa; --text-primary: #2d3436; --text-secondary: #636e72; --border-color: #e1e8ed; --hover-bg: #f1f3f5; --card-bg: #ffffff; }
        body { background: var(--primary-light); color: var(--text-primary); }
        .page-header { background: #ffffff; padding: 1.5rem 0; margin-bottom: 1.5rem; border-radius: 8px; border: 1px solid var(--border-color); box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .page-header h1 { color: var(--text-primary); font-weight: 600; margin: 0; font-size: 1.25rem; }
        .page-header p { color: var(--text-secondary); margin: 0.35rem 0 0 0; font-size: 0.9rem; }
        .card { border: 1px solid var(--border-color); border-radius: 8px; background: var(--card-bg); box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .card-body { padding: 1.5rem; }
        .card-title { font-weight: 600; font-size: 0.95rem; margin-bottom: 1rem; color: var(--text-primary); }
        .stat-card { background: #fff; border: 1px solid var(--border-color); border-radius: 8px; padding: 1.25rem; text-align: center; }
        .stat-card .stat-icon { font-size: 1.5rem; margin-bottom: 0.5rem; }
        .stat-card .stat-num { font-size: 1.5rem; font-weight: 700; color: var(--primary-blue); }
        .stat-card .stat-label { font-size: 0.78rem; color: var(--text-secondary); margin-top: 2px; }
        .stat-card.green .stat-num { color: #2b8a3e; }
        .stat-card.red .stat-num { color: #c92a2a; }
        .stat-card.orange .stat-num { color: #e67700; }
        .table thead th { background: #f8f9fa; color: var(--text-primary); font-weight: 600; border-bottom: 1px solid var(--border-color); padding: 0.75rem; font-size: 0.8rem; text-transform: uppercase; }
        .table tbody td { padding: 0.7rem 0.75rem; vertical-align: middle; border-color: var(--border-color); font-size: 0.875rem; }
        .table tbody tr:hover { background-color: var(--hover-bg); }
        .badge-in { background: #d3f9d8; color: #2b8a3e; padding: 0.25rem 0.55rem; border-radius: 12px; font-weight: 600; font-size: 0.73rem; }
        .badge-out { background: #fff5f5; color: #c92a2a; padding: 0.25rem 0.55rem; border-radius: 12px; font-weight: 600; font-size: 0.73rem; }
        .quick-link { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; border: 1px solid var(--border-color); background: #fff; color: var(--text-primary); text-decoration: none; transition: all 0.15s; }
        .quick-link:hover { background: var(--primary-blue); color: #fff; border-color: var(--primary-blue); }
        .manager-tag { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.25rem 0.6rem; border-radius: 12px; font-size: 0.78rem; background: #e8f4fd; color: var(--primary-blue); font-weight: 500; }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="fas fa-tachometer-alt me-2"></i>{{ $warehouse->name }} - Dashboard</h1>
                <p>Overview and quick stats for this warehouse</p>
            </div>
            <a href="{{ route('admin.warehouses.index') }}" class="quick-link">
                <i class="fas fa-arrow-left"></i> All Warehouses
            </a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-boxes" style="color:var(--primary-blue);"></i></div>
                    <div class="stat-num">{{ $totalProducts }}</div>
                    <div class="stat-label">Total Products</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-cubes" style="color:#e67700;"></i></div>
                    <div class="stat-num orange">{{ $totalStock }}</div>
                    <div class="stat-label">Total Stock Qty</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card green">
                    <div class="stat-icon"><i class="fas fa-arrow-down" style="color:#2b8a3e;"></i></div>
                    <div class="stat-num">{{ number_format($totalInValue, 2) }}</div>
                    <div class="stat-label">Total IN Value (Rs)</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card red">
                    <div class="stat-icon"><i class="fas fa-arrow-up" style="color:#c92a2a;"></i></div>
                    <div class="stat-num">{{ number_format($totalOutValue, 2) }}</div>
                    <div class="stat-label">Total OUT Value (Rs)</div>
                </div>
            </div>
        </div>

        @if($warehouse->managers->count())
        <div class="mb-3">
            <span style="font-size:0.8rem;font-weight:600;color:var(--text-secondary);text-transform:uppercase;">Managers:</span>
            @foreach($warehouse->managers as $mgr)
                <span class="manager-tag"><i class="fas fa-user"></i> {{ $mgr->name }}</span>
            @endforeach
        </div>
        @endif

        <div class="d-flex flex-wrap gap-2 mb-4">
            <a href="{{ route('admin.warehouses.inventory', $warehouse->id) }}" class="quick-link"><i class="fas fa-boxes"></i> Inventory</a>
            <a href="{{ route('admin.warehouses.transactions', $warehouse->id) }}" class="quick-link"><i class="fas fa-exchange-alt"></i> Transactions</a>
            <a href="{{ route('admin.warehouses.profitLoss', $warehouse->id) }}" class="quick-link"><i class="fas fa-chart-line"></i> Profit / Loss</a>
            <a href="{{ route('admin.warehouses.transfer') }}" class="quick-link"><i class="fas fa-arrow-right"></i> Transfer Stock</a>
            <a href="{{ route('admin.warehouses.exportInventory', $warehouse->id) }}" class="quick-link"><i class="fas fa-download"></i> Export Inventory CSV</a>
        </div>

        <div class="row g-3">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-clock me-1"></i> Recent Transactions</h5>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Product</th>
                                        <th>Type</th>
                                        <th>Qty</th>
                                        <th>By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentTxns as $txn)
                                    <tr>
                                        <td style="white-space:nowrap;">{{ $txn->created_at->format('d M, h:i A') }}</td>
                                        <td style="font-weight:600;">{{ $txn->product->item_name ?? '-' }}</td>
                                        <td>
                                            <span class="{{ $txn->transaction_type === 'IN' ? 'badge-in' : 'badge-out' }}">
                                                {{ $txn->transaction_type }}
                                            </span>
                                        </td>
                                        <td>{{ $txn->quantity }}</td>
                                        <td>{{ $txn->performer->name ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" class="text-center text-muted py-3">No transactions yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-trophy me-1"></i> Top Products by Stock</h5>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th style="text-align:right;">Available</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topProducts as $tp)
                                    <tr>
                                        <td style="font-weight:600;">{{ $tp->product->item_name ?? '-' }}</td>
                                        <td style="text-align:right;font-weight:700;">{{ $tp->available_qty }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="2" class="text-center text-muted py-3">No stock yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
