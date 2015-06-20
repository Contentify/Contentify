<?php namespace App\Modules\Groups;

use App\Modules\Groups\Permission;
use Hover, SoftDeletingTrait, Sentry, BaseModel;

/*
 * Important note:
 * This is not the same model that Sentry uses.
 * This model is only a helper so we can CRUD groups.
 * (See also: Sentry\Gropus\Eloquent\Group)
 */

class Group extends BaseModel {

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'permissions'];

    protected $rules = [
        'name'      => 'required|min:3',
    ];

    public static $relationsData = [
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

    public function modifiable()
    {
        return ($this->id > 5);
    }

    /**
     * Creates an array of permissions 
     * (Permission model with name, possible values and current value)
     * for the given group (or)
     * 
     * @param  int $id ID of a group
     * @return array
     */
    static public function permissions($id = null)
    {

        /*
         * Retrieve permission of the super admins group.
         * We assume the s. a. group has all available permissions on max level.
         */
        $group = Sentry::findGroupById(5);

        $originalPermissions = $group->getPermissions();

        /*
         * Retrieve permissions of a certain group
         */
        if ($id) {
            $group = Sentry::findGroupById($id);

            $currentPermissions = $group->getPermissions();
        }

        /*
         * Create an array with permissions (Permission model instances)
         */
        $permissions = [];
        foreach ($originalPermissions as $name => $value) {
            if ($value == 1) { // Boolean
                $values = [
                    0 => trans('app.no'), 
                    1 => trans('app.yes')
                ];
            } else { // Levels
                $values = [
                    0 => trans('groups::none'), 
                    1 => trans('groups::read'),
                    2 => trans('groups::create'),
                    3 => trans('groups::update'),
                    4 => trans('groups::delete'),
                ];
            }

            /*
             * Current permission value
             */
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