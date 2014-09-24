<?php namespace App\Modules\Teams\Models;

use SoftDeletingTrait, BaseModel;

class Teamcat extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title'];

    public static $rules = [
        'title'     => 'required'
    ];
 
    public static $relationsData = [
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];
       
}