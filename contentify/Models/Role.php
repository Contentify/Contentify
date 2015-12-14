<?php namespace Contentify\Models;

use Cartalyst\Sentinel\Roles\EloquentRole as OriginalModel;

class Role extends OriginalModel {

    /**
     * ID of the super admin role
     */
    const SUPER_ADMIN_ROLE = 5;

    /**
     * Allowed permissions values. 
     * Sentinel only supports 0 and 1 per default so we override that.
     * @var array
     */
    /*protected $allowedPermissionsValues = array(
        0, 
        PERM_READ, 
        PERM_CREATE, 
        PERM_UPDATE, 
        PERM_DELETE
    );*/
    // TODO: Remove / change

}