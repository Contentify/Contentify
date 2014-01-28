<?php namespace App\Modules\Images\Models;

use Ardent;

class Image extends Ardent {

    protected $softDelete = true;

    protected $fillable = array('tags');

    public static $fileHandling = array('image' => ['type' => 'image', 'thumbnails' => '100']);

    public static $rules = array(
        'tags'     => 'required',
    );

}