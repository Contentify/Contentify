<?php namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Route;

class RouteServiceProvider extends ServiceProvider {

	/**
	 * This namespace is applied to the controller routes in your routes file.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'App\Http\Controllers';

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot(Router $router)
	{
		parent::boot($router);

		/*
		|--------------------------------------------------------------------------
		| Authentication Filters
		|--------------------------------------------------------------------------
		|
		| The following filters are used to verify that the user of the current
		| session is logged into this application. The "basic" filter easily
		| integrates HTTP Basic authentication for quick, simple checking.
		|
		*/

		Route::filter('admin', function()
		{
		    if (! Sentry::check()) {
		        if (Request::ajax()) {
		            return Response::make('Unauthorized', 401);
		        } else {
		            Session::set('redirect', Request::path());
		            return View::make('backend.auth');
		        }
		    }

		    if (! user()->hasAccess('backend')) {
		        if (Request::ajax()) {
		            return Response::make('Unauthorized', 401);
		        } else {
		            return View::make('backend.no_access');
		        }
		    }
		});

		Route::filter('auth', function()
		{
		    if (! Sentry::check()) {
		        if (Request::ajax()) {
		            return Response::make('Unauthorized', 401);
		        } else {
		            Session::set('redirect', Request::path());
		            return Redirect::to('auth/login');
		        }
		    }
		});

		/*
		|--------------------------------------------------------------------------
		| CSRF Protection Filter
		|--------------------------------------------------------------------------
		|
		| The CSRF filter is responsible for protecting your application against
		| cross-site request forgery attacks. If this special token in a user
		| session does not match the one given in this request, we'll bail.
		|
		*/

		Route::filter('csrf', function()
		{
		    if (Session::token() !== Input::get('_token'))
		    {
		        throw new Illuminate\Session\TokenMismatchException;
		    }

		    /* 
		     * Spam protection: Forms that have set a value for _created_at
		     * are protected against mass submitting.
		     * WARNING: Not sending the field will not trigger the verification!
		     */
		    if ($time = Input::get('_created_at'))
		    {
		        $time = Crypt::decrypt($time);

		        if (is_numeric($time)) {
		            $time = (int) $time;
		            
		            if ($time <= time() - 3) return;
		        }

		        throw new MsgException(trans('app.spam_protection'));
		    }
		});
	}

	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function map(Router $router)
	{
		$router->group(['namespace' => $this->namespace], function($router)
		{
			require app_path('Http/routes.php');
		});
	}

}
