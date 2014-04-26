<?php namespace App\Modules\Pages\Models;

use StiModel;

class Page extends StiModel {

    protected $table = 'pages';

    protected $subclassField = 'pagecat_id';

    protected $softDelete = true;

    protected $slugable = true;

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
        'pagecat' => [self::BELONGS_TO, 'App\Modules\Pages\Models\Pagecat'],
        'creator' => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}