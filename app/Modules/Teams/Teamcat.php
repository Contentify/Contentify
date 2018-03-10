<?php 

namespace App\Modules\Teams;

use SoftDeletingTrait, BaseModel;

class Teamcat extends BaseModel 
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title'];

    protected $rules = [
        'title'     => 'required|min:3',
    ];
 
    public static $relationsData = [
        'teams'     => [self::HAS_MANY, 'App\Modules\Teams\Team', 'dependency' => true],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];
       
}