<?php namespace App\Modules\Pages\Models;

use Ardent;

class Page extends Ardent {

    protected $table = 'pages';

    protected $softDelete = true;

    protected $fillable = [
        'title', 
        'text', 
        'published_at', 
        'published',
        'internal',
        'enable_comments',
        'pagecat_id'];

    public static $rules = [
        'title'     => 'required',
    ];

    public static $relationsData = [
        'creator' => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}