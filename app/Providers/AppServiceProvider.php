<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        View::composer('layouts.partials.menuEcommercePartials', function ($view) {
            $cpOrderBadge = 0;
            $customerOrderBadge = 0;
            try {
                $cpOrderBadge = \App\Models\CpOrder::where('viewed_by_admin', 0)->count();
                $customerOrderBadge = \App\Models\CustomerOrder::where('viewed_by_admin', 0)->count();
            } catch (\Exception $e) {}
            $view->with('cpOrderBadge', $cpOrderBadge);
            $view->with('customerOrderBadge', $customerOrderBadge);
        });

    }
}
