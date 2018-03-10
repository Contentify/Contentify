<?php

namespace App\Modules\Opponents;

use SoftDeletingTrait, BaseModel;

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
        'url'           => 'sometimes|url',
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