<?php

namespace App\Exceptions;

use App\Traits\HttpResponses;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use HttpResponses;
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

    // Add this method to handle custom exceptions
    // can access all Exceptions via its type like instanceof AuthorizationException and handle it
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AccessDeniedHttpException || $exception instanceof AuthorizationException ) {
            return $this->error('', 'Yor are not authorized', 403);
        }

        if ($exception instanceof ModelNotFoundException) {
            return $this->error('', 'Resource not found', 404);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->error('', 'This Route not found', 404);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->error('', 'Yor are not authenticated', 404);
        }

        return parent::render($request, $exception);
    }
}
