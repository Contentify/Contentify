<?php namespace App\Modules\Forums;

use ConfigBag;

class ForumConfigBag extends ConfigBag {

    protected $namespace = 'forums::';

    protected $fillable = [
        'example',
    ];

    protected $rules = [
        'example'  => 'integer',
    ];

}