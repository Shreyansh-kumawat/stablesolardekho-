{{-- ===== ECOMMERCE SECTION (Top) ===== --}}
@include('layouts.partials.menuEcommercePartials')

@include('layouts.partials.menuWarehousePartials')

@include('layouts.partials.menuRfqPartials')

@if(Auth::user()->role_id == 1)
    @include('layouts.partials.menuUserSettingPartials')

    @include('layouts.partials.menuCpSettingPartials')

    @include('layouts.partials.menuProductPartials')

    @include('layouts.partials.menuInventoryPartials')

    @include('layouts.partials.modals.menuAdminSetting')
    @include('layouts.partials.menuFundSetting')

    @include('layouts.partials.menuOrderSetting')
@endif
