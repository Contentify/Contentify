<?php 

namespace App\Modules\Pages;

use SoftDeletingTrait, BaseModel;

class Pagecat extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title'];

    protected $rules = [
        'title'     => 'required|min:3',
    ];

    public static $relationsData = [
        'pages' => [self::HAS_MANY, 'App\Modules\Pages\Page', 'dependency' => true],
    ];

}