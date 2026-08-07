@extends('layouts.adminLayout')
@section('title', 'My Documents')

@section('css')
<style>
    :root { --blue: #2563eb; --blue-dark: #1d4ed8; --text: #1e293b; --muted: #64748b; --border: #e2e8f0; --white: #fff; }

    .doc-wrap { padding: 1.25rem; }
    .doc-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem; }
    .doc-header-left { display: flex; align-items: center; gap: 12px; }
    .doc-icon { width: 40px; height: 40px; background: var(--blue); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .doc-header h1 { font-size: 1.15rem; font-weight: 700; color: var(--text); margin: 0; }
    .doc-header p { font-size: 0.78rem; color: var(--muted); margin: 2px 0 0; }

    .doc-btn { display: inline-flex; align-items: center; gap: 5px; padding: 7px 14px; border-radius: 8px; font-size: 0.82rem; font-weight: 600; border: none; cursor: pointer; transition: background 0.15s; }
    .doc-btn-primary { background: var(--blue); color: #fff; }
    .doc-btn-primary:hover { background: var(--blue-dark); }
    .doc-btn-danger { background: #dc2626; color: #fff; }
    .doc-btn-danger:hover { background: #b91c1c; }
    .doc-btn-sm { padding: 4px 10px; font-size: 0.75rem; }

    .doc-filters { background: var(--white); border: 1px solid var(--border); border-radius: 10px; padding: 12px 16px; display: flex; gap: 10px; flex-wrap: wrap; align-items: end; margin-bottom: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .doc-filters label { font-size: 0.75rem; font-weight: 600; color: var(--text); display: block; margin-bottom: 3px; }
    .doc-filters select { border: 1px solid var(--border); border-radius: 6px; padding: 6px 10px; font-size: 0.8rem; }

    .doc-checklist { background: var(--white); border: 1px solid var(--border); border-radius: 10px; padding: 14px 18px; margin-bottom: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .doc-checklist-title { font-size: 0.78rem; font-weight: 700; color: var(--text); margin-bottom: 8px; }
    .doc-checklist-grid { display: flex; flex-wrap: wrap; gap: 8px; }
    .doc-check-item { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; }
    .doc-check-done { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
    .doc-check-pending { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .doc-check-opt { background: #f8fafc; color: var(--muted); border: 1px solid var(--border); }
    .doc-check-opt-done { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }

    .doc-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.75rem; margin-bottom: 1.25rem; }
    .doc-stat { background: var(--white); border: 1px solid var(--border); border-radius: 10px; padding: 12px 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .doc-stat-label { font-size: 0.72rem; font-weight: 600; color: var(--muted); text-transform: uppercase; }
    .doc-stat-value { font-size: 1.3rem; font-weight: 700; color: var(--text); margin-top: 2px; }

    .doc-card { background: var(--white); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .doc-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
    .doc-table thead { background: #f8fafc; }
    .doc-table th { padding: 10px 12px; font-weight: 700; color: #374151; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.5px; border-bottom: 2px solid var(--border); text-align: left; white-space: nowrap; }
    .doc-table td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; color: #374151; vertical-align: middle; }
    .doc-table tbody tr:hover { background: #f8fafc; }
    .doc-type-badge { display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 0.7rem; font-weight: 600; background: #eff6ff; color: #2563eb; }
    .doc-type-req { background: #fef2f2; color: #dc2626; font-size: 0.62rem; font-weight: 700; padding: 1px 5px; border-radius: 4px; margin-left: 4px; }
    .doc-file-link { color: var(--blue); text-decoration: none; font-weight: 600; font-size: 0.78rem; }
    .doc-file-link:hover { text-decoration: underline; }
    .doc-size { color: var(--muted); font-size: 0.75rem; }
    .doc-empty { text-align: center; padding: 2.5rem 1rem; color: var(--muted); }

    .modal-content { border-radius: 12px; border: 1px solid var(--border); box-shadow: 0 10px 30px rgba(0,0,0,0.12); }
    .modal-header { background: #f8f9fa; border-bottom: 1px solid var(--border); border-radius: 12px 12px 0 0; padding: 1rem 1.25rem; }
    .modal-footer { background: #f8f9fa; border-top: 1px solid var(--border); border-radius: 0 0 12px 12px; padding: 1rem 1.25rem; }
    .modal-title { font-size: 0.95rem; font-weight: 700; color: var(--text); }
    .form-label { font-size: 0.82rem; font-weight: 600; color: var(--text); margin-bottom: 4px; }
    .form-control, .form-select { border: 1px solid var(--border); border-radius: 8px; padding: 0.45rem 0.8rem; font-size: 0.84rem; color: var(--text); }
    .form-control:focus, .form-select:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.12); outline: none; }

    @media (max-width: 768px) { .doc-card { overflow-x: auto; } .doc-stats { grid-template-columns: repeat(2, 1fr); } }
</style>
@endsection

@section('content')
<div class="doc-wrap">
    <div class="doc-header">
        <div class="doc-header-left">
            <div class="doc-icon">
                <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
            </div>
            <div>
                <h1>My Documents</h1>
                <p>Upload and manage your documents</p>
            </div>
        </div>
        <button class="doc-btn doc-btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
            Upload Document
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" style="font-size:0.84rem; border-radius:10px; margin-bottom:1rem;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size:0.65rem;"></button>
        </div>
    @endif

    @php
        $uploadedTypes = $documents->pluck('document_type')->unique()->toArray();
    @endphp
    <div class="doc-checklist">
        <div class="doc-checklist-title">Document Checklist</div>
        <div class="doc-checklist-grid">
            @foreach($docTypes as $key => $label)
                @if(in_array($key, $compulsoryTypes))
                    <span class="doc-check-item {{ in_array($key, $uploadedTypes) ? 'doc-check-done' : 'doc-check-pending' }}">
                        @if(in_array($key, $uploadedTypes))
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        @else
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        @endif
                        {{ $label }} <span style="font-size:0.6rem;">(Required)</span>
                    </span>
                @elseif($key !== 'other')
                    <span class="doc-check-item {{ in_array($key, $uploadedTypes) ? 'doc-check-opt-done' : 'doc-check-opt' }}">
                        @if(in_array($key, $uploadedTypes))
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        @endif
                        {{ $label }} <span style="font-size:0.6rem;">(Optional)</span>
                    </span>
                @endif
            @endforeach
        </div>
    </div>

    <form class="doc-filters" method="GET" action="{{ route('cpDocuments') }}">
        <div>
            <label>Document Type</label>
            <select name="document_type">
                <option value="">All Types</option>
                @foreach($docTypes as $key => $label)
                    <option value="{{ $key }}" {{ request('document_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="doc-btn doc-btn-primary doc-btn-sm">Filter</button>
        <a href="{{ route('cpDocuments') }}" class="doc-btn doc-btn-sm" style="background:#e2e8f0; color:var(--text);">Clear</a>
    </form>

    <div class="doc-stats">
        <div class="doc-stat">
            <div class="doc-stat-label">Total Documents</div>
            <div class="doc-stat-value">{{ $documents->count() }}</div>
        </div>
        <div class="doc-stat">
            <div class="doc-stat-label">Required Uploaded</div>
            <div class="doc-stat-value">{{ count(array_intersect($compulsoryTypes, $uploadedTypes)) }} / {{ count($compulsoryTypes) }}</div>
        </div>
    </div>

    <div class="doc-card">
        <div style="overflow-x:auto;">
            <table class="doc-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>File</th>
                        <th>Size</th>
                        <th>Date</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $doc->title }}</td>
                        <td>
                            <span class="doc-type-badge">{{ $docTypes[$doc->document_type] ?? $doc->document_type }}</span>
                            @if(in_array($doc->document_type, $compulsoryTypes))<span class="doc-type-req">Required</span>@endif
                        </td>
                        <td>
                            <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="doc-file-link">
                                {{ Str::limit($doc->file_name, 30) }}
                            </a>
                        </td>
                        <td><span class="doc-size">{{ number_format($doc->file_size / 1024, 0) }} KB</span></td>
                        <td style="white-space:nowrap;">{{ $doc->created_at->format('d M Y') }}</td>
                        <td>{{ $doc->remarks ?: '-' }}</td>
                        <td>
                            <form method="POST" action="{{ route('cpDocumentDelete', $doc->id) }}" class="d-inline delete-doc-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="doc-btn doc-btn-danger doc-btn-sm" title="Delete">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="doc-empty">No documents uploaded yet. Click "Upload Document" to get started.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('cpDocumentStore') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Upload Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:1.25rem;">
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" required placeholder="e.g. Bill - August 2026">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Document Type *</label>
                        <select name="document_type" class="form-select" required>
                            <option value="">Select Type</option>
                            @foreach($docTypes as $key => $label)
                                <option value="{{ $key }}">{{ $label }}{{ in_array($key, $compulsoryTypes) ? ' (Compulsory)' : ($key !== 'other' ? ' (Optional)' : '') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File * <small style="color:var(--muted);">(Any file type - Max 20MB)</small></label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="Optional notes"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="doc-btn doc-btn-sm" style="background:#e2e8f0; color:var(--text);" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="doc-btn doc-btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.delete-doc-form').forEach(f => {
    f.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Delete Document?',
            text: 'This will permanently remove the file.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Yes, delete'
        }).then(r => { if (r.isConfirmed) f.submit(); });
    });
});
</script>
@endsection
