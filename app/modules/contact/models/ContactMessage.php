<?php namespace App\Modules\Contact\Models;

use SoftDeletingTrait, BaseModel;

class ContactMessage extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['username', 'email', 'title', 'text'];

    public static $rules = [
        'username'  => 'required',
        'email'     => 'required|email',
        'title'     => 'required',
        'text'      => 'required',
    ];

}