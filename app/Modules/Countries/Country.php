<?php

namespace App\Modules\Countries;

use SoftDeletingTrait, BaseModel;

class Country extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'code'];

    public static $fileHandling = ['icon' => ['type' => 'image']];

    protected $rules = [
        'title' => 'required|min:3',
        'code'  => 'required|min:2|max:3',
    ];

    public static $relationsData = [
        'users'     => [self::HAS_MANY, 'User', 'dependency' => true],
        'opponents' => [self::HAS_MANY, 'App\Modules\Opponents\Opponent', 'dependency' => true],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];
    
}