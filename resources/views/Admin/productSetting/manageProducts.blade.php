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

        .form-check-input:checked {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
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
            <h1><i class="fas fa-box me-2"></i>Manage Products</h1>
            <p>Organize and manage your products efficiently</p>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card shadow">
        <div class="card-body">
            <!-- Action Buttons -->
            <div class="btn-group-actions">
                <button type="button" class="btn btn-success" id="addNewProductBtn">
                    <i class="fas fa-plus me-2"></i> Add New Product
                </button>
            </div>

            <!-- Data Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="operatorTable" width="100%" cellspacing="0">
                    <thead>
                       <tr>
                            <th style="width: 5%;">S.No.</th>
                            <th style="width: 15%;">Category</th>
                            <th style="width: 15%;">Sub-Category</th>
                            <th style="width: 20%;">Product Name</th>
                            <th style="width: 10%;">Product Code</th>
                            <th style="width: 5%;">UOM</th>
                            <th style="width: 10%;">Serial Required</th>
                            <th style="width: 20%;">Actions</th>
                        </tr>
                    </thead>
                     <tbody>
                        @forelse ($product_list as $key => $product)
                            <tr>
                                <td class="fw-bold">{{ $key + 1 }}</td>
                                <td>
                                    <span class="fw-bold">{{ $product->category->category_name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <small class="text-muted-custom">{{ $product->subCategory->sub_category_name ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <small class="text-muted-custom">{{ $product->item_name }}</small>
                                </td>
                                <td>
                                    <small class="text-muted-custom">{{ $product->item_code ?? 'N/A' }}</small>
                                </td>
                                 <td>
                                    <small class="text-muted-custom">{{ $product->uom ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    @if($product->is_serialNumber_required)
                                        <span class="badge-yes">
                                            <i class="fas fa-check-circle me-1"></i>Yes
                                        </span>
                                    @else
                                        <span class="badge-no">
                                            <i class="fas fa-times-circle me-1"></i>No
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-primary btn-sm edit-product-btn"
                                            data-id="{{ $product->id }}"
                                            data-category-id="{{ $product->category_id }}"
                                            data-uom="{{ $product->uom }}"
                                            data-sub-category-id="{{ $product->sub_category_id }}"
                                            data-name="{{ $product->item_name }}"
                                            data-code="{{ $product->item_code }}"
                                            data-serial="{{ $product->is_serialNumber_required }}"
                                            title="Edit Product">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>
                                    </div>
                                </td>
                            </tr>
                      @empty
                            <tr>
                                <td class="fw-bold">-</td>
                                <td class="text-muted-custom">-</td>
                                <td class="text-muted-custom">-</td>
                                <td class="text-muted-custom">
                                    <span class="text-muted">No products found. Click "Add New Product" to get started.</span>
                                </td>
                                <td class="text-muted-custom">-</td>
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

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content glass-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Add New Product
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addProductForm" action="{{ route('saveNewProduct') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="addCategoryId" class="form-label">Select Category <span style="color: #e74c3c;">*</span></label>
                            <select class="form-select" id="addCategoryId" name="category_id" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted-custom">Select a category first</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="addSubCategoryId" class="form-label">Select Sub-Category <span style="color: #e74c3c;">*</span></label>
                            <select class="form-select" id="addSubCategoryId" name="sub_category_id" required disabled>
                                <option value="">-- Select Sub-Category --</option>
                            </select>
                            <small class="text-muted-custom">Select category to load sub-categories</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="productName" class="form-label">Product Name <span style="color: #e74c3c;">*</span></label>
                            <input type="text" class="form-control" id="productName" name="product_name" required
                                placeholder="Enter product name" maxlength="100">
                            <small class="text-muted-custom">Maximum 100 characters</small>
                        </div>

                        
                        <div class="form-group mb-3">
                            <label for="productCode" class="form-label">Product Code <span style="color: #e74c3c;">*</span></label>
                            <input type="text" class="form-control" id="productCode" name="item_code" required
                                placeholder="Enter product code" maxlength="50">
                            <small class="text-muted-custom">Unique product identifier</small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="uom" class="form-label">Unit of Measure (UOM) <span style="color: #e74c3c;">*</span></label>
                            <select class="form-select" id="uom" name="uom" required>
                                <option value="">-- Select UOM --</option>
                                <option value="Piece">Piece</option>
                                <option value="Kilogram">Kilogram</option>
                                <option value="Liter">Liter</option>
                                <option value="Meter">Meter</option>
                                <option value="Box">Box</option>
                                <option value="Pack">Pack</option>
                            </select>
                            <small class="text-muted-custom">Specify the unit of measure for the product</small>
                        </div>

                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="isSerialRequired" name="is_serialNumber_required" value="1">
                                <label class="form-check-label" for="isSerialRequired">
                                    Is Serial Number Required?
                                </label>
                            </div>
                            <small class="text-muted-custom">Check if this product requires serial number tracking</small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check-circle me-2"></i>Add Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content glass-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">
                        <i class="fas fa-edit me-2"></i>Edit Product
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editProductForm" method="POST" action="{{ route('updateProduct') }}">
                    @csrf
                    <input type="hidden" name="product_id" id="editProductId">

                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="editCategoryId" class="form-label">Select Category <span style="color: #e74c3c;">*</span></label>
                            <select class="form-select" id="editCategoryId" name="category_id" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted-custom">Select a category first</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="editSubCategoryId" class="form-label">Select Sub-Category <span style="color: #e74c3c;">*</span></label>
                            <select class="form-select" id="editSubCategoryId" name="sub_category_id" required>
                                <option value="">-- Select Sub-Category --</option>
                            </select>
                            <small class="text-muted-custom">Select category to load sub-categories</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="editProductName" class="form-label">Product Name <span style="color: #e74c3c;">*</span></label>
                            <input type="text" class="form-control" id="editProductName" name="product_name" required maxlength="100">
                            <small class="text-muted-custom">Maximum 100 characters</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="editProductCode" class="form-label">Product Code <span style="color: #e74c3c;">*</span></label>
                            <input type="text" class="form-control" id="editProductCode" name="item_code" required maxlength="50">
                            <small class="text-muted-custom">Unique product identifier</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="editProductUOM" class="form-label">UOM <span style="color: #e74c3c;">*</span></label>
                            <select class="form-select" id="editProductUOM" name="uom" required>
                                <option value="">-- Select UOM --</option>
                                <option value="Piece">Piece</option>
                                <option value="Kilogram">Kilogram</option>
                                <option value="Liter">Liter</option>
                                <option value="Meter">Meter</option>
                                <option value="Box">Box</option>
                                <option value="Pack">Pack</option>
                            </select>
                            <small class="text-muted-custom">Specify the unit of measure for the product</small>
                        </div>


                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="editIsSerialRequired" name="is_serial_required" value="1">
                                <label class="form-check-label" for="editIsSerialRequired">
                                    Is Serial Number Required?
                                </label>
                            </div>
                            <small class="text-muted-custom">Check if this product requires serial number tracking</small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Product
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
            // Initialize DataTable
            const table = $('#operatorTable').DataTable({
                dom: '<"row mb-3"<"col-md-6"l><"col-md-6"f>>' +
                    '<"row"<"col-12"B>>' +
                    '<"row"<"col-12"tr>>' +
                    '<"row"<"col-md-6"i><"col-md-6"p>>',
                buttons: [
                    { extend: 'copyHtml5', className: 'btn btn-sm dt-button', text: '<i class="fas fa-copy me-1"></i>Copy' },
                    { extend: 'excelHtml5', className: 'btn btn-sm dt-button', text: '<i class="fas fa-file-excel me-1"></i>Excel', filename: 'Products_' + new Date().toISOString().split('T')[0] },
                    { extend: 'csvHtml5', className: 'btn btn-sm dt-button', text: '<i class="fas fa-file-csv me-1"></i>CSV', filename: 'Products_' + new Date().toISOString().split('T')[0] },
                    { extend: 'print', className: 'btn btn-sm dt-button', text: '<i class="fas fa-print me-1"></i>Print' }
                ],
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                responsive: true,
                language: {
                    search: "Search products:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ products",
                    emptyTable: "No products available"
                }
            });

            $('div.dataTables_length select').addClass('form-control form-control-sm');
            $('div.dataTables_filter input').addClass('form-control form-control-sm');

            // Load Sub-Categories on Category Change (Add Modal)
            $('#addCategoryId').on('change', function() {
                const categoryId = $(this).val();
                const subCategorySelect = $('#addSubCategoryId');
                
                subCategorySelect.html('<option value="">-- Loading... --</option>').prop('disabled', true);

                if (categoryId) {
                    $.ajax({
                        url: '{{ route("getSubCategory") }}',
                        type: 'GET',
                        data: { category_id: categoryId },
                        success: function(response) {
                            subCategorySelect.html('<option value="">-- Select Sub-Category --</option>');
                            if (response.length > 0) {
                                response.forEach(function(subCat) {
                                    subCategorySelect.append(`<option value="${subCat.id}">${subCat.sub_category_name}</option>`);
                                });
                                subCategorySelect.prop('disabled', false);
                            } else {
                                subCategorySelect.html('<option value="">No sub-categories available</option>');
                            }
                        },
                        error: function() {
                            subCategorySelect.html('<option value="">Error loading sub-categories</option>');
                        }
                    });
                } else {
                    subCategorySelect.html('<option value="">-- Select Sub-Category --</option>').prop('disabled', true);
                }
            });

            // Load Sub-Categories on Category Change (Edit Modal)
            $('#editCategoryId').on('change', function() {
                const categoryId = $(this).val();
                const subCategorySelect = $('#editSubCategoryId');
                
                subCategorySelect.html('<option value="">-- Loading... --</option>').prop('disabled', true);

                if (categoryId) {
                    $.ajax({
                        url: '{{ route("getSubCategory") }}',
                        type: 'GET',
                        data: { category_id: categoryId },
                        success: function(response) {
                            subCategorySelect.html('<option value="">-- Select Sub-Category --</option>');
                            if (response.length > 0) {
                                response.forEach(function(subCat) {
                                    subCategorySelect.append(`<option value="${subCat.id}">${subCat.sub_category_name}</option>`);
                                });
                                subCategorySelect.prop('disabled', false);
                            } else {
                                subCategorySelect.html('<option value="">No sub-categories available</option>');
                            }
                        },
                        error: function() {
                            subCategorySelect.html('<option value="">Error loading sub-categories</option>');
                        }
                    });
                } else {
                    subCategorySelect.html('<option value="">-- Select Sub-Category --</option>').prop('disabled', true);
                }
            });

            // Add New Product
            $('#addNewProductBtn').on('click', function () {
                $('#addProductForm')[0].reset();
                $('#addSubCategoryId').html('<option value="">-- Select Sub-Category --</option>').prop('disabled', true);
                const modal = new bootstrap.Modal(document.getElementById('addProductModal'));
                modal.show();
            });

            // Edit Product
            $(document).on('click', '.edit-product-btn', function () {
                const id = $(this).data('id');
                const categoryId = $(this).data('category-id');
                const subCategoryId = $(this).data('sub-category-id');
                const name = $(this).data('name');
                const code = $(this).data('code');
                const isSerial = $(this).data('serial');

                $('#editProductId').val(id);
                $('#editCategoryId').val(categoryId);
                $('#editProductName').val(name);
                 $('#editProductUOM').val($(this).data('uom'));
                $('#editProductCode').val(code);
                $('#editIsSerialRequired').prop('checked', isSerial == 1);

                // Load sub-categories for selected category
                $.ajax({
                    url: '{{ route("getSubCategory") }}',
                    type: 'GET',
                    data: { category_id: categoryId },
                    success: function(response) {
                        const subCategorySelect = $('#editSubCategoryId');
                        subCategorySelect.html('<option value="">-- Select Sub-Category --</option>');
                        
                        if (response.length > 0) {
                            response.forEach(function(subCat) {
                                const selected = subCat.id == subCategoryId ? 'selected' : '';
                                subCategorySelect.append(`<option value="${subCat.id}" ${selected}>${subCat.sub_category_name}</option>`);
                            });
                            subCategorySelect.prop('disabled', false);
                        }

                        const modal = new bootstrap.Modal(document.getElementById('editProductModal'));
                        modal.show();
                    }
                });
            });

            // Form validation - Add
            $('#addProductForm').on('submit', function (e) {
                const categoryId = $('#addCategoryId').val();
                const subCategoryId = $('#addSubCategoryId').val();
                const productName = $('#productName').val().trim();
                const productCode = $('#productCode').val().trim();

                if (!categoryId || !subCategoryId || !productName || !productCode) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                    return false;
                }
            });

            // Form validation - Edit
            $('#editProductForm').on('submit', function (e) {
                const categoryId = $('#editCategoryId').val();
                const subCategoryId = $('#editSubCategoryId').val();
                const productName = $('#editProductName').val().trim();
                const productCode = $('#editProductCode').val().trim();

                if (!categoryId || !subCategoryId || !productName || !productCode) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                    return false;
                }
            });
        });
    </script>
@endsection