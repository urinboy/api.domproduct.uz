<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        ValidationException::class,
        AuthenticationException::class,
        AuthorizationException::class,
        ModelNotFoundException::class,
        NotFoundHttpException::class,
        MethodNotAllowedHttpException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
        'token',
        'access_token',
        'refresh_token',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            // Log detailed error information for debugging
            if (app()->environment(['production', 'staging'])) {
                Log::error('Application Error', [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'url' => request()->fullUrl(),
                    'method' => request()->method(),
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'user_id' => auth()->id(),
                ]);
            }
        });

        // Handle API exceptions
        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return $this->handleApiException($e, $request);
            }
        });
    }

    /**
     * Handle API exceptions with proper JSON responses
     *
     * @param Throwable $e
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleApiException(Throwable $e, $request)
    {
        $response = [
            'error' => 'Internal Server Error',
            'message' => 'Something went wrong.',
            'meta' => [
                'timestamp' => now()->toISOString(),
                'path' => $request->path(),
                'method' => $request->method(),
            ]
        ];

        $statusCode = 500;

        // Handle specific exception types
        if ($e instanceof ValidationException) {
            $response['error'] = 'Validation Error';
            $response['message'] = 'The given data was invalid.';
            $response['errors'] = $e->errors();
            $statusCode = 422;
        } elseif ($e instanceof ModelNotFoundException) {
            $response['error'] = 'Not Found';
            $response['message'] = 'The requested resource was not found.';
            $statusCode = 404;
        } elseif ($e instanceof NotFoundHttpException) {
            $response['error'] = 'Not Found';
            $response['message'] = 'The requested endpoint was not found.';
            $statusCode = 404;
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            $response['error'] = 'Method Not Allowed';
            $response['message'] = 'The requested method is not allowed for this endpoint.';
            $response['allowed_methods'] = $e->getHeaders()['Allow'] ?? [];
            $statusCode = 405;
        } elseif ($e instanceof AuthenticationException) {
            $response['error'] = 'Unauthenticated';
            $response['message'] = 'Authentication is required to access this resource.';
            $statusCode = 401;
        } elseif ($e instanceof AuthorizationException) {
            $response['error'] = 'Forbidden';
            $response['message'] = 'You do not have permission to access this resource.';
            $statusCode = 403;
        }

        // Add debug information in development
        if (app()->environment('local', 'development')) {
            $response['debug'] = [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => collect($e->getTrace())->take(5)->toArray(),
            ];
        }

        // Add request ID if available
        if ($request->hasHeader('X-Request-ID')) {
            $response['meta']['request_id'] = $request->header('X-Request-ID');
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'error' => 'Unauthenticated',
                'message' => 'Authentication is required to access this resource.',
                'meta' => [
                    'timestamp' => now()->toISOString(),
                    'path' => $request->path(),
                    'method' => $request->method(),
                ]
            ], 401);
        }

        return redirect()->guest($exception->redirectTo() ?? route('web.login'));
    }
}
