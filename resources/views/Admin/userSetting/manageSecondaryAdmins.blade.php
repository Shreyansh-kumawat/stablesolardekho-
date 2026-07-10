@extends('layouts.adminLayout')

@section('content')
<div class="p-6 space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-800">Sub-Admin Management</h1>
        <p class="text-gray-500 text-sm mt-0.5">Promote users to sub-admin and control what sections they can access.</p>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">{{ session('error') }}</div>
    @endif

    <!-- Add New Sub-Admin -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <h2 class="font-semibold text-gray-800 mb-4">Add Sub-Admin by Email</h2>
        <form action="{{ route('addSecondaryAdmin') }}" method="POST" class="flex gap-3 flex-wrap">
            @csrf
            <div class="flex-1 min-w-64">
                <input type="email" name="email" placeholder="Enter user's email address..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('email') border-red-400 @enderror"
                    value="{{ old('email') }}">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit"
                class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                Add as Sub-Admin
            </button>
        </form>
        <p class="text-xs text-gray-400 mt-2">The user must already have an account. They'll be upgraded to sub-admin role.</p>
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
        ];
    @endphp

    <!-- Sub-Admins List -->
    @if($secondaryAdmins->count())
    <div class="space-y-4">
        <h2 class="font-semibold text-gray-800">Current Sub-Admins ({{ $secondaryAdmins->count() }})</h2>
        @foreach($secondaryAdmins as $admin)
        @php $adminPerms = $admin->admin_permissions ?? []; @endphp
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <!-- Admin header -->
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ $admin->name }}</p>
                        <p class="text-xs text-gray-500">{{ $admin->email }} · Joined {{ $admin->created_at->format('d M Y') }}</p>
                    </div>
                </div>
                <form action="{{ route('removeSecondaryAdmin', $admin->id) }}" method="POST"
                    onsubmit="return confirm('Remove {{ addslashes($admin->name) }} from sub-admin? They will become a regular user.')">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                        </svg>
                        Remove
                    </button>
                </form>
            </div>

            <!-- Permissions -->
            <form action="{{ route('updateSecondaryAdminPermissions', $admin->id) }}" method="POST" class="px-5 py-4" id="permForm{{ $admin->id }}">
                @csrf
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-3">Access Permissions</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                    @foreach($permissionGroups as $groupKey => $group)
                    @php
                        $subKeys = array_keys($group['subs']);
                        $allSubPerms = array_map(fn($s) => "$groupKey.$s", $subKeys);
                        $checkedSubs = array_filter($allSubPerms, fn($p) => in_array($p, $adminPerms));
                        $allChecked = count($checkedSubs) > 0;
                        $someChecked = count($checkedSubs) > 0;
                    @endphp
                    <div class="border rounded-lg {{ $someChecked ? 'border-indigo-300 bg-indigo-50/50' : 'border-gray-200' }}" data-perm-group="{{ $groupKey }}" data-admin="{{ $admin->id }}">
                        <!-- Main toggle -->
                        <label class="flex items-center gap-3 p-3 cursor-pointer border-b {{ $someChecked ? 'border-indigo-200' : 'border-gray-100' }}">
                            <input type="checkbox" class="main-toggle w-4 h-4 text-indigo-600 rounded border-gray-300"
                                data-group="{{ $groupKey }}" data-admin="{{ $admin->id }}"
                                {{ $allChecked ? 'checked' : '' }}>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 {{ $someChecked ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $group['icon'] }}" />
                                </svg>
                                <span class="text-sm font-semibold text-gray-700">{{ $group['label'] }}</span>
                            </div>
                        </label>
                        <!-- Sub toggles -->
                        <div class="p-3 space-y-2">
                            @foreach($group['subs'] as $subKey => $subLabel)
                            @php $fullKey = "$groupKey.$subKey"; @endphp
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="permissions[]" value="{{ $fullKey }}"
                                    class="sub-toggle w-3.5 h-3.5 text-indigo-600 rounded border-gray-300"
                                    data-group="{{ $groupKey }}" data-admin="{{ $admin->id }}"
                                    {{ in_array($fullKey, $adminPerms) ? 'checked' : '' }}>
                                <span class="text-xs text-gray-600">{{ $subLabel }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                    Save Permissions
                </button>
            </form>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-12 text-center text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
        </svg>
        <p class="font-medium">No sub-admins yet</p>
        <p class="text-sm mt-1">Add a user's email above to make them a sub-admin</p>
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
            var anyChecked = Array.from(subs).some(function(s) { return s.checked; });
            main.checked = anyChecked;
            updateGroupStyle(group, adminId);
        });
    });

    function updateGroupStyle(group, adminId) {
        var container = document.querySelector('[data-perm-group="' + group + '"][data-admin="' + adminId + '"]');
        var subs = container.querySelectorAll('.sub-toggle');
        var anyChecked = Array.from(subs).some(function(s) { return s.checked; });
        if (anyChecked) {
            container.classList.add('border-indigo-300', 'bg-indigo-50/50');
            container.classList.remove('border-gray-200');
        } else {
            container.classList.remove('border-indigo-300', 'bg-indigo-50/50');
            container.classList.add('border-gray-200');
        }
    }
});
</script>
@endsection
