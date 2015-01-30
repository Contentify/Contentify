<?php namespace App\Modules\Navigations\Models;

use SoftDeletingTrait, BaseModel;

class Navigation extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'items'];

    protected $rules = [
        'title'     => 'required|min:3',
    ];

    public static $relationsData = [
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}