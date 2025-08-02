<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApiLoggingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        $requestId = Str::uuid()->toString();

        // Request ma'lumotlarini loglaymiz
        $this->logRequest($request, $requestId);

        $response = $next($request);

        $endTime = microtime(true);
        $duration = round(($endTime - $startTime) * 1000, 2); // ms

        // Response ma'lumotlarini loglaymiz
        $this->logResponse($request, $response, $requestId, $duration);

        // Performance headerlarini qo'shamiz
        $response->headers->set('X-Request-ID', $requestId);
        $response->headers->set('X-Response-Time', $duration . 'ms');

        return $response;
    }

    /**
     * Log request details
     *
     * @param Request $request
     * @param string $requestId
     */
    private function logRequest(Request $request, string $requestId)
    {
        $logData = [
            'request_id' => $requestId,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => $request->user() ? $request->user()->id : null,
            'timestamp' => now()->toISOString(),
        ];

        // Sensitive ma'lumotlarni chiqarib tashlaymiz
        $input = $request->except(['password', 'password_confirmation', 'token']);
        if (!empty($input)) {
            $logData['input'] = $input;
        }

        Log::channel('api')->info('API Request', $logData);
    }

    /**
     * Log response details
     *
     * @param Request $request
     * @param $response
     * @param string $requestId
     * @param float $duration
     */
    private function logResponse(Request $request, $response, string $requestId, float $duration)
    {
        $logData = [
            'request_id' => $requestId,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'status_code' => $response->getStatusCode(),
            'duration_ms' => $duration,
            'timestamp' => now()->toISOString(),
        ];

        // Performance warning
        if ($duration > 200) {
            $logData['performance_warning'] = 'Slow response detected';
            Log::channel('api')->warning('Slow API Response', $logData);
        } else {
            Log::channel('api')->info('API Response', $logData);
        }

        // Error logging
        if ($response->getStatusCode() >= 400) {
            $logData['error'] = true;
            if ($response->getStatusCode() >= 500) {
                Log::channel('api')->error('API Server Error', $logData);
            } else {
                Log::channel('api')->warning('API Client Error', $logData);
            }
        }
    }
}
