<?php namespace App\Http\Middleware;

use Closure;
use Crypt;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Illuminate\Session\TokenMismatchException;
use MsgException;

class VerifyCsrfToken extends BaseVerifier
{

    /**
     * Handle an incoming request.
     * Note: This method overwrites Laravel's default handle() method.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     * @throws TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        if ($this->isReading($request)) {
            return $next($request);
        }

        if (! hash_equals($request->session()->token(), $request->input('_token')))
        {
            throw new TokenMismatchException;
        }

        /* 
         * Spam protection: Forms that have set a value for _created_at
         * are protected against mass submitting.
         * WARNING: Not sending the field will not trigger the verification!
         */
        if ($time = $request->input('_created_at'))
        {
            $time = Crypt::decrypt($time);

            if (is_numeric($time)) {
                $time = (int) $time;
                
                if ($time <= time() - 3) {
                    return $next($request);
                }
            }

            throw new MsgException(trans('app.spam_protection'));
        }

        return $next($request);
    }

}
