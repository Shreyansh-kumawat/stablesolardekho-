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
            $badges = ['orders' => 0, 'referrals' => 0, 'users' => 0, 'cp_interest' => 0, 'cp_orders' => 0];
            try {
                $user = auth()->user();
                if ($user && in_array($user->role_id, [1, 2])) {
                    $seen = [];
                    $records = \App\Models\AdminLastSeen::where('user_id', $user->id)->get();
                    foreach ($records as $r) {
                        $seen[$r->section] = $r->seen_at;
                    }

                    if ($user->hasAdminPermission('orders')) {
                        $q = \App\Models\CustomerOrder::query();
                        if (isset($seen['orders'])) $q->where('created_at', '>', $seen['orders']);
                        $badges['orders'] = $q->count();
                    }

                    if ($user->hasAdminPermission('referrals')) {
                        $q = \App\Models\ReferralLead::query();
                        if (isset($seen['referrals'])) $q->where('created_at', '>', $seen['referrals']);
                        $badges['referrals'] = $q->count();
                    }

                    if ($user->hasAdminPermission('users')) {
                        $q = \App\Models\User::where('role_id', 3);
                        if (isset($seen['users'])) $q->where('created_at', '>', $seen['users']);
                        $badges['users'] = $q->count();
                    }

                    if ($user->hasAdminPermission('cp_interest')) {
                        $q = \App\Models\CpInterest::query();
                        if (isset($seen['cp_interest'])) $q->where('created_at', '>', $seen['cp_interest']);
                        $badges['cp_interest'] = $q->count();
                    }

                    if ($user->hasAdminPermission('cp_orders')) {
                        $q = \App\Models\CpOrder::query();
                        if (isset($seen['cp_orders'])) $q->where('created_at', '>', $seen['cp_orders']);
                        $badges['cp_orders'] = $q->count();
                    }
                }
            } catch (\Exception $e) {}
            $view->with('sidebarBadges', $badges);
        });

    }
}
