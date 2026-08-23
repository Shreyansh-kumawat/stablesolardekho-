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
        .btn-primary { background: var(--primary-blue); border: 1px solid var(--primary-blue); color: #fff; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; }
        .table thead th { background: #f8f9fa; color: var(--text-primary); font-weight: 600; border-bottom: 1px solid var(--border-color); padding: 0.9rem; font-size: 0.8rem; text-transform: uppercase; }
        .table tbody td { padding: 0.85rem; vertical-align: middle; border-color: var(--border-color); }
        .table tbody tr:hover { background-color: var(--hover-bg); }
        .stat-card { background: #fff; border: 1px solid var(--border-color); border-radius: 8px; padding: 1rem 1.25rem; text-align: center; }
        .stat-card .stat-num { font-size: 1.5rem; font-weight: 700; color: var(--primary-blue); }
        .stat-card .stat-label { font-size: 0.78rem; color: var(--text-secondary); margin-top: 2px; }
        .dt-button { background: var(--primary-blue) !important; border: 1px solid var(--primary-blue) !important; border-radius: 6px !important; padding: 0.45rem 0.8rem !important; font-weight: 600 !important; color: #fff !important; font-size: 0.8rem !important; }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="fas fa-boxes me-2"></i>{{ $warehouse->name }} - Inventory</h1>
                <p>Stock available in this warehouse</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.warehouses.exportInventory', $warehouse->id) }}" class="btn btn-primary" style="background:#fff;color:var(--text-primary);border-color:var(--border-color);">
                    <i class="fas fa-download me-1"></i> Export CSV
                </a>
                <a href="{{ route('admin.warehouses.transfer') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-right me-1"></i> Transfer Stock
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-num">{{ $inventory->count() }}</div>
                    <div class="stat-label">Total Products</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-num">{{ $inventory->sum('available_qty') }}</div>
                    <div class="stat-label">Total Stock</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-num">{{ $inventory->sum('total_in') }}</div>
                    <div class="stat-label">Total IN</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-num">{{ $inventory->sum('total_out') }}</div>
                    <div class="stat-label">Total OUT</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="invTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Code</th>
                                <th>Category</th>
                                <th>UOM</th>
                                <th>IN</th>
                                <th>OUT</th>
                                <th>Available</th>
                                <th style="text-align:center;">Adjust</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventory as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td style="font-weight:600;">{{ $item->item_name }}</td>
                                <td><code>{{ $item->item_code }}</code></td>
                                <td>{{ $item->category_name ?? '-' }}</td>
                                <td>{{ $item->uom }}</td>
                                <td><span style="color:#2b8a3e;font-weight:600;">{{ $item->total_in }}</span></td>
                                <td><span style="color:#c92a2a;font-weight:600;">{{ $item->total_out }}</span></td>
                                <td><span class="qty-display" data-product="{{ $item->product_id }}" style="font-weight:700;font-size:0.95rem;">{{ $item->available_qty }}</span></td>
                                <td style="text-align:center;">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <button class="btn btn-sm btn-adjust" onclick="adjustStock({{ $item->product_id }}, -1)" title="Decrease by 1" style="width:28px;height:28px;padding:0;border:1px solid #c92a2a;color:#c92a2a;background:#fff;border-radius:4px;font-weight:700;font-size:0.85rem;line-height:1;">-</button>
                                        <button class="btn btn-sm btn-adjust" onclick="adjustStock({{ $item->product_id }}, 1)" title="Increase by 1" style="width:28px;height:28px;padding:0;border:1px solid #2b8a3e;color:#2b8a3e;background:#fff;border-radius:4px;font-weight:700;font-size:0.85rem;line-height:1;">+</button>
                                        <button class="btn btn-sm btn-adjust" onclick="adjustStockCustom({{ $item->product_id }}, '{{ addslashes($item->item_name) }}')" title="Custom adjustment" style="height:28px;padding:0 6px;border:1px solid var(--primary-blue);color:var(--primary-blue);background:#fff;border-radius:4px;font-weight:600;font-size:0.7rem;">+/-</button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">No inventory in this warehouse yet.</td>
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
            $('#invTable').DataTable({
                pageLength: 25,
                order: [],
                dom: 'Bfrtip',
                buttons: ['copy', 'excel', 'csv']
            });
        });

        function adjustStock(productId, qty) {
            if (!confirm('Adjust stock by ' + (qty > 0 ? '+' : '') + qty + '?')) return;
            sendAdjust(productId, qty, 'Quick adjustment by admin');
        }

        function adjustStockCustom(productId, productName) {
            var qty = prompt('Enter adjustment for "' + productName + '"\n(positive to add, negative to subtract):');
            if (qty === null || qty === '') return;
            qty = parseInt(qty);
            if (isNaN(qty) || qty === 0) { alert('Invalid quantity.'); return; }
            var remark = prompt('Remarks (optional):') || 'Custom adjustment by admin';
            sendAdjust(productId, qty, remark);
        }

        function sendAdjust(productId, qty, remarks) {
            $.ajax({
                url: '{{ route("admin.warehouses.adjustStock", $warehouse->id) }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    product_id: productId,
                    adjustment: qty,
                    remarks: remarks
                },
                success: function (res) {
                    if (res.success) {
                        $('[data-product="' + productId + '"]').text(res.new_qty);
                        alert('Stock updated to ' + res.new_qty);
                    } else {
                        alert(res.message || 'Error');
                    }
                },
                error: function (xhr) {
                    var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Something went wrong';
                    alert(msg);
                }
            });
        }
    </script>
@endsection
