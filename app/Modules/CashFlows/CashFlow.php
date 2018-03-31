<?php

namespace App\Modules\CashFlows;

use BaseModel;
use SoftDeletingTrait;

/**
 * @property int $id
 * @property \Carbon $deleted_at
 * @property \Carbon $paid_at
 * @property string $title
 * @property string $description
 * @property int $revenues Incoming cash flow * 1/100
 * @property int $expenses Outgoing cash flow * 1/100
 * @property bool $paid Payment of this cash flow done?
 * @property \User|null $user
 * @property \User $creator
 */
class CashFlow extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at', 'paid_at'];

    protected $fillable = ['title', 'description', 'revenues', 'expenses', 'paid', 'paid_at', 'user_id'];

    public static $fileHandling = ['icon' => ['type' => 'image']];

    protected $rules = [
        'title'     => 'required|min:3',
        'revenues'  => 'integer|min:0',
        'expenses'  => 'integer|min:0',
        'paid'      => 'boolean'
    ];

    public static $relationsData = [
        'user'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
        'creator'  => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}