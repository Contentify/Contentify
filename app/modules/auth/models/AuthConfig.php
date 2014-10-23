<?php namespace App\Modules\Auth\Models;

use ConfigBag;

class AuthConfigBag extends ConfigBag {

    protected $namespace = 'auth::';

    protected $fillable = [
        'registration',
        'unicorns',
    ];

    protected $rules = [
        'registration'  => 'boolean',
    ];

}