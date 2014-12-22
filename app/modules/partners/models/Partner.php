<?php namespace App\Modules\Partners\Models;

use SoftDeletingTrait, BaseModel;

class Partner extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'text', 'url', 'position', 'partnercat_id'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    protected $rules = [
        'title'         => 'required|min:3',
        'url'           => 'required|url',
        'position'      => 'required|integer',
    ];

    public static $relationsData = [
        'partnercat'    => [self::BELONGS_TO, 'App\Modules\Partners\Models\Partnercat'],
        'creator'       => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}