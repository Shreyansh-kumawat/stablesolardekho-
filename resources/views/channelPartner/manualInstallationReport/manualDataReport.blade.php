@extends('layouts.adminLayout')
@section('css')
    <link rel="stylesheet" href="/assets/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="/assets/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('stable/css/datatableListCss.css') }}">
    <link rel="stylesheet" href="/assets/css/bootstrap-icons.min.css">

    <style>
        /* Export buttons */
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

        /* Compact table styling */
        .table-responsive {
            max-height: 70vh;
            overflow-y: auto;
        }

        .table thead th {
            position: sticky;
            top: 0;
            z-index: 10;
            padding: 0.5rem 0.3rem !important;
            font-size: 0.7rem !important;
            font-weight: 600;
            background: #f8f9fa;
        }

        .table tbody td {
            padding: 0.3rem 0.5rem !important;
            font-size: 0.75rem !important;
            white-space: nowrap;
        }

        /* Sticky first column */
        .table th:first-child,
        .table td:first-child {
            position: sticky;
            left: 0;
            background: #fff;
            z-index: 5;
        }

        .table thead th:first-child {
            background: #f8f9fa;
            z-index: 11;
        }

        /* Zebra striping for readability */
        .table tbody tr:nth-child(odd) {
            background-color: #fafbfc;
        }

        /* Reduce row height */
        .table tbody tr {
            height: 32px;
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
                            <h1 class="text-2xl font-bold text-slate-900">Manual Data Report</h1>
                            <p class="text-sm text-slate-600">Manage manual installation reports</p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-xl shadow-lg border border-slate-200">
                <div class="p-6">
                    <!-- Controls Section -->
                    <div class="controls-top">
                        <div id="buttons_export" style="flex: 1;"></div>

                    </div>

                    <!-- Table -->
<div class="table-container" style="max-height: 70vh; overflow-x: auto; overflow-y: auto; border: 1px solid #e1e8ed; border-radius: 8px;">
    <table id="userTable" class="table table-striped table-hover dataTable" style="width: max-content; margin-bottom: 0;">
                            <thead>
                                <tr>
                                    <th>Sno</th>
                                    <th>Customer</th>
                                    <th>Number</th>
                                    <th>DCR Panel</th>
                                    <th>Non DCR</th>
                                    <th>Inverter</th>
                                    <th>Meter</th>
                                    <th>Structure</th>
                                    <th>ACDB-BCDB</th>
                                    <th>Wire</th>
                                    <th>Nut</th>
                                    <th>Jhook</th>
                                    <th>Other 1</th>
                                    <th>Other 1 Count</th>
                                    <th>Other 2</th>
                                    <th>Other 2 Count</th>
                                    <th>Bill Number</th>
                                    <th>Bill Amount</th>
                                    <th>Gst</th>
                                    <th>Total Amount</th>
                                    <th>Installation Status</th>
                                    <th>Net Metering Status</th>
                                    <th>Payment1</th>
                                    <th>Payment2</th>
                                    <th>Payment3</th>
                                    <th>Stable Deposit</th>
                                    <th>Other Deposit</th>
                                    <th>Total Deposit</th>
                                    <th>Total Cost</th>
                                    <th>Pending Amount </th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($manualInstallations as $installation)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $installation->customer_name }}</td>
                                        <td>{{ $installation->customer_number }}</td>
                                        <td>{{ $installation->dcr_panel_count }}</td>
                                        <td>{{ $installation->non_dcr_panel_count }}</td>
                                        <td>{{ $installation->inverter_count }}</td>
                                        <td>{{ $installation->meter_count }}</td>
                                        <td>{{ $installation->structure_count }}</td>
                                        <td>{{ $installation->acdb_dcdb_count }}</td>
                                        <td>{{ $installation->wire_count }}</td>
                                        <td>{{ $installation->nut_count }}</td>
                                        <td>{{ $installation->jhook_count }}</td>
                                        <td>{{ $installation->other_1_name }}</td>
                                        <td>{{ $installation->other1_count }}</td>
                                        <td>{{ $installation->other_2_name }}</td>
                                        <td>{{ $installation->other2_count }}</td>
                                        <td>{{ $installation->bill_number }}</td>
                                        <td>{{ $installation->bill_amount }}</td>
                                        <td>{{ $installation->gst_amount }}</td>
                                        <td>{{ $installation->total_amount }}</td>
                                        <td>{{ $installation->installation_status == '1' ? 'Installed' : 'Not Installed' }}</td>
                                        <td>{{ $installation->net_metering_status == '1' ? 'Done' : 'Pending' }}</td>
                                        <td>{{ $installation->payment_1 }}</td>
                                        <td>{{ $installation->payment_2 }}</td>
                                        <td>{{ $installation->payment_3 }}</td>
                                        <td>{{ $installation->stable_deposit }}</td>
                                        <td>{{ $installation->other_deposit }}</td>
                                        <td>{{ $installation->total_deposit }}</td>
                                        <td>{{ $installation->total_cost }}</td>
                                        <td>{{ $installation->pending_amount }}</td>
                                        <td>Edit</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                    <div>
                        <h4 class="text-sm font-semibold text-blue-900">Tips</h4>
                        <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside mt-1">
                            <li>Use search box to quickly find orders by Order ID, Partner Name, or Date</li>
                            <li>Export order data in Excel or CSV format</li>
                            <li>You can view and check installations from the action menu</li>
                        </ul>
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
                    searchPlaceholder: "Search Orders...",
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