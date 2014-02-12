<?php namespace App\Modules\Images\Models;

use Ardent;

class Image extends Ardent {

    protected $softDelete = true;

    protected $fillable = ['tags'];

    public static $fileHandling = ['image' => ['type' => 'image', 'thumbnails' => '100']];

    public static $rules = [
        'tags'     => 'required',
    ];

}