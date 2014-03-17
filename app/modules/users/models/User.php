<?php namespace App\Modules\Users\Models;

use Ardent;
use User as BaseUser;

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

}