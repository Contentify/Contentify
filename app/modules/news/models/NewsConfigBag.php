<?php namespace App\Modules\News\Models;

use ConfigBag;

class NewsConfigBag extends ConfigBag {

    protected $namespace = 'news::';

    protected $fillable = [
        'example',
    ];

    protected $rules = [
        'example'  => 'integer',
    ];

}