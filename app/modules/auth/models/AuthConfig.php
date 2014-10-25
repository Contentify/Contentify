<?php namespace App\Modules\Auth\Models;

use ConfigBag;

class AuthConfigBag extends ConfigBag {

    protected $namespace = 'auth::';

    protected $fillable = [
        'registration',
    ];

    protected $rules = [
        'registration'  => 'boolean',
    ];

}