<?php namespace App\Modules\Games\Models;

class Game extends \Ardent {

    protected $fillable = array('title', 'tag', 'image');

    public static $rules = array(
        'title'   => 'required',
        'tag'     => 'required',
    );

}