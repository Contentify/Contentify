<?php namespace App\Modules\Pages\Models;

use BaseModel;

class Pagecat extends BaseModel {

    protected $softDelete = true;

    protected $fillable = ['title'];

    public static $rules = [
        'title'   => 'required',
    ];

}