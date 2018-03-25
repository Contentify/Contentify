<?php 

namespace App\Modules\Teams;

use SoftDeletingTrait, BaseModel;

/**
 * @property int $id
 * @property \Carbon $deleted_at
 * @property string $title
 */
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