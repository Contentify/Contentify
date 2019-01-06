<?php namespace App\Exceptions;

use Exception, Config, Response, View;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if (is_a($exception, 'MsgException')) {
            return Response::make(View::make('error_message', compact('exception')), 500);
        }
        // Laravel wraps any MsgException in an error exception so we have to unwrap it:
        if ($exception->getPrevious() and is_a($exception->getPrevious(), 'MsgException')) {
            return Response::make(View::make('error_message', ['exception' => $exception->getPrevious()]), 500);
        }

        if (! Config::get('app.debug')) { // If we are in debug mode we do not want to override Laravel's error output
            if (is_a($exception, \Illuminate\Database\Eloquent\ModelNotFoundException::class)) {
                return Response::make(View::make('error_not_found'), 404);
            }

            return Response::make(View::make('error'), 500);
        }
        
        return parent::render($request, $exception);
    }

}
