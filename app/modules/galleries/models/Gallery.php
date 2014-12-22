<?php namespace App\Modules\Galleries\Models;

use SoftDeletingTrait, BaseModel;

class Gallery extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title'];

    protected $slugable = true;

    protected $rules = [
        'title'     => 'required|min:3',
    ];

    public static $relationsData = [
        'images'    => [self::HAS_MANY, 'App\Modules\Images\Models\Image'],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}