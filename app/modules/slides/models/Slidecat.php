<?php namespace App\Modules\Slides\Models;

use SoftDeletingTrait, BaseModel;

class Slidecat extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title'];
    
    public static $rules = [
        'title'   => 'required',
    ];
}