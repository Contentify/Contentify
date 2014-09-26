<?php namespace App\Modules\Downloads\Models;

use SoftDeletingTrait, BaseModel;

class Downloadcat extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $slugable = true;

    protected $fillable = ['title'];
    
    protected $rules = [
        'title'   => 'required',
    ];

    public static $relationsData = [
        'downloads' => [self::HAS_MANY, 'App\Modules\Downloads\Models\Download'],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}