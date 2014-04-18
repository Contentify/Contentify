<?php namespace App\Modules\Partners\Models;

use Ardent;

class Partner extends Ardent {

    protected $softDelete = true;

    protected $fillable = ['title', 'text', 'url', 'type', 'position'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    public static $rules = [
        'title'     => 'required',
        'url'       => 'url',
        'type'      => 'integer',
        'position'  => 'integer',
    ];

}