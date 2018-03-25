<?php

namespace App\Modules\Cups;

use ConfigBag;

/**
 * @property int $cup_points
 */
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