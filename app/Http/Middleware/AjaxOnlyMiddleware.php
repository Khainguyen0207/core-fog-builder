<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Configuration\Middleware;

class AjaxOnlyMiddleware extends Middleware
{
    public function handle($request, Closure $next)
    {
        if (! request()->ajax()) {
            abort(404);
        }

        return $next($request);
    }
}
