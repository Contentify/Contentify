<?php

namespace App\Modules\Auth;

use User;
use Sentinel;

class AuthManager
{

    /**
     * Name of the event that will be fired after a user has been login in
     */
    const EVENT_NAME_USER_LOGGED_IN = 'auth::userLoggedIn';

    /**
     * Name of the event that will be fired after a user has been login out
     */
    const EVENT_NAME_USER_LOGGED_OUT = 'auth::userLoggedOut';

    /**
     * Name of the event that will be fired after a new user has been registered
     */
    const EVENT_NAME_USER_REGISTERED = 'auth::userRegistered';

    /**
     * Login the current user (client session) via email and password.
     * Returns the user object or null.
     *
     * $credentials example:
     *
     * $credentials = [
     *     'email'     => Request::get('email'),
     *     'password'  => Request::get('password')
     * ];
     *
     * @param array $credentials
     * @return null|User
     */
    public function loginUserByEmail(array $credentials)
    {
        /** @var User $user */
        $user = Sentinel::authenticate($credentials, false); // Login the user (if possible)

        if ($user and $user->banned) {
            Sentinel::logout();
            $user = null;
        }

        if ($user) {
            event(self::EVENT_NAME_USER_LOGGED_IN, [$user]);
        }

        return $user;
    }

    /**
     * Logout the current user (client session)
     */
    public function logoutUser()
    {
        $user = user();

        Sentinel::logout();

        if ($user) {
            event(AuthManager::EVENT_NAME_USER_LOGGED_OUT, [$user]);
        }
    }
}
