<?php

namespace App\Modules\Cups;

use ConfigBag;

class CupConfigBag extends ConfigBag
{

    protected $namespace = 'cups::';

    protected $fillable = [
        'cup_points',
    ];

    protected $rules = [
        'cup_points'  => 'integer|min:1',
    ];

}