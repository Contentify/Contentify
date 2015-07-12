<?php namespace Contentify\Middleware;

use Session, Carbon, App, File, Lang, Closure;

class UpdateUser {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*
        |--------------------------------------------------------------------------
        | Update User's Last Active Time
        |--------------------------------------------------------------------------
        |
        | To decide if a user si only or not we use an attribute in the 
        | users table/user model called last_active. We update it to 
        | the current time in a specific interval.
        |
        */

        if (user()) {
            $lastActive = Session::get('lastActiveUpdate', 0);

            if (time() - $lastActive > 60) {
                $user = user();
                $user->last_active = new Carbon();
                $user->save();
                Session::set('lastActiveUpdate', time());
            }            
        }

        /*
        |--------------------------------------------------------------------------
        | Language Settings
        |--------------------------------------------------------------------------
        |
        | Set the language for the user (also if not logged in)
        |
        */

        if (! Session::has('app.locale')) {
            if (user()) {
                Session::set('app.locale', user()->language->code);
                App::setLocale(Session::get('app.locale'));
            } else {
                if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                    $clientLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
                    $languages = File::directories(base_path().'/resources/lang');

                    array_walk($languages, function(&$value, $key)
                    {
                        $value = basename($value);
                    });

                    if (in_array($clientLanguage, $languages)) {
                        Session::set('app.locale', $clientLanguage);
                    } else {
                        Session::set('app.locale', Lang::getLocale());
                    }
                } else {
                    Session::set('app.locale', Lang::getLocale());
                }
            }
        } else {
            App::setLocale(Session::get('app.locale'));
        }

        Carbon::setToStringFormat(trans('app.date_format'));

        return $next($request);     
    }

}
