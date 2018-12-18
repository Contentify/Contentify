<?php

namespace App\Modules\Countries;

use BaseModel;
use SoftDeletingTrait;

/**
 * @property \Carbon                           $created_at
 * @property \Carbon                           $deleted_at
 * @property string                            $title
 * @property string                            $code
 * @property string                            $icon
 * @property int                               $creator_id
 * @property int                               $updater_id
 * @property \User[]                           $users
 * @property \App\Modules\Opponents\Opponent[] $opponents
 * @property \User                             $creator
 */
class Country extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'code'];

    public static $fileHandling = ['icon' => ['type' => 'image']];

    protected $rules = [
        'title' => 'required|min:3',
        'code'  => 'required|min:2|max:3',
    ];

    public static $relationsData = [
        'users'     => [self::HAS_MANY, 'User', 'dependency' => true],
        'opponents' => [self::HAS_MANY, 'App\Modules\Opponents\Opponent', 'dependency' => true],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];
    
}
