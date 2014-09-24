<?php namespace App\Modules\Games\Models;

use SoftDeletingTrait, BaseModel;

class Game extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'short'];

    public static $fileHandling = ['icon' => ['type' => 'image']];

    public static $rules = [
        'title'     => 'required',
        'short'     => 'required',
    ];

    public static $relationsData = [
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}