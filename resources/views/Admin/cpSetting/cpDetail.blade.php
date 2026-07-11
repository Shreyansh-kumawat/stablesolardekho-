@extends('layouts.adminLayout')

@section('title', $cp->cp_name . ' — CP Detail')

@section('css')
<link href="/assets/css/fa-all.min.css" rel="stylesheet">
<style>
    .cp-detail-wrap { max-width: 1200px; margin: 0 auto; padding: 1.5rem 1rem; }
    .cp-back { display: inline-flex; align-items: center; gap: .4rem; color: #4A90E2; font-size: .85rem; font-weight: 600; text-decoration: none; margin-bottom: 1rem; }
    .cp-back:hover { text-decoration: underline; }

    /* Profile Card */
    .cp-profile { background: #fff; border-radius: 10px; border: 1px solid #e1e8ed; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,.04); }
    .cp-profile-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: .75rem; }
    .cp-profile-name { font-size: 1.35rem; font-weight: 700; color: #1f2937; }
    .cp-profile-role { display: inline-block; background: #e3f2fd; color: #4A90E2; padding: 3px 10px; border-radius: 12px; font-size: .75rem; font-weight: 700; margin-left: .5rem; }
    .cp-badge-active { background: #d1fae5; color: #065f46; padding: 3px 10px; border-radius: 12px; font-size: .75rem; font-weight: 700; }
    .cp-badge-inactive { background: #fee2e2; color: #991b1b; padding: 3px 10px; border-radius: 12px; font-size: .75rem; font-weight: 700; }
    .cp-info-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem; }
    .cp-info-item label { display: block; font-size: .7rem; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 2px; }
    .cp-info-item span { font-size: .9rem; color: #1f2937; }

    /* Stat Cards */
    .cp-stats { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .cp-stat { background: #fff; border-radius: 10px; border: 1px solid #e1e8ed; padding: 1rem 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,.04); }
    .cp-stat-label { font-size: .7rem; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: .5px; }
    .cp-stat-value { font-size: 1.5rem; font-weight: 800; color: #1f2937; margin-top: .25rem; }
    .cp-stat-sub { font-size: .75rem; color: #6b7280; }

    /* Tabs */
    .cp-tabs { display: flex; gap: 0; border-bottom: 2px solid #e5e7eb; margin-bottom: 0; }
    .cp-tab { padding: .6rem 1.25rem; font-size: .85rem; font-weight: 600; color: #6b7280; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all .15s; background: none; border-top: none; border-left: none; border-right: none; }
    .cp-tab:hover { color: #1f2937; }
    .cp-tab.active { color: #4A90E2; border-bottom-color: #4A90E2; }
    .cp-tab-content { display: none; background: #fff; border: 1px solid #e1e8ed; border-top: none; border-radius: 0 0 10px 10px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,.04); }
    .cp-tab-content.active { display: block; }

    /* Tables inside tabs */
    .cp-table { width: 100%; border-collapse: collapse; font-size: .85rem; }
    .cp-table th { text-align: left; padding: .6rem .75rem; background: #f9fafb; color: #6b7280; font-weight: 700; font-size: .75rem; text-transform: uppercase; letter-spacing: .3px; border-bottom: 1px solid #e5e7eb; }
    .cp-table td { padding: .6rem .75rem; border-bottom: 1px solid #f3f4f6; color: #374151; }
    .cp-table tr:hover { background: #f9fafb; }
    .cp-table-empty { text-align: center; padding: 2rem; color: #9ca3af; font-size: .9rem; }

    .badge-status { padding: 3px 10px; border-radius: 12px; font-size: .7rem; font-weight: 700; text-transform: uppercase; }
    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-approved { background: #d1fae5; color: #065f46; }
    .badge-rejected { background: #fee2e2; color: #991b1b; }
    .badge-completed { background: #dbeafe; color: #1e40af; }
    .badge-credit { background: #d1fae5; color: #065f46; }
    .badge-debit { background: #fee2e2; color: #991b1b; }
</style>
@endsection

@section('content')
<div class="cp-detail-wrap">
    <a href="{{ route('cpList') }}" class="cp-back">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
        Back to CP Partners
    </a>

    {{-- Profile Card --}}
    <div class="cp-profile">
        <div class="cp-profile-header">
            <div>
                <span class="cp-profile-name">{{ $cp->cp_name }}</span>
                <span class="cp-profile-role">{{ $cp->role->role_name ?? 'N/A' }}</span>
                @if($cp->is_active)
                    <span class="cp-badge-active">Active</span>
                @else
                    <span class="cp-badge-inactive">Inactive</span>
                @endif
            </div>
            <div style="display:flex;gap:.5rem;">
                <a href="{{ route('edit_cp', $cp->id) }}" style="display:inline-flex;align-items:center;gap:.35rem;padding:.4rem .8rem;background:#4A90E2;color:#fff;border-radius:6px;font-size:.8rem;font-weight:600;text-decoration:none;">
                    <i class="fas fa-edit"></i> Edit
                </a>
            </div>
        </div>
        <div class="cp-info-grid">
            <div class="cp-info-item">
                <label>Contact Person</label>
                <span>{{ $cp->contact_person }}</span>
            </div>
            <div class="cp-info-item">
                <label>Email</label>
                <span>{{ $cp->email }}</span>
            </div>
            <div class="cp-info-item">
                <label>Phone</label>
                <span>{{ $cp->phone_number }}</span>
            </div>
            <div class="cp-info-item">
                <label>City</label>
                <span>{{ $cp->city }}</span>
            </div>
            <div class="cp-info-item">
                <label>State</label>
                <span>{{ $cp->state }}</span>
            </div>
            <div class="cp-info-item">
                <label>Address</label>
                <span>{{ $cp->full_address ?? '—' }}</span>
            </div>
            <div class="cp-info-item">
                <label>Pin Code</label>
                <span>{{ $cp->zip_code ?? '—' }}</span>
            </div>
            <div class="cp-info-item">
                <label>Joined</label>
                <span>{{ $cp->created_at->format('d M Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="cp-stats">
        <div class="cp-stat">
            <div class="cp-stat-label">Wallet Balance</div>
            <div class="cp-stat-value" style="color:#059669;">₹{{ $cp->wallet ? number_format($cp->wallet->balance, 2) : '0.00' }}</div>
        </div>
        <div class="cp-stat">
            <div class="cp-stat-label">Total Orders</div>
            <div class="cp-stat-value">{{ $orders->count() }}</div>
            @if($orders->where('status','pending')->count() > 0)
                <div class="cp-stat-sub">{{ $orders->where('status','pending')->count() }} pending</div>
            @endif
        </div>
        <div class="cp-stat">
            <div class="cp-stat-label">Associated Users</div>
            <div class="cp-stat-value">{{ $cp->associateUsers->count() }}</div>
        </div>
        <div class="cp-stat">
            <div class="cp-stat-label">Inventory Items</div>
            <div class="cp-stat-value">{{ $inventory->count() }}</div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="cp-tabs">
        <button class="cp-tab active" data-tab="orders">Orders ({{ $orders->count() }})</button>
        <button class="cp-tab" data-tab="wallet">Wallet ({{ $walletTransactions->count() }})</button>
        <button class="cp-tab" data-tab="users">Users ({{ $cp->associateUsers->count() }})</button>
    </div>

    {{-- Orders Tab --}}
    <div class="cp-tab-content active" id="tab-orders">
        @if($orders->count())
        <div style="overflow-x:auto;">
            <table class="cp-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Products</th>
                        <th>Grand Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td style="font-weight:600;">#{{ $order->order_id ?? $order->id }}</td>
                        <td>{{ $order->order_date ? $order->order_date->format('d M Y') : $order->created_at->format('d M Y') }}</td>
                        <td>
                            @if($order->products)
                                {{ count($order->products) }} item(s)
                            @else
                                —
                            @endif
                        </td>
                        <td style="font-weight:600;">₹{{ number_format($order->grand_total ?? 0, 2) }}</td>
                        <td>
                            @php $st = strtolower($order->status ?? 'pending'); @endphp
                            <span class="badge-status badge-{{ $st === 'approved' || $st === 'completed' ? 'approved' : ($st === 'rejected' || $st === 'cancelled' ? 'rejected' : 'pending') }}">
                                {{ ucfirst($order->status ?? 'Pending') }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="cp-table-empty">No orders yet.</div>
        @endif
    </div>

    {{-- Wallet Tab --}}
    <div class="cp-tab-content" id="tab-wallet">
        @if($walletTransactions->count())
        <div style="overflow-x:auto;">
            <table class="cp-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Txn ID</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Opening</th>
                        <th>Closing</th>
                        <th>Source</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($walletTransactions as $txn)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $txn->created_at->format('d M Y H:i') }}</td>
                        <td style="font-family:monospace;font-size:.8rem;">{{ $txn->txn_id ?? '—' }}</td>
                        <td>
                            <span class="badge-status {{ strtolower($txn->transaction_type) === 'credit' ? 'badge-credit' : 'badge-debit' }}">
                                {{ ucfirst($txn->transaction_type) }}
                            </span>
                        </td>
                        <td style="font-weight:600;">₹{{ number_format($txn->amount, 2) }}</td>
                        <td>₹{{ number_format($txn->opening_balance ?? 0, 2) }}</td>
                        <td>₹{{ number_format($txn->closing_balance ?? 0, 2) }}</td>
                        <td>{{ $txn->source ?? '—' }}</td>
                        <td style="font-size:.8rem;color:#6b7280;">{{ $txn->remarks ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="cp-table-empty">No wallet transactions yet.</div>
        @endif
    </div>

    {{-- Users Tab --}}
    <div class="cp-tab-content" id="tab-users">
        @if($cp->associateUsers->count())
        <div style="overflow-x:auto;">
            <table class="cp-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Permissions</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cp->associateUsers as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td style="font-weight:600;">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->mobile_number ?? '—' }}</td>
                        <td>
                            @php $perms = is_array($user->cp_permissions) ? $user->cp_permissions : json_decode($user->cp_permissions ?? '[]', true); @endphp
                            @if(!empty($perms))
                                @foreach($perms as $perm)
                                    <span style="display:inline-block;background:#f3f4f6;color:#374151;padding:2px 6px;border-radius:4px;font-size:.7rem;font-weight:600;margin:1px;">{{ str_replace('_',' ',$perm) }}</span>
                                @endforeach
                            @else
                                <span style="color:#9ca3af;font-size:.8rem;">None</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="cp-table-empty">No associated users.</div>
        @endif
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.cp-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.cp-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.cp-tab-content').forEach(c => c.classList.remove('active'));
            tab.classList.add('active');
            document.getElementById('tab-' + tab.dataset.tab).classList.add('active');
        });
    });
});
</script>
@endsection
