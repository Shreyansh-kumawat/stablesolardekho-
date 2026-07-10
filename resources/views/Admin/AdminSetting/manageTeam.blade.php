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
            <h1><i class="fas fa-box me-2"></i>Manage Team</h1>
            <p>Organize and manage your team efficiently</p>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card shadow">
        <div class="card-body">
            <!-- Action Buttons -->
            <div class="btn-group-actions">
                <button type="button" class="btn btn-success" id="addNewTeamMemberBtn">
                    <i class="fas fa-plus me-2"></i> Add New Team Member
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="operatorTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 5%;">S.No.</th>
                            <th style="width: 15%;">Name</th>
                            <th style="width: 12%;">Mobile Number</th>
                            <th style="width: 20%;">Address</th>
                            <th style="width: 10%;">District</th>
                            <th style="width: 5%;">State</th>
                            <th style="width: 10%;">Profile Photo</th>
                            <th style="width: 8%;">Active Status</th>
                            <th style="width: 17%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($solarTeam as $key => $teamMember)
                            <tr>
                                <td class="fw-bold">{{ $key + 1 }}</td>
                                <td><span class="fw-bold">{{ $teamMember->name ?? 'N/A' }}</span></td>
                                <td><small class="text-muted-custom">{{ $teamMember->mobile_number ?? 'N/A' }}</small></td>
                                <td><small class="text-muted-custom">{{ $teamMember->address ?? 'N/A' }}</small></td>
                                <td><small class="text-muted-custom">{{ $teamMember->district ?? 'N/A' }}</small></td>
                                <td><small class="text-muted-custom">{{ $teamMember->state ?? 'N/A' }}</small></td>
                                <td>
                                    @if(!empty($teamMember->profile_photo))
                                        <img src="{{ asset('storage/' . $teamMember->profile_photo) }}" alt="Profile"
                                            class="img-thumbnail" style="max-height:60px;">
                                    @else
                                        <small class="text-muted-custom">N/A</small>
                                    @endif
                                </td>
                                <td>
                                    @if($teamMember->status == '1')
                                        <span class="badge-yes"><i class="fas fa-check-circle me-1"></i>Yes</span>
                                    @else
                                        <span class="badge-no"><i class="fas fa-times-circle me-1"></i>No</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-primary btn-sm edit-team-btn"
                                            data-id="{{ $teamMember->id }}"
                                            data-name="{{ $teamMember->name }}"
                                            data-mobile="{{ $teamMember->mobile_number }}"
                                            data-address="{{ $teamMember->address }}"
                                            data-district-id="{{ $teamMember->district }}"
                                            data-state-id="{{ $teamMember->state }}"
                                            data-position="{{ $teamMember->position }}"
                                            data-status="{{ $teamMember->status }}"
                                            data-featured="{{ $teamMember->is_featured ?? 0 }}"
                                            data-photo="{{ $teamMember->profile_photo }}">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>
                                        

                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Team Member Modal -->
    <div class="modal fade" id="addTeamModal" tabindex="-1" aria-labelledby="addTeamModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-lg">
            <div class="modal-content glass-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTeamModalLabel">Add Team Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addTeamForm" action="{{ route('storeTeam') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="mobile_number" class="form-control" required>
                            </div>


                            <div class="col-md-12">
                                <label class="form-label">Profile Photo</label>
                                <input type="file" name="profile_photo" id="add_profile_photo" class="form-control"
                                    accept="image/*">
                                <div class="mt-2">
                                    <img id="add_photo_preview" src="" alt="Preview" class="img-thumbnail d-none"
                                        style="max-height:120px;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Active Status</label>
                                <select name="status" class="form-select">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Position</label>
                                <select name="position" class="form-select" required>
                                    <option value="">Select Position</option>
                                    @foreach(($positions ?? []) as $pos)
                                        <option value="{{ $pos }}">{{ $pos }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">State</label>
                                <select name="state_id" class="form-select" required>
                                    <option value="">Select State</option>
                                    @foreach(($states ?? []) as $state)
                                        <option value="{{ $state }}">{{ $state }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">District</label>
                                <select name="district_id" class="form-select" required>
                                    <option value="">Select District</option>
                                    @foreach(($districts ?? []) as $district)
                                        <option value="{{ $district }}">{{ $district }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Edit Team Member Modal -->
    <div class="modal fade" id="editTeamModal" tabindex="-1" aria-labelledby="editTeamModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content glass-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTeamModalLabel">Edit Team Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editTeamForm" action="{{ route('updateTeam') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="edit_id">
                    <input type="hidden" name="current_photo" id="edit_current_photo">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="mobile_number" id="edit_mobile" class="form-control" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" id="edit_address" class="form-control">
                            </div>
                        
                            <div class="col-md-12">
                                <label class="form-label">Profile Photo</label>
                                <input type="file" name="profile_photo" id="edit_profile_photo" class="form-control"
                                    accept="image/*">
                                <small class="text-muted-custom" id="edit_photo_hint"></small>
                                <div class="mt-2">
                                    <img id="edit_photo_preview" src="" alt="Preview" class="img-thumbnail d-none"
                                        style="max-height:120px;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Active Status</label>
                                <select name="status" id="edit_status" class="form-select">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Position</label>
                                <select name="position" id="edit_position" class="form-select" required>
                                    <option value="">Select Position</option>
                                    @foreach(($positions ?? []) as $pos)
                                        <option value="{{ $pos }}">{{ $pos }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">State</label>
                                <select name="state_id" id="edit_state_id" class="form-select" required>
                                    <option value="">Select State</option>
                                    @foreach(($states ?? []) as $state)
                                        <option value="{{ $state }}">{{ $state }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">District</label>
                                <select name="district_id" id="edit_district_id" class="form-select" required>
                                    <option value="">Select District</option>
                                    @foreach(($districts ?? []) as $district)
                                        <option value="{{ $district }}">{{ $district }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/jquery.dataTables.min.js"></script>
    <script src="/assets/js/dataTables.bootstrap5.min.js"></script>
    <script src="/assets/js/dataTables.buttons.min.js"></script>
    <script src="/assets/js/buttons.bootstrap5.min.js"></script>
    <script src="/assets/js/jszip.min.js"></script>
    <script src="/assets/js/buttons.html5.min.js"></script>
    <script src="/assets/js/buttons.print.min.js"></script>

    <script>
        $(function () {
            $('#operatorTable').DataTable({
                dom: 'Bfrtip',
                buttons: ['excel', 'csv', 'print'],
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, 'All']
                ],
                pageLength: 10,
                language: {
                    emptyTable: 'No Team found. Click "Add New Team Member" to get started.'
                }
            });

            const addModal = new bootstrap.Modal(document.getElementById('addTeamModal'));
            const editModal = new bootstrap.Modal(document.getElementById('editTeamModal'));

            $('#addNewTeamMemberBtn').on('click', function () {
                $('#addTeamForm')[0].reset();
                addModal.show();
            });

            function showPreview(input, imgSelector) {
                const file = input.files && input.files[0];
                const $img = $(imgSelector);
                if (!file) {
                    $img.addClass('d-none').attr('src', '');
                    return;
                }
                const reader = new FileReader();
                reader.onload = e => $img.removeClass('d-none').attr('src', e.target.result);
                reader.readAsDataURL(file);
            }

            $('#add_profile_photo').on('change', function () {
                showPreview(this, '#add_photo_preview');
            });

            $('#edit_profile_photo').on('change', function () {
                showPreview(this, '#edit_photo_preview');
            });

            $('.edit-team-btn').on('click', function () {
                const btn = $(this);

                $('#edit_id').val(btn.data('id'));
                $('#edit_name').val(btn.data('name'));
                $('#edit_mobile').val(btn.data('mobile'));
                $('#edit_address').val(btn.data('address'));
                $('#edit_position').val(btn.data('position') || '');
                $('#edit_state_id').val(btn.data('state-id') || '');
                $('#edit_district_id').val(btn.data('district-id') || '');
                $('#edit_status').val(String(btn.data('status') ?? '1'));
                $('#edit_featured').val(String(btn.data('featured') ?? '0'));

                const photo = btn.data('photo') || '';
                if (photo) {
                    $('#edit_photo_preview')
                        .removeClass('d-none')
                        .attr('src', "{{ asset('storage') }}/" + photo);
                } else {
                    $('#edit_photo_preview').addClass('d-none').attr('src', '');
                }

                editModal.show();
            });
        });
    </script>
@endsection