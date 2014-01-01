<?php namespace App\Modules\Games\Models;

class Game extends \Ardent {

    protected $softDelete = true;

    protected $fillable = array('title', 'tag');

    public static $rules = array(
        'title'   => 'required',
        'tag'     => 'required',
    );

}