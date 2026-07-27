@extends('layouts.adminLayout')
@section('title', 'CP Inventory')
@section('page_title', 'CP Inventory')

@section('css')
<style>
    .cpi-wrap { padding: 1.25rem; }
    .cpi-header { display: flex; align-items: center; gap: 12px; margin-bottom: 1.25rem; }
    .cpi-icon { width: 40px; height: 40px; background: #2563eb; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .cpi-header h1 { font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 0; }
    .cpi-header p { font-size: 0.78rem; color: #64748b; margin: 2px 0 0; }
    .cpi-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .cpi-search { padding: 12px 16px; border-bottom: 1px solid #f1f5f9; }
    .cpi-search input { border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px 10px; font-size: 0.8rem; width: 100%; max-width: 300px; }
    .cpi-table { width: 100%; border-collapse: collapse; font-size: 0.84rem; }
    .cpi-table thead { background: #f8fafc; }
    .cpi-table th { padding: 10px 14px; font-weight: 700; color: #374151; text-transform: uppercase; font-size: 0.72rem; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; text-align: left; }
    .cpi-table td { padding: 10px 14px; border-bottom: 1px solid #f1f5f9; color: #374151; vertical-align: middle; }
    .cpi-table tbody tr:hover { background: #f8fafc; }
    .cpi-cp-name { font-weight: 700; color: #1e293b; }
    .cpi-role { font-size: 0.72rem; color: #64748b; }
    .cpi-btn { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; text-decoration: none; background: #2563eb; color: #fff; }
    .cpi-btn:hover { background: #1d4ed8; color: #fff; }
    .cpi-items { font-weight: 700; color: #059669; }
</style>
@endsection

@section('content')
<div class="cpi-wrap">
    <div class="cpi-header">
        <div class="cpi-icon">
            <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
        </div>
        <div>
            <h1>CP Inventory</h1>
            <p>View and manage inventory for all channel partners</p>
        </div>
    </div>

    <div class="cpi-card">
        <div class="cpi-search">
            <input type="text" id="cpiSearch" placeholder="Search channel partners...">
        </div>
        <div style="overflow-x:auto;">
            <table class="cpi-table" id="cpiTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Channel Partner</th>
                        <th>Contact Person</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Items</th>
                        <th>Total Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cps as $cp)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="cpi-cp-name">{{ $cp->cp_name }}</span></td>
                        <td>{{ $cp->contact_person ?? '-' }}</td>
                        <td>{{ $cp->phone_number ?? '-' }}</td>
                        <td><span class="cpi-role">{{ $cp->role->name ?? 'N/A' }}</span></td>
                        @php $stat = $invStats[$cp->id] ?? null; @endphp
                        <td><span class="cpi-items">{{ $stat ? $stat->items_count : 0 }}</span></td>
                        <td><span class="cpi-items">{{ $stat ? $stat->total_stock : 0 }}</span></td>
                        <td>
                            <a href="{{ route('adminCpInventoryDetail', $cp->id) }}" class="cpi-btn">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                View Inventory
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.getElementById('cpiSearch').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    document.querySelectorAll('#cpiTable tbody tr').forEach(function(r) {
        r.style.display = r.textContent.toLowerCase().indexOf(q) !== -1 ? '' : 'none';
    });
});
</script>
@endsection
