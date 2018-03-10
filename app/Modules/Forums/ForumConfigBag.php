<?php

namespace App\Modules\Forums;

use ConfigBag;

class ForumConfigBag extends ConfigBag
{

    protected $namespace = 'forums::';

    protected $fillable = [
        'reports',
    ];

    protected $rules = [
        'reports'  => 'boolean',
    ];

}