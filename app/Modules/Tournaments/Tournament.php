<?php namespace App\Modules\Tournaments;

use SoftDeletingTrait, BaseModel;

class Tournament extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'short', 'url'];

    public static $fileHandling = ['icon' => ['type' => 'image']];

    protected $rules = [
        'title'     => 'required|min:3',
        'short'     => 'required|max:6',
        'url'       => 'sometimes|url',
    ];

    public static $relationsData = [
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}