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

        .photo-preview {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            align-items: center;
        }

        .photo-preview img {
            width: 38px;
            height: 38px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            background: #fff;
        }
    </style>
@endsection
@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container-fluid">
            <h1><i class="fas fa-box me-2"></i>Manage Stories</h1>
            <p>Organize and manage your stories efficiently</p>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card shadow">
        <div class="card-body">
            <!-- Action Buttons -->
            <div class="btn-group-actions">
                <button type="button" class="btn btn-success" id="addNewTeamMemberBtn">
                    <i class="fas fa-plus me-2"></i> Add New Installation Story
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="operatorTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 5%;">S.No.</th>
                            <th style="width: 15%;">Installation Type</th>
                            <th style="width: 12%;">Location</th>
                            <th style="width: 20%;">System Size (kW)</th>
                            <th style="width: 10%;">Installation Date</th>
                            <th style="width: 5%;">Video</th>
                            <th style="width: 10%;">Photos</th>
                            <th style="width: 8%;">Active Status</th>
                            <th style="width: 17%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($listStories as $key => $story)
                            <tr>
                                <td class="fw-bold">{{ $key + 1 }}</td>
                                <td><span class="fw-bold">{{ $story->installation_type ?? 'N/A' }}</span></td>
                                <td><small class="text-muted-custom">{{ $story->location ?? 'N/A' }}</small></td>
                                <td><small class="text-muted-custom">{{ $story->system_size_kw ?? 'N/A' }}</small></td>
                                <td><small class="text-muted-custom">{{ $story->installation_date ?? 'N/A' }}</small></td>
                                <td><small class="text-muted-custom">{{ $story->videos ?? 'N/A' }}</small></td>
                                <td>
                                    @php
                                        $photosRaw = $story->photos ?? null;
                                        $photos = [];

                                        if (is_array($photosRaw)) {
                                            $photos = $photosRaw;
                                        } elseif (is_string($photosRaw) && $photosRaw !== '') {
                                            $decoded = json_decode($photosRaw, true);
                                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                $photos = $decoded;
                                            } else {
                                                $photos = array_filter(array_map('trim', explode(',', $photosRaw)));
                                            }
                                        }
                                    @endphp

                                    @if(!empty($photos))
                                        <div class="photo-preview">
                                            @foreach($photos as $p)
                                                @php
                                                    $p = ltrim($p, '/');
                                                    $isAbsolute = \Illuminate\Support\Str::startsWith($p, ['http://', 'https://', 'storage/']);
                                                    $url = $isAbsolute ? (str_starts_with($p, 'storage/') ? asset($p) : $p) : asset('storage/' . $p);
                                                @endphp
                                                <a href="{{ $url }}" target="_blank" title="View Photo">
                                                    <img src="{{ $url }}" alt="photo">
                                                </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <small class="text-muted-custom">N/A</small>
                                    @endif
                                </td>
                                <td>
                                    @if($story->active_status == '1')
                                        <span class="badge-yes"><i class="fas fa-check-circle me-1"></i>Yes</span>
                                    @else
                                        <span class="badge-no"><i class="fas fa-times-circle me-1"></i>No</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-primary btn-sm edit-team-btn"
                                            data-id="{{ $story->id }}" data-name="{{ $story->installation_type }}"
                                            data-mobile="{{ $story->location }}" data-address="{{ $story->system_size_kw }}"
                                            data-district-id="{{ $story->district }}" data-state-id="{{ $story->state }}"
                                            data-position="{{ $story->position }}" data-status="{{ $story->status }}"
                                            data-featured="{{ $story->is_featured ?? 0 }}"
                                            data-photo="{{ $story->profile_photo }}">
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

<div class="modal fade" id="editStoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content glass-modal">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Installation Story</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editStoryForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="storyId" name="id">

                <div class="modal-body">
                    <div class="row">
                       
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Installation Type</label>
                            <select id="editInstallationType" name="installation_type" class="form-control" required>
                                <option value="">Select type</option>
                                <option value="Residential">Residential</option>
                                <option value="Commercial">Commercial</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">System Size (kW)</label>
                            <input type="number" step="0.01" id="editSystemSize" name="system_size_kw" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Installation Date</label>
                            <input type="date" id="editInstallationDate" name="installation_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" id="editLocation" name="location" class="form-control">
                        </div>
                       
                      
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Video URL (optional)</label>
                            <input type="url" id="editVideoUrl" name="video_url" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Active Status</label>
                            <select id="editActiveStatus" name="active_status" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Photos</label>
                            <input type="file" id="editPhotos" name="images[]" class="form-control" multiple accept="image/*">
                            <small class="text-muted">Leave empty to keep existing photos</small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Story
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('js')
    <script src="/assets/js/jquery-3.6.0.min.js"></script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/jquery.dataTables.min.js"></script>
    <script src="/assets/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#operatorTable').DataTable({
                pageLength: 10,
                ordering: true,
                searching: true
            });

            // Add New Story Button
            $('#addNewTeamMemberBtn').on('click', function() {
                window.location.href = "{{ route('newInstallationStory') }}";
            });

            // Edit Story Button
            $(document).on('click', '.edit-team-btn', function(e) {
                e.preventDefault();
                const storyId = $(this).data('id');
                console.log('Editing story ID:', storyId);
                
                // Fetch story data via AJAX
                $.ajax({
                    url: `/admin/get-story/${storyId}`,
                    type: 'GET',
                    success: function(data) {
                        console.log('Data received:', data);
                        $('#storyId').val(data.id);
                        $('#editTitle').val(data.title || '');
                        $('#editInstallationType').val(data.installation_type || '');
                        $('#editSystemSize').val(data.system_size_kw || '');
                        $('#editInstallationDate').val(data.installation_date || '');
                        $('#editLocation').val(data.location || '');
                        $('#editCustomerName').val(data.customer_name || '');
                        $('#editDescription').val(data.description || '');
                        $('#editVideoUrl').val(data.videos || '');
                        $('#editActiveStatus').val(data.active_status || 0);
                        
                        // Update form action
                        $('#editStoryForm').attr('action', `/admin/update-story/${data.id}`);
                        
                        // Show modal
                        const modal = new bootstrap.Modal(document.getElementById('editStoryModal'));
                        modal.show();
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        alert('Error loading story data');
                    }
                });
            });
        });
    </script>
@endsection

<!-- Edit Modal -->
