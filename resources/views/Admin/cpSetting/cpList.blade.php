@extends('layouts.adminLayout')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('stable/css/datatableListCss.css') }}">
    <style>
        .dataTables_wrapper {
            margin-top: 1rem;
        }

        .dataTables_paginate {
            margin-top: 1.5rem;
            text-align: right;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }

        .dataTables_paginate .paginate_button {
            padding: 0.5rem 0.75rem !important;
            margin: 0 0.25rem !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 4px !important;
            background: var(--card-bg) !important;
            color: var(--text-primary) !important;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-block;
        }

        .dataTables_paginate .paginate_button:hover:not(.disabled) {
            background: var(--primary-blue) !important;
            color: white !important;
            border-color: var(--primary-blue) !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .dataTables_paginate .paginate_button.current {
            background: var(--primary-blue) !important;
            color: white !important;
            border-color: var(--primary-blue) !important;
            font-weight: 600;
        }

        .dataTables_paginate .paginate_button.current:hover {
            background: #3b7dc4 !important;
            border-color: #3b7dc4 !important;
        }

        .dataTables_paginate .paginate_button.disabled,
        .dataTables_paginate .paginate_button.disabled:hover {
            opacity: 0.5;
            cursor: not-allowed;
            background: var(--primary-light) !important;
        }

        .dataTables_info {
            padding: 1rem 0;
            color: var(--text-secondary);
            font-size: 0.9rem;
            float: left;
            margin-top: 1.5rem;
        }

        .dt-buttons {
            margin-bottom: 1rem;
            display: inline-flex;
            gap: 0.5rem;
        }

        .dt-buttons .btn {
            padding: 0.5rem 1rem !important;
            margin-right: 0 !important;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .dt-buttons .btn-success {
            background: #28a745 !important;
            color: white !important;
        }

        .dt-buttons .btn-success:hover {
            background: #218838 !important;
        }

        .dt-buttons .btn-info {
            background: #17a2b8 !important;
            color: white !important;
        }

        .dt-buttons .btn-info:hover {
            background: #138496 !important;
        }

        .dt-buttons .btn-warning {
            background: #ffc107 !important;
            color: #333 !important;
        }

        .dt-buttons .btn-warning:hover {
            background: #e0a800 !important;
        }
    </style>
@endsection

@section('content')
    <div style="background: var(--primary-light); min-height: 100vh; padding: 2rem 0;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
            <!-- Header -->
            <div style="margin-bottom: 1.5rem;">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="padding: 0.5rem; background: var(--primary-blue); border-radius: 8px;">
                            <i class="fas fa-handshake text-white" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin: 0;">
                                Channel Partners Management
                            </h1>
                            <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0; font-size: 0.9rem;">
                                Manage all channel partners and their information
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('addNewCp') }}"
                        style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: var(--primary-blue); color: white; border-radius: 6px; text-decoration: none; font-weight: 600; transition: background 0.2s;">
                        <i class="fas fa-plus"></i>
                        <span>Add New Partner</span>
                    </a>
                </div>
            </div>

            <!-- Main Card -->
            <div
                style="background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color); box-shadow: 0 1px 3px rgba(0,0,0,0.04); padding: 1.5rem;">
                <!-- Controls Section -->
                <div
                    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; gap: 1rem; flex-wrap: wrap;">
                    <div>
                        <label
                            style="font-size: 0.85rem; font-weight: 600; color: var(--text-primary); margin-right: 0.5rem;">
                            Show entries:
                        </label>
                        <select id="cpTable_length"
                            style="padding: 0.35rem 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; font-size: 0.85rem;">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div id="buttons_export"></div>
                    <div style="flex: 0 1 300px;">
                        <input type="text" id="cpTable_filter_input" placeholder="Search by name, email, city..."
                            style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.9rem;">
                    </div>
                </div>

                <!-- Table -->
                <div style="overflow-x: auto;">
                    <table id="cpTable" class="table table-striped table-hover" style="width:100%;">
                        <thead>
                            <tr style="background: var(--primary-light); border-bottom: 2px solid var(--border-color);">
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600; color: #fff;">Company Name
                                </th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600; color: #fff;">Contact
                                    Person</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600; color: #fff;">Email</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600; color: #fff;">Mobile</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600; color: #fff;">City</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600; color: #fff;">CP Type</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600; color: #fff;">Users</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600; color: #fff;">Balance</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600; color: #fff;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cp_list as $cp)
                                <tr style="border-bottom: 1px solid var(--border-color); transition: background 0.2s;">
                                    <td style="padding: 0.75rem; color: var(--text-primary); font-weight: 500;">{{ $cp->email }}
                                    </td>
                                    <td style="padding: 0.75rem; color: var(--text-secondary);">{{ $cp->contact_person }}</td>
                                    <td style="padding: 0.75rem; color: var(--text-secondary); font-size: 0.9rem;">
                                        {{ $cp->email }}</td>
                                    <td style="padding: 0.75rem; color: var(--text-secondary);">{{ $cp->phone_number }}</td>
                                    <td style="padding: 0.75rem; color: var(--text-secondary);">{{ $cp->city ?? 'N/A' }}</td>
                                    <td style="padding: 0.75rem;">
                                        <span
                                            style="background: #e3f2fd; color: var(--primary-blue); padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">
                                            {{ $cp->role ? $cp->role->role_name : 'N/A' }}
                                        </span>
                                    </td>
                                    <td style="padding: 0.75rem; text-align: center;">
                                        <span
                                            style="background: #f3e5f5; color: #6a1b9a; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">
                                            {{ $cp->associateUsers ? $cp->associateUsers->count() : 0 }}
                                        </span>
                                    </td>
                                    <td style="padding: 0.75rem; color: var(--text-secondary); font-size: 0.9rem;">
                                        {{ $cp->wallet ? number_format($cp->wallet->balance, 2) : '0.00' }}
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <div style="display: flex; gap: 0.35rem;">
                                            <a href="{{ route('edit_cp', $cp->id) }}" class="btn-action btn-edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <button type="button" class="btn-action btn-delete"
                                                onclick="deleteCp({{ $cp->id }})">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Info Card -->
            <div
                style="margin-top: 1.5rem; padding: 1rem; background: #e3f2fd; border: 1px solid #90caf9; border-radius: 8px;">
                <div style="display: flex; gap: 1rem;">
                    <i class="fas fa-info-circle"
                        style="color: var(--primary-blue); font-size: 1.2rem; flex-shrink: 0;"></i>
                    <div>
                        <h4 style="margin: 0 0 0.5rem 0; color: var(--text-primary); font-weight: 600;">Tips</h4>
                        <ul style="margin: 0; padding-left: 1.5rem; color: var(--text-secondary); font-size: 0.9rem;">
                            <li>Use search box to find partners by name, email, or city</li>
                            <li>Export data in Excel or CSV format</li>
                            <li>View associated users count for each partner</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.3/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.3/vfs_fonts.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function () {
            let table = $('#cpTable').DataTable({
                pageLength: 10,
                lengthChange: false,
                ordering: true,
                searching: true,
                paging: true,
                info: true,
                dom: 'rtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-sm btn-success me-2'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        className: 'btn btn-sm btn-info me-2'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'btn btn-sm btn-warning'
                    }
                ]
            });

            // Move export buttons to custom container
            table.buttons().container().appendTo('#buttons_export');

            // Custom search with input field
            $('#cpTable_filter_input').on('keyup', function () {
                table.search(this.value).draw();
            });

            // Page length control
            $('#cpTable_length').on('change', function () {
                table.page.len(parseInt(this.value)).draw();
            });
        });

        function deleteCp(id) {
            if (confirm('Are you sure you want to delete this channel partner?')) {
                $.ajax({
                    url: '/cp/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        alert('Channel Partner deleted successfully');
                        location.reload();
                    },
                    error: function () {
                        alert('Error deleting channel partner');
                    }
                });
            }
        }
    </script>
@endsection