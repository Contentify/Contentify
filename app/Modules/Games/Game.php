<?php

namespace App\Modules\Games;

use BaseModel;
use SoftDeletingTrait;

/**
 * @property \Carbon                       $created_at
 * @property \Carbon                       $deleted_at
 * @property string                        $title
 * @property string                        $short
 * @property string                        $icon
 * @property int                           $creator_id
 * @property int                           $updater_id
 * @property \App\Modules\Awards\Award[]   $awards
 * @property \App\Modules\Maps\Map[]       $maps
 * @property \App\Modules\Matches\Match[]  $matches
 * @property \App\Modules\Servers\Server[] $servers
 * @property \User                         $creator
 */
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
