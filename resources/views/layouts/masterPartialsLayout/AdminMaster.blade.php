@if(Auth::user()->role?->name === 'master_admin')
    @include('layouts.partials.menuUserSettingPartials')
@endif

@if(Auth::user()->hasAdminPermission('cp_orders'))
    @include('layouts.partials.menuCpSettingPartials')
@endif

@if(Auth::user()->hasAdminPermission('products'))
    @include('layouts.partials.menuProductPartials')
@endif

@if(Auth::user()->hasAdminPermission('inventory'))
    @include('layouts.partials.menuInventoryPartials')
@endif

@if(Auth::user()->role?->name === 'master_admin')
    @include('layouts.partials.modals.menuAdminSetting')
    @include('layouts.partials.menuFundSetting')
@endif

@if(Auth::user()->hasAdminPermission('orders') || Auth::user()->hasAdminPermission('cp_orders'))
    @include('layouts.partials.menuOrderSetting')
@endif

{{-- ===== ECOMMERCE SECTION ===== --}}
<div class="mt-4 mb-1 px-3">
    <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Ecommerce</p>
</div>
@include('layouts.partials.menuEcommercePartials')
