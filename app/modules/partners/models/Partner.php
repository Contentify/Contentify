<?php namespace App\Modules\Partners\Models;

use BaseModel;

class Partner extends BaseModel {

    protected $softDelete = true;

    protected $fillable = ['title', 'text', 'url', 'layout_type', 'position'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    public static $rules = [
        'title'         => 'required',
        'url'           => 'url',
        'layout_type'   => 'integer',
        'position'      => 'integer',
    ];

}