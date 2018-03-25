<?php 

namespace App\Modules\Slides;

use SoftDeletingTrait, BaseModel;

/**
 * @property int $id
 * @property \Carbon $deleted_at
 * @property string $title
 */
class Slidecat extends BaseModel
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