<?php namespace App\Modules\Galleries\Models;

//use App\Modules\Images\Models\Image;
use SoftDeletingTrait, BaseModel;

class Gallery extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title'];

    protected $slugable = true;

    public static $rules = [
        'title'     => 'required',
    ];

    public static $relationsData = [
        'images' => [self::HAS_MANY, 'App\Modules\Images\Models\Image']
    ];

}