<?php namespace App\Modules\Images\Models;

use BaseModel;

class Image extends BaseModel {

    protected $fillable = ['tags', 'gallery_id', 'title'];

    public static $fileHandling = ['image' => ['type' => 'image', 'thumbnails' => [100, 200]]];

    public static $rules = [
        'tags'     => 'required',
    ];

    public static $relationsData = [
        'gallery' => [self::BELONGS_TO, 'App\Modules\Galleries\Models\Gallery']
    ];

}