{{-- ===== ECOMMERCE SECTION (Top) ===== --}}
<div class="mt-2 mb-1 px-3">
    <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Ecommerce</p>
</div>
@include('layouts.partials.menuEcommercePartials')

@if(Auth::user()->role_id == 1)
    @include('layouts.partials.menuUserSettingPartials')

    @include('layouts.partials.menuCpSettingPartials')

    @include('layouts.partials.menuProductPartials')

    @include('layouts.partials.menuInventoryPartials')

    @include('layouts.partials.modals.menuAdminSetting')
    @include('layouts.partials.menuFundSetting')

    @include('layouts.partials.menuOrderSetting')
@endif
