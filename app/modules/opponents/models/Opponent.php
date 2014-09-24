<?php namespace App\Modules\Opponents\Models;

use SoftDeletingTrait, BaseModel;

class Opponent extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $slugable = true;

    protected $fillable = ['title', 'short', 'url', 'lineup', 'country_id'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    public static $rules = [
        'title'     => 'required',
        'short'     => 'required',
    ];

    public static $relationsData = [
        'country'   => [self::BELONGS_TO, 'App\Modules\Countries\Models\Country'],
    ];

}