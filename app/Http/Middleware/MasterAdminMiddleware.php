<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MasterAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role_id, [1, 2])) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
