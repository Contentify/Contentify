<?php

namespace App\Modules\Questions;

use BaseModel;
use SoftDeletingTrait;

/**
 * @property int $id
 * @property \Carbon $deleted_at
 * @property string $title
 * @property string $answer
 * @property bool $published
 * @property \User $creator
 */
class Question extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'answer', 'position', 'published'];

    protected $rules = [
        'title'     => 'required|min:3',
        'answer'    => 'required|min:3',
        'position'  => 'integer',
        'published' => 'boolean',
    ];

    public static $relationsData = [
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}