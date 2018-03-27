<?php

namespace App\Modules\Navigations;

use BaseModel;
use SoftDeletingTrait;

/**
 * @property int $id
 * @property \Carbon $deleted_at
 * @property string $title
 * @property bool $translate
 * @property string $items
 * @property \User $creator
 */
class Navigation extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'translate', 'items'];

    protected $rules = [
        'title'     => 'required|min:3',
        'translate' => 'boolean',
    ];

    public static $relationsData = [
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}