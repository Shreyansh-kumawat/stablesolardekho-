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

        if (!$user || !$user->role || !in_array($user->role->name, ['master_admin', 'secondary_admin'])) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
