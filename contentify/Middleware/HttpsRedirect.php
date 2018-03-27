<?php

namespace Contentify\Middleware;

use Closure;
use Config;

class HttpsRedirect
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $apiKey = Config::get('steam-auth.api_key');

        // Redirect to HTTPS version of the current URL if the protocol is not HTTPS but HTTPS support is enabled
        if (! $request->secure() and $apiKey) {
            if (! $request->ajax()) { // Ignore AJAX requests / allow them to use HTTP if they want to
                if ($request->path() === '/') { // Only when on landing page
                    $newUrl = url('/', [], true);

                    return redirect($newUrl, 301); // 301 = Moved Permanently
                }
            }
        }

        return $next($request);
    }

}
