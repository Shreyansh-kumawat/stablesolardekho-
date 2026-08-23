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
        .stat-card { background: #fff; border: 1px solid var(--border-color); border-radius: 8px; padding: 1.25rem; text-align: center; }
        .stat-card .stat-num { font-size: 1.4rem; font-weight: 700; }
        .stat-card .stat-label { font-size: 0.78rem; color: var(--text-secondary); margin-top: 2px; }
        .table thead th { background: #f8f9fa; color: var(--text-primary); font-weight: 600; border-bottom: 1px solid var(--border-color); padding: 0.85rem; font-size: 0.8rem; text-transform: uppercase; }
        .table tbody td { padding: 0.8rem 0.85rem; vertical-align: middle; border-color: var(--border-color); }
        .table tbody tr:hover { background-color: var(--hover-bg); }
        .filter-bar { background: #fff; border: 1px solid var(--border-color); border-radius: 8px; padding: 1rem 1.25rem; margin-bottom: 1rem; }
        .filter-bar label { font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; }
        .filter-bar .form-select, .filter-bar .form-control { font-size: 0.85rem; border-color: var(--border-color); }
        .btn-primary { background: var(--primary-blue); border: 1px solid var(--primary-blue); color: #fff; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; }
        .dt-button { background: var(--primary-blue) !important; border: 1px solid var(--primary-blue) !important; border-radius: 6px !important; padding: 0.45rem 0.8rem !important; font-weight: 600 !important; color: #fff !important; font-size: 0.8rem !important; }
        .profit { color: #2b8a3e; }
        .loss { color: #c92a2a; }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="container-fluid">
            <h1><i class="fas fa-chart-line me-2"></i>{{ $warehouse->name }} - Profit / Loss</h1>
            <p>Purchase vs Sales analysis for this warehouse</p>
        </div>
    </div>

    <div class="container-fluid">
        <div class="filter-bar">
            <form method="GET" action="{{ route('admin.warehouses.profitLoss', $warehouse->id) }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label>Period</label>
                    <select name="period" class="form-select" id="periodSelect">
                        <option value="all" {{ $period == 'all' ? 'selected' : '' }}>All Time</option>
                        <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ $period == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ $period == 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="year" {{ $period == 'year' ? 'selected' : '' }}>This Year</option>
                        <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                </div>
                <div class="col-md-3 custom-date" style="{{ $period == 'custom' ? '' : 'display:none;' }}">
                    <label>From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3 custom-date" style="{{ $period == 'custom' ? '' : 'display:none;' }}">
                    <label>To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i> Apply</button>
                </div>
            </form>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-num" style="color:#2b8a3e;">Rs {{ number_format($purchaseCost, 2) }}</div>
                    <div class="stat-label">Total Purchase Cost (IN)</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-num" style="color:var(--primary-blue);">Rs {{ number_format($salesRevenue, 2) }}</div>
                    <div class="stat-label">Total Sales Revenue (OUT)</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-num {{ $profitLoss >= 0 ? 'profit' : 'loss' }}">
                        Rs {{ number_format(abs($profitLoss), 2) }}
                        <small style="font-size:0.65rem;">{{ $profitLoss >= 0 ? 'PROFIT' : 'LOSS' }}</small>
                    </div>
                    <div class="stat-label">Net Profit / Loss</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-num" style="color:#e67700;">{{ $totalIn }} / {{ $totalOut }}</div>
                    <div class="stat-label">Qty IN / OUT</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 style="font-weight:600;font-size:0.95rem;margin-bottom:1rem;">Product-wise Breakdown</h5>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="plTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Code</th>
                                <th>Qty IN</th>
                                <th>Qty OUT</th>
                                <th>Purchase Value</th>
                                <th>Sales Value</th>
                                <th>P/L</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productWise as $i => $pw)
                            @php $pl = $pw->sales_value - $pw->purchase_value; @endphp
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td style="font-weight:600;">{{ $pw->item_name }}</td>
                                <td><code>{{ $pw->item_code }}</code></td>
                                <td>{{ $pw->qty_in }}</td>
                                <td>{{ $pw->qty_out }}</td>
                                <td>Rs {{ number_format($pw->purchase_value, 2) }}</td>
                                <td>Rs {{ number_format($pw->sales_value, 2) }}</td>
                                <td class="{{ $pl >= 0 ? 'profit' : 'loss' }}" style="font-weight:700;">
                                    {{ $pl >= 0 ? '+' : '-' }} Rs {{ number_format(abs($pl), 2) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No data for selected period.</td>
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
            $('#plTable').DataTable({
                pageLength: 25,
                order: [],
                dom: 'Bfrtip',
                buttons: ['copy', 'excel', 'csv']
            });

            $('#periodSelect').on('change', function () {
                if ($(this).val() === 'custom') {
                    $('.custom-date').show();
                } else {
                    $('.custom-date').hide();
                }
            });
        });
    </script>
@endsection
