<?php

namespace App\Modules\Opponents;

use BaseModel;
use SoftDeletingTrait;

/**
 * @property \Carbon                        $created_at
 * @property \Carbon                        $deleted_at
 * @property string                         $title
 * @property string                         $slug
 * @property string                         $short
 * @property string                         $url
 * @property string                         $lineup
 * @property int                            $country_id
 * @property string                         $image
 * @property int                            $creator_id
 * @property int                            $updater_id
 * @property \App\Modules\Matches\Match[]   $matches
 * @property \App\Modules\Countries\Country $country
 * @property \User                          $creator
 */
class Opponent extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $slugable = true;

    protected $fillable = ['title', 'short', 'url', 'lineup', 'country_id'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    protected $rules = [
        'title'         => 'required|min:3',
        'short'         => 'required|max:6',
        'url'           => 'nullable||url',
        'country_id'    => 'required|integer'
    ];

    public static $relationsData = [
        'matches'   => [
            self::HAS_MANY, 'App\Modules\Matches\Match', 'foreignKey' => 'right_team_id', 'dependency' => true
        ],
        'country'   => [self::BELONGS_TO, 'App\Modules\Countries\Country'],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}
