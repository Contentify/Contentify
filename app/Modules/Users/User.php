<?php namespace App\Modules\Users;

use User as BaseUser;
use Sentry, BaseModel;

/**
 * NOTE: This is a helper class that extends the actual user class.
 */
class User extends BaseUser {

    protected $fillable = ['activated', 'relation_groups', 'relation_teams'];

    protected $slugable = true;

    public static $relationsData = [
        'groups'    => [BaseModel::BELONGS_TO_MANY, 'Group', 'table' => 'users_groups'],
        'teams'     => [BaseModel::BELONGS_TO_MANY, 'App\Modules\Teams\Models\Team', 'table' => 'team_user'],
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
     * This is a copy of an BaseModel method (for compatibility).
     * 
     * @return bool
     */
    public function modifiable()
    {
        return true;
    }

}