<?php

namespace App\Http\Middleware;

use App\Models\ChannelPartner;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class WarehouseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cp_role = ChannelPartner::where('id',Auth::user()->cp_id)->value('cp_role');
        if($cp_role != 3){
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}
