<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $maxAttempts
     * @param  int  $decayMinutes
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $maxAttempts = 60, $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);

        if (Cache::has($key)) {
            $attempts = Cache::get($key);

            if ($attempts >= $maxAttempts) {
                return response()->json([
                    'error' => 'Too Many Requests',
                    'message' => 'Rate limit exceeded. Try again in ' . $decayMinutes . ' minute(s).',
                    'retry_after' => $decayMinutes * 60
                ], 429)->header('Retry-After', $decayMinutes * 60);
            }

            Cache::put($key, $attempts + 1, now()->addMinutes($decayMinutes));
        } else {
            Cache::put($key, 1, now()->addMinutes($decayMinutes));
        }

        $response = $next($request);

        // Rate limit headers qo'shamiz
        $remaining = max(0, $maxAttempts - (Cache::get($key, 0)));

        return $response->withHeaders([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => $remaining,
            'X-RateLimit-Reset' => now()->addMinutes($decayMinutes)->timestamp,
        ]);
    }

    /**
     * Resolve request signature.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function resolveRequestSignature(Request $request)
    {
        if ($user = $request->user()) {
            return sha1('rate_limit_user_' . $user->id);
        }

        return sha1('rate_limit_ip_' . $request->ip());
    }
}
