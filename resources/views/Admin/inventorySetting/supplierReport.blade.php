@extends('layouts.adminLayout')

@section('css')
<style>
    :root {
        --primary-blue: #4A90E2;
        --border-color: #e1e8ed;
        --text-primary: #2d3436;
        --text-secondary: #636e72;
        --card-bg: #ffffff;
        --bg-soft: #f5f7fa;
    }
    body { background: var(--bg-soft); color: var(--text-primary); }
    .sr-header { background:#fff; padding:20px 24px; border-radius:8px; border:1px solid var(--border-color); margin-bottom:20px; display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap; }
    .sr-header h1 { font-size:1.3rem; font-weight:600; margin:0; }
    .sr-header p { color:var(--text-secondary); font-size:.9rem; margin:4px 0 0; }
    .btn-primary-sm { background: var(--primary-blue); color:#fff; padding: 7px 16px; border-radius:7px; text-decoration:none; font-size:.85rem; font-weight:600; }
    .btn-success-sm { background:#059669; color:#fff; padding:7px 16px; border-radius:7px; text-decoration:none; font-size:.85rem; font-weight:600; }

    .sr-card { background:#fff; border:1px solid var(--border-color); border-radius:10px; padding:22px; margin-bottom:16px; }
    .sr-card h5 { font-size:.95rem; font-weight:600; margin:0 0 14px; padding-bottom:10px; border-bottom:1px solid #f1f3f5; }

    .filter-row { display:flex; gap:10px; margin-bottom:16px; align-items:center; flex-wrap:wrap; }
    .filter-row input { padding: 8px 12px; border:1px solid var(--border-color); border-radius:7px; font-size:.86rem; }

    .supplier-grid { display:grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap:12px; }
    .supplier-card { background:#fff; border:1px solid var(--border-color); border-radius:10px; padding:16px; cursor:pointer; transition:all .15s; text-decoration:none; color:var(--text-primary); }
    .supplier-card:hover { border-color: var(--primary-blue); box-shadow: 0 6px 14px rgba(74,144,226,.15); color:var(--text-primary); }
    .supplier-card.active { border-color: var(--primary-blue); background: rgba(74,144,226,0.04); }
    .supplier-name { font-weight:700; font-size:.95rem; margin-bottom:8px; }
    .supplier-stats { display:flex; justify-content:space-between; font-size:.78rem; color:var(--text-secondary); }
    .supplier-stat strong { color:var(--primary-blue); font-size:.9rem; display:block; }

    .details-table { width:100%; font-size:.82rem; }
    .details-table th { text-align:left; background:#f9fafb; padding:10px 12px; font-weight:600; color:var(--text-secondary); border-bottom:2px solid var(--border-color); font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; }
    .details-table td { padding:10px 12px; border-bottom:1px solid #f3f4f6; }
    .details-table tbody tr:hover { background: #f9fafb; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="sr-header">
        <div>
            <h1><i class="fas fa-truck me-2"></i> Supplier Purchase Report</h1>
            <p>All products received from each supplier, with quantities and values.</p>
        </div>
        <a href="{{ route('inventorySupplierReportExport', array_merge(request()->all(), [])) }}" class="btn-success-sm">
            <i class="fas fa-file-excel me-1"></i> Download Excel (CSV)
        </a>
    </div>

    <form method="GET" action="{{ route('inventorySupplierReport') }}" class="sr-card" style="margin-bottom:16px;">
        <div class="filter-row">
            <label style="font-size:.82rem; font-weight:600; color:var(--text-secondary);">From:</label>
            <input type="date" name="from" value="{{ $from }}">
            <label style="font-size:.82rem; font-weight:600; color:var(--text-secondary);">To:</label>
            <input type="date" name="to" value="{{ $to }}">
            <input type="hidden" name="supplier" value="{{ $selectedSupplier }}">
            <button type="submit" class="btn-primary-sm" style="border:none;cursor:pointer;">Apply</button>
            @if($from || $to)
                <a href="{{ route('inventorySupplierReport') }}" style="color:#dc2626; font-size:.85rem;">Clear Filters</a>
            @endif
        </div>
    </form>

    <div class="sr-card">
        <h5>Suppliers ({{ $summary->count() }})</h5>
        @if($summary->count())
        <div class="supplier-grid">
            @foreach($summary as $s)
                <a href="{{ route('inventorySupplierReport', array_merge(request()->all(), ['supplier' => $s->supplier_name])) }}"
                   class="supplier-card {{ $selectedSupplier === $s->supplier_name ? 'active' : '' }}">
                    <div class="supplier-name">
                        <i class="fas fa-building me-1"></i> {{ $s->supplier_name }}
                    </div>
                    <div class="supplier-stats">
                        <div class="supplier-stat">
                            <strong>{{ $s->product_count }}</strong>
                            <span>Products</span>
                        </div>
                        <div class="supplier-stat">
                            <strong>{{ number_format($s->total_qty) }}</strong>
                            <span>Total Qty</span>
                        </div>
                        <div class="supplier-stat">
                            <strong>&#8377;{{ number_format($s->total_value, 0) }}</strong>
                            <span>Value</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        @else
        <p style="text-align:center; padding:30px; color:#94a3b8;">No supplier data yet. Add stock with supplier names to see them here.</p>
        @endif
    </div>

    @if($selectedSupplier)
    <div class="sr-card">
        <h5>
            <i class="fas fa-list me-1"></i>
            All purchases from "{{ $selectedSupplier }}" ({{ $details->count() }})
            <a href="{{ route('inventorySupplierReportExport', array_merge(request()->all(), [])) }}" class="btn-success-sm" style="float:right; text-decoration:none;">
                <i class="fas fa-file-excel me-1"></i> Export
            </a>
        </h5>
        @if($details->count())
        <div style="overflow-x:auto;">
            <table class="details-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>GST</th>
                        <th>Total</th>
                        <th>Invoice</th>
                        <th>Txn ID</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($details as $d)
                    <tr>
                        <td>{{ $d->created_at ? $d->created_at->format('d M Y') : '-' }}</td>
                        <td>
                            <strong>{{ $d->product->item_name ?? '-' }}</strong>
                            @if($d->product && $d->product->item_code)
                                <br><small style="color:#6b7280;">{{ $d->product->item_code }}</small>
                            @endif
                        </td>
                        <td>{{ $d->quantity }}</td>
                        <td>{{ $d->unit_price ? '₹' . number_format($d->unit_price, 2) : '-' }}</td>
                        <td>{{ $d->gst_percent ? $d->gst_percent . '%' : '-' }}</td>
                        <td><strong>{{ $d->total_with_gst ? '₹' . number_format($d->total_with_gst, 2) : '-' }}</strong></td>
                        <td>{{ $d->invoice_number ?? '-' }}</td>
                        <td style="font-family:monospace; font-size:.72rem;">{{ $d->txn_id ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p style="text-align:center; padding:30px; color:#94a3b8;">No details found.</p>
        @endif
    </div>
    @endif
</div>
@endsection
