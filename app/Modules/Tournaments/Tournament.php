<?php 

namespace App\Modules\Tournaments;

use SoftDeletingTrait, BaseModel;

/**
 * @property int $id
 * @property \Carbon $deleted_at
 * @property string $title
 * @property string $short
 * @property string $url
 * @property string $icon
 */
class Tournament extends BaseModel 
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'short', 'url'];

    public static $fileHandling = ['icon' => ['type' => 'image']];

    protected $rules = [
        'title'     => 'required|min:3',
        'short'     => 'required|max:6',
        'url'       => 'sometimes|url',
    ];

    public static $relationsData = [
        'awards'    => [self::HAS_MANY, 'App\Modules\Awards\Award', 'dependency' => true],
        'matches'   => [self::HAS_MANY, 'App\Modules\Matches\Match', 'dependency' => true],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}