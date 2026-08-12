@extends('layouts.adminLayout')
@section('title', 'Export Data')

@section('css')
<style>
    :root { --blue: #2563eb; --blue-dark: #1d4ed8; --text: #1e293b; --muted: #64748b; --border: #e2e8f0; --white: #fff; --purple: #7c3aed; }

    .ex-wrap { padding: 1.25rem; }
    .ex-header { display: flex; align-items: center; gap: 12px; margin-bottom: 1.25rem; }
    .ex-icon { width: 40px; height: 40px; background: var(--purple); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .ex-header h1 { font-size: 1.15rem; font-weight: 700; color: var(--text); margin: 0; }
    .ex-header p { font-size: 0.78rem; color: var(--muted); margin: 2px 0 0; }

    .ex-tabs { display: flex; gap: 4px; border-bottom: 2px solid var(--border); margin-bottom: 1rem; overflow-x: auto; }
    .ex-tab { padding: 8px 16px; font-size: 0.8rem; font-weight: 600; color: var(--muted); background: none; border: none; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -2px; white-space: nowrap; transition: all 0.15s; }
    .ex-tab:hover { color: var(--text); }
    .ex-tab.active { color: var(--purple); border-bottom-color: var(--purple); }

    .ex-panel { display: none; }
    .ex-panel.active { display: block; }

    .ex-toolbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem; flex-wrap: wrap; gap: 8px; }
    .ex-toolbar-left { font-size: 0.78rem; color: var(--muted); }
    .ex-btn { display: inline-flex; align-items: center; gap: 5px; padding: 7px 16px; border-radius: 8px; font-size: 0.82rem; font-weight: 600; border: none; cursor: pointer; background: var(--purple); color: #fff; transition: background 0.15s; }
    .ex-btn:hover { background: #6d28d9; }

    .ex-card { background: var(--white); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .ex-table-wrap { overflow-x: auto; max-height: 500px; overflow-y: auto; }
    .ex-table { width: 100%; border-collapse: collapse; font-size: 0.78rem; }
    .ex-table thead { background: #f8fafc; position: sticky; top: 0; z-index: 1; }
    .ex-table th { padding: 8px 10px; font-weight: 700; color: #374151; text-transform: uppercase; font-size: 0.68rem; letter-spacing: 0.5px; border-bottom: 2px solid var(--border); text-align: left; white-space: nowrap; }
    .ex-table td { padding: 7px 10px; border-bottom: 1px solid #f1f5f9; color: #374151; white-space: nowrap; }
    .ex-table tbody tr:hover { background: #f8fafc; }
    .ex-badge { display: inline-block; padding: 2px 7px; border-radius: 5px; font-size: 0.68rem; font-weight: 600; }
    .ex-badge-green { background: #d1fae5; color: #065f46; }
    .ex-badge-yellow { background: #fef3c7; color: #92400e; }
    .ex-badge-red { background: #fee2e2; color: #991b1b; }
    .ex-badge-blue { background: #dbeafe; color: #1e40af; }
    .ex-empty { text-align: center; padding: 2rem; color: var(--muted); font-size: 0.85rem; }

    @media (max-width: 768px) { .ex-tabs { gap: 0; } .ex-tab { padding: 8px 10px; font-size: 0.75rem; } }
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
            <p>View and download all data as Excel/CSV</p>
        </div>
    </div>

    <div class="ex-tabs">
        <button class="ex-tab active" data-tab="customer-orders">Customer Orders</button>
        <button class="ex-tab" data-tab="cp-orders">CP Orders</button>
        <button class="ex-tab" data-tab="cp-payments">CP Payments</button>
        <button class="ex-tab" data-tab="material-ledger">Material Ledger</button>
        <button class="ex-tab" data-tab="users">Users</button>
        <button class="ex-tab" data-tab="channel-partners">Channel Partners</button>
    </div>

    {{-- Customer Orders --}}
    <div class="ex-panel active" id="panel-customer-orders">
        <div class="ex-toolbar">
            <div class="ex-toolbar-left">Showing latest 50 records</div>
            <a href="{{ route('exportCustomerOrders') }}" class="ex-btn">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Download All (CSV)
            </a>
        </div>
        <div class="ex-card">
            <div class="ex-table-wrap">
                <table class="ex-table">
                    <thead><tr><th>#</th><th>Order No</th><th>Customer</th><th>Phone</th><th>City</th><th>State</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($customerOrders as $o)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="font-weight:700;">{{ $o->order_number }}</td>
                            <td>{{ $o->name }}</td>
                            <td>{{ $o->phone }}</td>
                            <td>{{ $o->city ?? '-' }}</td>
                            <td>{{ $o->state ?? '-' }}</td>
                            <td style="font-weight:700; color:#059669;">{{ number_format($o->total_amount, 2) }}</td>
                            <td><span class="ex-badge {{ $o->payment_status == 'paid' ? 'ex-badge-green' : 'ex-badge-yellow' }}">{{ ucfirst($o->payment_status) }}</span></td>
                            <td><span class="ex-badge {{ $o->status == 'delivered' ? 'ex-badge-green' : ($o->status == 'cancelled' ? 'ex-badge-red' : 'ex-badge-blue') }}">{{ ucfirst($o->status) }}</span></td>
                            <td>{{ $o->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="10" class="ex-empty">No customer orders found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- CP Orders --}}
    <div class="ex-panel" id="panel-cp-orders">
        <div class="ex-toolbar">
            <div class="ex-toolbar-left">Showing latest 50 records</div>
            <a href="{{ route('exportCpOrders') }}" class="ex-btn">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Download All (CSV)
            </a>
        </div>
        <div class="ex-card">
            <div class="ex-table-wrap">
                <table class="ex-table">
                    <thead><tr><th>#</th><th>Order ID</th><th>CP Name</th><th>Order Date</th><th>Status</th><th>Amount</th><th>Payment</th></tr></thead>
                    <tbody>
                        @forelse($cpOrders as $o)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="font-weight:700;">{{ $o->order_id }}</td>
                            <td>{{ $o->channelPartner->cp_name ?? '-' }}</td>
                            <td>{{ $o->order_date }}</td>
                            <td><span class="ex-badge {{ $o->status == 'approved' ? 'ex-badge-green' : ($o->status == 'rejected' ? 'ex-badge-red' : 'ex-badge-yellow') }}">{{ ucfirst($o->status) }}</span></td>
                            <td style="font-weight:700; color:#059669;">{{ number_format($o->grand_total ?? $o->quote_amount ?? 0, 2) }}</td>
                            <td><span class="ex-badge {{ ($o->payment_status ?? '') == 'verified' ? 'ex-badge-green' : 'ex-badge-yellow' }}">{{ ucfirst($o->payment_status ?? 'N/A') }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="ex-empty">No CP orders found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- CP Payments --}}
    <div class="ex-panel" id="panel-cp-payments">
        <div class="ex-toolbar">
            <div class="ex-toolbar-left">Showing latest 50 records</div>
            <a href="{{ route('exportPayments') }}" class="ex-btn">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Download All (CSV)
            </a>
        </div>
        <div class="ex-card">
            <div class="ex-table-wrap">
                <table class="ex-table">
                    <thead><tr><th>#</th><th>CP Name</th><th>Amount</th><th>Mode</th><th>Reference</th><th>Date</th><th>Status</th><th>Remarks</th></tr></thead>
                    <tbody>
                        @forelse($cpPayments as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->channelPartner->cp_name ?? '-' }}</td>
                            <td style="font-weight:700; color:#059669;">{{ number_format($p->amount, 2) }}</td>
                            <td>{{ ucfirst($p->payment_mode) }}</td>
                            <td>{{ $p->reference_number ?: '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->payment_date)->format('d M Y') }}</td>
                            <td><span class="ex-badge {{ $p->status == 'verified' ? 'ex-badge-green' : ($p->status == 'rejected' ? 'ex-badge-red' : 'ex-badge-yellow') }}">{{ ucfirst($p->status) }}</span></td>
                            <td>{{ Str::limit($p->remarks, 30) ?: '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="ex-empty">No payments found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Material Ledger --}}
    <div class="ex-panel" id="panel-material-ledger">
        <div class="ex-toolbar">
            <div class="ex-toolbar-left">Showing latest 50 records</div>
            <a href="{{ route('exportMaterialLedger') }}" class="ex-btn">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Download All (CSV)
            </a>
        </div>
        <div class="ex-card">
            <div class="ex-table-wrap">
                <table class="ex-table">
                    <thead><tr><th>#</th><th>CP Name</th><th>Material</th><th>Qty</th><th>Unit</th><th>Rate</th><th>Total</th><th>Date</th><th>Invoice</th></tr></thead>
                    <tbody>
                        @forelse($materialLedger as $e)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $e->channelPartner->cp_name ?? '-' }}</td>
                            <td>{{ $e->material_name }}</td>
                            <td>{{ $e->quantity }}</td>
                            <td>{{ $e->unit }}</td>
                            <td>{{ number_format($e->rate, 2) }}</td>
                            <td style="font-weight:700; color:#059669;">{{ number_format($e->total_amount, 2) }}</td>
                            <td>{{ $e->entry_date }}</td>
                            <td>{{ $e->invoice_number ?: '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="ex-empty">No material entries found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Users --}}
    <div class="ex-panel" id="panel-users">
        <div class="ex-toolbar">
            <div class="ex-toolbar-left">Showing latest 50 registered users</div>
            <a href="{{ route('exportUsers') }}" class="ex-btn">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Download All (CSV)
            </a>
        </div>
        <div class="ex-card">
            <div class="ex-table-wrap">
                <table class="ex-table">
                    <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>City</th><th>District</th><th>State</th><th>Pincode</th><th>Registered</th></tr></thead>
                    <tbody>
                        @forelse($users as $u)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="font-weight:700;">{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->mobile_number ?? '-' }}</td>
                            <td>{{ $u->city ?? '-' }}</td>
                            <td>{{ $u->district ?? '-' }}</td>
                            <td>{{ $u->state ?? '-' }}</td>
                            <td>{{ $u->pincode ?? '-' }}</td>
                            <td>{{ $u->created_at?->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="ex-empty">No users found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Channel Partners --}}
    <div class="ex-panel" id="panel-channel-partners">
        <div class="ex-toolbar">
            <div class="ex-toolbar-left">Showing latest 50 channel partners</div>
            <a href="{{ route('exportChannelPartners') }}" class="ex-btn">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Download All (CSV)
            </a>
        </div>
        <div class="ex-card">
            <div class="ex-table-wrap">
                <table class="ex-table">
                    <thead><tr><th>#</th><th>Name</th><th>Contact Person</th><th>Email</th><th>Phone</th><th>City</th><th>State</th><th>Active</th><th>Created</th></tr></thead>
                    <tbody>
                        @forelse($channelPartners as $c)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="font-weight:700;">{{ $c->cp_name }}</td>
                            <td>{{ $c->contact_person ?? '-' }}</td>
                            <td>{{ $c->email }}</td>
                            <td>{{ $c->phone_number }}</td>
                            <td>{{ $c->city ?? '-' }}</td>
                            <td>{{ $c->state ?? '-' }}</td>
                            <td><span class="ex-badge {{ $c->is_active ? 'ex-badge-green' : 'ex-badge-red' }}">{{ $c->is_active ? 'Yes' : 'No' }}</span></td>
                            <td>{{ $c->created_at?->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="ex-empty">No channel partners found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.querySelectorAll('.ex-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.ex-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.ex-panel').forEach(p => p.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('panel-' + this.dataset.tab).classList.add('active');
    });
});
</script>
@endsection
