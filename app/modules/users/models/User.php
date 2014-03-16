<?php namespace App\Modules\Users\Models;

use User as BaseUser;

class User extends BaseUser {

    protected $fillable = ['activated'];

}