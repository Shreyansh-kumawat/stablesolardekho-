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

        /* Custom Position Dropdown */
        .pos-wrap { position: relative; }
        .pos-trigger {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: #fff;
            cursor: pointer;
            font-size: 0.88rem;
            color: var(--text-primary);
            transition: border-color 0.15s, box-shadow 0.15s;
            min-height: 40px;
        }
        .pos-trigger:hover { border-color: #b8c5d2; }
        .pos-trigger.active { border-color: var(--primary-blue); box-shadow: 0 0 0 3px rgba(74,144,226,0.12); }
        .pos-trigger .pos-value { flex: 1; text-align: left; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .pos-trigger .pos-value.placeholder { color: #94a3b8; font-weight: 400; }
        .pos-trigger .pos-caret {
            width: 18px; height: 18px; flex-shrink: 0;
            transition: transform 0.2s;
        }
        .pos-trigger.active .pos-caret { transform: rotate(180deg); }

        .pos-panel {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.18);
            z-index: 1080;
            display: none;
            max-height: 320px;
            flex-direction: column;
            overflow: hidden;
        }
        .pos-panel.show { display: flex; }
        .pos-search-wrap {
            padding: 10px;
            border-bottom: 1px solid #f1f3f5;
            background: #fafbfc;
        }
        .pos-search {
            width: 100%;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 7px 10px;
            font-size: 0.85rem;
            outline: none;
        }
        .pos-search:focus { border-color: var(--primary-blue); }
        .pos-list {
            flex: 1;
            overflow-y: auto;
            padding: 6px 0;
        }
        .pos-item {
            padding: 9px 14px;
            cursor: pointer;
            font-size: 0.86rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.1s;
        }
        .pos-item:hover, .pos-item.hl { background: #f1f5fb; }
        .pos-item.selected { background: rgba(74,144,226,0.1); color: var(--primary-blue); font-weight: 600; }
        .pos-item .dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: #cbd5e1;
            flex-shrink: 0;
        }
        .pos-item.selected .dot { background: var(--primary-blue); }
        .pos-empty { padding: 14px; text-align: center; color: #94a3b8; font-size: 0.82rem; }
        .pos-custom {
            padding: 10px 12px;
            border-top: 1px solid #f1f3f5;
            background: #fefce8;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.82rem;
            color: #92400e;
            cursor: pointer;
        }
        .pos-custom:hover { background: #fef3c7; }
        .pos-custom svg { flex-shrink: 0; }
        .pos-custom strong { color: #78350f; }
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
                                        <form action="{{ route('deleteTeamMember', $teamMember->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this team member?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash me-1"></i> Delete</button>
                                        </form>
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
                                <div class="pos-wrap" data-pos="add">
                                    <input type="hidden" name="position" id="add_position_value" required>
                                    <button type="button" class="pos-trigger" onclick="togglePos('add')">
                                        <span class="pos-value placeholder" id="add_position_label">Select or add position</span>
                                        <svg class="pos-caret" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <div class="pos-panel" id="add_position_panel">
                                        <div class="pos-search-wrap">
                                            <input type="text" class="pos-search" id="add_position_search" placeholder="Search or type new position...">
                                        </div>
                                        <div class="pos-list" id="add_position_list"></div>
                                    </div>
                                </div>
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
                                <input type="text" name="district_id" class="form-control" placeholder="Enter district">
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
                                <div class="pos-wrap" data-pos="edit">
                                    <input type="hidden" name="position" id="edit_position_value" required>
                                    <button type="button" class="pos-trigger" onclick="togglePos('edit')">
                                        <span class="pos-value placeholder" id="edit_position_label">Select or add position</span>
                                        <svg class="pos-caret" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <div class="pos-panel" id="edit_position_panel">
                                        <div class="pos-search-wrap">
                                            <input type="text" class="pos-search" id="edit_position_search" placeholder="Search or type new position...">
                                        </div>
                                        <div class="pos-list" id="edit_position_list"></div>
                                    </div>
                                </div>
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
                                <input type="text" name="district_id" id="edit_district_id" class="form-control" placeholder="Enter district">
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

            $(document).on('click', '.edit-team-btn', function () {
                const btn = $(this);

                $('#edit_id').val(btn.data('id'));
                $('#edit_name').val(btn.data('name'));
                $('#edit_mobile').val(btn.data('mobile'));
                $('#edit_address').val(btn.data('address'));
                setPosValue('edit', btn.data('position') || '');
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

        /* ── Custom Position Dropdown ── */
        (function () {
            const PRESETS = @json(array_values($positions ?? []));
            const state = { add: { positions: PRESETS.slice() }, edit: { positions: PRESETS.slice() } };

            window.togglePos = function (prefix) {
                const panel = document.getElementById(prefix + '_position_panel');
                const trigger = panel.previousElementSibling;
                const isOpen = panel.classList.contains('show');
                // close others
                document.querySelectorAll('.pos-panel.show').forEach(p => {
                    p.classList.remove('show');
                    p.previousElementSibling.classList.remove('active');
                });
                if (!isOpen) {
                    renderPosList(prefix, '');
                    panel.classList.add('show');
                    trigger.classList.add('active');
                    const search = document.getElementById(prefix + '_position_search');
                    search.value = '';
                    setTimeout(() => search.focus(), 30);
                }
            };

            window.setPosValue = function (prefix, value) {
                const val = (value || '').trim();
                document.getElementById(prefix + '_position_value').value = val;
                const label = document.getElementById(prefix + '_position_label');
                if (val) {
                    label.textContent = val;
                    label.classList.remove('placeholder');
                    // add to state list if new
                    if (val && !state[prefix].positions.some(p => p.toLowerCase() === val.toLowerCase())) {
                        state[prefix].positions.push(val);
                    }
                } else {
                    label.textContent = 'Select or add position';
                    label.classList.add('placeholder');
                }
            };

            function renderPosList(prefix, query) {
                const list = document.getElementById(prefix + '_position_list');
                const current = (document.getElementById(prefix + '_position_value').value || '').toLowerCase();
                const q = (query || '').trim().toLowerCase();
                const items = state[prefix].positions.filter(p => !q || p.toLowerCase().includes(q));

                let html = '';
                if (items.length === 0 && !q) {
                    html = '<div class="pos-empty">No positions yet. Start typing to add one.</div>';
                } else {
                    items.forEach(p => {
                        const sel = p.toLowerCase() === current ? ' selected' : '';
                        html += '<div class="pos-item' + sel + '" data-val="' + escAttr(p) + '"><span class="dot"></span>' + escHtml(p) + '</div>';
                    });
                }

                // "Add custom" if query is non-empty and doesn't exactly match
                if (q) {
                    const exact = state[prefix].positions.some(p => p.toLowerCase() === q);
                    if (!exact) {
                        html += '<div class="pos-custom" data-add="' + escAttr(query.trim()) + '">'
                              + '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>'
                              + 'Add new position: <strong>' + escHtml(query.trim()) + '</strong></div>';
                    }
                }
                list.innerHTML = html;

                list.querySelectorAll('.pos-item').forEach(el => {
                    el.addEventListener('click', () => {
                        setPosValue(prefix, el.getAttribute('data-val'));
                        closePos(prefix);
                    });
                });
                const custom = list.querySelector('.pos-custom');
                if (custom) {
                    custom.addEventListener('click', () => {
                        setPosValue(prefix, custom.getAttribute('data-add'));
                        closePos(prefix);
                    });
                }
            }

            function closePos(prefix) {
                const panel = document.getElementById(prefix + '_position_panel');
                panel.classList.remove('show');
                panel.previousElementSibling.classList.remove('active');
            }

            ['add', 'edit'].forEach(prefix => {
                const search = document.getElementById(prefix + '_position_search');
                if (!search) return;
                search.addEventListener('input', e => renderPosList(prefix, e.target.value));
                search.addEventListener('keydown', e => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const val = search.value.trim();
                        if (val) {
                            setPosValue(prefix, val);
                            closePos(prefix);
                        }
                    } else if (e.key === 'Escape') {
                        closePos(prefix);
                    }
                });
            });

            // close on outside click
            document.addEventListener('click', function (e) {
                document.querySelectorAll('.pos-wrap').forEach(w => {
                    if (!w.contains(e.target)) {
                        const prefix = w.getAttribute('data-pos');
                        closePos(prefix);
                    }
                });
            });

            function escHtml(s) {
                return String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
            }
            function escAttr(s) {
                return String(s).replace(/"/g, '&quot;');
            }

            // Reset add form on modal close
            const addModalEl = document.getElementById('addTeamModal');
            if (addModalEl) {
                addModalEl.addEventListener('hidden.bs.modal', () => setPosValue('add', ''));
            }
        })();
    </script>
@endsection