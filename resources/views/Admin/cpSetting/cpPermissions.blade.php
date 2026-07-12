@extends('layouts.adminLayout')

@section('title', 'CP Permissions')

@section('css')
<style>
    .perm-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .perm-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
    }
    .perm-user {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .perm-avatar {
        width: 40px; height: 40px;
        border-radius: 50%;
        background: #e0e7ff;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; color: #4338ca; font-size: 14px;
    }
    .perm-name { font-weight: 600; color: #1f2937; }
    .perm-email { font-size: 12px; color: #6b7280; }
    .perm-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 12px;
        padding: 16px 20px;
    }
    .perm-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s;
    }
    .perm-item:hover { background: #f9fafb; }
    .perm-item.active { border-color: #818cf8; background: #eef2ff; }
    .perm-item input[type="checkbox"] {
        width: 16px; height: 16px;
        accent-color: #4f46e5;
    }
    .perm-label { font-size: 13px; font-weight: 500; color: #374151; }
    .perm-desc { font-size: 11px; color: #9ca3af; }
    .perm-footer {
        padding: 12px 20px;
        border-top: 1px solid #f3f4f6;
        display: flex;
        justify-content: flex-end;
    }
    .btn-save {
        padding: 8px 20px;
        background: #4f46e5;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-save:hover { background: #4338ca; }
    .page-title { font-size: 22px; font-weight: 700; color: #1f2937; margin-bottom: 4px; }
    .page-desc { font-size: 14px; color: #6b7280; margin-bottom: 24px; }
</style>
@endsection

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="page-title">CP User Permissions</h1>
    <p class="page-desc">Control which features each Channel Partner user can access</p>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @php
        $permissions = [
            'new_request' => ['label' => 'New Order', 'desc' => 'Place new orders with payment proof'],
            'view_requests' => ['label' => 'My Orders', 'desc' => 'View own order history and status'],
            'manual_installations' => ['label' => 'Installations', 'desc' => 'Create and view installation reports'],
        ];
    @endphp

    @forelse($cpUsers as $user)
    @php $userPerms = $user->cp_permissions ?? []; @endphp
    <form action="{{ route('updateCpPermissions', $user->id) }}" method="POST">
        @csrf
        <div class="perm-card">
            <div class="perm-header">
                <div class="perm-user">
                    <div class="perm-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <div>
                        <div class="perm-name">{{ $user->name }}</div>
                        <div class="perm-email">{{ $user->email }} · CP: {{ $user->channelPartner->cp_name ?? 'N/A' }}</div>
                    </div>
                </div>
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12px; color: #6b7280;">
                    <input type="checkbox" class="select-all" data-user="{{ $user->id }}" {{ count($userPerms) === count($permissions) ? 'checked' : '' }}>
                    Select All
                </label>
            </div>
            <div class="perm-grid">
                @foreach($permissions as $key => $perm)
                <label class="perm-item {{ in_array($key, $userPerms) ? 'active' : '' }}">
                    <input type="checkbox" name="permissions[]" value="{{ $key }}"
                        class="perm-cb" data-user="{{ $user->id }}"
                        {{ in_array($key, $userPerms) ? 'checked' : '' }}>
                    <div>
                        <div class="perm-label">{{ $perm['label'] }}</div>
                        <div class="perm-desc">{{ $perm['desc'] }}</div>
                    </div>
                </label>
                @endforeach
            </div>
            <div class="perm-footer">
                <button type="submit" class="btn-save">Save Permissions</button>
            </div>
        </div>
    </form>
    @empty
    <div class="perm-card" style="padding: 40px; text-align: center; color: #9ca3af;">
        <p style="font-weight: 600;">No Channel Partner users found</p>
        <p style="font-size: 13px; margin-top: 4px;">Add CP users first from the CP Settings section</p>
    </div>
    @endforelse
</div>
@endsection

@section('js')
<script>
document.querySelectorAll('.select-all').forEach(function(sa) {
    sa.addEventListener('change', function() {
        var uid = this.dataset.user;
        var cbs = document.querySelectorAll('.perm-cb[data-user="' + uid + '"]');
        var checked = this.checked;
        cbs.forEach(function(cb) {
            cb.checked = checked;
            cb.closest('.perm-item').classList.toggle('active', checked);
        });
    });
});

document.querySelectorAll('.perm-cb').forEach(function(cb) {
    cb.addEventListener('change', function() {
        this.closest('.perm-item').classList.toggle('active', this.checked);
        var uid = this.dataset.user;
        var all = document.querySelectorAll('.perm-cb[data-user="' + uid + '"]');
        var allChecked = Array.from(all).every(function(c) { return c.checked; });
        var sa = document.querySelector('.select-all[data-user="' + uid + '"]');
        if (sa) sa.checked = allChecked;
    });
});
</script>
@endsection
