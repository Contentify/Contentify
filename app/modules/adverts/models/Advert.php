<?php namespace App\Modules\Adverts\Models;

use SoftDeletingTrait, BaseModel;

class Advert extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'code', 'url', 'advertcat_id'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    protected $rules = [
        'title'         => 'required',
        'url'           => 'required|url',
        'advertcat_id'  => 'required|integer'
    ];

    public static $relationsData = [
        'advertcat' => [self::BELONGS_TO, 'App\Modules\Adverts\Models\Advertcat'],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}