<?php namespace App\Http\Middleware;

use Closure;
use Sentinel;
use Session;

class VerifyAdminAccess
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
        $path = $request->path();

        if ($path === 'admin' or starts_with($path, 'admin/')) {
            if (! Sentinel::check()) {
                if ($request->ajax()) {
                    return response('Unauthorized', 401);
                } else {
                    Session::put('redirect', $request->path());
                    return response(view('backend.auth'));
                }
            }

            if (! user()->hasAccess('backend')) {
                if ($request->ajax()) {
                    return response('Unauthorized', 401);
                } else {
                    return response(view('backend.no_access'));
                }
            }
        }

        return $next($request);
    }

}
