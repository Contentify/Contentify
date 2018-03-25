<?php

namespace App\Modules\Galleries;

use SoftDeletingTrait, BaseModel;

/**
 * @property int $id
 * @property \Carbon $deleted_at
 * @property string $title
 * @property string $slug
 * @property \App\Modules\Images\Image[] $images
 * @property \User $creator
 */
class Gallery extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title'];

    protected $slugable = true;

    protected $rules = [
        'title'     => 'required|min:3',
    ];

    public static $relationsData = [
        'images'    => [self::HAS_MANY, 'App\Modules\Images\Image'], // Probably this is not a strong dependency.
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}