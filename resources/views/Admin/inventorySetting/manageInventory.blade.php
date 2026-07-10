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
        }

        .page-header {
            background: #ffffff;
            padding: 1.5rem 0;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .page-header h1 {
            color: var(--text-primary);
            font-weight: 600;
            margin: 0;
            font-size: 1.25rem;
        }

        .page-header p {
            color: var(--text-secondary);
            margin: 0.35rem 0 0 0;
            font-size: 0.9rem;
        }

        .card {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--card-bg);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .card-body {
            padding: 1.5rem;
        }

        .btn-group-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            align-items: center;
            margin-bottom: 1.25rem;
            padding: 0.75rem;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
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
            border-radius: 6px;
            border: 1px solid var(--border-color);
            padding: 0.55rem 0.75rem;
            font-size: 0.9rem;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.15);
            border-color: var(--primary-blue);
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
        }

        .table thead th {
            background: #f8f9fa;
            color: var(--text-primary);
            font-weight: 600;
            border-bottom: 1px solid var(--border-color);
            padding: 0.9rem;
            font-size: 0.8rem;
            text-transform: uppercase;
        }

        .table tbody td {
            padding: 0.85rem;
            vertical-align: middle;
            border-color: var(--border-color);
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
            background: var(--primary-blue) !important;
            border: 1px solid var(--primary-blue) !important;
            border-radius: 6px !important;
            padding: 0.45rem 0.8rem !important;
            font-weight: 600 !important;
            color: #fff !important;
            font-size: 0.8rem !important;
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
            <h1><i class="fas fa-warehouse me-2"></i>Manage Inventory</h1>
            <p>View and manage current inventory stock</p>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex gap-2 mb-3">
                    <a href="{{ route('addNewInventory') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> ADD
                    </a>
                    <a href="{{ route('transferInventory') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-random me-1"></i> TRANSFER
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="inventoryTable" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item Name</th>
                                <th>Item Code</th>
                                <th>UOM</th>
                                <th>Category</th>
                                <th>Sub Category</th>
                                <th>Total In</th>
                                <th>Total Out</th>
                                <th>Current Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(($inventory_list ?? []) as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ data_get($item, 'item_name', '') }}</td>
                                    <td>{{ data_get($item, 'item_code', '') }}</td>
                                    <td>{{ data_get($item, 'uom', '') }}</td>
                                    <td>{{ data_get($item, 'category_name', '') }}</td>
                                    <td>{{ data_get($item, 'sub_category_name', '') }}</td>
                                    <td>{{ data_get($item, 'total_in', 0) }}</td>
                                    <td>{{ data_get($item, 'total_out', 0) }}</td>
                                    <td>{{ data_get($item, 'current_stock', 0) }}</td>
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
    <script src="/assets/js/buttons.print.min.js"></script>
    <script>
        $(function () {
            $('#inventoryTable').DataTable({
                dom: 'Bfrtip',
                buttons: ['excel', 'csv', 'print'],
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, 'All']
                ],
                pageLength: 10
            });
        });
    </script>
@endsection