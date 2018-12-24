<?php 

namespace App\Modules\Teams;

use BaseModel;
use SoftDeletingTrait;

/**
 * @property \Carbon                   $created_at
 * @property \Carbon                   $deleted_at
 * @property string                    $title
 * @property int                       $creator_id
 * @property int                       $updater_id
 * @property \App\Modules\Teams\Team[] $teams
 * @property \User                     $creator
 */
class TeamCat extends BaseModel
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
