{{-- ===== ECOMMERCE SECTION (Top) ===== --}}
@include('layouts.partials.menuEcommercePartials')

@include('layouts.partials.menuWarehousePartials')

@include('layouts.partials.menuRfqPartials')

@if(Auth::user()->role_id == 1)
    {{-- Hidden per client request --}}
    {{-- @include('layouts.partials.menuUserSettingPartials') --}}

    {{-- Hidden per client request --}}
    {{-- @include('layouts.partials.menuCpSettingPartials') --}}

    @include('layouts.partials.menuProductPartials')

    {{-- Hidden per client request --}}
    {{-- @include('layouts.partials.menuInventoryPartials') --}}

    @include('layouts.partials.modals.menuAdminSetting')

    {{-- Hidden per client request --}}
    {{-- @include('layouts.partials.menuFundSetting') --}}

    {{-- Hidden per client request --}}
    {{-- @include('layouts.partials.menuOrderSetting') --}}
@endif
