<?php namespace App\Modules\Images\Models;

use BaseModel;

class Image extends BaseModel {

    protected $softDelete = false;

    protected $fillable = ['tags'];

    public static $fileHandling = ['image' => ['type' => 'image', 'thumbnails' => '100']];

    public static $rules = [
        'tags'     => 'required',
    ];

}