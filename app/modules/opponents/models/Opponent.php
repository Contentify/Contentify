<?php namespace App\Modules\Opponents\Models;

use SoftDeletingTrait, BaseModel;

class Opponent extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $slugable = true;

    protected $fillable = ['title', 'short', 'url', 'lineup', 'country_id'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    protected $rules = [
        'title'         => 'required',
        'short'         => 'required|max:6',
        'url'           => 'sometimes|url',
        'country_id'    => 'required|integer'
    ];

    public static $relationsData = [
        'country'   => [self::BELONGS_TO, 'App\Modules\Countries\Models\Country'],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}