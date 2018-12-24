<?php 

namespace App\Modules\Tournaments;

use BaseModel;
use SoftDeletingTrait;

/**
 * @property \Carbon                      $created_at
 * @property \Carbon                      $deleted_at
 * @property string                       $title
 * @property string                       $short
 * @property string                       $url
 * @property string                       $icon
 * @property int                          $creator_id
 * @property int                          $updater_id
 * @property \App\Modules\Awards\Award[]  $awards
 * @property \App\Modules\Matches\Match[] $matches
 * @property \User                        $creator
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
        'url'       => 'nullable||url',
    ];

    public static $relationsData = [
        'awards'    => [self::HAS_MANY, 'App\Modules\Awards\Award', 'dependency' => true],
        'matches'   => [self::HAS_MANY, 'App\Modules\Matches\Match', 'dependency' => true],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}
