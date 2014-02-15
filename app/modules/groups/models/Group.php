<?php namespace App\Modules\Groups\Models;

use Ardent;

/*
 * Important note:
 * This is not the same model that Sentry uses.
 * This model is only a helper so we can CRUD groups.
 * (See also: Sentry\Gropus\Eloquent\Group)
 */

class Group extends Ardent {

    //protected $softDelete = true;

    protected $fillable = ['title', 'permissions'];

    public static $rules = [
        'title'     => 'required',
    ];

}