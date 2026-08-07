@extends('layouts.adminLayout')
@section('title', 'Payment Tracking')

@section('css')
<style>
    :root { --blue: #2563eb; --blue-dark: #1d4ed8; --text: #1e293b; --muted: #64748b; --border: #e2e8f0; --white: #fff; }

    .pt-wrap { padding: 1.25rem; }
    .pt-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem; }
    .pt-header-left { display: flex; align-items: center; gap: 12px; }
    .pt-icon { width: 40px; height: 40px; background: #059669; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .pt-header h1 { font-size: 1.15rem; font-weight: 700; color: var(--text); margin: 0; }
    .pt-header p { font-size: 0.78rem; color: var(--muted); margin: 2px 0 0; }

    .pt-btn { display: inline-flex; align-items: center; gap: 5px; padding: 7px 14px; border-radius: 8px; font-size: 0.82rem; font-weight: 600; border: none; cursor: pointer; transition: background 0.15s; }
    .pt-btn-primary { background: var(--blue); color: #fff; }
    .pt-btn-primary:hover { background: var(--blue-dark); }
    .pt-btn-success { background: #059669; color: #fff; }
    .pt-btn-success:hover { background: #047857; }
    .pt-btn-danger { background: #dc2626; color: #fff; }
    .pt-btn-danger:hover { background: #b91c1c; }
    .pt-btn-warning { background: #f59e0b; color: #fff; }
    .pt-btn-sm { padding: 4px 10px; font-size: 0.75rem; }

    .pt-filters { background: var(--white); border: 1px solid var(--border); border-radius: 10px; padding: 12px 16px; display: flex; gap: 10px; flex-wrap: wrap; align-items: end; margin-bottom: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .pt-filters label { font-size: 0.75rem; font-weight: 600; color: var(--text); display: block; margin-bottom: 3px; }
    .pt-filters select, .pt-filters input { border: 1px solid var(--border); border-radius: 6px; padding: 6px 10px; font-size: 0.8rem; color: var(--text); }

    .pt-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 0.75rem; margin-bottom: 1.25rem; }
    .pt-stat { background: var(--white); border: 1px solid var(--border); border-radius: 10px; padding: 12px 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .pt-stat-label { font-size: 0.72rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; }
    .pt-stat-value { font-size: 1.3rem; font-weight: 700; color: var(--text); margin-top: 2px; }

    .pt-card { background: var(--white); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .pt-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
    .pt-table thead { background: #f8fafc; }
    .pt-table th { padding: 10px 12px; font-weight: 700; color: #374151; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.5px; border-bottom: 2px solid var(--border); text-align: left; white-space: nowrap; }
    .pt-table td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; color: #374151; vertical-align: middle; }
    .pt-table tbody tr:hover { background: #f8fafc; }
    .pt-cp-name { font-weight: 700; color: var(--text); font-size: 0.8rem; }
    .pt-amount { font-weight: 700; color: #059669; }
    .pt-badge { display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 0.7rem; font-weight: 600; }
    .pt-badge-verified { background: #d1fae5; color: #065f46; }
    .pt-badge-pending { background: #fef3c7; color: #92400e; }
    .pt-badge-rejected { background: #fee2e2; color: #991b1b; }
    .pt-empty { text-align: center; padding: 2.5rem 1rem; color: var(--muted); }

    .modal-content { border-radius: 12px; border: 1px solid var(--border); box-shadow: 0 10px 30px rgba(0,0,0,0.12); }
    .modal-header { background: #f8f9fa; border-bottom: 1px solid var(--border); border-radius: 12px 12px 0 0; padding: 1rem 1.25rem; }
    .modal-footer { background: #f8f9fa; border-top: 1px solid var(--border); border-radius: 0 0 12px 12px; padding: 1rem 1.25rem; }
    .modal-title { font-size: 0.95rem; font-weight: 700; color: var(--text); }
    .form-label { font-size: 0.82rem; font-weight: 600; color: var(--text); margin-bottom: 4px; }
    .form-control, .form-select { border: 1px solid var(--border); border-radius: 8px; padding: 0.45rem 0.8rem; font-size: 0.84rem; color: var(--text); }
    .form-control:focus, .form-select:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.12); outline: none; }

    @media (max-width: 768px) {
        .pt-card { overflow-x: auto; }
        .pt-stats { grid-template-columns: repeat(2, 1fr); }
    }
</style>
@endsection

@section('content')
<div class="pt-wrap">
    <div class="pt-header">
        <div class="pt-header-left">
            <div class="pt-icon">
                <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
            </div>
            <div>
                <h1>Payment Tracking</h1>
                <p>Track and manage CP payments</p>
            </div>
        </div>
        <button class="pt-btn pt-btn-success" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Record Payment
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" style="font-size:0.84rem; border-radius:10px; margin-bottom:1rem;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size:0.65rem;"></button>
        </div>
    @endif

    <form class="pt-filters" method="GET" action="{{ route('adminPayments') }}">
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
            <label>Status</label>
            <select name="status">
                <option value="">All</option>
                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
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
        <button type="submit" class="pt-btn pt-btn-primary pt-btn-sm">Filter</button>
        <a href="{{ route('adminPayments') }}" class="pt-btn pt-btn-sm" style="background:#e2e8f0; color:var(--text);">Clear</a>
    </form>

    <div class="pt-stats">
        <div class="pt-stat">
            <div class="pt-stat-label">Total</div>
            <div class="pt-stat-value">{{ number_format($stats['total'], 2) }}</div>
        </div>
        <div class="pt-stat">
            <div class="pt-stat-label">Verified</div>
            <div class="pt-stat-value" style="color:#059669;">{{ number_format($stats['verified'], 2) }}</div>
        </div>
        <div class="pt-stat">
            <div class="pt-stat-label">Pending</div>
            <div class="pt-stat-value" style="color:#f59e0b;">{{ number_format($stats['pending'], 2) }}</div>
        </div>
        <div class="pt-stat">
            <div class="pt-stat-label">Rejected</div>
            <div class="pt-stat-value" style="color:#dc2626;">{{ number_format($stats['rejected'], 2) }}</div>
        </div>
    </div>

    <div class="pt-card">
        <div style="overflow-x:auto;">
            <table class="pt-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>CP</th>
                        <th>Amount</th>
                        <th>Mode</th>
                        <th>Reference</th>
                        <th>Date</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="pt-cp-name">{{ $p->channelPartner->cp_name ?? '-' }}</span></td>
                        <td><span class="pt-amount">{{ number_format($p->amount, 2) }}</span></td>
                        <td>{{ ucfirst($p->payment_mode) }}</td>
                        <td>{{ $p->reference_number ?: '-' }}</td>
                        <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($p->payment_date)->format('d M Y') }}</td>
                        <td>{{ $p->cpOrder->order_id ?? '-' }}</td>
                        <td>
                            <span class="pt-badge pt-badge-{{ $p->status }}">{{ ucfirst($p->status) }}</span>
                        </td>
                        <td>{{ Str::limit($p->remarks, 30) ?: '-' }}</td>
                        <td style="white-space:nowrap;">
                            @if($p->status === 'pending')
                            <form method="POST" action="{{ route('adminPaymentVerify', $p->id) }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="action" value="verify">
                                <button type="submit" class="pt-btn pt-btn-success pt-btn-sm" title="Verify">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('adminPaymentVerify', $p->id) }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="pt-btn pt-btn-danger pt-btn-sm" title="Reject">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('adminPaymentDelete', $p->id) }}" class="d-inline delete-payment-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="pt-btn pt-btn-sm" style="background:#e2e8f0;" title="Delete">
                                    <svg width="14" height="14" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="pt-empty">No payments recorded.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('adminPaymentStore') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Record Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:1.25rem;">
                    <div class="mb-3">
                        <label class="form-label">Channel Partner *</label>
                        <select name="cp_id" id="paymentCpSelect" class="form-select" required>
                            <option value="">Select CP</option>
                            @foreach($cps as $cp)
                                <option value="{{ $cp->id }}">{{ $cp->cp_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Link to Order <small style="color:var(--muted);">(optional)</small></label>
                        <select name="cp_order_id" id="paymentOrderSelect" class="form-select">
                            <option value="">No specific order</option>
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Amount *</label>
                            <input type="number" name="amount" class="form-control" step="0.01" min="0.01" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Payment Date *</label>
                            <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Payment Mode *</label>
                            <select name="payment_mode" class="form-select" required>
                                <option value="cash">Cash</option>
                                <option value="upi">UPI</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cheque">Cheque</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Reference No.</label>
                            <input type="text" name="reference_number" class="form-control" placeholder="Txn/Cheque No.">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Screenshot <small style="color:var(--muted);">(optional)</small></label>
                        <input type="file" name="screenshot" class="form-control" accept=".jpg,.jpeg,.png,.webp,.pdf">
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="Optional notes"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="pt-btn pt-btn-sm" style="background:#e2e8f0; color:var(--text);" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="pt-btn pt-btn-success">Save Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('paymentCpSelect').addEventListener('change', function() {
    const sel = document.getElementById('paymentOrderSelect');
    sel.innerHTML = '<option value="">Loading...</option>';
    if (!this.value) { sel.innerHTML = '<option value="">No specific order</option>'; return; }
    fetch('/admin/cp-payment-orders/' + this.value)
        .then(r => r.json())
        .then(orders => {
            sel.innerHTML = '<option value="">No specific order</option>';
            orders.forEach(o => {
                sel.innerHTML += `<option value="${o.id}">${o.order_id} - ₹${o.grand_total || 0} (${o.status})</option>`;
            });
        });
});

document.querySelectorAll('.delete-payment-form').forEach(f => {
    f.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Delete Payment?',
            text: 'This cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Yes, delete'
        }).then(r => { if (r.isConfirmed) f.submit(); });
    });
});
</script>
@endsection
