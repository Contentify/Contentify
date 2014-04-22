<?php namespace App\Modules\News\Models;

use BaseModel;

class Newscat extends BaseModel {

    protected $softDelete = true;

    protected $fillable = ['title'];

    public static $fileHandling = ['image' => ['type' => 'image']];
    
    public static $rules = [
        'title'   => 'required',
    ];
}