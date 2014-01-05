<?php namespace App\Modules\News\Models;

class Newscat extends \Ardent {

    protected $softDelete = true;

    protected $fillable = array('title');
    
    public static $rules = array(
        'title'   => 'required',
    );
}