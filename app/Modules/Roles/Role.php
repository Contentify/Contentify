<?php 

namespace App\Modules\Roles;

use BaseModel;
use Sentinel;

/**
 * Important note:
 * This is not the same model that Sentinel uses.
 * This model is only a helper so we can CRUD roles.
 * (See also: Cartalyst\Sentinel\Roles\EloquentRole)
 *
 * @property \Carbon $created_at
 * @property \Carbon $deleted_at
 * @property string  $name
 * @property string  $slug
 * @property string  $permissions
 * @property \User[] $uses
 * @property \User   $creator
 */
class Role extends BaseModel
{

    protected $dates = ['deleted_at'];

    protected $slugable = true;

    protected $fillable = ['name', 'permissions'];

    protected $rules = [
        'name'      => 'required|min:3',
    ];

    public static $relationsData = [
        'users'     => [self::BELONGS_TO_MANY, 'User', 'table' => 'role_users', 'dependency' => true],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

    public static function boot()
    {
        parent::boot();

        self::saving(function(self $role)
        {
           if (! $role->slug) {
               $role->createSlug(true, 'name');
           }
        });
    }

    public function modifiable()
    {
        $adminRole = Sentinel::findRoleBySlug('super-admins');

        return (parent::modifiable() and $this->id > $adminRole->id);
    }

    /**
     * Creates an array of permissions (Permission model with name,
     * possible values and current value) for the given role
     * 
     * @param  int $roleId The ID of a role
     * @return Permission[]
     */
    static public function permissions($roleId = null)
    {
        /*
         * Retrieve permission of the super admins role.
         * We assume this role has all available permissions on max level.
         */
        /** @var \Cartalyst\Sentinel\Roles\EloquentRole $role */
        $role = Sentinel::findRoleBySlug('super-admins');

        $originalPermissions = $role->getPermissions();

        /*
         * Retrieve permissions of a certain role
         */
        if ($roleId) {
            $role = Sentinel::findRoleById($roleId);

            $currentPermissions = $role->getPermissions();
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
                $values = self::getPermissionTranslations();
            }

            /*
             * Current permission value
             */
            if ($roleId and isset($currentPermissions[$name])) {
                $current = $currentPermissions[$name];
            } else {
                $current = null;
            }

            $permissions[] = new Permission($name, $values, $current);
        }

        return $permissions;
    }

    /**
     * Returns translations of the permission level names
     *
     * @return string[]
     */
    public static function getPermissionTranslations()
    {
        return [
            0 => trans('app.permission_none'),
            1 => trans('app.permission_read'),
            2 => trans('app.permission_create'),
            3 => trans('app.permission_update'),
            4 => trans('app.permission_delete'),
        ];
    }

}
