<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class WarehouseManagerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $warehouseId = DB::table('warehouse_managers')
            ->where('user_id', $user->id)
            ->value('warehouse_id');

        if (!$warehouseId) {
            abort(403, 'You are not assigned to any warehouse.');
        }

        $request->attributes->set('managed_warehouse_id', $warehouseId);
        return $next($request);
    }
}
