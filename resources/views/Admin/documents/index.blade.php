@extends('layouts.adminLayout')
@section('title', 'CP Documents')

@section('css')
<style>
    :root { --blue: #2563eb; --blue-dark: #1d4ed8; --text: #1e293b; --muted: #64748b; --border: #e2e8f0; --white: #fff; --red: #dc2626; }

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

    .doc-filters { background: var(--white); border: 1px solid var(--border); border-radius: 10px; padding: 12px 16px; display: flex; gap: 10px; flex-wrap: wrap; align-items: end; margin-bottom: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .doc-filters label { font-size: 0.75rem; font-weight: 600; color: var(--text); display: block; margin-bottom: 3px; }
    .doc-filters select, .doc-filters input { border: 1px solid var(--border); border-radius: 6px; padding: 6px 10px; font-size: 0.8rem; color: var(--text); }

    .doc-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 0.75rem; margin-bottom: 1.25rem; }
    .doc-stat { background: var(--white); border: 1px solid var(--border); border-radius: 10px; padding: 12px 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .doc-stat-label { font-size: 0.72rem; font-weight: 600; color: var(--muted); text-transform: uppercase; }
    .doc-stat-value { font-size: 1.3rem; font-weight: 700; color: var(--text); margin-top: 2px; }

    .client-card { background: var(--white); border: 1px solid var(--border); border-radius: 10px; margin-bottom: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .client-card-header { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; cursor: pointer; transition: background 0.1s; }
    .client-card-header:hover { background: #f8fafc; }
    .client-info h3 { font-size: 0.88rem; font-weight: 700; color: var(--text); margin: 0; }
    .client-info p { font-size: 0.75rem; color: var(--muted); margin: 2px 0 0; }
    .client-meta { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
    .client-doc-count { font-size: 0.72rem; font-weight: 600; color: var(--blue); background: #eff6ff; padding: 3px 8px; border-radius: 5px; }
    .cp-badge { font-size: 0.68rem; font-weight: 700; color: #7c3aed; background: #f5f3ff; padding: 3px 8px; border-radius: 5px; }
    .client-toggle { font-size: 0.7rem; color: var(--muted); transition: transform 0.2s; }
    .client-toggle.open { transform: rotate(180deg); }

    .client-card-body { display: none; border-top: 1px solid var(--border); padding: 12px 16px; }
    .client-card-body.show { display: block; }

    .client-doc-list { width: 100%; font-size: 0.8rem; border-collapse: collapse; }
    .client-doc-list th { text-align: left; font-size: 0.7rem; font-weight: 700; color: var(--muted); text-transform: uppercase; padding: 6px 8px; border-bottom: 1px solid var(--border); }
    .client-doc-list td { padding: 7px 8px; border-bottom: 1px solid #f1f5f9; color: #374151; vertical-align: middle; }
    .client-doc-list tr:last-child td { border-bottom: none; }
    .doc-type-badge { display: inline-block; padding: 2px 8px; border-radius: 5px; font-size: 0.7rem; font-weight: 600; background: #eff6ff; color: var(--blue); }
    .doc-type-req { background: #fef2f2; color: var(--red); font-size: 0.62rem; font-weight: 700; padding: 1px 5px; border-radius: 4px; margin-left: 4px; }
    .doc-file-link { color: var(--blue); text-decoration: none; font-weight: 600; font-size: 0.78rem; }
    .doc-file-link:hover { text-decoration: underline; }
    .doc-size { color: var(--muted); font-size: 0.72rem; }
    .del-btn { background: none; border: none; color: var(--red); cursor: pointer; padding: 2px; }
    .del-btn:hover { color: #991b1b; }
    .no-docs { text-align: center; padding: 2rem; color: var(--muted); font-size: 0.85rem; background: var(--white); border: 1px solid var(--border); border-radius: 10px; }

    @media (max-width: 768px) { .doc-stats { grid-template-columns: repeat(2, 1fr); } }
</style>
@endsection

@section('content')
<div class="doc-wrap">
    <div class="doc-header">
        <div class="doc-icon">
            <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
        </div>
        <div>
            <h1>CP Documents</h1>
            <p>Client documents uploaded by channel partners</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" style="font-size:0.84rem; border-radius:10px; margin-bottom:1rem;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size:0.65rem;"></button>
        </div>
    @endif

    <form class="doc-filters" method="GET" action="{{ route('adminDocuments') }}">
        <div>
            <label>Channel Partner</label>
            <select name="cp_id">
                <option value="">All CPs</option>
                @foreach($cps as $cp)
                    <option value="{{ $cp->id }}" {{ request('cp_id') == $cp->id ? 'selected' : '' }}>{{ $cp->cp_name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Client Name</label>
            <input type="text" name="client_name" value="{{ request('client_name') }}" placeholder="Search client...">
        </div>
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
        <a href="{{ route('adminDocuments') }}" class="doc-btn doc-btn-sm" style="background:#e2e8f0; color:var(--text);">Clear</a>
    </form>

    @php
        $totalDocs = $documents->count();
        $totalSize = $documents->sum('file_size');
        $uniqueCps = $documents->pluck('cp_id')->unique()->count();
        $uniqueClients = $documents->whereNotNull('batch_id')->pluck('batch_id')->unique()->count();
    @endphp
    <div class="doc-stats">
        <div class="doc-stat">
            <div class="doc-stat-label">Total Documents</div>
            <div class="doc-stat-value">{{ $totalDocs }}</div>
        </div>
        <div class="doc-stat">
            <div class="doc-stat-label">Channel Partners</div>
            <div class="doc-stat-value">{{ $uniqueCps }}</div>
        </div>
        <div class="doc-stat">
            <div class="doc-stat-label">Clients</div>
            <div class="doc-stat-value">{{ $uniqueClients }}</div>
        </div>
        <div class="doc-stat">
            <div class="doc-stat-label">Total Size</div>
            <div class="doc-stat-value" style="font-size:1.1rem;">{{ number_format($totalSize / 1048576, 1) }} MB</div>
        </div>
    </div>

    @php
        $grouped = $documents->whereNotNull('batch_id')->groupBy('batch_id');
        $ungrouped = $documents->whereNull('batch_id');
    @endphp

    @if($grouped->isEmpty() && $ungrouped->isEmpty())
        <div class="no-docs">No documents found.</div>
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
                <span class="cp-badge">{{ $firstDoc->channelPartner->cp_name ?? '-' }}</span>
                <span class="client-doc-count">{{ $batchDocs->count() }} doc(s)</span>
                <span style="font-size:0.72rem; color:var(--muted);">{{ $firstDoc->created_at->format('d M Y') }}</span>
                <span class="client-toggle">&#9660;</span>
            </div>
        </div>
        <div class="client-card-body">
            <table class="client-doc-list">
                <thead>
                    <tr><th>Type</th><th>File</th><th>Size</th><th>Uploaded By</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach($batchDocs as $doc)
                    <tr>
                        <td>
                            <span class="doc-type-badge">{{ $docTypes[$doc->document_type] ?? $doc->document_type }}</span>
                            @if(in_array($doc->document_type, $compulsoryTypes))<span class="doc-type-req">Required</span>@endif
                        </td>
                        <td><a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="doc-file-link">{{ Str::limit($doc->file_name, 30) }}</a></td>
                        <td><span class="doc-size">{{ number_format($doc->file_size / 1024, 0) }} KB</span></td>
                        <td style="font-size:0.75rem; color:var(--muted);">{{ $doc->uploadedByUser->name ?? '-' }}</td>
                        <td>
                            <form method="POST" action="{{ route('adminDocumentDelete', $doc->id) }}" class="d-inline delete-doc-form">
                                @csrf @method('DELETE')
                                <button type="submit" class="del-btn" title="Delete"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($batchDocs->first()->remarks)
            <div style="margin-top:8px; font-size:0.75rem; color:var(--muted);">Remarks: {{ $batchDocs->first()->remarks }}</div>
            @endif
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
                    <tr><th>CP</th><th>Type</th><th>Title</th><th>File</th><th>Size</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach($ungrouped as $doc)
                    <tr>
                        <td style="font-size:0.78rem; font-weight:700;">{{ $doc->channelPartner->cp_name ?? '-' }}</td>
                        <td><span class="doc-type-badge">{{ $docTypes[$doc->document_type] ?? $doc->document_type }}</span></td>
                        <td>{{ $doc->title }}</td>
                        <td><a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="doc-file-link">{{ Str::limit($doc->file_name, 25) }}</a></td>
                        <td><span class="doc-size">{{ number_format($doc->file_size / 1024, 0) }} KB</span></td>
                        <td>
                            <form method="POST" action="{{ route('adminDocumentDelete', $doc->id) }}" class="d-inline delete-doc-form">
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
@endsection

@section('js')
<script>
function toggleClient(header) {
    const body = header.nextElementSibling;
    const arrow = header.querySelector('.client-toggle');
    body.classList.toggle('show');
    arrow.classList.toggle('open');
}

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
