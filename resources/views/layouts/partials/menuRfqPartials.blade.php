{{-- ===== RFQ SECTION ===== --}}
@if(Auth::user()->hasAdminPermission('rfq'))
<div class="w-full flex items-center justify-between px-3 py-2 rounded-md mt-2">
    <span class="flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
        </svg>
        <span class="text-xs font-semibold uppercase tracking-widest text-slate-500">Customer RFQ</span>
    </span>
</div>

<ul class="ml-4 mt-1 space-y-0.5">
    <li>
        <a href="{{ route('admin.rfq.index') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('admin.rfq.*') ? 'ec-active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
            </svg>
            <span>All Requests</span>
        </a>
    </li>
</ul>
@endif
