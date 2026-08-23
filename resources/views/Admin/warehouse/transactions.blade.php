@extends('layouts.adminLayout')

@section('css')
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="/assets/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root { --primary-blue: #4A90E2; --primary-light: #f5f7fa; --text-primary: #2d3436; --text-secondary: #636e72; --border-color: #e1e8ed; --hover-bg: #f1f3f5; --card-bg: #ffffff; }
        body { background: var(--primary-light); color: var(--text-primary); }
        .page-header { background: #ffffff; padding: 1.5rem 0; margin-bottom: 1.5rem; border-radius: 8px; border: 1px solid var(--border-color); box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .page-header h1 { color: var(--text-primary); font-weight: 600; margin: 0; font-size: 1.25rem; }
        .page-header p { color: var(--text-secondary); margin: 0.35rem 0 0 0; font-size: 0.9rem; }
        .card { border: 1px solid var(--border-color); border-radius: 8px; background: var(--card-bg); box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .card-body { padding: 1.5rem; }
        .table thead th { background: #f8f9fa; color: var(--text-primary); font-weight: 600; border-bottom: 1px solid var(--border-color); padding: 0.9rem; font-size: 0.8rem; text-transform: uppercase; }
        .table tbody td { padding: 0.85rem; vertical-align: middle; border-color: var(--border-color); }
        .table tbody tr:hover { background-color: var(--hover-bg); }
        .badge-in { background: #d3f9d8; color: #2b8a3e; padding: 0.3rem 0.65rem; border-radius: 12px; font-weight: 600; font-size: 0.75rem; }
        .badge-out { background: #fff5f5; color: #c92a2a; padding: 0.3rem 0.65rem; border-radius: 12px; font-weight: 600; font-size: 0.75rem; }
        .dt-button { background: var(--primary-blue) !important; border: 1px solid var(--primary-blue) !important; border-radius: 6px !important; padding: 0.45rem 0.8rem !important; font-weight: 600 !important; color: #fff !important; font-size: 0.8rem !important; }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="fas fa-exchange-alt me-2"></i>{{ $warehouse->name }} - Transactions</h1>
                <p>All inventory transactions for this warehouse</p>
            </div>
            <a href="{{ route('admin.warehouses.exportTransactions', array_merge(['id' => $warehouse->id], request()->only('from_date', 'to_date'))) }}" class="btn btn-primary" style="background:var(--primary-blue);border-color:var(--primary-blue);padding:0.5rem 1rem;border-radius:6px;font-weight:600;font-size:0.85rem;">
                <i class="fas fa-download me-1"></i> Export CSV
            </a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card mb-3">
            <div class="card-body" style="padding:1rem 1.25rem;">
                <form method="GET" action="{{ route('admin.warehouses.transactions', $warehouse->id) }}" class="row g-2 align-items-end">
                    <div class="col-md-2">
                        <label style="font-size:0.78rem;font-weight:600;color:var(--text-secondary);text-transform:uppercase;">Type</label>
                        <select name="type" class="form-select" style="font-size:0.85rem;">
                            <option value="">All</option>
                            <option value="IN" {{ request('type') == 'IN' ? 'selected' : '' }}>IN</option>
                            <option value="OUT" {{ request('type') == 'OUT' ? 'selected' : '' }}>OUT</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label style="font-size:0.78rem;font-weight:600;color:var(--text-secondary);text-transform:uppercase;">Product</label>
                        <select name="product_id" class="form-select" style="font-size:0.85rem;">
                            <option value="">All Products</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>{{ $p->item_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label style="font-size:0.78rem;font-weight:600;color:var(--text-secondary);text-transform:uppercase;">From Date</label>
                        <input type="date" name="from_date" class="form-control" style="font-size:0.85rem;" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label style="font-size:0.78rem;font-weight:600;color:var(--text-secondary);text-transform:uppercase;">To Date</label>
                        <input type="date" name="to_date" class="form-control" style="font-size:0.85rem;" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100" style="background:var(--primary-blue);border-color:var(--primary-blue);padding:0.5rem;font-size:0.85rem;font-weight:600;border-radius:6px;">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('admin.warehouses.transactions', $warehouse->id) }}" class="btn btn-outline-secondary w-100" style="padding:0.5rem;font-size:0.85rem;border-radius:6px;">Clear</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="txnTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Product</th>
                                <th>Type</th>
                                <th>Qty</th>
                                <th>Transfer</th>
                                <th>Unit Price</th>
                                <th>Invoice</th>
                                <th>Serial</th>
                                <th>TXN ID</th>
                                <th>By</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $i => $txn)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td style="white-space:nowrap;">{{ $txn->created_at->format('d M Y, h:i A') }}</td>
                                <td style="font-weight:600;">{{ $txn->product->item_name ?? '-' }}</td>
                                <td>
                                    <span class="{{ $txn->transaction_type === 'IN' ? 'badge-in' : 'badge-out' }}">
                                        {{ $txn->transaction_type }}
                                    </span>
                                </td>
                                <td>{{ $txn->quantity }}</td>
                                <td>{{ $txn->transfer_type ?? '-' }}</td>
                                <td>{{ $txn->unit_price ? number_format($txn->unit_price, 2) : '-' }}</td>
                                <td>{{ $txn->invoice_number ?? '-' }}</td>
                                <td><code>{{ $txn->serial->serial_number ?? '-' }}</code></td>
                                <td><small>{{ $txn->txn_id ?? '-' }}</small></td>
                                <td>{{ $txn->performer->name ?? '-' }}</td>
                                <td><small>{{ $txn->remarks ?? '-' }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="text-center text-muted py-4">No transactions yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('admin.warehouses.dashboard', $warehouse->id) }}" style="padding:0.5rem 1rem;border-radius:6px;font-weight:600;font-size:0.85rem;border:1px solid #e1e8ed;background:#fff;color:#2d3436;text-decoration:none;">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/js/jquery.dataTables.min.js"></script>
    <script src="/assets/js/dataTables.bootstrap5.min.js"></script>
    <script src="/assets/js/dataTables.buttons.min.js"></script>
    <script src="/assets/js/buttons.bootstrap5.min.js"></script>
    <script src="/assets/js/jszip.min.js"></script>
    <script src="/assets/js/buttons.html5.min.js"></script>
    <script>
        $(function () {
            $('#txnTable').DataTable({
                pageLength: 25,
                order: [[1, 'desc']],
                dom: 'Bfrtip',
                buttons: ['copy', 'excel', 'csv']
            });
        });
    </script>
@endsection
