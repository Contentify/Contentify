<?php namespace App\Modules\Teams\Models;

use Ardent;

class Team extends Ardent {

    protected $softDelete = true;

    protected $fillable = ['title', 'description', 'position', 'teamcat_id'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    public static $rules = [
        'title'     => 'required',
        'position'  => 'integer',
    ];

    public static $relationsData = [
        'teamcat'   => [self::BELONGS_TO, 'App\Modules\Teams\Models\Teamcat'],
        'users'     => [self::BELONGS_TO_MANY, 'User'],
    ];

}