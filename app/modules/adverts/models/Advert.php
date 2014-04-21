<?php namespace App\Modules\Adverts\Models;

use Ardent;

class Advert extends Ardent {

    protected $softDelete = true;

    protected $fillable = ['title', 'code', 'url', 'type'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    public static $rules = [
        'title'     => 'required',
        'url'       => 'url',
        'type'      => 'integer'
    ];

}