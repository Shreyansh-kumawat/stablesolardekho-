@extends('layouts.adminLayout')

@section('title', 'Sub-Admin Management')

@section('css')
<style>
    .sa-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1.25rem;
        transition: box-shadow .15s;
    }
    .sa-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.06); }

    .sa-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.25rem;
        background: #f9fafb;
        border-bottom: 1px solid #f3f4f6;
        flex-wrap: wrap;
        gap: .75rem;
    }
    .sa-avatar {
        width: 42px; height: 42px; border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-weight: 700; font-size: 16px; flex-shrink: 0;
    }
    .sa-name { font-size: .9rem; font-weight: 700; color: #1f2937; }
    .sa-email { font-size: .75rem; color: #9ca3af; }

    .sa-perms {
        padding: 1.25rem;
    }
    .sa-perms-label {
        font-size: .7rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .5px; color: #9ca3af; margin-bottom: .75rem;
    }
    .perm-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: .75rem;
    }
    .perm-box {
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        padding: .75rem;
        transition: all .15s;
    }
    .perm-box.active {
        border-color: #818cf8;
        background: #eef2ff;
    }
    .perm-box-header {
        display: flex; align-items: center; gap: .5rem;
        margin-bottom: .5rem; cursor: pointer;
    }
    .perm-box-header input[type="checkbox"] {
        width: 16px; height: 16px; accent-color: #6366f1; cursor: pointer;
    }
    .perm-box-header svg { width: 16px; height: 16px; color: #9ca3af; }
    .perm-box.active .perm-box-header svg { color: #6366f1; }
    .perm-box-header span {
        font-size: .82rem; font-weight: 700; color: #374151;
    }
    .perm-sub {
        display: flex; align-items: center; gap: .4rem;
        padding: .2rem 0; cursor: pointer;
    }
    .perm-sub input[type="checkbox"] {
        width: 14px; height: 14px; accent-color: #6366f1; cursor: pointer;
    }
    .perm-sub span { font-size: .75rem; color: #6b7280; }

    .sa-footer {
        display: flex; align-items: center; justify-content: flex-end;
        padding: .75rem 1.25rem;
        background: #f9fafb;
        border-top: 1px solid #f3f4f6;
        gap: .5rem;
    }

    .btn-save {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .5rem 1.25rem; background: #4f46e5; color: #fff;
        font-size: .82rem; font-weight: 600; border: none; border-radius: 8px;
        cursor: pointer; transition: background .15s;
    }
    .btn-save:hover { background: #4338ca; }

    .btn-remove {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .5rem 1rem; background: #fff; color: #ef4444;
        font-size: .78rem; font-weight: 600; border: 1.5px solid #fecaca;
        border-radius: 8px; cursor: pointer; transition: all .15s;
    }
    .btn-remove:hover { background: #fef2f2; border-color: #f87171; }

    .add-card {
        background: #fff; border: 1px solid #e5e7eb;
        border-radius: 12px; padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    .add-card h2 {
        font-size: .9rem; font-weight: 700; color: #1f2937; margin: 0 0 .75rem;
    }
    .add-form {
        display: flex; gap: .75rem; flex-wrap: wrap; align-items: flex-start;
    }
    .add-form input {
        flex: 1; min-width: 240px; padding: .6rem .85rem;
        border: 1.5px solid #e5e7eb; border-radius: 8px;
        font-size: .85rem; color: #1f2937;
    }
    .add-form input:focus { outline: none; border-color: #818cf8; box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
    .add-form button {
        padding: .6rem 1.25rem; background: #4f46e5; color: #fff;
        border: none; border-radius: 8px; font-size: .82rem; font-weight: 600;
        cursor: pointer; transition: background .15s; white-space: nowrap;
    }
    .add-form button:hover { background: #4338ca; }
    .add-hint { font-size: .72rem; color: #9ca3af; margin-top: .5rem; }

    .empty-box {
        background: #fff; border: 1px solid #e5e7eb; border-radius: 12px;
        padding: 3rem 1rem; text-align: center; color: #9ca3af;
    }
    .empty-box svg { width: 40px; height: 40px; margin: 0 auto .75rem; color: #d1d5db; }
    .empty-box p { font-size: .85rem; }
    .empty-box p.sub { font-size: .78rem; margin-top: .25rem; }
</style>
@endsection

@section('content')
<div style="padding: 1.25rem; max-width: 960px; margin: 0 auto;">

    {{-- Header --}}
    <div style="margin-bottom: 1.25rem;">
        <h1 style="font-size: 1.35rem; font-weight: 800; color: #1f2937; margin: 0;">Sub-Admin Management</h1>
        <p style="font-size: .82rem; color: #9ca3af; margin: .25rem 0 0;">Promote users and control their access permissions.</p>
    </div>

    @if(session('success'))
        <div style="padding: .75rem 1rem; background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; border-radius: 8px; font-size: .82rem; margin-bottom: 1rem;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="padding: .75rem 1rem; background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; border-radius: 8px; font-size: .82rem; margin-bottom: 1rem;">{{ session('error') }}</div>
    @endif

    {{-- Add Sub-Admin --}}
    <div class="add-card">
        <h2>Add Sub-Admin by Email</h2>
        <form action="{{ route('addSecondaryAdmin') }}" method="POST" class="add-form">
            @csrf
            <input type="email" name="email" placeholder="Enter user's email address..." value="{{ old('email') }}" required>
            <button type="submit">
                <svg style="width:14px;height:14px;display:inline;vertical-align:middle;margin-right:4px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
                Add Sub-Admin
            </button>
        </form>
        @error('email') <p style="color:#ef4444;font-size:.75rem;margin-top:.35rem;">{{ $message }}</p> @enderror
        <p class="add-hint">The user must already have an account. They will be upgraded to sub-admin role.</p>
    </div>

    @php
        $permissionGroups = [
            'banners' => [
                'label' => 'Banners',
                'icon' => 'M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z',
                'subs' => ['add' => 'Add', 'edit' => 'Edit', 'remove' => 'Remove'],
            ],
            'products' => [
                'label' => 'Products',
                'icon' => 'M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9',
                'subs' => ['add' => 'Add', 'edit' => 'Edit', 'delete' => 'Delete'],
            ],
            'categories' => [
                'label' => 'Categories',
                'icon' => 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z',
                'subs' => ['add' => 'Add', 'edit' => 'Edit', 'delete' => 'Delete'],
            ],
            'users' => [
                'label' => 'Users',
                'icon' => 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z',
                'subs' => ['add' => 'Add', 'edit' => 'Edit', 'delete' => 'Delete'],
            ],
            'orders' => [
                'label' => 'Orders',
                'icon' => 'M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z',
                'subs' => ['manage' => 'Manage Orders'],
            ],
            'cp_interest' => [
                'label' => 'CP Interest Requests',
                'icon' => 'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z',
                'subs' => ['manage' => 'Manage Requests'],
            ],
            'cp_partners' => [
                'label' => 'CP Partners',
                'icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
                'subs' => ['manage' => 'Manage Partners'],
            ],
            'cp_orders' => [
                'label' => 'CP Orders',
                'icon' => 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z',
                'subs' => ['manage' => 'Manage CP Orders'],
            ],
        ];
    @endphp

    {{-- Sub-Admins List --}}
    @if($secondaryAdmins->count())
    <div style="margin-bottom: .75rem;">
        <h2 style="font-size: .9rem; font-weight: 700; color: #1f2937;">Current Sub-Admins ({{ $secondaryAdmins->count() }})</h2>
    </div>

    @foreach($secondaryAdmins as $admin)
    @php $adminPerms = $admin->admin_permissions ?? []; @endphp
    <div class="sa-card">
        {{-- Header --}}
        <div class="sa-header">
            <div style="display:flex; align-items:center; gap:.75rem;">
                <div class="sa-avatar">{{ strtoupper(substr($admin->name, 0, 1)) }}</div>
                <div>
                    <p class="sa-name">{{ $admin->name }}</p>
                    <p class="sa-email">{{ $admin->email }} &middot; Joined {{ $admin->created_at ? $admin->created_at->format('d M Y') : '—' }}</p>
                </div>
            </div>
            <form action="{{ route('removeSecondaryAdmin', $admin->id) }}" method="POST"
                onsubmit="return confirm('Remove {{ addslashes($admin->name) }} from sub-admin? They will become a regular user.')">
                @csrf
                <button type="submit" class="btn-remove">
                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
                    Remove
                </button>
            </form>
        </div>

        {{-- Permissions --}}
        <form action="{{ route('updateSecondaryAdminPermissions', $admin->id) }}" method="POST">
            @csrf
            <div class="sa-perms">
                <p class="sa-perms-label">Access Permissions</p>
                <div class="perm-grid">
                    @foreach($permissionGroups as $groupKey => $group)
                    @php
                        $subKeys = array_keys($group['subs']);
                        $allSubPerms = array_map(fn($s) => "$groupKey.$s", $subKeys);
                        $checkedSubs = array_filter($allSubPerms, fn($p) => in_array($p, $adminPerms));
                        $someChecked = count($checkedSubs) > 0;
                    @endphp
                    <div class="perm-box {{ $someChecked ? 'active' : '' }}" data-perm-group="{{ $groupKey }}" data-admin="{{ $admin->id }}">
                        <label class="perm-box-header">
                            <input type="checkbox" class="main-toggle"
                                data-group="{{ $groupKey }}" data-admin="{{ $admin->id }}"
                                {{ $someChecked ? 'checked' : '' }}>
                            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $group['icon'] }}" />
                            </svg>
                            <span>{{ $group['label'] }}</span>
                        </label>
                        @foreach($group['subs'] as $subKey => $subLabel)
                        @php $fullKey = "$groupKey.$subKey"; @endphp
                        <label class="perm-sub">
                            <input type="checkbox" name="permissions[]" value="{{ $fullKey }}"
                                class="sub-toggle" data-group="{{ $groupKey }}" data-admin="{{ $admin->id }}"
                                {{ in_array($fullKey, $adminPerms) ? 'checked' : '' }}>
                            <span>{{ $subLabel }}</span>
                        </label>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Footer with Save --}}
            <div class="sa-footer">
                <button type="submit" class="btn-save">
                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Save Permissions
                </button>
            </div>
        </form>
    </div>
    @endforeach

    @else
    <div class="empty-box">
        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
        </svg>
        <p style="font-weight:600;">No sub-admins yet</p>
        <p class="sub">Enter a user's email above to add them as a sub-admin.</p>
    </div>
    @endif
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.main-toggle').forEach(function(mainCb) {
        mainCb.addEventListener('change', function() {
            var group = this.dataset.group;
            var adminId = this.dataset.admin;
            var subs = document.querySelectorAll('.sub-toggle[data-group="' + group + '"][data-admin="' + adminId + '"]');
            subs.forEach(function(sub) { sub.checked = mainCb.checked; });
            updateGroupStyle(group, adminId);
        });
    });

    document.querySelectorAll('.sub-toggle').forEach(function(subCb) {
        subCb.addEventListener('change', function() {
            var group = this.dataset.group;
            var adminId = this.dataset.admin;
            var subs = document.querySelectorAll('.sub-toggle[data-group="' + group + '"][data-admin="' + adminId + '"]');
            var main = document.querySelector('.main-toggle[data-group="' + group + '"][data-admin="' + adminId + '"]');
            main.checked = Array.from(subs).some(function(s) { return s.checked; });
            updateGroupStyle(group, adminId);
        });
    });

    function updateGroupStyle(group, adminId) {
        var container = document.querySelector('[data-perm-group="' + group + '"][data-admin="' + adminId + '"]');
        var subs = container.querySelectorAll('.sub-toggle');
        var anyChecked = Array.from(subs).some(function(s) { return s.checked; });
        if (anyChecked) {
            container.classList.add('active');
        } else {
            container.classList.remove('active');
        }
    }
});
</script>
@endsection
