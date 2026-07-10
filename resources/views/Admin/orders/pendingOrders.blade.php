@extends('layouts.adminLayout')
@section('css')
<link rel="stylesheet" href="/assets/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="/assets/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="{{ asset('stable/css/datatableListCss.css') }}">
<link rel="stylesheet" href="/assets/css/bootstrap-icons.min.css">
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-600 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3.545a1.5 1.5 0 01-1.5-1.5V5.455c0-.82.67-1.5 1.5-1.5h15.91c.83 0 1.5.67 1.5 1.5v12.045M9 12h6m-3 3v3"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">Pending Inventory Requests</h1>
                        <p class="text-sm text-slate-600">Review and approve/cancel inventory requests from channel partners</p>
                    </div>
                </div>
              
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200">
            <div class="p-6">
                <!-- Controls Section -->
                <div class="controls-top">
                    <div>
                        <label class="text-sm font-medium text-slate-700">Show entries:</label>
                        <select id="userTable_length" class="rounded border-slate-300">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="-1">All</option>
                        </select>
                    </div>
                    <div id="buttons_export" style="flex: 1;"></div>
                    {{-- <div style="flex: 1;">
                        <input type="text" id="userTable_filter_input" placeholder="Search users..." class="w-full rounded border-slate-300">
                    </div> --}}
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table id="userTable" class="table table-striped table-hover dataTable" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Request ID</th>
                                <th>Channel Partner</th>
                                <th>Request Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $order->order_id }}</td>
                                <td>{{ $order->channelPartner->cp_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('viewSingleOrder', ['id' => $order->id]) }}" class="btn btn-sm btn-primary-theme d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-eye"></i><span>Review</span>
                                        </a>
                                    </div>
                                </td>
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
                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                </svg>
                <div>
                    <h4 class="text-sm font-semibold text-blue-900">Tips</h4>
                    <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside mt-1">
                        <li>Use search box to find requests by Request ID, Partner Name, or Date</li>
                        <li>Export data in Excel or CSV format</li>
                        <li>Click Review to approve or cancel a request</li>
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
$(document).ready(function() {
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
    $('#userTable_filter_input').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Handle length change
    $('#userTable_length').on('change', function() {
        table.page.len(this.value).draw();
    });
});
</script>
@endsection
