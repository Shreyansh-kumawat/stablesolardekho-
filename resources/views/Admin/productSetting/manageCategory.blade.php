@extends('layouts.adminLayout')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
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
    <!-- Page Header -->
    <div class="page-header">
        <div class="container-fluid">
            <h1><i class="fas fa-folder-open me-2"></i>Manage Categories</h1>
            <p>Organize and manage your product categories efficiently</p>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card shadow">
        <div class="card-body">
            <!-- Action Buttons -->
            <div class="btn-group-actions">
                <button type="button" class="btn btn-success" id="addNewCategoryBtn">
                    <i class="fas fa-plus me-2"></i> Add New Category
                </button>
            </div>

            <!-- Data Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="operatorTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 5%;">S.No.</th>
                            <th style="width: 20%;">Category Name</th>
                            <th style="width: 35%;">Description</th>
                            <th style="width: 20%;">Active Status</th>
                            <th style="width: 25%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($category_list->count())
                            @foreach ($category_list as $key => $category)
                                <tr>
                                    <td class="fw-bold">{{ $key + 1 }}</td>
                                    <td>
                                        <span class="fw-bold">{{ $category->category_name }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted-custom">{{ Str::limit($category->category_description, 50) }}</small>
                                    </td>
                                    <td>
                                        @if($category->active_status == 1)
                                            <span class="badge-yes">
                                                <i class="fas fa-check-circle me-1"></i>Active
                                            </span>
                                        @else
                                            <span class="badge-no">
                                                <i class="fas fa-times-circle me-1"></i>Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-primary btn-sm edit-user-btn"
                                                data-id="{{ $category->id }}" data-name="{{ $category->category_name }}"
                                                data-description="{{ $category->category_description }}" title="Edit Category">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addUnitModal" tabindex="-1" role="dialog" aria-labelledby="addUnitModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content glass-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUnitModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Add New Category
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addUnitForm" action="{{ route('saveNewCategory') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="categoryName" class="form-label">Category Name <span
                                    style="color: #e74c3c;">*</span></label>
                            <input type="text" class="form-control" id="categoryName" name="categoryName" required
                                placeholder="Enter category name" maxlength="100">
                            <small class="text-muted-custom">Maximum 100 characters</small>
                        </div>

                        <div class="form-group">
                            <label for="categoryDiscription" class="form-label">Description <span
                                    style="color: #e74c3c;">*</span></label>
                            <textarea class="form-control" id="categoryDiscription" name="categoryDiscription" rows="4"
                                required placeholder="Enter category description" maxlength="500"></textarea>
                            <small class="text-muted-custom">Maximum 500 characters</small>
                        </div>

                      
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check-circle me-2"></i>Add Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content glass-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">
                        <i class="fas fa-edit me-2"></i>Edit Category
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCategoryForm" method="POST" action="{{ route('updateCategory') }}">
                    @csrf
                    <input type="hidden" name="category_id" id="editCategoryId">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editCategoryName" class="form-label">Category Name <span
                                    style="color: #e74c3c;">*</span></label>
                            <input type="text" class="form-control" id="editCategoryName" name="categoryName" required
                                maxlength="100">
                            <small class="text-muted-custom">Maximum 100 characters</small>
                        </div>

                        <div class="form-group">
                            <label for="editCategoryDescription" class="form-label">Description <span
                                    style="color: #e74c3c;">*</span></label>
                            <textarea class="form-control" id="editCategoryDescription" name="categoryDiscription" rows="4"
                                required maxlength="500"></textarea>
                            <small class="text-muted-custom">Maximum 500 characters</small>
                        </div>

                      
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize DataTable with enhanced features
            const table = $('#operatorTable').DataTable({
                dom: '<"row mb-3"<"col-md-6"l><"col-md-6"f>>' +
                    '<"row"<"col-12"B>>' +
                    '<"row"<"col-12"tr>>' +
                    '<"row"<"col-md-6"i><"col-md-6"p>>',
                buttons: [
                    {
                        extend: 'copyHtml5',
                        className: 'btn btn-sm dt-button',
                        text: '<i class="fas fa-copy me-1"></i>Copy'
                    },
                    {
                        extend: 'excelHtml5',
                        className: 'btn btn-sm dt-button',
                        text: '<i class="fas fa-file-excel me-1"></i>Excel',
                        filename: 'Categories_' + new Date().toISOString().split('T')[0]
                    },
                    {
                        extend: 'csvHtml5',
                        className: 'btn btn-sm dt-button',
                        text: '<i class="fas fa-file-csv me-1"></i>CSV',
                        filename: 'Categories_' + new Date().toISOString().split('T')[0]
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-sm dt-button',
                        text: '<i class="fas fa-print me-1"></i>Print'
                    }
                ],
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, 'All']
                ],
                responsive: true,
                language: {
                    search: "Search categories:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ categories",
                    emptyTable: "No categories available"
                }
            });

            // Style DataTables elements
            $('div.dataTables_length select').addClass('form-control form-control-sm');
            $('div.dataTables_filter input').addClass('form-control form-control-sm');

            // Add New Category button - Opens modal only on click
            $('#addNewCategoryBtn').on('click', function () {
                // Reset form
                $('#addUnitForm')[0].reset();
                

                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('addUnitModal'));
                modal.show();
            });

            // Edit button click handler - Opens modal only on click
            $(document).on('click', '.edit-user-btn', function () {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const description = $(this).data('description');

                $('#editCategoryId').val(id);
                $('#editCategoryName').val(name);
                $('#editCategoryDescription').val(description);

                // Show modal with animation
                const modal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
                modal.show();
            });

            // Form validation
            $('#addUnitForm').on('submit', function (e) {
                const categoryName = $('#categoryName').val().trim();
                const categoryDesc = $('#categoryDiscription').val().trim();

                if (!categoryName || !categoryDesc) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                    return false;
                }
            });

            $('#editCategoryForm').on('submit', function (e) {
                const categoryName = $('#editCategoryName').val().trim();
                const categoryDesc = $('#editCategoryDescription').val().trim();

                if (!categoryName || !categoryDesc) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                    return false;
                }
            });

            // Prevent modal from opening on page load
            const addModal = new bootstrap.Modal(document.getElementById('addUnitModal'), {
                backdrop: 'static',
                keyboard: false
            });

            const editModal = new bootstrap.Modal(document.getElementById('editCategoryModal'), {
                backdrop: 'static',
                keyboard: false
            });
        });
    </script>
@endsection