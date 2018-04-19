<?php

namespace Contentify\Middleware;

use App;
use Carbon;
use Closure;
use DB;
use Lang;
use Session;

/**
 * This middleware class deals with user related data
 */
class UpdateUser
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Closure                  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*
        |--------------------------------------------------------------------------
        | Visitor Statistics
        |--------------------------------------------------------------------------
        |
        | Updates the global visitor statistics.
        |
        */

        if (! App::runningInConsole() and installed()) {
            $today          = time();
            $isNewVisitor   = (Session::get('ipLogged') == null);

            if (Session::get('ipLogged') and (Session::get('ipLogged') != date('d', $today))) {
                $isNewVisitor = true; // Change of day makes every user a new visitor
            }

            if ($isNewVisitor) {
                $ip = getenv('REMOTE_ADDR'); // Get the client agent's IP

                $rowsAffected = DB::table('visits')->whereIp($ip)->whereVisitedAt(date('Y-m-d', $today))
                                    ->increment('user_agents');

                if (! $rowsAffected) {
                    DB::table('visits')
                        ->insert(array('ip' => $ip, 'user_agents' => 1, 'visited_at' => date('Y-m-d', $today)));
                }
                
                Session::put('ipLogged', date('d', $today)); // Keep in our session-mind the day we logged this IP
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Update User's Last Active Time
        |--------------------------------------------------------------------------
        |
        | To decide if a user is online or not we use an attribute in the
        | users table/model called last_active. We update it to 
        | the current time in a specific interval.
        |
        */

        if (installed() and user()) {
            $lastActive = Session::get('lastActiveUpdate', 0);

            if (time() - $lastActive > 60) {
                $user = user();
                $user->last_active = new Carbon();
                $user->save();
                Session::put('lastActiveUpdate', time());
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
                Session::put('app.locale', user()->language->code);
                App::setLocale(Session::get('app.locale'));
            } else {
                if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                    $clientLanguageCode = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
                    $languageCodes = Lang::languageCodes();

                    if (in_array($clientLanguageCode, $languageCodes)) {
                        Session::put('app.locale', $clientLanguageCode);
                    } else {
                        Session::put('app.locale', Lang::getLocale());
                    }
                } else {
                    Session::put('app.locale', Lang::getLocale());
                }
            }
        } else {
            App::setLocale(Session::get('app.locale'));
        }

        Carbon::setToStringFormat(trans('app.date_format'));

        return $next($request);
    }

}
