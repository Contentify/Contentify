<?php

use Cartalyst\Sentry\Groups\Eloquent\Group as SentryModel;

class Group extends SentryModel {

    /**
     * Allowed permissions values. 
     * Sentry only supports 0 and 1 per default so we change it.
     *
     * Possible options:
     *    0 => Remove.
     *    1 => Add.
     *
     * @var array
     */
    protected $allowedPermissionsValues = array(0, 1, 2, 3, 4);

}