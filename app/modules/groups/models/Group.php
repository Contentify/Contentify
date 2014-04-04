<?php namespace App\Modules\Groups\Models;

use App\Modules\Groups\Models\Permission;
use Sentry, Ardent;

/*
 * Important note:
 * This is not the same model that Sentry uses.
 * This model is only a helper so we can CRUD groups.
 * (See also: Sentry\Gropus\Eloquent\Group)
 */

class Group extends Ardent {

    //protected $softDelete = true;

    protected $fillable = ['name', 'permissions'];

    public static $rules = [
        'name'     => 'required',
    ];

    static public function permissions($id = null)
    {
        // Find the super admins group using the group id
        $group = Sentry::findGroupById(5);

        $originalPermissions = $group->getPermissions();

        if ($id) {
            $group = Sentry::findGroupById($id);

            $currentPermissions = $group->getPermissions();
        }

        $permissions = [];
        foreach ($originalPermissions as $name => $value) {
            if ($value == 1) {
                $values = [
                    0 => trans('app.no'), 
                    1 => trans('app.yes')
                ];
            } else {
                $values = [
                    0 => trans('groups::none'), 
                    1 => trans('groups::read'),
                    2 => trans('groups::create'),
                    3 => trans('groups::update'),
                    4 => trans('groups::delete'),
                ];
            }

            if ($id and isset($currentPermissions[$name])) {
                $current = $currentPermissions[$name];
            } else {
                $current = null;
            }

            $permissions[] = new Permission($name, $values, $current);
        }

        return $permissions;
    }

}