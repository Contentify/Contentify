<?php namespace App\Modules\Partners\Models;

use SoftDeletingTrait, BaseModel;

class Partnercat extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title'];
    
    public static $rules = [
        'title'   => 'required',
    ];
}