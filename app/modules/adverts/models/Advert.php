<?php namespace App\Modules\Adverts\Models;

use BaseModel;

class Advert extends BaseModel {

    protected $softDelete = true;

    protected $fillable = ['title', 'code', 'url', 'advertcat_id'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    public static $rules = [
        'title'         => 'required',
        'url'           => 'url',
    ];

    public static $relationsData = [
        'advertcat'   => [self::BELONGS_TO, 'App\Modules\Adverts\Models\Advertcat'],
    ];

}