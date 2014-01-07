<?php namespace App\Modules\News\Models;

use Ardent;

class Newscat extends Ardent {

    protected $softDelete = true;

    protected $fillable = array('title');
    
    public static $rules = array(
        'title'   => 'required',
    );
}