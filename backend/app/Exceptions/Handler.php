<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Handle API requests
        if ($request->is('api/*') || $request->expectsJson()) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    private function handleApiException(Request $request, Throwable $e): JsonResponse
    {
        $status = 500;
        $message = 'Internal Server Error';
        $errors = null;

        if ($e instanceof ValidationException) {
            $status = 422;
            $message = 'Validation failed';
            $errors = $e->errors();
        } elseif ($e instanceof NotFoundHttpException) {
            $status = 404;
            $message = 'Resource not found';
        } elseif ($e instanceof AccessDeniedHttpException) {
            $status = 403;
            $message = 'Access denied';
        } elseif ($e instanceof \Illuminate\Auth\AuthenticationException) {
            $status = 401;
            $message = 'Unauthenticated';
        } elseif ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            $status = 404;
            $message = 'Resource not found';
        } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
            $status = 405;
            $message = 'Method not allowed';
        } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException) {
            $status = 429;
            $message = 'Too many requests';
        }

        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        // Add debug information in development
        if (config('app.debug')) {
            $response['debug'] = [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ];
        }

        return response()->json($response, $status);
    }
}