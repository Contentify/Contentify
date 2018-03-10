<?php

namespace App\Modules\Games;

use SoftDeletingTrait, BaseModel;

class Game extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'short'];

    public static $fileHandling = ['icon' => ['type' => 'image']];

    protected $rules = [
        'title'     => 'required|min:3',
        'short'     => 'required|max:6',
    ];

    public static $relationsData = [
        'awards'    => [self::HAS_MANY, 'App\Modules\Awards\Award', 'dependency' => true],
        'maps'      => [self::HAS_MANY, 'App\Modules\Maps\Map', 'dependency' => true],
        'matches'   => [self::HAS_MANY, 'App\Modules\Matches\Match', 'dependency' => true],
        'servers'   => [self::HAS_MANY, 'App\Modules\Servers\Server', 'dependency' => true],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}