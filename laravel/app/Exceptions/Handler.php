<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
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
        if ($request->is('manage/*')) {
            $response = [];
            $error = $this->convertExceptionToResponse($exception);
            $response['status'] = $error->getStatusCode();
            $response['msg'] = 'something error';
            if (config('app.debug')) {
                $response['msg'] = empty($exception->getMessage()) ? 'something error' : $exception->getMessage();
                if ($error->getStatusCode() >= 500) {
                    if (config('app.debug')) {
                        $response['trace'] = $exception->getTraceAsString();
                        $response['code'] = $exception->getCode();
                    }
                }
            }
            $response['data'] = [];
            return response()->json($response, $error->getStatusCode());
        } else {
            return parent::render($request, $exception);
        }
    }
}
