@extends('layouts.adminLayout')
@section('css')
    <link rel="stylesheet" href="/assets/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="/assets/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('stable/css/datatableListCss.css') }}">
    <link rel="stylesheet" href="/assets/css/bootstrap-icons.min.css">

    <style>
        /* Export buttons */
         body {
        background: var(--primary-light);
        color: var(--text-primary);
        font-size: 0.78rem; /* smaller global font */
        line-height: 1.3;
    }
        .page-header {
        padding: 0.7rem 0;
        margin-bottom: 0.75rem;
    }

    .page-header h1 {
        font-size: 0.95rem;
    }

    .page-header p {
        font-size: 0.72rem;
    }

    .card-body {
        padding: 0.75rem;
    }
        #buttons_export .dt-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        #buttons_export .dt-button {
            border-radius: 0.375rem;
            padding: 0.35rem 0.75rem;
            font-size: 0.875rem;
        }

        /* Pagination */
        .dataTables_wrapper .dataTables_paginate {
            display: flex;
            justify-content: flex-end;
        }
        .dataTables_wrapper .dataTables_paginate .pagination {
            margin: 0;
            gap: 0.25rem;
        }
        .dataTables_wrapper .dataTables_paginate .page-item .page-link {
            border-radius: 0.375rem;
            padding: 0.35rem 0.65rem;
        }
        .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
            background-color: #2563eb;
            border-color: #2563eb;
        }
    </style>
@endsection
@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-600 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3.545a1.5 1.5 0 01-1.5-1.5V5.455c0-.82.67-1.5 1.5-1.5h15.91c.83 0 1.5.67 1.5 1.5v12.045M9 12h6m-3 3v3" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-slate-900">Current Purchase Pricing of all Products (Without GST or any other applicable charges)</h1>
                            <p class="text-sm text-slate-600">See the latest pricing details for all products available for purchase.(Subject to change)</p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-xl shadow-lg border border-slate-200">
                <div class="p-6">
                    <!-- Controls Section -->
                    <div class="controls-top">
                        {{-- <div>
                            <label class="text-sm font-medium text-slate-700">Show entries:</label>
                            <select id="userTable_length" class="rounded border-slate-300">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="-1">All</option>
                            </select>
                        </div> --}}
                        <div id="buttons_export" style="flex: 1;"></div>
                        {{-- <div style="flex: 1;">
                            <input type="text" id="userTable_filter_input" placeholder="Search users..."
                                class="w-full rounded border-slate-300">
                        </div> --}}
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table id="userTable" class="table table-striped table-hover dataTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Product Name</th>
                                    <th>Product Code</th>
                                    <th>UOM (Unit)</th>
                                    <th>Current Price(Basic)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $product->category->category_name ?? 'N/A' }}</td>
                                        <td>{{ $product->subCategory->sub_category_name ?? 'N/A' }}</td>
                                        <td>{{ $product->item_name }}</td>
                                        <td>{{ $product->item_code }}</td>
                                        <td>{{ $product->uom }}</td>
                                        <td>{{ $product->current_sale_price }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/js/jquery-3.7.0.min.js"></script>
    <script src="/assets/js/jquery.dataTables.min.js"></script>
    <script src="/assets/js/dataTables.bootstrap5.min.js"></script>
    <script src="/assets/js/dataTables.buttons.min.js"></script>
    <script src="/assets/js/buttons.bootstrap5.min.js"></script>
    <script src="/assets/js/jszip.min.js"></script>
    <script src="/assets/js/pdfmake.min.js"></script>
    <script src="/assets/js/vfs_fonts.min.js"></script>
    <script src="/assets/js/buttons.html5.min.js"></script>
    <script src="/assets/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function () {
            var table = $('#userTable').DataTable({
                dom: 'Blfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="bi bi-file-earmark-spreadsheet"></i> Export Excel',
                        className: 'btn btn-sm btn-success',
                        title: 'Pending Orders',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="bi bi-file-earmark-text"></i> Export CSV',
                        className: 'btn btn-sm btn-info',
                        title: 'Pending Orders',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="bi bi-printer"></i> Print',
                        className: 'btn btn-sm btn-warning',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ],
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                pageLength: 10,
                orderCellsTop: true,
                autoWidth: false,
                responsive: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search Products...",
                    lengthMenu: "Show _MENU_ entries"
                }
            });

            // Move buttons to custom div
            table.buttons().container().appendTo('#buttons_export');

            // Custom search functionality
            $('#userTable_filter_input').on('keyup', function () {
                table.search(this.value).draw();
            });

            // Handle length change
            $('#userTable_length').on('change', function () {
                table.page.len(this.value).draw();
            });
        });
    </script>
@endsection