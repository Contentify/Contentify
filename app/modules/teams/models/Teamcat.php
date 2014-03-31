<?php namespace App\Modules\Teams\Models;

use Ardent;

class Teamcat extends Ardent {

    protected $softDelete = true;

    protected $fillable = ['title'];

    public static $rules = [
        'title'     => 'required'
    ];

}