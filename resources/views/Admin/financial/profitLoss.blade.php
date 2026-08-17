@extends('layouts.adminLayout')
@section('title', 'Profit & Loss')

@section('css')
<style>
    :root { --blue: #2563eb; --text: #1e293b; --muted: #64748b; --border: #e2e8f0; --white: #fff; }

    .pl-wrap { padding: 1.25rem; }
    .pl-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem; }
    .pl-header-left { display: flex; align-items: center; gap: 12px; }
    .pl-icon { width: 40px; height: 40px; background: #7c3aed; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .pl-header h1 { font-size: 1.15rem; font-weight: 700; color: var(--text); margin: 0; }
    .pl-header p { font-size: 0.78rem; color: var(--muted); margin: 2px 0 0; }

    .pl-period { display: flex; gap: 4px; flex-wrap: wrap; }
    .pl-period a { padding: 5px 12px; border-radius: 6px; font-size: 0.78rem; font-weight: 600; color: var(--muted); text-decoration: none; border: 1px solid var(--border); }
    .pl-period a.active { background: #7c3aed; color: #fff; border-color: #7c3aed; }

    .pl-net { background: var(--white); border: 1px solid var(--border); border-radius: 14px; padding: 1.5rem 2rem; text-align: center; margin-bottom: 1.5rem; box-shadow: 0 2px 6px rgba(0,0,0,0.06); }
    .pl-net-label { font-size: 0.85rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; }
    .pl-net-value { font-size: 2.2rem; font-weight: 800; margin-top: 4px; }

    .pl-section { margin-bottom: 1.25rem; }
    .pl-section-card { background: var(--white); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .pl-section-header { padding: 12px 16px; font-weight: 700; font-size: 0.88rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 8px; }

    .pl-row { display: flex; justify-content: space-between; padding: 10px 16px; border-bottom: 1px solid #f1f5f9; font-size: 0.84rem; }
    .pl-row:last-child { border-bottom: none; }
    .pl-row-label { color: #374151; }
    .pl-row-value { font-weight: 700; color: var(--text); }
    .pl-row-total { background: #f8fafc; font-weight: 700; font-size: 0.88rem; }
    .pl-row-total .pl-row-label { color: var(--text); }

    .pl-outstanding { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.75rem; margin-top: 1.25rem; }
    .pl-out-card { background: var(--white); border: 1px solid var(--border); border-radius: 10px; padding: 14px 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .pl-out-label { font-size: 0.72rem; font-weight: 600; color: var(--muted); text-transform: uppercase; }
    .pl-out-value { font-size: 1.3rem; font-weight: 700; color: #f59e0b; margin-top: 3px; }
</style>
@endsection

@section('content')
<div class="pl-wrap">
    <div class="pl-header">
        <div class="pl-header-left">
            <div class="pl-icon">
                <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h1>Profit & Loss Overview</h1>
                <p>Revenue, expenses, and net position</p>
            </div>
        </div>
        <div class="pl-period">
            @foreach(['all' => 'All Time', 'today' => 'Today', 'week' => 'This Week', 'month' => 'This Month', 'year' => 'This Year'] as $key => $label)
                <a href="{{ route('adminProfitLoss', ['period' => $key]) }}" class="{{ $period == $key ? 'active' : '' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    <div class="pl-net">
        <div class="pl-net-label">Net Profit / Loss</div>
        <div class="pl-net-value" style="color: {{ $profitLoss >= 0 ? '#059669' : '#dc2626' }};">
            {{ $profitLoss >= 0 ? '+' : '' }}{{ number_format($profitLoss, 2) }}
        </div>
    </div>

    <div class="pl-section">
        <div class="pl-section-card">
            <div class="pl-section-header" style="color:#059669;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>
                Revenue (Income)
            </div>
            <div class="pl-row">
                <span class="pl-row-label">Sales Revenue (Delivered Orders)</span>
                <span class="pl-row-value" style="color:#059669;">{{ number_format($revenue['sales'], 2) }}</span>
            </div>
            <div class="pl-row">
                <span class="pl-row-label">Customer Orders (Paid)</span>
                <span class="pl-row-value" style="color:#059669;">{{ number_format($revenue['customer_orders'], 2) }}</span>
            </div>
            <div class="pl-row pl-row-total">
                <span class="pl-row-label">Total Revenue</span>
                <span class="pl-row-value" style="color:#059669; font-size:1rem;">{{ number_format($revenue['total'], 2) }}</span>
            </div>
        </div>
    </div>

    <div class="pl-section">
        <div class="pl-section-card">
            <div class="pl-section-header" style="color:#dc2626;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6L9 12.75l4.286-4.286a11.948 11.948 0 014.306 6.43l.776 2.898m0 0l3.182-5.511m-3.182 5.51l-5.511-3.181"/></svg>
                Expenses
            </div>
            <div class="pl-row">
                <span class="pl-row-label">Inventory Purchase Cost</span>
                <span class="pl-row-value" style="color:#dc2626;">{{ number_format($expenses['inventory_purchase'], 2) }}</span>
            </div>
            <div class="pl-row">
                <span class="pl-row-label">Material Costs (Allocated to CPs)</span>
                <span class="pl-row-value" style="color:#dc2626;">{{ number_format($expenses['material_cost'], 2) }}</span>
            </div>
            <div class="pl-row">
                <span class="pl-row-label">Wallet Transfers to CPs</span>
                <span class="pl-row-value" style="color:#dc2626;">{{ number_format($expenses['wallet_transfers'], 2) }}</span>
            </div>
            <div class="pl-row pl-row-total">
                <span class="pl-row-label">Total Expenses</span>
                <span class="pl-row-value" style="color:#dc2626; font-size:1rem;">{{ number_format($expenses['total'], 2) }}</span>
            </div>
        </div>
    </div>

    <div class="pl-outstanding">
        <div class="pl-out-card">
            <div class="pl-out-label">CP Orders Pending</div>
            <div class="pl-out-value">{{ $outstanding['cp_pending'] }} orders</div>
        </div>
        <div class="pl-out-card">
            <div class="pl-out-label">Customer Orders Unpaid</div>
            <div class="pl-out-value">{{ number_format($outstanding['customer_pending'], 2) }}</div>
        </div>
        <div class="pl-out-card">
            <div class="pl-out-label">Total Outstanding</div>
            <div class="pl-out-value" style="color:#dc2626;">{{ number_format($outstanding['total'], 2) }}</div>
        </div>
    </div>
</div>
@endsection
