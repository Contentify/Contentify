<?php namespace App\Modules\Galleries\Models;

use SoftDeletingTrait, BaseModel;

class Gallery extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title'];

    protected $slugable = true;

    public static $rules = [
        'title'     => 'required',
    ];

}