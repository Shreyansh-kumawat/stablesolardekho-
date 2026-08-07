@extends('layouts.adminLayout')
@section('title', 'Financial Dashboard')

@section('css')
<style>
    :root { --blue: #2563eb; --text: #1e293b; --muted: #64748b; --border: #e2e8f0; --white: #fff; }

    .fd-wrap { padding: 1.25rem; }
    .fd-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem; }
    .fd-header-left { display: flex; align-items: center; gap: 12px; }
    .fd-icon { width: 40px; height: 40px; background: #0891b2; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .fd-header h1 { font-size: 1.15rem; font-weight: 700; color: var(--text); margin: 0; }
    .fd-header p { font-size: 0.78rem; color: var(--muted); margin: 2px 0 0; }

    .fd-period { display: flex; gap: 4px; flex-wrap: wrap; }
    .fd-period a { padding: 5px 12px; border-radius: 6px; font-size: 0.78rem; font-weight: 600; color: var(--muted); text-decoration: none; border: 1px solid var(--border); }
    .fd-period a.active { background: var(--blue); color: #fff; border-color: var(--blue); }

    .fd-section { margin-bottom: 1.5rem; }
    .fd-section-title { font-size: 0.85rem; font-weight: 700; color: var(--text); margin: 0 0 0.75rem; padding-bottom: 6px; border-bottom: 2px solid var(--border); }

    .fd-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 0.75rem; }
    .fd-stat { background: var(--white); border: 1px solid var(--border); border-radius: 10px; padding: 14px 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .fd-stat-label { font-size: 0.72rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; }
    .fd-stat-value { font-size: 1.4rem; font-weight: 700; color: var(--text); margin-top: 3px; }
    .fd-stat-sub { font-size: 0.72rem; color: var(--muted); margin-top: 2px; }

    .fd-card { background: var(--white); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .fd-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
    .fd-table thead { background: #f8fafc; }
    .fd-table th { padding: 10px 12px; font-weight: 700; color: #374151; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.5px; border-bottom: 2px solid var(--border); text-align: left; white-space: nowrap; }
    .fd-table td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; color: #374151; }
    .fd-table tbody tr:hover { background: #f8fafc; }
    .fd-cp-name { font-weight: 700; color: var(--text); }

    .fd-bar-wrap { padding: 1.25rem; }
    .fd-bar-row { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
    .fd-bar-label { font-size: 0.75rem; font-weight: 600; color: var(--text); width: 70px; text-align: right; flex-shrink: 0; }
    .fd-bar-track { flex: 1; height: 22px; background: #f1f5f9; border-radius: 6px; overflow: hidden; }
    .fd-bar-fill { height: 100%; border-radius: 6px; display: flex; align-items: center; padding-left: 8px; font-size: 0.7rem; font-weight: 700; color: #fff; min-width: fit-content; }
    .fd-bar-value { font-size: 0.75rem; font-weight: 600; color: var(--text); width: 80px; flex-shrink: 0; }

    @media (max-width: 768px) {
        .fd-stats { grid-template-columns: repeat(2, 1fr); }
        .fd-card { overflow-x: auto; }
    }
</style>
@endsection

@section('content')
<div class="fd-wrap">
    <div class="fd-header">
        <div class="fd-header-left">
            <div class="fd-icon">
                <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
            </div>
            <div>
                <h1>Financial Dashboard</h1>
                <p>Overview of all financial data</p>
            </div>
        </div>
        <div class="fd-period">
            @foreach(['all' => 'All Time', 'today' => 'Today', 'week' => 'This Week', 'month' => 'This Month', 'year' => 'This Year'] as $key => $label)
                <a href="{{ route('adminFinancialDashboard', ['period' => $key]) }}" class="{{ $period == $key ? 'active' : '' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    <div class="fd-section">
        <div class="fd-section-title">CP Orders</div>
        <div class="fd-stats">
            <div class="fd-stat">
                <div class="fd-stat-label">Total Value</div>
                <div class="fd-stat-value" style="color:#059669;">{{ number_format($cpOrderStats['total'], 2) }}</div>
                <div class="fd-stat-sub">{{ $cpOrderStats['count'] }} orders</div>
            </div>
            <div class="fd-stat">
                <div class="fd-stat-label">Completed</div>
                <div class="fd-stat-value">{{ number_format($cpOrderStats['completed'], 2) }}</div>
            </div>
            <div class="fd-stat">
                <div class="fd-stat-label">Pending</div>
                <div class="fd-stat-value" style="color:#f59e0b;">{{ number_format($cpOrderStats['pending'], 2) }}</div>
            </div>
        </div>
    </div>

    <div class="fd-section">
        <div class="fd-section-title">Customer Orders</div>
        <div class="fd-stats">
            <div class="fd-stat">
                <div class="fd-stat-label">Total Revenue</div>
                <div class="fd-stat-value" style="color:#059669;">{{ number_format($custOrderStats['total'], 2) }}</div>
                <div class="fd-stat-sub">{{ $custOrderStats['count'] }} orders</div>
            </div>
            <div class="fd-stat">
                <div class="fd-stat-label">Paid</div>
                <div class="fd-stat-value">{{ number_format($custOrderStats['paid'], 2) }}</div>
            </div>
        </div>
    </div>

    <div class="fd-section">
        <div class="fd-section-title">Payments Received</div>
        <div class="fd-stats">
            <div class="fd-stat">
                <div class="fd-stat-label">Verified</div>
                <div class="fd-stat-value" style="color:#059669;">{{ number_format($paymentStats['received'], 2) }}</div>
                <div class="fd-stat-sub">{{ $paymentStats['count'] }} entries</div>
            </div>
            <div class="fd-stat">
                <div class="fd-stat-label">Pending Verification</div>
                <div class="fd-stat-value" style="color:#f59e0b;">{{ number_format($paymentStats['pending'], 2) }}</div>
            </div>
        </div>
    </div>

    <div class="fd-section">
        <div class="fd-section-title">Material Expenses</div>
        <div class="fd-stats">
            <div class="fd-stat">
                <div class="fd-stat-label">Total Spent</div>
                <div class="fd-stat-value" style="color:#dc2626;">{{ number_format($materialStats['total_spent'], 2) }}</div>
                <div class="fd-stat-sub">{{ $materialStats['entries'] }} entries</div>
            </div>
        </div>
    </div>

    @if($monthlyRevenue->count())
    <div class="fd-section">
        <div class="fd-section-title">Monthly CP Revenue</div>
        <div class="fd-card">
            <div class="fd-bar-wrap">
                @php $maxRev = $monthlyRevenue->max('total') ?: 1; @endphp
                @foreach($monthlyRevenue as $m)
                <div class="fd-bar-row">
                    <div class="fd-bar-label">{{ \Carbon\Carbon::parse($m->month . '-01')->format('M Y') }}</div>
                    <div class="fd-bar-track">
                        <div class="fd-bar-fill" style="width: {{ max(($m->total / $maxRev) * 100, 5) }}%; background: #2563eb;">
                            {{ number_format($m->total, 0) }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @if($topCps->count())
    <div class="fd-section">
        <div class="fd-section-title">Top Channel Partners</div>
        <div class="fd-card">
            <div style="overflow-x:auto;">
                <table class="fd-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>CP Name</th>
                            <th>Total Orders</th>
                            <th>Total Paid</th>
                            <th>Outstanding</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topCps as $cp)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="fd-cp-name">{{ $cp->cp_name }}</span></td>
                            <td style="font-weight:700; color:#059669;">{{ number_format($cp->total_orders, 2) }}</td>
                            <td>{{ number_format($cp->total_paid, 2) }}</td>
                            <td style="color: {{ ($cp->total_orders - $cp->total_paid) > 0 ? '#dc2626' : '#059669' }}; font-weight:700;">
                                {{ number_format($cp->total_orders - $cp->total_paid, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
