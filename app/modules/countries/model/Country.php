<?php namespace App\Modules\Countries\Models;

use Ardent;

class Country extends Ardent {

    protected $softDelete = true;

    protected $fillable = ['title', 'code'];
  
    public static $rules = [
        'title' => 'required',
        'code'  => 'required',
    ];
}