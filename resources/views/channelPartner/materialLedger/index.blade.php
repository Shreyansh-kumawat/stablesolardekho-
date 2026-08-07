@extends('layouts.adminLayout')
@section('title', 'Material Ledger')

@section('css')
<style>
    :root { --blue: #2563eb; --text: #1e293b; --muted: #64748b; --border: #e2e8f0; --white: #fff; }

    .ml-wrap { padding: 1.25rem; }
    .ml-header { display: flex; align-items: center; gap: 12px; margin-bottom: 1.25rem; }
    .ml-icon { width: 40px; height: 40px; background: var(--blue); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .ml-header h1 { font-size: 1.15rem; font-weight: 700; color: var(--text); margin: 0; }
    .ml-header p { font-size: 0.78rem; color: var(--muted); margin: 2px 0 0; }

    .ml-filters { background: var(--white); border: 1px solid var(--border); border-radius: 10px; padding: 12px 16px; display: flex; gap: 10px; flex-wrap: wrap; align-items: end; margin-bottom: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .ml-filters label { font-size: 0.75rem; font-weight: 600; color: var(--text); display: block; margin-bottom: 3px; }
    .ml-filters input { border: 1px solid var(--border); border-radius: 6px; padding: 6px 10px; font-size: 0.8rem; }
    .ml-btn { display: inline-flex; align-items: center; gap: 5px; padding: 7px 14px; border-radius: 8px; font-size: 0.82rem; font-weight: 600; border: none; cursor: pointer; }
    .ml-btn-primary { background: var(--blue); color: #fff; }
    .ml-btn-sm { padding: 4px 10px; font-size: 0.75rem; }

    .ml-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.75rem; margin-bottom: 1.25rem; }
    .ml-stat { background: var(--white); border: 1px solid var(--border); border-radius: 10px; padding: 12px 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .ml-stat-label { font-size: 0.72rem; font-weight: 600; color: var(--muted); text-transform: uppercase; }
    .ml-stat-value { font-size: 1.3rem; font-weight: 700; color: var(--text); margin-top: 2px; }

    .ml-card { background: var(--white); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .ml-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
    .ml-table thead { background: #f8fafc; }
    .ml-table th { padding: 10px 12px; font-weight: 700; color: #374151; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.5px; border-bottom: 2px solid var(--border); text-align: left; white-space: nowrap; }
    .ml-table td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; color: #374151; vertical-align: middle; }
    .ml-table tbody tr:hover { background: #f8fafc; }
    .ml-amount { font-weight: 700; color: #059669; }
    .ml-invoice-link { color: var(--blue); text-decoration: none; font-weight: 600; font-size: 0.78rem; }
    .ml-invoice-link:hover { text-decoration: underline; }
    .ml-empty { text-align: center; padding: 2.5rem 1rem; color: var(--muted); }

    @media (max-width: 768px) { .ml-card { overflow-x: auto; } .ml-stats { grid-template-columns: repeat(2, 1fr); } }
</style>
@endsection

@section('content')
<div class="ml-wrap">
    <div class="ml-header">
        <div class="ml-icon">
            <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
        </div>
        <div>
            <h1>Material Ledger</h1>
            <p>Your material records</p>
        </div>
    </div>

    <form class="ml-filters" method="GET" action="{{ route('cpMaterialLedger') }}">
        <div>
            <label>From Date</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}">
        </div>
        <div>
            <label>To Date</label>
            <input type="date" name="to_date" value="{{ request('to_date') }}">
        </div>
        <button type="submit" class="ml-btn ml-btn-primary ml-btn-sm">Filter</button>
        <a href="{{ route('cpMaterialLedger') }}" class="ml-btn ml-btn-sm" style="background:#e2e8f0; color:var(--text);">Clear</a>
    </form>

    @php
        $totalEntries = $entries->count();
        $totalAmount = $entries->sum('total_amount');
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
    </div>

    <div class="ml-card">
        <div style="overflow-x:auto;">
            <table class="ml-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Material</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th>Total</th>
                        <th>Invoice</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $entry)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($entry->entry_date)->format('d M Y') }}</td>
                        <td>{{ $entry->material_name }}</td>
                        <td>{{ $entry->quantity }} {{ $entry->unit }}</td>
                        <td>{{ number_format($entry->rate, 2) }}</td>
                        <td><span class="ml-amount">{{ number_format($entry->total_amount, 2) }}</span></td>
                        <td>
                            @if($entry->invoice_file)
                                <a href="{{ Storage::url($entry->invoice_file) }}" target="_blank" class="ml-invoice-link">
                                    {{ $entry->invoice_number ?: 'View' }}
                                </a>
                            @elseif($entry->invoice_number)
                                {{ $entry->invoice_number }}
                            @else
                                <span style="color:#94a3b8;">-</span>
                            @endif
                        </td>
                        <td>{{ $entry->remarks ?: '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="ml-empty">No material records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
