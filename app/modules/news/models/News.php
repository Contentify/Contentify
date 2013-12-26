<?php namespace App\Modules\News\Models;

class News extends \Ardent {

    protected $fillable = array('title', 'intro', 'text', 'published', 'internal', 'allow_comments', 'newscat_id');

    public static $rules = array(
        'title'   => 'required',
    );

    public function newscats()
    {
        return $this->hasOne('Newscats');
    }
}