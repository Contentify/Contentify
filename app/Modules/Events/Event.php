<?php

namespace App\Modules\Events;

use Illuminate\Database\Eloquent\Builder;
use SoftDeletingTrait, BaseModel;

class Event extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at', 'starts_at'];

    protected $slugable = true;

    protected $fillable = ['title', 'text', 'url', 'location', 'starts_at'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    protected $rules = [
        'title'     => 'required|min:3',
        'url'       => 'sometimes|url',
    ];

    public static $relationsData = [
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

    /**
     * Returns a query with events of a specific month (of a year).
     *
     * @param Builder $query
     * @param int     $year  The year
     * @param int     $month The month of the year
     * @return Builder
     */
    public function scopeEventsOfMonth($query, $year, $month)
    {
        return $query->whereMonth('starts_at', '=', $month)
            ->whereYear('starts_at', '=', $year)
            ->orderBy('starts_at', 'DESC');
    }

}