<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            $message = 'Erreur 404 : La page est introuvable.';
            return response()->view('errors.http', compact('message'), Response::HTTP_NOT_FOUND);
        }

        
        if ($exception instanceof AuthenticationException) {
            $message = 'Erreur 401 : vous n\'avez pas l\'autorisation d\'accéder à cette page, veuillez vous connecter.';
            return response()->view('errors.http', compact('message'), Response::HTTP_UNAUTHORIZED);
        }
        
        if ($exception instanceof AuthorizationException) {
            $message = 'Erreur 403 : Accès refusé.';
            return response()->view('errors.http', compact('message'), Response::HTTP_FORBIDDEN);
        }

        return parent::render($request, $exception);
    }
}
