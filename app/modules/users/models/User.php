<?php namespace App\Modules\Users\Models;

use User as BaseUser;
use Sentry, Ardent;

class User extends BaseUser {

    protected $fillable = ['activated'];

    public static $relationsData = [
        'groups' => [Ardent::BELONGS_TO_MANY, 'Group', 'table' => 'users_groups'],
    ];

    /**
     * Getter for $relationsData.
     * NOTE: This model does not inherit from Ardent.
     * The relations method is used to copy some of the Ardent behaviour.
     * 
     * @return array
     */
    public static function relations()
    {
        return static::$relationsData;
    }

    /**
     * The throttle system is not part of the Sentry core module.
     * This helper method accesses the banned attribute.
     *
     * @return boolean
     */
    public function isBanned()
    {
        // This is what Sentry gives us to get the banned attribute... Not cool.
        // If there is a better way let us know.
        $throttle = Sentry::findThrottlerByUserId($this->id);

        return $throttle->isBanned();
    }

}