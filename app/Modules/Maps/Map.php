<?php

namespace App\Modules\Maps;

use BaseModel;
use SoftDeletingTrait;

/**
 * @property \Carbon                 $created_at
 * @property \Carbon                 $deleted_at
 * @property string                  $title
 * @property int                     $game_id
 * @property string                  $image
 * @property int                     $creator_id
 * @property int                     $updater_id
 * @property \App\Modules\Games\Game $game
 * @property \User                   $creator
 */
class Map extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'game_id'];

    public static $fileHandling = ['image' => ['type' => 'image', 'thumbnails' => 16]];

    protected $rules = [
        'title'     => 'required|min:3',
        'game_id'   => 'required|integer',
    ];

    public static $relationsData = [
        'game'      => [self::BELONGS_TO, 'App\Modules\Games\Game'],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}
