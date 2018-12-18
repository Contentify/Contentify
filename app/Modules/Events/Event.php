<?php

namespace App\Modules\Events;

use BaseModel;
use Illuminate\Database\Eloquent\Builder;
use SoftDeletingTrait;

/**
 * @property \Carbon $created_at
 * @property \Carbon $deleted_at
 * @property \Carbon $starts_at
 * @property string  $title
 * @property string  $slug
 * @property string  $text
 * @property string  $url
 * @property string  $location
 * @property bool    $internal
 * @property string  $image
 * @property int     $access_counter
 * @property int     $creator_id
 * @property int     $updater_id
 * @property \User   $creator
 */
class Event extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at', 'starts_at'];

    protected $slugable = true;

    protected $fillable = ['title', 'text', 'url', 'location', 'internal', 'starts_at'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    protected $rules = [
        'title'     => 'required|min:3',
        'url'       => 'nullable|url',
        'internal'  => 'boolean',
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
