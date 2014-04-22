<?php namespace App\Modules\Adverts\Models;

use BaseModel;

class Advert extends BaseModel {

    protected $softDelete = true;

    protected $fillable = ['title', 'code', 'url', 'layout_type'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    public static $rules = [
        'title'         => 'required',
        'url'           => 'url',
        'layout_type'   => 'integer'
    ];

}