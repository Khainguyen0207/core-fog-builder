<?php

namespace App\Http\Middleware;

use App\Plugins\PluginManager;
use App\Plugins\PluginStatus;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequirePlugin
{
    public function __construct(private readonly PluginManager $plugins) {}

    public function handle(Request $request, Closure $next, string $packageName): Response
    {
        $status = $this->plugins->status($packageName);

        if ($status->enabled) {
            return $next($request);
        }

        $responseStatus = $status->reason === PluginStatus::DISABLED
            ? Response::HTTP_NOT_FOUND
            : Response::HTTP_SERVICE_UNAVAILABLE;
        $message = $responseStatus === Response::HTTP_NOT_FOUND
            ? 'The requested feature is not available.'
            : 'The requested feature is temporarily unavailable.';

        if ($request->is('api/*') || $request->expectsJson()) {
            return new JsonResponse([
                'error' => true,
                'data' => null,
                'message' => $message,
            ], $responseStatus);
        }

        abort($responseStatus, $message);
    }
}
