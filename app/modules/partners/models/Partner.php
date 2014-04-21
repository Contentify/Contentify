<?php namespace App\Modules\Partners\Models;

use Ardent;

class Partner extends Ardent {

    protected $softDelete = true;

    protected $fillable = ['title', 'text', 'url', 'layout_type', 'position'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    public static $rules = [
        'title'         => 'required',
        'url'           => 'url',
        'layout_type'   => 'integer',
        'position'      => 'integer',
    ];

}