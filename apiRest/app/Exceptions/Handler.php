<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
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

    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            $data = [
                'mensaje' => ('Los datos proporcionados no son validos.'),
                'errors' => $exception->errors(),
            ]   
        ], $exception->status);
    }

    public function render($request, Throwable $exception)
    {
        if($exception instanceof ModelNotFoundException){
            return response()->json(
                $data = [
                    'respuesta' => false,
                    'mensaje' => 'Error modelo no encontrado'
                ], 
                400
            );
        }

        if($exception instanceof RouteNotFoundException){
            return response()->json(
                $data = [
                    'respuesta' => false,
                    'mensaje' => 'La ruta no se ha encontrado, sin permisos'
                ], 
                401
            );
        }

        return parent::render($request, $exception);
    }
}
