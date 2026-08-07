<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyWebMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->acceptsHtml()) {
            abort(404);
        }

        Log::channel('api')->info('Request: ' . $request->fullUrl(), [
            'headers' => $request->headers->all(),
            'body' => $request->all(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return $next($request);
    }
}
