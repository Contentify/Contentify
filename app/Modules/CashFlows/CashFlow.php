<?php

namespace App\Modules\CashFlows;

use BaseModel;
use SoftDeletingTrait;

/**
 * @property \Carbon    $created_at
 * @property \Carbon    $deleted_at
 * @property \Carbon    $paid_at
 * @property string     $title
 * @property string     $description
 * @property float      $revenues Incoming cash flow - do not use this value to calc, to avoid problems with floats!
 * @property float      $expenses Outgoing cash flow - do not use this value to calc, to avoid problems with floats!
 * @property int        $integer_revenues Incoming cash flow * 100 - use this value for calculation!
 * @property int        $integer_expenses Outgoing cash flow * 100 - use this value for calculation!
 * @property bool       $paid Payment of this cash flow done?
 * @property \User|null $user
 * @property \User      $creator
 */
class CashFlow extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at', 'paid_at'];

    protected $fillable = ['title', 'description', 'revenues', 'expenses', 'paid', 'paid_at', 'user_id'];

    public static $fileHandling = ['icon' => ['type' => 'image']];

    protected $rules = [
        'title'     => 'required|min:3',
        'paid'      => 'boolean'
    ];

    public static $relationsData = [
        'user'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
        'creator'  => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

    /**
     * Get the revenues as float. Internally they are stored as integer.
     *
     * @param  float|null  $value
     * @return float
     */
    public function getRevenuesAttribute($value)
    {
        return $this->integer_revenues / 100;
    }

    /**
     * Set the revenues name as float. Internally they are stored as integer.
     *
     * @param  float  $value
     * @return void
     */
    public function setRevenuesAttribute($value)
    {
        $this->integer_revenues = (int) round($value * 100);;
    }

    /**
     * Get the expenses as float. Internally they are stored as integer.
     *
     * @param  float|null  $value
     * @return float
     */
    public function getExpensesAttribute($value)
    {
        return $this->integer_expenses / 100;
    }

    /**
     * Set the expenses name as float. Internally they are stored as integer.
     *
     * @param  float  $value
     * @return void
     */
    public function setExpensesAttribute($value)
    {
        $this->integer_expenses = (int) round($value * 100);;
    }

}
