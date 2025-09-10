<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;


class Handler extends ExceptionHandler
{
    use ApiResponse;

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

        $this->renderable(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return $this->errorResponse($e->getMessage() ?: 'Unauthenticated', Response::HTTP_UNAUTHORIZED);
            }
        });

        // Override the default unauthenticated method to ensure proper API responses
        $this->renderable(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                return $this->errorResponse($e->getMessage() ?: 'Unauthenticated', Response::HTTP_UNAUTHORIZED);
            }
        });

        // Handle general exceptions for API routes
        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
                $message = $e->getMessage() ?: 'Server Error';

                if (app()->environment('production')) {
                    $message = $statusCode == Response::HTTP_INTERNAL_SERVER_ERROR
                        ? 'Server Error'
                        : $message;
                }

                return $this->errorResponse($message, $statusCode);
            }
        });

        $this->renderable(function (ValidationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => $e->validator->errors(),
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        });
    }
}
