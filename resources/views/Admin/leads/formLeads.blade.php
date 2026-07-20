@extends('layouts.adminLayout')
@section('title', 'Form Leads')
@section('page_title', 'Form Leads')
@section('css')
<style>
    .fl-wrap { padding: 1.25rem; }
    .fl-header { display: flex; align-items: center; gap: 12px; margin-bottom: 1.25rem; }
    .fl-icon { width: 40px; height: 40px; background: #f97316; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .fl-header h1 { font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 0; }
    .fl-header p { font-size: 0.78rem; color: #64748b; margin: 2px 0 0; }
    .fl-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .fl-controls { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 16px; border-bottom: 1px solid #f1f5f9; flex-wrap: wrap; }
    .fl-controls label { font-size: 0.78rem; font-weight: 600; color: #475569; }
    .fl-controls select { border: 1px solid #e2e8f0; border-radius: 6px; padding: 4px 8px; font-size: 0.8rem; }
    .fl-controls input[type="text"] { border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px 10px; font-size: 0.8rem; min-width: 200px; }
    .fl-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
    .fl-table thead { background: #f8fafc; }
    .fl-table th { padding: 10px 14px; font-weight: 700; color: #374151; text-transform: uppercase; font-size: 0.72rem; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; text-align: left; white-space: nowrap; }
    .fl-table td { padding: 10px 14px; border-bottom: 1px solid #f1f5f9; color: #374151; vertical-align: middle; }
    .fl-table tbody tr:hover { background: #f8fafc; }
    .fl-name { font-weight: 600; color: #1e293b; }
    .fl-phone { font-family: monospace; font-weight: 600; color: #334155; }
    .fl-bill { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 0.72rem; font-weight: 600; background: #fef3c7; color: #92400e; }
    .fl-date { color: #64748b; font-size: 0.78rem; }
    .fl-btn-del { background: none; border: 1px solid #fca5a5; color: #dc2626; padding: 4px 10px; border-radius: 6px; font-size: 0.72rem; font-weight: 600; cursor: pointer; }
    .fl-btn-del:hover { background: #fef2f2; }
    .fl-btn-call { display: inline-flex; align-items: center; gap: 4px; background: #059669; color: #fff; padding: 4px 10px; border-radius: 6px; font-size: 0.72rem; font-weight: 600; text-decoration: none; }
    .fl-btn-call:hover { background: #047857; color: #fff; }
    .fl-empty { text-align: center; padding: 3rem 1rem; color: #94a3b8; }
    .fl-footer { padding: 10px 16px; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; font-size: 0.75rem; color: #64748b; flex-wrap: wrap; gap: 8px; }
    .fl-pagination { display: flex; gap: 4px; }
    .fl-pagination button { padding: 4px 10px; border: 1px solid #e2e8f0; border-radius: 4px; background: #fff; font-size: 0.75rem; cursor: pointer; color: #374151; }
    .fl-pagination button.active { background: #f97316; color: #fff; border-color: #f97316; }
    .fl-pagination button:disabled { opacity: 0.4; cursor: default; }
    .fl-stat { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; background: #fff7ed; border: 1px solid #fed7aa; border-radius: 8px; font-size: 0.82rem; font-weight: 600; color: #c2410c; }
    @media(max-width:768px) {
        .fl-controls { flex-direction: column; align-items: stretch; }
        .fl-table { font-size: 0.75rem; }
        .fl-table th, .fl-table td { padding: 8px 10px; }
        .fl-footer { flex-direction: column; align-items: center; }
    }
</style>
@endsection

@section('content')
<div class="fl-wrap">
    <div class="fl-header">
        <div class="fl-icon">
            <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
        </div>
        <div>
            <h1>Form Leads</h1>
            <p>Quote requests from the homepage form</p>
        </div>
        <div style="margin-left:auto;">
            <span class="fl-stat">{{ $leads->count() }} total leads</span>
        </div>
    </div>

    <div class="fl-card">
        <div class="fl-controls">
            <div style="display:flex;align-items:center;gap:6px;">
                <label>Show</label>
                <select id="flPerPage">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="-1">All</option>
                </select>
                <label>entries</label>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                <svg width="14" height="14" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                <input type="text" id="flSearch" placeholder="Search leads...">
            </div>
        </div>

        @if($leads->count() > 0)
        <div style="overflow-x:auto;">
            <table class="fl-table" id="flTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Bill Range</th>
                        <th>PIN</th>
                        <th>City</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leads as $lead)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="fl-name">{{ $lead->customer_name }}</span></td>
                        <td><span class="fl-phone">{{ $lead->mobile_number ?? '-' }}</span></td>
                        <td><span class="fl-bill">{{ $lead->monthly_bill ?? '-' }}</span></td>
                        <td>{{ $lead->pin_code ?? '-' }}</td>
                        <td>{{ $lead->city ?? '-' }}</td>
                        <td><span class="fl-date">{{ $lead->created_at ? $lead->created_at->format('d M Y, h:i A') : '-' }}</span></td>
                        <td style="display:flex;gap:6px;align-items:center;">
                            @if($lead->mobile_number)
                            <a href="https://wa.me/91{{ $lead->mobile_number }}" target="_blank" class="fl-btn-call">
                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.75.75 0 00.917.918l4.458-1.495A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-2.326 0-4.478-.751-6.233-2.026l-.435-.326-2.636.884.884-2.636-.326-.435A9.956 9.956 0 012 12C2 6.486 6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z"/></svg>
                                WhatsApp
                            </a>
                            @endif
                            <form method="POST" action="{{ route('admin.formLead.delete', $lead->id) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="fl-btn-del" onclick="return confirm('Delete this lead?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="fl-footer" id="flFooter">
            <span id="flInfo"></span>
            <div class="fl-pagination" id="flPagination"></div>
        </div>
        @else
        <div class="fl-empty">
            <svg width="48" height="48" fill="none" stroke="#cbd5e1" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
            <p style="font-weight:600;font-size:0.9rem;margin:12px 0 0;">No leads yet</p>
            <p style="font-size:0.78rem;margin:4px 0 0;">Leads from the homepage quote form will appear here</p>
        </div>
        @endif
    </div>
</div>
@endsection

@section('js')
<script>
(function() {
    var rows = document.querySelectorAll('#flTable tbody tr');
    if (!rows.length) return;
    var allRows = Array.from(rows);
    var perPage = 10;
    var currentPage = 1;
    var filtered = allRows.slice();

    function render() {
        var start = (currentPage - 1) * perPage;
        var end = perPage === -1 ? filtered.length : start + perPage;
        var visible = filtered.slice(start, end);
        allRows.forEach(function(r) { r.style.display = 'none'; });
        visible.forEach(function(r) { r.style.display = ''; });
        var total = filtered.length;
        var showing = perPage === -1 ? total : Math.min(end, total);
        document.getElementById('flInfo').textContent = 'Showing ' + (total ? start + 1 : 0) + ' to ' + showing + ' of ' + total + ' entries';
        var pages = perPage === -1 ? 1 : Math.ceil(total / perPage);
        var pag = document.getElementById('flPagination');
        pag.innerHTML = '';
        if (pages > 1) {
            var prev = document.createElement('button');
            prev.textContent = 'Prev';
            prev.disabled = currentPage === 1;
            prev.onclick = function() { currentPage--; render(); };
            pag.appendChild(prev);
            for (var i = 1; i <= pages; i++) {
                var btn = document.createElement('button');
                btn.textContent = i;
                if (i === currentPage) btn.className = 'active';
                btn.onclick = (function(p) { return function() { currentPage = p; render(); }; })(i);
                pag.appendChild(btn);
            }
            var next = document.createElement('button');
            next.textContent = 'Next';
            next.disabled = currentPage === pages;
            next.onclick = function() { currentPage++; render(); };
            pag.appendChild(next);
        }
    }

    document.getElementById('flPerPage').addEventListener('change', function() {
        perPage = parseInt(this.value);
        currentPage = 1;
        render();
    });

    document.getElementById('flSearch').addEventListener('input', function() {
        var q = this.value.toLowerCase();
        filtered = allRows.filter(function(r) { return r.textContent.toLowerCase().indexOf(q) !== -1; });
        currentPage = 1;
        render();
    });

    render();
})();
</script>
@endsection
