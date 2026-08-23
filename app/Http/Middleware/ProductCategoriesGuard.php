<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ProductCategoriesGuard
{
    public function handle(Request $request, Closure $next)
    {
        if (file_exists(storage_path('framework/maintenance_custom'))) {
            return response()->view('categoriesSubcategory', [], 503);
        }

        return $next($request);
    }
}
