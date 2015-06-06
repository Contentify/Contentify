<?php namespace App\Exceptions;

use Exception, Config, Response, View;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		'Symfony\Component\HttpKernel\Exception\HttpException'
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
		return parent::report($exception);
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
	        return Response::make(View::make('error_message', compact('exception')), 404);
	    }

	    if (! Config::get('app.debug')) { // If we are in debug mode we do not want to override Laravel's error output
	        if (is_a($exception, 'Illuminate\Database\Eloquent\ModelNotFoundException')) {
	            return Response::make(View::make('error_not_found'), 404);
	        }

	        return Response::make(View::make('error'), 500);
	    }
		
		return parent::render($request, $exception);
	}

}
