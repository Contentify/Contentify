<?php

namespace App\Modules\Downloads;

use BaseModel;
use SoftDeletingTrait;

/**
 * @property \Carbon                           $created_at
 * @property \Carbon                           $deleted_at
 * @property string                            $title
 * @property int                               $access_counter
 * @property int                               $creator_id
 * @property int                               $updater_id
 * @property \App\Modules\Downloads\Download[] $downloads
 * @property \User                             $creator
 */
class DownloadCat extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $slugable = true;

    protected $fillable = ['title'];
    
    protected $rules = [
        'title'     => 'required|min:3',
    ];

    public static $relationsData = [
        'downloads' => [self::HAS_MANY, 'App\Modules\Downloads\Download', 'dependency' => true],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}
