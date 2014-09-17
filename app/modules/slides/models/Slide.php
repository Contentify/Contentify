<?php namespace App\Modules\Slides\Models;

use SoftDeletingTrait, BaseModel;

class Slide extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'url', 'position', 'slidecat_id'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    public static $rules = [
        'title'         => 'required',
        'url'           => 'url',
    ];

    public static $relationsData = [
        'slidecat'   => [self::BELONGS_TO, 'App\Modules\Slides\Models\Slidecat'],
    ];

}