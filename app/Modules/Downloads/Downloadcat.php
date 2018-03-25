<?php

namespace App\Modules\Downloads;

use SoftDeletingTrait, BaseModel;

/**
 * @property int $id
 * @property \Carbon $deleted_at
 * @property string $title
 */
class Downloadcat extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $slugable = true;

    protected $fillable = ['title'];
    
    protected $rules = [
        'title'     => 'required|min:3',
    ];

    public static $relationsData = [
        'downloads' => [self::HAS_MANY, 'App\Modules\Downloads\Download', 'dependency' => true],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}