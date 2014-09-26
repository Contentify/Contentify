<?php namespace Contentify\Models;

use Cartalyst\Sentry\Groups\Eloquent\Group as SentryModel;

class Group extends SentryModel {

    /**
     * ID of the super admin group
     */
    const SUPER_ADMIN_GROUP = 5;

    /**
     * Allowed permissions values. 
     * Sentry only supports 0 and 1 per default so we override that.
     * @var array
     */
    protected $allowedPermissionsValues = array(
        0, 
        PERM_READ, 
        PERM_CREATE, 
        PERM_UPDATE, 
        PERM_DELETE
    );

}