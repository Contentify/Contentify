<?php namespace App\Modules\Partners\Models;

use BaseModel;

class Partner extends BaseModel {

    protected $softDelete = true;

    protected $fillable = ['title', 'text', 'url', 'position'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    public static $rules = [
        'title'         => 'required',
        'url'           => 'url',
        'position'      => 'integer',
    ];

    public static $relationsData = [
        'partnercat'   => [self::BELONGS_TO, 'App\Modules\Partners\Models\Partnercat'],
    ];

}