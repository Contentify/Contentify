<?php namespace App\Modules\Contact\Models;

class ContactMessage extends \Ardent {

    protected $fillable = array('username', 'email', 'title', 'text');

    public static $rules = array(
        'username'  => 'required',
        'email'     => 'required|email',
        'title'     => 'required',
        'text'      => 'required',
    );

}