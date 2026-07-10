@extends('layouts.adminLayout')

@section('css')
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="/assets/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #4A90E2;
            --primary-light: #f5f7fa;
            --text-primary: #2d3436;
            --text-secondary: #636e72;
            --border-color: #e1e8ed;
            --hover-bg: #f1f3f5;
            --card-bg: #ffffff;
        }

        body {
            background: var(--primary-light);
            color: var(--text-primary);
            font-size: 0.78rem; /* smaller global font */
            line-height: 1.3;
        }

        .page-header {
            padding: 0.7rem 0;
            margin-bottom: 0.75rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .page-header h1 {
            font-size: 0.95rem;
            color: var(--text-primary);
            font-weight: 600;
            margin: 0;
            font-size: 1.05rem; /* reduced */
        }

        .page-header p {
            color: var(--text-secondary);
            margin: 0.25rem 0 0 0;
            font-size: 0.8rem; /* reduced */
        }

        .card {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--card-bg);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .card-body {
            padding: 0.75rem; /* reduced */
        }

        .btn,
        .btn-sm,
        .btn-success,
        .btn-primary,
        .btn-secondary {
            font-size: 0.75rem !important;
            padding: 0.3rem 0.6rem !important;
            border-radius: 5px;
            border: 1px solid var(--border-color);
            color: #fff;
        }

        .btn-success,
        .btn-primary {
            background: var(--primary-blue);
            border: 1px solid var(--primary-blue);
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            box-shadow: none;
        }

        .btn-success:hover,
        .btn-primary:hover {
            background: #3b7dc4;
            border-color: #3b7dc4;
            color: #fff;
        }

        .btn-secondary {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            border: 1px solid var(--border-color);
            background: #fff;
            color: var(--text-primary);
        }

        .btn-secondary:hover {
            background: var(--hover-bg);
        }

        .btn-sm {
            padding: 0.4rem 0.75rem;
            font-size: 0.8rem;
        }

        .form-control,
        .form-select {
            font-size: 0.75rem;
            padding: 0.3rem 0.5rem;
            border-radius: 5px;
            border: 1px solid var(--border-color);
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.15);
            border-color: var(--primary-blue);
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
            font-size: 0.73rem;
        }

        .table thead th {
            background: #f8f9fa;
            color: var(--text-primary);
            font-weight: 600;
            border-bottom: 1px solid var(--border-color);
            padding: 0.45rem; /* reduced */
            font-size: 0.68rem; /* reduced */
            text-transform: uppercase;
        }

        .table tbody td {
            padding: 0.4rem; /* reduced */
            vertical-align: middle;
            border-color: var(--border-color);
            font-size: 0.72rem; /* reduced */
        }

        .table tbody tr:hover {
            background-color: var(--hover-bg);
        }

        .table-responsive {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .dt-button {
            font-size: 0.72rem !important;
            padding: 0.3rem 0.55rem !important;
            background: var(--primary-blue) !important;
            border: 1px solid var(--primary-blue) !important;
            border-radius: 6px !important;
            color: #fff !important;
            box-shadow: none !important;
        }

        .dt-button:hover {
            background: #3b7dc4 !important;
            border-color: #3b7dc4 !important;
        }

        .badge-yes {
            background: #e7f5ff;
            color: #1c7ed6;
            padding: 0.35rem 0.7rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .badge-no {
            background: #fff5f5;
            color: #c92a2a;
            padding: 0.35rem 0.7rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .text-muted-custom {
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        .modal-content.glass-modal {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .modal-header {
            background: #f8f9fa;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.25rem;
        }

        .modal-title {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 1rem;
        }

        .modal-footer {
            background: #f8f9fa;
            border-top: 1px solid var(--border-color);
            padding: 1rem 1.25rem;
        }

        .empty-state {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 2rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
            opacity: 0.6;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }

            .table {
                font-size: 0.85rem;
            }
        }
    </style>
@endsection
@section('content')
    <div class="page-header mb-3">
        <div class="container-fluid">
            <h1><i class="fas fa-warehouse me-2"></i>View Inventory Transactions</h1>
            <p>View Inventory transactions</p>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex gap-2 mb-3">
                    
                    <a href="{{ route('transferInventoryWarehouse') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-random me-1"></i> TRANSFER
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm" id="inventoryTable" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Transaction Type</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Invoice Number</th>
                                <th>Invoice Date</th>
                                <th>Channel Partner</th>
                                <th>Serial Number</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                                @forelse($txn_list as $index => $txn)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $txn->product->item_name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $txn->transaction_type === 'IN' ? '#1c7ed6' : '#c92a2a' }}; color: white;">
                                                {{ $txn->transaction_type }}
                                            </span>
                                        </td>
                                        <td>{{ $txn->quantity }}</td>
                                        <td>{{ number_format($txn->unit_price, 2) }}</td>
                                        <td>{{ $txn->invoice_number }}</td>
                                        <td>{{ $txn->invoice_date ? date('M d, Y', strtotime($txn->invoice_date)) : 'N/A' }}</td>
                                        <td>{{ $txn->transferToCp->cp_name ?? 'Admin' }}</td>
                                        <td>{{ $txn->serial->serial_number ?? 'N/A' }}</td>
                                        <td>{{ $txn->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center text-muted-custom">
                                        <i class="fas fa-inbox"></i> No inventory transactions found
                                    </td>
                                </tr>
                            @endforelse
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
    <script src="/assets/js/buttons.print.min.js"></script>
    <script>
        $(function () {
            $('#inventoryTable').DataTable({
                dom: 'Blfrtip', // added "l" to show page-size dropdown
                buttons: ['excel', 'csv', 'print'],
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, 'All']
                ],
                pageLength: 10,
                pagingType: 'full_numbers', // show numbered pages
                order: [[9, 'desc']]
            });
        });
    </script>
@endsection