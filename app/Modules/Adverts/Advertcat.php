<?php

namespace App\Modules\Adverts;

use SoftDeletingTrait, BaseModel;

class Advertcat extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title'];
    
    protected $rules = [
        'title'     => 'required|min:3',
    ];

    public static $relationsData = [
    	'adverts'   => [self::HAS_MANY, 'App\Modules\Adverts\Advert', 'dependency' => true],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}