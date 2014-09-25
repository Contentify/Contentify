<?php namespace Contentify\Models;

use Cartalyst\Sentry\Groups\Eloquent\Group as SentryModel;

class Group extends SentryModel {

    /**
     * ID of the super admin group
     */
    const SUPER_ADMIN_GROUP = 5;

    /**
     * Allowed permissions values. 
     * Sentry only supports 0 and 1 per default so we change it.
     *
     * 0 = [none]
     * 1 = PERM_READ
     * 2 = PERM_CREATE
     * 3 = PERM_UPDATE
     * 4 = PERM_DELETE
     *
     * @var array
     */
    protected $allowedPermissionsValues = array(0, 1, 2, 3, 4);

}