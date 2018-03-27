<?php 

namespace App\Modules\Users;

use BaseModel;
use User as BaseUser;

/**
 * NOTE: This is a helper class that extends the actual user class.
 *
 * @property bool $banned
 * @property \App\Modules\Roles\Role[] $roles
 * @property \App\Modules\Teams\Team[] $teams
 */
class User extends BaseUser 
{

    protected $fillable = ['banned', 'relation_roles', 'relation_teams'];

    protected $slugable = true;

    public static $relationsData = [
        'roles'     => [BaseModel::BELONGS_TO_MANY, 'App\Modules\Roles\Role', 'table' => 'role_users'],
        'teams'     => [BaseModel::BELONGS_TO_MANY, 'App\Modules\Teams\Team', 'table' => 'team_user'],
    ];
    
    /**
     * Getter for $relationsData.
     * NOTE: This model does not inherit from BaseModel.
     * The relations method is used to copy some of the BaseModel's behaviour.
     * 
     * @return array
     */
    public static function relations()
    {
        return static::$relationsData;
    }

    /**
     * This is a copy of a BaseModel method (for compatibility).
     * 
     * @return bool
     */
    public function modifiable()
    {
        return true;
    }

}