@extends('layouts.adminLayout')
@section('title', 'My Payments')

@section('css')
<style>
    :root { --blue: #2563eb; --text: #1e293b; --muted: #64748b; --border: #e2e8f0; --white: #fff; }

    .pt-wrap { padding: 1.25rem; }
    .pt-header { display: flex; align-items: center; gap: 12px; margin-bottom: 1.25rem; }
    .pt-icon { width: 40px; height: 40px; background: #059669; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .pt-header h1 { font-size: 1.15rem; font-weight: 700; color: var(--text); margin: 0; }
    .pt-header p { font-size: 0.78rem; color: var(--muted); margin: 2px 0 0; }

    .pt-filters { background: var(--white); border: 1px solid var(--border); border-radius: 10px; padding: 12px 16px; display: flex; gap: 10px; flex-wrap: wrap; align-items: end; margin-bottom: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .pt-filters label { font-size: 0.75rem; font-weight: 600; color: var(--text); display: block; margin-bottom: 3px; }
    .pt-filters select, .pt-filters input { border: 1px solid var(--border); border-radius: 6px; padding: 6px 10px; font-size: 0.8rem; }
    .pt-btn { display: inline-flex; align-items: center; gap: 5px; padding: 7px 14px; border-radius: 8px; font-size: 0.82rem; font-weight: 600; border: none; cursor: pointer; }
    .pt-btn-primary { background: var(--blue); color: #fff; }
    .pt-btn-sm { padding: 4px 10px; font-size: 0.75rem; }

    .pt-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.75rem; margin-bottom: 1.25rem; }
    .pt-stat { background: var(--white); border: 1px solid var(--border); border-radius: 10px; padding: 12px 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .pt-stat-label { font-size: 0.72rem; font-weight: 600; color: var(--muted); text-transform: uppercase; }
    .pt-stat-value { font-size: 1.3rem; font-weight: 700; color: var(--text); margin-top: 2px; }

    .pt-card { background: var(--white); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .pt-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
    .pt-table thead { background: #f8fafc; }
    .pt-table th { padding: 10px 12px; font-weight: 700; color: #374151; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.5px; border-bottom: 2px solid var(--border); text-align: left; white-space: nowrap; }
    .pt-table td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; color: #374151; vertical-align: middle; }
    .pt-table tbody tr:hover { background: #f8fafc; }
    .pt-amount { font-weight: 700; color: #059669; }
    .pt-badge { display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 0.7rem; font-weight: 600; }
    .pt-badge-verified { background: #d1fae5; color: #065f46; }
    .pt-badge-pending { background: #fef3c7; color: #92400e; }
    .pt-badge-rejected { background: #fee2e2; color: #991b1b; }
    .pt-empty { text-align: center; padding: 2.5rem 1rem; color: var(--muted); }

    @media (max-width: 768px) { .pt-card { overflow-x: auto; } .pt-stats { grid-template-columns: repeat(2, 1fr); } }
</style>
@endsection

@section('content')
<div class="pt-wrap">
    <div class="pt-header">
        <div class="pt-icon">
            <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
        </div>
        <div>
            <h1>My Payments</h1>
            <p>Your payment history</p>
        </div>
    </div>

    <form class="pt-filters" method="GET" action="{{ route('cpPayments') }}">
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
        <a href="{{ route('cpPayments') }}" class="pt-btn pt-btn-sm" style="background:#e2e8f0; color:var(--text);">Clear</a>
    </form>

    <div class="pt-stats">
        <div class="pt-stat">
            <div class="pt-stat-label">Total Paid</div>
            <div class="pt-stat-value" style="color:#059669;">{{ number_format($stats['total'], 2) }}</div>
        </div>
        <div class="pt-stat">
            <div class="pt-stat-label">Verified</div>
            <div class="pt-stat-value">{{ number_format($stats['verified'], 2) }}</div>
        </div>
        <div class="pt-stat">
            <div class="pt-stat-label">Pending</div>
            <div class="pt-stat-value" style="color:#f59e0b;">{{ number_format($stats['pending'], 2) }}</div>
        </div>
    </div>

    <div class="pt-card">
        <div style="overflow-x:auto;">
            <table class="pt-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Amount</th>
                        <th>Mode</th>
                        <th>Reference</th>
                        <th>Date</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="pt-amount">{{ number_format($p->amount, 2) }}</span></td>
                        <td>{{ ucfirst($p->payment_mode) }}</td>
                        <td>{{ $p->reference_number ?: '-' }}</td>
                        <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($p->payment_date)->format('d M Y') }}</td>
                        <td>{{ $p->cpOrder->order_id ?? '-' }}</td>
                        <td><span class="pt-badge pt-badge-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                        <td>{{ $p->remarks ?: '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="pt-empty">No payment records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
