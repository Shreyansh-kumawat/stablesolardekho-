@extends('layouts.adminLayout')
@section('title', 'Export Data')

@section('css')
<style>
    :root { --blue: #2563eb; --text: #1e293b; --muted: #64748b; --border: #e2e8f0; --white: #fff; }

    .ex-wrap { padding: 1.25rem; }
    .ex-header { display: flex; align-items: center; gap: 12px; margin-bottom: 1.5rem; }
    .ex-icon { width: 40px; height: 40px; background: #7c3aed; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .ex-header h1 { font-size: 1.15rem; font-weight: 700; color: var(--text); margin: 0; }
    .ex-header p { font-size: 0.78rem; color: var(--muted); margin: 2px 0 0; }

    .ex-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; }
    .ex-card { background: var(--white); border: 1px solid var(--border); border-radius: 12px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .ex-card h3 { font-size: 0.92rem; font-weight: 700; color: var(--text); margin: 0 0 4px; }
    .ex-card p { font-size: 0.78rem; color: var(--muted); margin: 0 0 12px; }
    .ex-card form { display: flex; gap: 8px; flex-wrap: wrap; align-items: end; }
    .ex-card label { font-size: 0.72rem; font-weight: 600; color: var(--text); display: block; margin-bottom: 2px; }
    .ex-card input { border: 1px solid var(--border); border-radius: 6px; padding: 5px 8px; font-size: 0.78rem; width: 130px; }

    .ex-btn { display: inline-flex; align-items: center; gap: 5px; padding: 7px 16px; border-radius: 8px; font-size: 0.82rem; font-weight: 600; border: none; cursor: pointer; background: #7c3aed; color: #fff; transition: background 0.15s; }
    .ex-btn:hover { background: #6d28d9; }
    .ex-btn svg { flex-shrink: 0; }
</style>
@endsection

@section('content')
<div class="ex-wrap">
    <div class="ex-header">
        <div class="ex-icon">
            <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
        </div>
        <div>
            <h1>Export Data</h1>
            <p>Download data as CSV files</p>
        </div>
    </div>

    <div class="ex-grid">
        <div class="ex-card">
            <h3>CP Orders</h3>
            <p>Export all channel partner orders with amounts and status</p>
            <form action="{{ route('exportCpOrders') }}" method="GET">
                <div><label>From</label><input type="date" name="from_date"></div>
                <div><label>To</label><input type="date" name="to_date"></div>
                <button type="submit" class="ex-btn">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    Export CSV
                </button>
            </form>
        </div>

        <div class="ex-card">
            <h3>Customer Orders</h3>
            <p>Export website customer orders with details</p>
            <form action="{{ route('exportCustomerOrders') }}" method="GET">
                <div><label>From</label><input type="date" name="from_date"></div>
                <div><label>To</label><input type="date" name="to_date"></div>
                <button type="submit" class="ex-btn">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    Export CSV
                </button>
            </form>
        </div>

        <div class="ex-card">
            <h3>Inventory</h3>
            <p>Export current inventory stock levels</p>
            <form action="{{ route('exportInventory') }}" method="GET">
                <button type="submit" class="ex-btn">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    Export CSV
                </button>
            </form>
        </div>

        <div class="ex-card">
            <h3>Material Ledger</h3>
            <p>Export material entries with CP details</p>
            <form action="{{ route('exportMaterialLedger') }}" method="GET">
                <div><label>From</label><input type="date" name="from_date"></div>
                <div><label>To</label><input type="date" name="to_date"></div>
                <button type="submit" class="ex-btn">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    Export CSV
                </button>
            </form>
        </div>

        <div class="ex-card">
            <h3>CP Payments</h3>
            <p>Export payment records with status</p>
            <form action="{{ route('exportPayments') }}" method="GET">
                <div><label>From</label><input type="date" name="from_date"></div>
                <div><label>To</label><input type="date" name="to_date"></div>
                <button type="submit" class="ex-btn">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    Export CSV
                </button>
            </form>
        </div>

        <div class="ex-card">
            <h3>Channel Partners</h3>
            <p>Export all channel partner details</p>
            <form action="{{ route('exportChannelPartners') }}" method="GET">
                <button type="submit" class="ex-btn">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    Export CSV
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
