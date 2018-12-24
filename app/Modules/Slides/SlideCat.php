<?php 

namespace App\Modules\Slides;

use BaseModel;
use SoftDeletingTrait;

/**
 * @property \Carbon                     $created_at
 * @property \Carbon                     $deleted_at
 * @property string                      $title
 * @property int                         $creator_id
 * @property int                         $updater_id
 * @property \App\Modules\Slides\Slide[] $slides
 * @property \User                       $creator
 */
class SlideCat extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title'];
    
    protected $rules = [
        'title'     => 'required|min:3',
    ];

    public static $relationsData = [
        'slides'    => [self::HAS_MANY, 'App\Modules\Slides\Slide', 'dependency' => true],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];
    
}
