<?php namespace App\Modules\Pages\Models;

use SoftDeletingTrait, BaseModel;

class Pagecat extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title'];

    protected $rules = [
        'title'     => 'required|min:3',
    ];

}