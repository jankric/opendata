<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TrackApiUsageMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        // Track request
        $this->trackRequest($request);
        
        $response = $next($request);
        
        // Track response
        $this->trackResponse($request, $response, $startTime);
        
        return $response;
    }

    private function trackRequest(Request $request): void
    {
        $key = 'api_requests_' . now()->format('Y-m-d-H');
        Cache::increment($key, 1);
        Cache::expire($key, 3600 * 25); // Keep for 25 hours
        
        // Track by endpoint
        $endpoint = $request->route()?->getName() ?? 'unknown';
        $endpointKey = "api_endpoint_{$endpoint}_" . now()->format('Y-m-d');
        Cache::increment($endpointKey, 1);
        Cache::expire($endpointKey, 86400 * 8); // Keep for 8 days
        
        // Track by user if authenticated
        if ($request->user()) {
            $userKey = "api_user_{$request->user()->id}_" . now()->format('Y-m-d');
            Cache::increment($userKey, 1);
            Cache::expire($userKey, 86400 * 8);
        }
    }

    private function trackResponse(Request $request, Response $response, float $startTime): void
    {
        $duration = microtime(true) - $startTime;
        
        // Log slow requests
        if ($duration > 2.0) {
            Log::warning('Slow API request', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'duration' => $duration,
                'user_id' => $request->user()?->id,
                'ip' => $request->ip(),
            ]);
        }
        
        // Track response status
        $statusKey = "api_status_{$response->getStatusCode()}_" . now()->format('Y-m-d');
        Cache::increment($statusKey, 1);
        Cache::expire($statusKey, 86400 * 8);
    }
}