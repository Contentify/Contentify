<?php namespace App\Modules\Contact\Models;

use Ardent;

class ContactMessage extends Ardent {

    protected $softDelete = true;

    protected $fillable = ['username', 'email', 'title', 'text'];

    public static $rules = [
        'username'  => 'required',
        'email'     => 'required|email',
        'title'     => 'required',
        'text'      => 'required',
    ];

}