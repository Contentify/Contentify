<?php namespace App\Modules\Matches\Models;

use SoftDeletingTrait, BaseModel;

class Matchresult extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = [];

    public static $rules = [
    ];

}