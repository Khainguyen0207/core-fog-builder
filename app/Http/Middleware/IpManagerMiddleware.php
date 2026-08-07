<?php

namespace App\Http\Middleware;

use App\Models\IpLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class IpManagerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        $location = geoip($ip);

        if (!$location->default && !$location->cached) {

            IpLog::updateOrCreate([
                'ip' => $ip,
            ], [
                'iso_code' => $location->isoCode,
                'country' => $location->country,
                'city' => $location->city,
                'state' => $location->state,
                'state_name' => $location->stateName,
                'postal_code' => $location->postalCode,
                'currency' => $location->currency,
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $next($request);
    }
}
