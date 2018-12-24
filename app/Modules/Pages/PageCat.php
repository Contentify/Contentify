<?php 

namespace App\Modules\Pages;

use BaseModel;
use SoftDeletingTrait;

/**
 * @property \Carbon                   $created_at
 * @property \Carbon                   $deleted_at
 * @property string                    $title
 * @property \App\Modules\Pages\Page[] $pages
 */
class PageCat extends BaseModel
{

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
