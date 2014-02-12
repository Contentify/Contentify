<?php namespace App\Modules\News\Models;

use Ardent;

class Newscat extends Ardent {

    protected $softDelete = true;

    protected $fillable = ['title'];

    public static $fileHandling = ['image' => ['type' => 'image']];
    
    public static $rules = [
        'title'   => 'required',
    ];
}