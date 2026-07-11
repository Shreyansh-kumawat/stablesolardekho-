@extends('layouts.adminLayout')

@section('css')
<style>
    .customer-card {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: white;
        padding: 14px;
        margin-bottom: 10px;
        transition: box-shadow 0.15s;
    }
    .customer-card:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
    .customer-avatar {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: #e0e7ff;
        display: flex; align-items: center; justify-content: center;
        color: #4338ca; font-weight: 700; font-size: 14px;
        flex-shrink: 0;
    }
    .customer-meta { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 6px; }
    .customer-meta .tag {
        display: inline-flex; align-items: center; gap: 3px;
        padding: 2px 8px; border-radius: 99px;
        font-size: 11px; font-weight: 500;
    }
    .customer-actions { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 10px; }
    .customer-actions .btn-act {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 5px 10px; border-radius: 6px;
        font-size: 12px; font-weight: 500;
        text-decoration: none; border: none; cursor: pointer;
        transition: background 0.15s;
    }
    .btn-orders { background: #4f46e5; color: white; }
    .btn-orders:hover { background: #4338ca; }
    .btn-admin { background: #7c3aed; color: white; }
    .btn-admin:hover { background: #6d28d9; }
    .btn-delete { background: #dc2626; color: white; }
    .btn-delete:hover { background: #b91c1c; }

    @media (min-width: 768px) {
        .customers-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    }
    @media (min-width: 1024px) {
        .customers-grid { grid-template-columns: repeat(3, 1fr); }
    }
</style>
@endsection

@section('content')
<div class="p-3 sm:p-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4 sm:mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Users</h1>
            <p class="text-gray-500 text-sm mt-0.5">All registered users on the platform</p>
        </div>
        <span class="inline-flex items-center self-start sm:self-auto px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
            Total: {{ $users->count() }}
        </span>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
    @endif

    {{-- Search --}}
    <div class="mb-4">
        <input type="text" id="customerSearch" placeholder="Search users..."
            class="w-full sm:max-w-sm border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
    </div>

    {{-- Customer Cards --}}
    <div class="customers-grid" id="customersList">
        @foreach($users as $user)
        <div class="customer-card" data-search="{{ strtolower($user->name . ' ' . $user->email . ' ' . $user->mobile_number) }}">
            <div class="flex items-start gap-3">
                <div class="customer-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm truncate">{{ $user->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                    <div class="customer-meta">
                        <span class="tag bg-gray-100 text-gray-600">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                            {{ $user->mobile_number ?? '—' }}
                        </span>
                        <span class="tag {{ $user->customer_orders_count > 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $user->customer_orders_count }} order{{ $user->customer_orders_count != 1 ? 's' : '' }}
                        </span>
                        <span class="tag bg-blue-50 text-blue-600">
                            {{ $user->created_at ? $user->created_at->format('d M Y') : '—' }}
                        </span>
                        @if($user->role)
                        <span class="tag {{ $user->role_id == 4 ? 'bg-purple-100 text-purple-700' : ($user->role_id == 2 ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-500') }}">
                            {{ $user->role_id == 4 ? 'CP' : ($user->role_id == 2 ? 'Admin' : 'User') }}
                        </span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-1.5 flex-shrink-0">
                    <button
                        onclick="toggleStatus({{ $user->id }}, this)"
                        data-active="{{ $user->is_active ? '1' : '0' }}"
                        class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none
                            {{ $user->is_active ? 'bg-green-500' : 'bg-gray-300' }}">
                        <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow transition-transform
                            {{ $user->is_active ? 'translate-x-4' : 'translate-x-0.5' }}"></span>
                    </button>
                    <span class="text-xs text-gray-500 toggle-label-{{ $user->id }}">
                        {{ $user->is_active ? 'Active' : 'Blocked' }}
                    </span>
                </div>
            </div>
            <div class="customer-actions">
                <a href="{{ route('adminUserOrders', $user->id) }}" class="btn-act btn-orders">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                    Orders
                </a>
                @if(Auth::user()->role?->name === 'master_admin' && !in_array($user->role_id, [1, 2]))
                <form action="{{ route('addSecondaryAdmin') }}" method="POST" class="make-admin-form" style="margin:0;">
                    @csrf
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    <button type="button" class="btn-act btn-admin make-admin-btn" data-name="{{ $user->name }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                        Make Admin
                    </button>
                </form>
                @endif
                @if($user->role_id != 2)
                <form action="{{ route('admin.customer.delete', $user->id) }}" method="POST" class="delete-customer-form" style="margin:0;">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn-act btn-delete delete-customer-btn"
                        data-name="{{ $user->name }}"
                        data-pending="{{ $user->pending_orders_count }}"
                        data-delivered="{{ $user->delivered_orders_count }}"
                        data-total="{{ $user->customer_orders_count }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Delete
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    @if($users->isEmpty())
    <div class="bg-white rounded-xl border border-gray-200 p-12 text-center text-gray-400">
        <p class="font-medium">No customers yet</p>
    </div>
    @endif
</div>
@endsection

@section('js')
<script>
$('#customerSearch').on('keyup', function() {
    var val = this.value.toLowerCase();
    $('#customersList .customer-card').each(function() {
        var match = $(this).data('search').indexOf(val) > -1;
        $(this).toggle(match);
    });
});

$(document).on('click', '.make-admin-btn', function () {
    var btn = $(this);
    var name = btn.data('name');
    Swal.fire({
        title: 'Make Secondary Admin?',
        html: 'Are you sure you want to make <strong>"' + name + '"</strong> a secondary admin?<br><small class="text-gray-500">They will have no permissions by default. You can assign permissions from the Secondary Admin page.</small>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#7c3aed',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Make Admin',
        cancelButtonText: 'Cancel'
    }).then(function (result) {
        if (result.isConfirmed) {
            btn.closest('.make-admin-form').submit();
        }
    });
});

$(document).on('click', '.delete-customer-btn', function () {
    var btn = $(this);
    var name = btn.data('name');
    var pending = btn.data('pending');
    var delivered = btn.data('delivered');
    var total = btn.data('total');
    Swal.fire({
        title: 'Delete Customer?',
        html: 'Are you sure you want to permanently delete <strong>"' + name + '"</strong> and all their orders?<br><br>' +
              '<div style="text-align:left;background:#f9fafb;border-radius:8px;padding:10px 14px;font-size:13px;line-height:1.8;">' +
              '<strong>Total Orders:</strong> ' + total + '<br>' +
              '<strong>Pending/Active:</strong> <span style="color:#d97706;">' + pending + '</span><br>' +
              '<strong>Delivered:</strong> <span style="color:#16a34a;">' + delivered + '</span></div>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete Permanently',
        cancelButtonText: 'Cancel'
    }).then(function (result) {
        if (result.isConfirmed) {
            btn.closest('.delete-customer-form').submit();
        }
    });
});

function toggleStatus(userId, btn) {
    fetch('/admin/ecommerce-customers/' + userId + '/toggle-status', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        var nowActive = data.is_active == 1;
        btn.dataset.active = nowActive ? '1' : '0';
        btn.className = 'relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none ' + (nowActive ? 'bg-green-500' : 'bg-gray-300');
        btn.querySelector('span').className = 'inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow transition-transform ' + (nowActive ? 'translate-x-4' : 'translate-x-0.5');
        document.querySelector('.toggle-label-' + userId).textContent = nowActive ? 'Active' : 'Blocked';
    });
}
</script>
@endsection
