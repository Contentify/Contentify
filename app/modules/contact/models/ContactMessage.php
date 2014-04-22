<?php namespace App\Modules\Contact\Models;

use BaseModel;

class ContactMessage extends BaseModel {

    protected $softDelete = true;

    protected $fillable = ['username', 'email', 'title', 'text'];

    public static $rules = [
        'username'  => 'required',
        'email'     => 'required|email',
        'title'     => 'required',
        'text'      => 'required',
    ];

}