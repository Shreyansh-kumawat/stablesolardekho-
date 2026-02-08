@extends('layouts.adminLayout')

@section('title', 'New Installation Story')

@section('css')
    <style>
        :root {
            --primary-blue: #4A90E2;
            --primary-light: #f5f7fa;
            --text-primary: #2d3436;
            --text-secondary: #636e72;
            --border-color: #e1e8ed;
            --hover-bg: #f1f3f5;
            --card-bg: #ffffff;
            --solar-yellow: #f5c542;
            --solar-green: #27ae60;
        }

        body { background: var(--primary-light); color: var(--text-primary); font-size: 0.9rem; }

        .solar-header {
            background: linear-gradient(135deg, #4A90E2 0%, #357abd 100%);
            color: #fff;
            padding: 1rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }

        .solar-header h1 { font-size: 1.2rem; margin: 0 0 0.25rem 0; font-weight: 700; }
        .solar-header p { margin: 0; font-size: 0.85rem; opacity: 0.9; }

        .solar-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .form-label { font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem; }
        .form-control {
            border-radius: 6px !important;
            border: 1px solid var(--border-color) !important;
            padding: 0.5rem 0.75rem !important;
            font-size: 0.85rem !important;
            min-height: 40px;
        }

        .btn-solar {
            background: var(--primary-blue);
            border: 1px solid var(--primary-blue);
            color: #fff;
            border-radius: 6px;
            padding: 0.45rem 0.9rem;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .btn-solar:hover { background: #3b7dc4; border-color: #3b7dc4; }

        .btn-outline-solar {
            background: #fff;
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 6px;
            padding: 0.45rem 0.9rem;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .btn-remove {
            background: #fff5f5;
            border: 1px solid #f5c2c7;
            color: #dc3545;
            border-radius: 6px;
            padding: 0.35rem 0.7rem;
            font-size: 0.8rem;
        }

        .file-row {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .file-row .form-control { flex: 1; }

        .section-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0.75rem 0 0.5rem;
        }

        .note {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }
    </style>
@endsection

@section('content')
    <div style="padding: 1rem 0;">
        <div style="max-width: 1100px; margin: 0 auto; padding: 0 1rem;">
            <div class="solar-header">
                <h1><i class="fas fa-sun me-2"></i>Add Installation Story</h1>
                <p>Create a new solar installation story with media and details</p>
            </div>

            <div class="solar-card">
                <form method="POST" action="{{ route('storeStory') }}" enctype="multipart/form-data">
                    @csrf

                    <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
                        

                        <div style="flex: 1; min-width: 260px;">
                            <label class="form-label">Installation Type</label>
                            <select name="installation_type" class="form-control" required>
                                <option value="">Select type</option>
                                <option value="Residential" {{ old('installation_type') == 'Residential' ? 'selected' : '' }}>Residential</option>
                                <option value="Commercial" {{ old('installation_type') == 'Commercial' ? 'selected' : '' }}>Commercial</option>
                            </select>
                            @error('installation_type') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div style="flex: 1; min-width: 260px;">
                            <label class="form-label">System Size (kW)</label>
                            <input type="number" step="0.01" name="system_size_kw" class="form-control" required
                                value="{{ old('system_size_kw') }}" placeholder="5.5">
                            @error('system_size_kw') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div style="flex: 1; min-width: 260px;">
                            <label class="form-label">Installation Date</label>
                            <input type="date" name="installation_date" class="form-control" required
                                value="{{ old('installation_date') }}">
                            @error('installation_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div style="flex: 1; min-width: 260px;">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control"
                                value="{{ old('location') }}" placeholder="City, State,address...">
                            @error('location') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="section-title">Media</div>
                    <div class="note">Add photos one by one. You can remove any row before saving.</div>

                    <div id="photoList" style="margin-top: 0.5rem; display: flex; flex-direction: column; gap: 0.75rem;">
                        <div class="file-row">
                            <input type="file" name="images[]" class="form-control" accept="image/*">
                            <button type="button" class="btn-remove" onclick="removePhotoRow(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <button type="button" class="btn-outline-solar" style="margin-top: 0.75rem;" onclick="addPhotoRow()">
                        <i class="fas fa-plus me-1"></i> Add Photo
                    </button>

                    <div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-top: 1rem;">
                        <div style="flex: 1; min-width: 260px;">
                            <label class="form-label">Video URL (optional)</label>
                            <input type="url" name="video_url" class="form-control"
                                value="{{ old('video_url') }}" placeholder="https://...">
                            @error('video_url') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                        <button type="submit" class="btn-solar">
                            <i class="fa fa-save me-1"></i> Save Story
                        </button>
                        <a href="{{ route('listStories') }}" class="btn-outline-solar">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function addPhotoRow() {
            const list = document.getElementById('photoList');
            const row = document.createElement('div');
            row.className = 'file-row';
            row.innerHTML = `
                <input type="file" name="images[]" class="form-control" accept="image/*">
                <button type="button" class="btn-remove" onclick="removePhotoRow(this)">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            list.appendChild(row);
        }

        function removePhotoRow(btn) {
            const row = btn.closest('.file-row');
            if (row && document.querySelectorAll('#photoList .file-row').length > 1) {
                row.remove();
            }
        }
    </script>
@endsection