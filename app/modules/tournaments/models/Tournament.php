<?php namespace App\Modules\Tournaments\Models;

use SoftDeletingTrait, BaseModel;

class Tournament extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'short'];

    public static $fileHandling = ['icon' => ['type' => 'image']];

    public static $rules = [
        'title'     => 'required',
        'short'     => 'required',
    ];

}