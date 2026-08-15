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

    .fd-revenue-card { background: var(--white); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .fd-rev-header { padding: 12px 16px; font-weight: 700; font-size: 0.88rem; color: #059669; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 8px; }
    .fd-rev-row { display: flex; justify-content: space-between; padding: 10px 16px; border-bottom: 1px solid #f1f5f9; font-size: 0.84rem; }
    .fd-rev-row:last-child { border-bottom: none; }
    .fd-rev-label { color: #374151; }
    .fd-rev-value { font-weight: 700; color: #059669; }
    .fd-rev-total { background: #f0fdf4; font-weight: 700; font-size: 0.9rem; }
    .fd-rev-total .fd-rev-label { color: var(--text); }
    .fd-rev-total .fd-rev-value { font-size: 1rem; }

    @media (max-width: 768px) {
        .fd-stats { grid-template-columns: repeat(2, 1fr); }
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

    {{-- Total Revenue Card --}}
    <div class="fd-section">
        <div class="fd-revenue-card">
            <div class="fd-rev-header">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>
                Total Revenue
            </div>
            <div class="fd-rev-row">
                <span class="fd-rev-label">Sales Revenue (Delivered Orders)</span>
                <span class="fd-rev-value">{{ number_format($revenue['sales'], 2) }}</span>
            </div>
            <div class="fd-rev-row">
                <span class="fd-rev-label">Customer Orders (Paid)</span>
                <span class="fd-rev-value">{{ number_format($revenue['customer_orders'], 2) }}</span>
            </div>
            <div class="fd-rev-row fd-rev-total">
                <span class="fd-rev-label">Total Revenue</span>
                <span class="fd-rev-value">{{ number_format($revenue['total'], 2) }}</span>
            </div>
        </div>
    </div>

    {{-- CP Orders --}}
    <div class="fd-section">
        <div class="fd-section-title">CP Orders</div>
        <div class="fd-stats">
            <div class="fd-stat">
                <div class="fd-stat-label">Total Orders</div>
                <div class="fd-stat-value" style="color:#059669;">{{ $cpOrderStats['count'] }}</div>
            </div>
            <div class="fd-stat">
                <div class="fd-stat-label">Delivered / Completed</div>
                <div class="fd-stat-value">{{ $cpOrderStats['delivered_count'] }}</div>
            </div>
            <div class="fd-stat">
                <div class="fd-stat-label">Pending</div>
                <div class="fd-stat-value" style="color:#f59e0b;">{{ $cpOrderStats['pending_count'] }}</div>
            </div>
        </div>
    </div>

    {{-- Customer Orders --}}
    <div class="fd-section">
        <div class="fd-section-title">Customer Orders</div>
        <div class="fd-stats">
            <div class="fd-stat">
                <div class="fd-stat-label">Total Value</div>
                <div class="fd-stat-value" style="color:#059669;">{{ number_format($custOrderStats['total'], 2) }}</div>
                <div class="fd-stat-sub">{{ $custOrderStats['count'] }} orders</div>
            </div>
            <div class="fd-stat">
                <div class="fd-stat-label">Paid</div>
                <div class="fd-stat-value">{{ number_format($custOrderStats['paid'], 2) }}</div>
            </div>
        </div>
    </div>

    {{-- Payments Received --}}
    <div class="fd-section">
        <div class="fd-section-title">CP Payments</div>
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

    {{-- Inventory Purchase Cost --}}
    <div class="fd-section">
        <div class="fd-section-title">Inventory Purchase Cost</div>
        <div class="fd-stats">
            <div class="fd-stat">
                <div class="fd-stat-label">Total Purchase Cost</div>
                <div class="fd-stat-value" style="color:#dc2626;">{{ number_format($inventoryCostStats['total'], 2) }}</div>
                <div class="fd-stat-sub">{{ $inventoryCostStats['entries'] }} purchase entries</div>
            </div>
        </div>
    </div>

    {{-- Material Expenses --}}
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
</div>
@endsection
