@extends('layouts.adminLayout')
@section('title', 'Material Ledger')

@section('css')
<style>
    :root { --blue: #2563eb; --blue-dark: #1d4ed8; --text: #1e293b; --muted: #64748b; --border: #e2e8f0; --white: #fff; }

    .ml-wrap { padding: 1.25rem; }
    .ml-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem; }
    .ml-header-left { display: flex; align-items: center; gap: 12px; }
    .ml-icon { width: 40px; height: 40px; background: var(--blue); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .ml-header h1 { font-size: 1.15rem; font-weight: 700; color: var(--text); margin: 0; }
    .ml-header p { font-size: 0.78rem; color: var(--muted); margin: 2px 0 0; }

    .ml-btn { display: inline-flex; align-items: center; gap: 5px; padding: 7px 14px; border-radius: 8px; font-size: 0.82rem; font-weight: 600; border: none; cursor: pointer; transition: background 0.15s; }
    .ml-btn-primary { background: var(--blue); color: #fff; }
    .ml-btn-primary:hover { background: var(--blue-dark); }
    .ml-btn-danger { background: #dc2626; color: #fff; }
    .ml-btn-danger:hover { background: #b91c1c; }
    .ml-btn-sm { padding: 4px 10px; font-size: 0.75rem; }

    .ml-filters { background: var(--white); border: 1px solid var(--border); border-radius: 10px; padding: 12px 16px; display: flex; gap: 10px; flex-wrap: wrap; align-items: end; margin-bottom: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .ml-filters label { font-size: 0.75rem; font-weight: 600; color: var(--text); display: block; margin-bottom: 3px; }
    .ml-filters select, .ml-filters input { border: 1px solid var(--border); border-radius: 6px; padding: 6px 10px; font-size: 0.8rem; color: var(--text); }

    .ml-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 0.75rem; margin-bottom: 1.25rem; }
    .ml-stat { background: var(--white); border: 1px solid var(--border); border-radius: 10px; padding: 12px 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .ml-stat-label { font-size: 0.72rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; }
    .ml-stat-value { font-size: 1.3rem; font-weight: 700; color: var(--text); margin-top: 2px; }

    .ml-card { background: var(--white); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .ml-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
    .ml-table thead { background: #f8fafc; }
    .ml-table th { padding: 10px 12px; font-weight: 700; color: #374151; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.5px; border-bottom: 2px solid var(--border); text-align: left; white-space: nowrap; }
    .ml-table td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; color: #374151; vertical-align: middle; }
    .ml-table tbody tr:hover { background: #f8fafc; }
    .ml-cp-name { font-weight: 700; color: var(--text); font-size: 0.8rem; }
    .ml-amount { font-weight: 700; color: #059669; }
    .ml-invoice-link { color: var(--blue); text-decoration: none; font-weight: 600; font-size: 0.78rem; }
    .ml-invoice-link:hover { text-decoration: underline; }
    .ml-empty { text-align: center; padding: 2.5rem 1rem; color: var(--muted); }

    .modal-content { border-radius: 12px; border: 1px solid var(--border); box-shadow: 0 10px 30px rgba(0,0,0,0.12); }
    .modal-header { background: #f8f9fa; border-bottom: 1px solid var(--border); border-radius: 12px 12px 0 0; padding: 1rem 1.25rem; }
    .modal-footer { background: #f8f9fa; border-top: 1px solid var(--border); border-radius: 0 0 12px 12px; padding: 1rem 1.25rem; }
    .modal-title { font-size: 0.95rem; font-weight: 700; color: var(--text); }
    .form-label { font-size: 0.82rem; font-weight: 600; color: var(--text); margin-bottom: 4px; }
    .form-control, .form-select { border: 1px solid var(--border); border-radius: 8px; padding: 0.45rem 0.8rem; font-size: 0.84rem; color: var(--text); }
    .form-control:focus, .form-select:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.12); outline: none; }

    @media (max-width: 768px) {
        .ml-table-wrap { overflow-x: auto; }
        .ml-stats { grid-template-columns: repeat(2, 1fr); }
    }
</style>
@endsection

@section('content')
<div class="ml-wrap">
    <div class="ml-header">
        <div class="ml-header-left">
            <div class="ml-icon">
                <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
            </div>
            <div>
                <h1>CP Material Ledger</h1>
                <p>Track materials given to channel partners</p>
            </div>
        </div>
        <button class="ml-btn ml-btn-primary" data-bs-toggle="modal" data-bs-target="#addEntryModal">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Entry
        </button>
    </div>

    {{-- Filters --}}
    <form class="ml-filters" method="GET" action="{{ route('adminMaterialLedger') }}">
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
            <label>From Date</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}">
        </div>
        <div>
            <label>To Date</label>
            <input type="date" name="to_date" value="{{ request('to_date') }}">
        </div>
        <button type="submit" class="ml-btn ml-btn-primary ml-btn-sm">Filter</button>
        <a href="{{ route('adminMaterialLedger') }}" class="ml-btn ml-btn-sm" style="background:#e2e8f0; color:var(--text);">Clear</a>
    </form>

    {{-- Stats --}}
    @php
        $totalEntries = $entries->count();
        $totalAmount = $entries->sum('total_amount');
        $totalQty = $entries->sum('quantity');
        $uniqueCps = $entries->pluck('cp_id')->unique()->count();
    @endphp
    <div class="ml-stats">
        <div class="ml-stat">
            <div class="ml-stat-label">Entries</div>
            <div class="ml-stat-value">{{ $totalEntries }}</div>
        </div>
        <div class="ml-stat">
            <div class="ml-stat-label">Total Amount</div>
            <div class="ml-stat-value" style="color:#059669;">{{ number_format($totalAmount, 2) }}</div>
        </div>
        <div class="ml-stat">
            <div class="ml-stat-label">Total Quantity</div>
            <div class="ml-stat-value">{{ number_format($totalQty, 2) }}</div>
        </div>
        <div class="ml-stat">
            <div class="ml-stat-label">CPs Involved</div>
            <div class="ml-stat-value">{{ $uniqueCps }}</div>
        </div>
    </div>

    {{-- Table --}}
    <div class="ml-card">
        <div class="ml-table-wrap" style="overflow-x:auto;">
            <table class="ml-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Channel Partner</th>
                        <th>Material</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th>Total</th>
                        <th>Invoice</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $entry)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($entry->entry_date)->format('d M Y') }}</td>
                        <td><span class="ml-cp-name">{{ $entry->channelPartner->cp_name ?? '-' }}</span></td>
                        <td>{{ $entry->material_name }}</td>
                        <td>{{ $entry->quantity }} {{ $entry->unit }}</td>
                        <td>{{ number_format($entry->rate, 2) }}</td>
                        <td><span class="ml-amount">{{ number_format($entry->total_amount, 2) }}</span></td>
                        <td>
                            @if($entry->invoice_file)
                                <a href="{{ url('serve/' . $entry->invoice_file) }}" target="_blank" class="ml-invoice-link">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:middle;"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                    {{ $entry->invoice_number ?: 'View' }}
                                </a>
                            @elseif($entry->invoice_number)
                                {{ $entry->invoice_number }}
                            @else
                                <span style="color:#94a3b8;">-</span>
                            @endif
                        </td>
                        <td style="white-space:nowrap;">
                            <button class="ml-btn ml-btn-primary ml-btn-sm edit-entry-btn"
                                data-id="{{ $entry->id }}"
                                data-material="{{ $entry->material_name }}"
                                data-quantity="{{ $entry->quantity }}"
                                data-unit="{{ $entry->unit }}"
                                data-rate="{{ $entry->rate }}"
                                data-date="{{ $entry->entry_date }}"
                                data-invoice="{{ $entry->invoice_number }}"
                                data-remarks="{{ $entry->remarks }}">
                                <i class="fas fa-pen" style="font-size:0.7rem;"></i>
                            </button>
                            <form action="{{ route('adminMaterialLedgerDelete', $entry->id) }}" method="POST" style="display:inline;" class="delete-entry-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="ml-btn ml-btn-danger ml-btn-sm delete-entry-btn">
                                    <i class="fas fa-trash" style="font-size:0.7rem;"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="ml-empty">
                            <svg width="32" height="32" fill="none" stroke="#94a3b8" stroke-width="1.5" viewBox="0 0 24 24" style="display:block;margin:0 auto 8px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                            No entries found. Click "Add Entry" to get started.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Entry Modal --}}
<div class="modal fade" id="addEntryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2" style="color:var(--blue);"></i>Add Material Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('adminMaterialLedgerStore') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" style="display:flex; flex-direction:column; gap:0.85rem; padding:1.25rem;">
                    <div>
                        <label class="form-label">Channel Partner <span style="color:#e74c3c;">*</span></label>
                        <select class="form-select" name="cp_id" required>
                            <option value="">Select CP</option>
                            @foreach($cps as $cp)
                            <option value="{{ $cp->id }}">{{ $cp->cp_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Material Name <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control" name="material_name" required placeholder="e.g. Solar Panel 545W" maxlength="255">
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px;">
                        <div>
                            <label class="form-label">Quantity <span style="color:#e74c3c;">*</span></label>
                            <input type="number" class="form-control" name="quantity" step="0.01" min="0.01" required placeholder="0" id="addQty">
                        </div>
                        <div>
                            <label class="form-label">Unit</label>
                            <select class="form-select" name="unit">
                                <option>Piece</option><option>Kilogram</option><option>Meter</option><option>Liter</option><option>Box</option><option>Set</option><option>KW</option><option>Watt</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Rate <span style="color:#e74c3c;">*</span></label>
                            <input type="number" class="form-control" name="rate" step="0.01" min="0" required placeholder="0.00" id="addRate">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Total Amount</label>
                        <input type="text" class="form-control" id="addTotal" disabled style="background:#f1f5f9; font-weight:700; color:#059669;">
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                        <div>
                            <label class="form-label">Date <span style="color:#e74c3c;">*</span></label>
                            <input type="date" class="form-control" name="entry_date" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <label class="form-label">Invoice Number</label>
                            <input type="text" class="form-control" name="invoice_number" placeholder="e.g. INV-001" maxlength="100">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Invoice File <span style="color:#94a3b8; font-weight:400;">(PDF/Image, max 5MB)</span></label>
                        <input type="file" class="form-control" name="invoice_file" accept=".pdf,.jpg,.jpeg,.png,.webp">
                    </div>
                    <div>
                        <label class="form-label">Remarks</label>
                        <textarea class="form-control" name="remarks" rows="2" placeholder="Optional notes..." maxlength="500"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="gap:8px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size:0.85rem;">Cancel</button>
                    <button type="submit" class="ml-btn ml-btn-primary">
                        <i class="fas fa-plus me-1"></i>Add Entry
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Entry Modal --}}
<div class="modal fade" id="editEntryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-pen me-2" style="color:var(--blue);"></i>Edit Material Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editEntryForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" style="display:flex; flex-direction:column; gap:0.85rem; padding:1.25rem;">
                    <div>
                        <label class="form-label">Material Name <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control" name="material_name" id="editMaterial" required maxlength="255">
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px;">
                        <div>
                            <label class="form-label">Quantity <span style="color:#e74c3c;">*</span></label>
                            <input type="number" class="form-control" name="quantity" step="0.01" min="0.01" required id="editQty">
                        </div>
                        <div>
                            <label class="form-label">Unit</label>
                            <select class="form-select" name="unit" id="editUnit">
                                <option>Piece</option><option>Kilogram</option><option>Meter</option><option>Liter</option><option>Box</option><option>Set</option><option>KW</option><option>Watt</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Rate <span style="color:#e74c3c;">*</span></label>
                            <input type="number" class="form-control" name="rate" step="0.01" min="0" required id="editRate">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Total Amount</label>
                        <input type="text" class="form-control" id="editTotal" disabled style="background:#f1f5f9; font-weight:700; color:#059669;">
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                        <div>
                            <label class="form-label">Date <span style="color:#e74c3c;">*</span></label>
                            <input type="date" class="form-control" name="entry_date" id="editDate" required>
                        </div>
                        <div>
                            <label class="form-label">Invoice Number</label>
                            <input type="text" class="form-control" name="invoice_number" id="editInvoice" maxlength="100">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Invoice File <span style="color:#94a3b8; font-weight:400;">(leave empty to keep existing)</span></label>
                        <input type="file" class="form-control" name="invoice_file" accept=".pdf,.jpg,.jpeg,.png,.webp">
                    </div>
                    <div>
                        <label class="form-label">Remarks</label>
                        <textarea class="form-control" name="remarks" id="editRemarks" rows="2" maxlength="500"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="gap:8px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size:0.85rem;">Cancel</button>
                    <button type="submit" class="ml-btn ml-btn-primary">
                        <i class="fas fa-save me-1"></i>Update Entry
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    function calcTotal(qtyEl, rateEl, totalEl) {
        var q = parseFloat($(qtyEl).val()) || 0;
        var r = parseFloat($(rateEl).val()) || 0;
        $(totalEl).val((q * r).toFixed(2));
    }
    $('#addQty, #addRate').on('input', function() { calcTotal('#addQty', '#addRate', '#addTotal'); });
    $('#editQty, #editRate').on('input', function() { calcTotal('#editQty', '#editRate', '#editTotal'); });

    $(document).on('click', '.edit-entry-btn', function() {
        var btn = $(this);
        var id = btn.data('id');
        $('#editEntryForm').attr('action', '{{ url("admin/material-ledger") }}/' + id + '/update');
        $('#editMaterial').val(btn.data('material'));
        $('#editQty').val(btn.data('quantity'));
        $('#editUnit').val(btn.data('unit'));
        $('#editRate').val(btn.data('rate'));
        $('#editDate').val(btn.data('date'));
        $('#editInvoice').val(btn.data('invoice'));
        $('#editRemarks').val(btn.data('remarks'));
        calcTotal('#editQty', '#editRate', '#editTotal');
        new bootstrap.Modal(document.getElementById('editEntryModal')).show();
    });

    $(document).on('click', '.delete-entry-btn', function() {
        var form = $(this).closest('.delete-entry-form');
        Swal.fire({
            title: 'Delete Entry?',
            text: 'This cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel'
        }).then(function(result) {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
@endsection
