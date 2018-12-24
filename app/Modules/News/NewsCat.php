<?php

namespace App\Modules\News;

use BaseModel;
use SoftDeletingTrait;

/**
 * @property \Carbon                  $created_at
 * @property \Carbon                  $deleted_at
 * @property string                   $title
 * @property string                   $image
 * @property \App\Modules\News\News[] $news
 * @property \User                    $creator
 */
class NewsCat extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title'];

    public static $fileHandling = ['image' => ['type' => 'image']];
    
    protected $rules = [
        'title'     => 'required|min:3',
    ];

    public static $relationsData = [
        'news'      => [self::HAS_MANY, 'App\Modules\News\News', 'dependency' => true],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];
    
}
