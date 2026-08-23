@extends('layouts.adminLayout')

@section('css')
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="/assets/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root { --primary-blue: #4A90E2; --primary-light: #f5f7fa; --text-primary: #2d3436; --text-secondary: #636e72; --border-color: #e1e8ed; --card-bg: #ffffff; }
        body { background: var(--primary-light); color: var(--text-primary); }
        .page-header { background: #fff; padding: 1.5rem; margin-bottom: 1.5rem; border-radius: 8px; border: 1px solid var(--border-color); }
        .page-header h1 { font-weight: 600; margin: 0; font-size: 1.25rem; }
        .page-header p { color: var(--text-secondary); margin: 0.35rem 0 0 0; font-size: 0.9rem; }
        .card { border: 1px solid var(--border-color); border-radius: 8px; background: var(--card-bg); }
        .card-body { padding: 1.5rem; }
        .table thead th { background: #f8f9fa; font-weight: 600; font-size: 0.78rem; text-transform: uppercase; padding: 0.85rem; }
        .table tbody td { padding: 0.75rem 0.85rem; font-size: 0.85rem; vertical-align: middle; }
        .dt-button { background: var(--primary-blue) !important; border: 1px solid var(--primary-blue) !important; border-radius: 6px !important; padding: 0.45rem 0.8rem !important; font-weight: 600 !important; color: #fff !important; font-size: 0.8rem !important; }
        .badge-IN { background: #d3f9d8; color: #2b8a3e; padding: 0.25rem 0.6rem; border-radius: 12px; font-weight: 600; font-size: 0.72rem; }
        .badge-OUT { background: #ffe3e3; color: #c92a2a; padding: 0.25rem 0.6rem; border-radius: 12px; font-weight: 600; font-size: 0.72rem; }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <h1><i class="fas fa-exchange-alt me-2"></i>Transactions</h1>
        <p>{{ $warehouse->name }}</p>
    </div>

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="txnTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Txn ID</th>
                                <th>Product</th>
                                <th>Type</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Performed By</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($txns as $t)
                            <tr>
                                <td style="white-space:nowrap;">{{ $t->created_at->format('d M Y H:i') }}</td>
                                <td>{{ $t->txn_id }}</td>
                                <td style="font-weight:600;">{{ $t->product->item_name ?? '-' }}</td>
                                <td><span class="badge-{{ $t->transaction_type }}">{{ $t->transaction_type }}</span></td>
                                <td>{{ $t->quantity }}</td>
                                <td>{{ $t->unit_price ? 'Rs '.number_format($t->unit_price, 2) : '-' }}</td>
                                <td>{{ $t->performer->name ?? '-' }}</td>
                                <td>{{ $t->remarks ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
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
            $('#txnTable').DataTable({ pageLength: 25, order: [[0, 'desc']], dom: 'Bfrtip', buttons: ['copy', 'excel', 'csv'] });
        });
    </script>
@endsection
