<?php 

namespace App\Modules\Partners;

use SoftDeletingTrait, BaseModel;

/**
 * @property int $id
 * @property \Carbon $deleted_at
 * @property string $title
 */
class Partnercat extends BaseModel
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