<?php namespace App\Http\Middleware;

use Session, Sentry, Closure;

class Authenticate {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! Sentry::check()) {
            if ($request->ajax()) {
                return response('Unauthorized', 401);
            } else {
                Session::set('redirect', $request->path());
                return redirect('auth/login');
            }
        }

        return $next($request);     
    }

}
