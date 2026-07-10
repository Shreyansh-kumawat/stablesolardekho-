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
    <!-- Page Header -->
    <div class="page-header">
        <div class="container-fluid">
            <h1><i class="fas fa-sitemap me-2"></i>Manage Sub-Categories</h1>
            <p>Organize and manage your product sub-categories efficiently</p>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card shadow">
        <div class="card-body">
            <!-- Action Buttons -->
            <div class="btn-group-actions">
                <button type="button" class="btn btn-success" id="addNewSubCategoryBtn">
                    <i class="fas fa-plus me-2"></i> Add New Sub-Category
                </button>
            </div>

            <!-- Data Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="operatorTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 5%;">S.No.</th>
                            <th style="width: 20%;">Sub-Category Name</th>
                            <th style="width: 25%;">Category Name</th>
                            <th style="width: 30%;">Description</th>
                            <th style="width: 20%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sub_category_list as $key => $subCategory)
                            <tr>
                                <td class="fw-bold">{{ $key + 1 }}</td>
                                <td>
                                    <span class="fw-bold">{{ $subCategory->sub_category_name }}</span>
                                </td>
                                <td>
                                    <small class="text-muted-custom">{{ $subCategory->category->category_name ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <small class="text-muted-custom">{{ Str::limit($subCategory->remarks, 50) }}</small>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-primary btn-sm edit-user-btn"
                                            data-id="{{ $subCategory->id }}"
                                            data-category-id="{{ $subCategory->category_id }}"
                                            data-name="{{ $subCategory->sub_category_name }}"
                                            data-description="{{ $subCategory->remarks }}"
                                            title="Edit Sub-Category">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="fw-bold">-</td>
                                <td>
                                    <span class="text-muted-custom">No sub-categories found.</span>
                                </td>
                                <td class="text-muted-custom">-</td>
                                <td class="text-muted-custom">-</td>
                                <td class="text-muted-custom">-</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Sub-Category Modal -->
    <div class="modal fade" id="addSubCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addSubCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content glass-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubCategoryModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Add New Sub-Category
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addSubCategoryForm" action="{{ route('saveNewSubCategory') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="categoryId" class="form-label">Select Category <span style="color: #e74c3c;">*</span></label>
                            <select class="form-select" id="categoryId" name="category_id" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted-custom">Select a category for this sub-category</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="subCategoryName" class="form-label">Sub-Category Name <span style="color: #e74c3c;">*</span></label>
                            <input type="text" class="form-control" id="subCategoryName" name="sub_category_name" required
                                placeholder="Enter sub-category name" maxlength="100">
                            <small class="text-muted-custom">Maximum 100 characters</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="subCategoryDescription" class="form-label">Description <span style="color: #e74c3c;">*</span></label>
                            <textarea class="form-control" id="subCategoryDescription" name="sub_category_description" rows="4"
                                required placeholder="Enter sub-category description" maxlength="500"></textarea>
                            <small class="text-muted-custom">Maximum 500 characters</small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check-circle me-2"></i>Add Sub-Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Sub-Category Modal -->
    <div class="modal fade" id="editSubCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editSubCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content glass-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSubCategoryModalLabel">
                        <i class="fas fa-edit me-2"></i>Edit Sub-Category
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editSubCategoryForm" method="POST" action="{{ route('updateSubCategory') }}">
                    @csrf
                    <input type="hidden" name="sub_category_id" id="editSubCategoryId">

                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="editCategoryId" class="form-label">Select Category <span style="color: #e74c3c;">*</span></label>
                            <select class="form-select" id="editCategoryId" name="category_id" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted-custom">Select a category for this sub-category</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="editSubCategoryName" class="form-label">Sub-Category Name <span style="color: #e74c3c;">*</span></label>
                            <input type="text" class="form-control" id="editSubCategoryName" name="sub_category_name" required maxlength="100">
                            <small class="text-muted-custom">Maximum 100 characters</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="editSubCategoryDescription" class="form-label">Description <span style="color: #e74c3c;">*</span></label>
                            <textarea class="form-control" id="editSubCategoryDescription" name="sub_category_description" rows="4"
                                required maxlength="500"></textarea>
                            <small class="text-muted-custom">Maximum 500 characters</small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Sub-Category
                        </button>
                    </div>
                </form>
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
        $(document).ready(function () {
            // Safely re-init DataTable
            if ($.fn.DataTable.isDataTable('#operatorTable')) {
                $('#operatorTable').DataTable().clear().destroy();
            }

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
                        filename: 'SubCategories_' + new Date().toISOString().split('T')[0]
                    },
                    {
                        extend: 'csvHtml5',
                        className: 'btn btn-sm dt-button',
                        text: '<i class="fas fa-file-csv me-1"></i>CSV',
                        filename: 'SubCategories_' + new Date().toISOString().split('T')[0]
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-sm dt-button',
                        text: '<i class="fas fa-print me-1"></i>Print'
                    }
                ],
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                responsive: true,
                language: {
                    search: "Search sub-categories:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ sub-categories",
                    emptyTable: "No sub-categories available"
                }
            });

            $('div.dataTables_length select').addClass('form-control form-control-sm');
            $('div.dataTables_filter input').addClass('form-control form-control-sm');

            // Add New Sub-Category
            $('#addNewSubCategoryBtn').on('click', function () {
                $('#addSubCategoryForm')[0].reset();
                const modal = new bootstrap.Modal(document.getElementById('addSubCategoryModal'));
                modal.show();
            });

            // Edit Sub-Category
            $(document).on('click', '.edit-user-btn', function () {
                const id = $(this).data('id');
                const categoryId = $(this).data('category-id');
                const name = $(this).data('name');
                const description = $(this).data('description');

                $('#editSubCategoryId').val(id);
                $('#editCategoryId').val(categoryId);
                $('#editSubCategoryName').val(name);
                $('#editSubCategoryDescription').val(description);

                const modal = new bootstrap.Modal(document.getElementById('editSubCategoryModal'));
                modal.show();
            });

            // Form validation - Add
            $('#addSubCategoryForm').on('submit', function (e) {
                const categoryId = $('#categoryId').val().trim();
                const subCategoryName = $('#subCategoryName').val().trim();
                const description = $('#subCategoryDescription').val().trim();

                if (!categoryId || !subCategoryName || !description) {
                    e.preventDefault();
                    showNotification('error', 'Validation Error', 'Please fill in all required fields.');
                    return false;
                }
            });

            // Form validation - Edit
            $('#editSubCategoryForm').on('submit', function (e) {
                const categoryId = $('#editCategoryId').val().trim();
                const subCategoryName = $('#editSubCategoryName').val().trim();
                const description = $('#editSubCategoryDescription').val().trim();

                if (!categoryId || !subCategoryName || !description) {
                    e.preventDefault();
                    showNotification('error', 'Validation Error', 'Please fill in all required fields.');
                    return false;
                }
            });
        });
    </script>
@endsection