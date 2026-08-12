@extends('layouts.adminLayout')
@section('title', 'Client Documents')

@section('css')
<style>
    :root { --blue: #2563eb; --blue-dark: #1d4ed8; --text: #1e293b; --muted: #64748b; --border: #e2e8f0; --white: #fff; --green: #16a34a; --red: #dc2626; --orange: #ea580c; }

    .doc-wrap { padding: 1.25rem; max-width: 1100px; }
    .doc-header { display: flex; align-items: center; gap: 12px; margin-bottom: 1.25rem; }
    .doc-icon { width: 40px; height: 40px; background: var(--blue); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .doc-header h1 { font-size: 1.15rem; font-weight: 700; color: var(--text); margin: 0; }
    .doc-header p { font-size: 0.78rem; color: var(--muted); margin: 2px 0 0; }

    .doc-btn { display: inline-flex; align-items: center; gap: 5px; padding: 7px 14px; border-radius: 8px; font-size: 0.82rem; font-weight: 600; border: none; cursor: pointer; transition: background 0.15s; }
    .doc-btn-primary { background: var(--blue); color: #fff; }
    .doc-btn-primary:hover { background: var(--blue-dark); }
    .doc-btn-danger { background: var(--red); color: #fff; }
    .doc-btn-danger:hover { background: #b91c1c; }
    .doc-btn-sm { padding: 5px 12px; font-size: 0.76rem; }

    .doc-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 0.75rem; margin-bottom: 1.25rem; }
    .doc-stat { background: var(--white); border: 1px solid var(--border); border-radius: 10px; padding: 12px 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .doc-stat-label { font-size: 0.72rem; font-weight: 600; color: var(--muted); text-transform: uppercase; }
    .doc-stat-value { font-size: 1.3rem; font-weight: 700; color: var(--text); margin-top: 2px; }

    /* Upload Form */
    .upload-section { background: var(--white); border: 1px solid var(--border); border-radius: 12px; padding: 20px; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .upload-title { font-size: 0.95rem; font-weight: 700; color: var(--text); margin-bottom: 4px; }
    .upload-sub { font-size: 0.78rem; color: var(--muted); margin-bottom: 16px; }

    .client-fields { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid var(--border); }
    .field-group label { font-size: 0.74rem; font-weight: 600; color: var(--text); display: block; margin-bottom: 3px; }
    .field-group input, .field-group textarea { border: 1px solid var(--border); border-radius: 7px; padding: 7px 10px; font-size: 0.82rem; width: 100%; color: var(--text); background: var(--white); }
    .field-group input:focus, .field-group textarea:focus { border-color: var(--blue); box-shadow: 0 0 0 2px rgba(37,99,235,0.1); outline: none; }

    .doc-file-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 10px; margin-bottom: 14px; }
    .doc-file-item { border: 1px dashed var(--border); border-radius: 8px; padding: 10px 12px; background: #f8fafc; }
    .doc-file-item label { font-size: 0.76rem; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 5px; margin-bottom: 6px; }
    .doc-file-item .req-badge { font-size: 0.6rem; color: var(--red); font-weight: 700; }
    .doc-file-item .opt-badge { font-size: 0.6rem; color: var(--muted); font-weight: 600; }
    .doc-file-item input[type="file"] { font-size: 0.75rem; width: 100%; }

    .remarks-row { margin-bottom: 14px; }
    .remarks-row textarea { resize: vertical; min-height: 36px; }

    /* Client Cards */
    .clients-section { margin-bottom: 1.5rem; }
    .clients-title { font-size: 0.95rem; font-weight: 700; color: var(--text); margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
    .client-card { background: var(--white); border: 1px solid var(--border); border-radius: 10px; margin-bottom: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .client-card-header { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; cursor: pointer; transition: background 0.1s; }
    .client-card-header:hover { background: #f8fafc; }
    .client-info h3 { font-size: 0.88rem; font-weight: 700; color: var(--text); margin: 0; }
    .client-info p { font-size: 0.75rem; color: var(--muted); margin: 2px 0 0; }
    .client-meta { display: flex; align-items: center; gap: 8px; }
    .client-doc-count { font-size: 0.72rem; font-weight: 600; color: var(--blue); background: #eff6ff; padding: 3px 8px; border-radius: 5px; }
    .client-toggle { font-size: 0.7rem; color: var(--muted); transition: transform 0.2s; }
    .client-toggle.open { transform: rotate(180deg); }

    .client-card-body { display: none; border-top: 1px solid var(--border); padding: 12px 16px; }
    .client-card-body.show { display: block; }

    .client-doc-list { width: 100%; font-size: 0.8rem; border-collapse: collapse; }
    .client-doc-list th { text-align: left; font-size: 0.7rem; font-weight: 700; color: var(--muted); text-transform: uppercase; padding: 6px 8px; border-bottom: 1px solid var(--border); }
    .client-doc-list td { padding: 7px 8px; border-bottom: 1px solid #f1f5f9; color: #374151; vertical-align: middle; }
    .client-doc-list tr:last-child td { border-bottom: none; }
    .doc-type-badge { display: inline-block; padding: 2px 8px; border-radius: 5px; font-size: 0.7rem; font-weight: 600; background: #eff6ff; color: var(--blue); }
    .doc-file-link { color: var(--blue); text-decoration: none; font-weight: 600; font-size: 0.78rem; }
    .doc-file-link:hover { text-decoration: underline; }
    .doc-size { color: var(--muted); font-size: 0.72rem; }
    .del-btn { background: none; border: none; color: var(--red); cursor: pointer; padding: 2px; }
    .del-btn:hover { color: #991b1b; }

    .no-clients { text-align: center; padding: 2rem; color: var(--muted); font-size: 0.85rem; background: var(--white); border: 1px solid var(--border); border-radius: 10px; }

    @media (max-width: 768px) {
        .client-fields { grid-template-columns: 1fr; }
        .doc-file-grid { grid-template-columns: 1fr 1fr; }
        .doc-stats { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 480px) {
        .doc-file-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="doc-wrap">
    <div class="doc-header">
        <div class="doc-icon">
            <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
        </div>
        <div>
            <h1>Client Documents</h1>
            <p>Upload documents for each client installation</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" style="font-size:0.84rem; border-radius:10px; margin-bottom:1rem;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size:0.65rem;"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" style="font-size:0.84rem; border-radius:10px; margin-bottom:1rem;">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size:0.65rem;"></button>
        </div>
    @endif

    <div class="doc-stats">
        <div class="doc-stat">
            <div class="doc-stat-label">Total Documents</div>
            <div class="doc-stat-value">{{ $documents->count() }}</div>
        </div>
        <div class="doc-stat">
            <div class="doc-stat-label">Total Clients</div>
            <div class="doc-stat-value">{{ $clients->count() }}</div>
        </div>
    </div>

    <!-- Upload Section -->
    <div class="upload-section">
        <div class="upload-title">Upload Documents for a Client</div>
        <div class="upload-sub">Fill client details, attach documents, and upload all at once.</div>

        <form method="POST" action="{{ route('cpDocumentStore') }}" enctype="multipart/form-data">
            @csrf

            <div class="client-fields">
                <div class="field-group">
                    <label>Client Name *</label>
                    <input type="text" name="client_name" required placeholder="e.g. Rajesh Kumar">
                </div>
                <div class="field-group">
                    <label>Client Phone</label>
                    <input type="text" name="client_phone" placeholder="e.g. 9876543210">
                </div>
                <div class="field-group">
                    <label>Client Address</label>
                    <input type="text" name="client_address" placeholder="e.g. 123, Jaipur, Rajasthan">
                </div>
            </div>

            <div style="font-size:0.78rem; font-weight:600; color:var(--text); margin-bottom:8px;">Attach Documents (any file type, max 20MB each)</div>
            <div class="doc-file-grid">
                @foreach($docTypes as $key => $label)
                <div class="doc-file-item">
                    <label>
                        {{ $label }}
                        @if(in_array($key, $compulsoryTypes))
                            <span class="req-badge">Required</span>
                        @elseif($key !== 'other')
                            <span class="opt-badge">Optional</span>
                        @endif
                    </label>
                    <input type="file" name="doc_{{ $key }}">
                </div>
                @endforeach
            </div>

            <div class="remarks-row field-group">
                <label>Remarks</label>
                <textarea name="remarks" rows="1" placeholder="Optional notes for this client"></textarea>
            </div>

            <button type="submit" class="doc-btn doc-btn-primary">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                Upload All Documents
            </button>
        </form>
    </div>

    <!-- Client-wise Documents -->
    <div class="clients-section">
        <div class="clients-title">
            <svg width="18" height="18" fill="none" stroke="var(--blue)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
            Client Documents
        </div>

        @php
            $grouped = $documents->whereNotNull('batch_id')->groupBy('batch_id');
            $ungrouped = $documents->whereNull('batch_id');
        @endphp

        @if($grouped->isEmpty() && $ungrouped->isEmpty())
            <div class="no-clients">No documents uploaded yet. Use the form above to upload documents for a client.</div>
        @endif

        @foreach($grouped as $batchId => $batchDocs)
        @php $firstDoc = $batchDocs->first(); @endphp
        <div class="client-card">
            <div class="client-card-header" onclick="toggleClient(this)">
                <div class="client-info">
                    <h3>{{ $firstDoc->client_name ?? 'Unknown Client' }}</h3>
                    <p>{{ $firstDoc->client_phone ?? '' }}{{ $firstDoc->client_phone && $firstDoc->client_address ? ' &bull; ' : '' }}{{ $firstDoc->client_address ?? '' }}</p>
                </div>
                <div class="client-meta">
                    <span class="client-doc-count">{{ $batchDocs->count() }} doc(s)</span>
                    <span style="font-size:0.72rem; color:var(--muted);">{{ $firstDoc->created_at->format('d M Y') }}</span>
                    <span class="client-toggle">&#9660;</span>
                </div>
            </div>
            <div class="client-card-body">
                <table class="client-doc-list">
                    <thead>
                        <tr><th>Type</th><th>File</th><th>Size</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach($batchDocs as $doc)
                        <tr>
                            <td><span class="doc-type-badge">{{ $docTypes[$doc->document_type] ?? $doc->document_type }}</span></td>
                            <td><a href="{{ url('serve/' . $doc->file_path) }}" target="_blank" class="doc-file-link">{{ Str::limit($doc->file_name, 30) }}</a></td>
                            <td><span class="doc-size">{{ number_format($doc->file_size / 1024, 0) }} KB</span></td>
                            <td>
                                <form method="POST" action="{{ route('cpDocumentDelete', $doc->id) }}" class="d-inline delete-doc-form">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="del-btn" title="Delete"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="margin-top:10px; text-align:right;">
                    <form method="POST" action="{{ route('cpDocumentDeleteBatch', $batchId) }}" class="d-inline delete-batch-form">
                        @csrf @method('DELETE')
                        <button type="submit" class="doc-btn doc-btn-danger doc-btn-sm">Delete All for this Client</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach

        @if($ungrouped->isNotEmpty())
        <div class="client-card">
            <div class="client-card-header" onclick="toggleClient(this)">
                <div class="client-info">
                    <h3 style="color:var(--muted);">Other Documents (No Client)</h3>
                </div>
                <div class="client-meta">
                    <span class="client-doc-count">{{ $ungrouped->count() }} doc(s)</span>
                    <span class="client-toggle">&#9660;</span>
                </div>
            </div>
            <div class="client-card-body">
                <table class="client-doc-list">
                    <thead>
                        <tr><th>Type</th><th>Title</th><th>File</th><th>Size</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach($ungrouped as $doc)
                        <tr>
                            <td><span class="doc-type-badge">{{ $docTypes[$doc->document_type] ?? $doc->document_type }}</span></td>
                            <td>{{ $doc->title }}</td>
                            <td><a href="{{ url('serve/' . $doc->file_path) }}" target="_blank" class="doc-file-link">{{ Str::limit($doc->file_name, 25) }}</a></td>
                            <td><span class="doc-size">{{ number_format($doc->file_size / 1024, 0) }} KB</span></td>
                            <td>
                                <form method="POST" action="{{ route('cpDocumentDelete', $doc->id) }}" class="d-inline delete-doc-form">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="del-btn" title="Delete"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('js')
<script>
function toggleClient(header) {
    const body = header.nextElementSibling;
    const arrow = header.querySelector('.client-toggle');
    body.classList.toggle('show');
    arrow.classList.toggle('open');
}

document.querySelectorAll('.delete-doc-form, .delete-batch-form').forEach(f => {
    f.addEventListener('submit', function(e) {
        e.preventDefault();
        const isBatch = f.classList.contains('delete-batch-form');
        Swal.fire({
            title: isBatch ? 'Delete all documents for this client?' : 'Delete Document?',
            text: 'This will permanently remove the file(s).',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Yes, delete'
        }).then(r => { if (r.isConfirmed) f.submit(); });
    });
});
</script>
@endsection
