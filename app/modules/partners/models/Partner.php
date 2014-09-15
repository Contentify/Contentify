<?php namespace App\Modules\Partners\Models;

use SoftDeletingTrait, BaseModel;

class Partner extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'text', 'url', 'position', 'partnercat_id'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    public static $rules = [
        'title'         => 'required',
        'url'           => 'url',
        'position'      => 'integer',
    ];

    public static $relationsData = [
        'partnercat'   => [self::BELONGS_TO, 'App\Modules\Partners\Models\Partnercat'],
    ];

}