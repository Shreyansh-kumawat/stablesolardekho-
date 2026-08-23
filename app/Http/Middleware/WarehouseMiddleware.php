<?php

namespace App\Http\Middleware;

use App\Models\ChannelPartner;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class WarehouseMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (in_array($user->role_id, [1, 2])) {
            return $next($request);
        }

        $isWarehouseManager = DB::table('warehouse_managers')->where('user_id', $user->id)->exists();
        if ($isWarehouseManager) {
            return $next($request);
        }

        $cp_role = ChannelPartner::where('id', $user->cp_id)->value('cp_role');
        if ($cp_role == 3) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
