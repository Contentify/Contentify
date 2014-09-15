<?php namespace App\Modules\Adverts\Models;

use BaseModel;

class Advertcat extends BaseModel {

    protected $softDelete = true;

    protected $fillable = ['title'];
    
    public static $rules = [
        'title'   => 'required',
    ];
}