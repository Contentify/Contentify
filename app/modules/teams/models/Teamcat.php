<?php namespace App\Modules\Teams\Models;

use BaseModel;

class Teamcat extends BaseModel {

    protected $softDelete = true;

    protected $fillable = ['title'];

    public static $rules = [
        'title'     => 'required'
    ];
    
}