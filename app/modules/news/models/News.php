<?php namespace App\Modules\News\Models;

class News extends \Ardent {

    protected $softDelete = true;

    protected $fillable = array('title', 'intro', 'text', 'published', 'internal', 'allow_comments', 'newscat_id');

    public static $rules = array(
        'title'   => 'required',
    );

    public static $relationsData = array(
        'newscat' => array(self::BELONGS_TO, 'App\Modules\News\Models\Newscat'),
    );
}