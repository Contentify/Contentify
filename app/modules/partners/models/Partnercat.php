<?php namespace App\Modules\Partners\Models;

use BaseModel;

class Partnercat extends BaseModel {

    protected $softDelete = true;

    protected $fillable = ['title'];
    
    public static $rules = [
        'title'   => 'required',
    ];
}