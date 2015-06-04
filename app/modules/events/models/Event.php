<?php namespace App\Modules\Events\Models;

use SoftDeletingTrait, BaseModel;

class Event extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at', 'starts_at'];

    protected $slugable = true;

    protected $fillable = ['title', 'text', 'url', 'location', 'starts_at'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    protected $rules = [
        'title'     => 'required|min:3',
        'url'       => 'sometimes|url',
    ];

    public static $relationsData = [
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}