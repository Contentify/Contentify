<?php namespace App\Http\Middleware;

use Closure;
use Sentinel;
use Session;

class Authenticate
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure                  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! Sentinel::check()) {
            if ($request->ajax()) {
                return response('Unauthorized', 401);
            } else {
                Session::put('redirect', $request->path());
                return redirect('auth/login');
            }
        }

        return $next($request);
    }

}
