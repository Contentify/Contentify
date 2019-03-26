<?php namespace App\Exceptions;

use ErrorException;
use Exception;
use Config;
use Response;
use View;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use MsgException;

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
        // Laravel wraps any exceptions thrown in views in an error exception so we have to unwrap it
        // @see https://github.com/laravel/ideas/issues/956
        if ($exception instanceof ErrorException and
            $exception->getPrevious() and $exception->getPrevious() instanceof MsgException) {
            /* @var $innerException MsgException */
            $innerException = $exception->getPrevious();
            return $innerException->render($request);
        }

        if (! Config::get('app.debug')) { // If we are in debug mode we do not want to override Laravel's error output
            if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return Response::make(View::make('error_not_found'), 404);
            }

            return Response::make(View::make('error'), 500);
        }
        
        return parent::render($request, $exception);
    }
}
