<?php

namespace App\Modules\Maps;

use SoftDeletingTrait, BaseModel;

/**
 * @property int $id
 * @property \Carbon $deleted_at
 * @property string $title
 * @property int $game_id
 * @property string $image
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