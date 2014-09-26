<?php namespace App\Modules\Countries\Models;

use SoftDeletingTrait, BaseModel;

class Country extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'code'];

    public static $fileHandling = ['icon' => ['type' => 'image']];

    protected $rules = [
        'title' => 'required',
        'code'  => 'required|min:2|max:3',
    ];

    public static $relationsData = [
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];
    
}