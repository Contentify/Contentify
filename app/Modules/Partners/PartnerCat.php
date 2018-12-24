<?php 

namespace App\Modules\Partners;

use BaseModel;
use SoftDeletingTrait;

/**
 * @property \Carbon                         $created_at
 * @property \Carbon                         $deleted_at
 * @property string                          $title
 * @property int                             $creator_id
 * @property int                             $updater_id
 * @property \App\Modules\Partners\Partner[] $partners
 * @property \User                           $creator
 */
class PartnerCat extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title'];
    
    protected $rules = [
        'title'     => 'required|min:3',
    ];

    public static $relationsData = [
        'partners'  => [self::HAS_MANY, 'App\Modules\Partners\Partner', 'dependency' => true],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}
