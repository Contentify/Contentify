<?php

namespace App\Modules\Galleries;

use BaseModel;
use Illuminate\Database\Eloquent\Builder;
use SoftDeletingTrait;

/**
 * @property \Carbon                     $created_at
 * @property \Carbon                     $deleted_at
 * @property string                      $title
 * @property string                      $slug
 * @property bool                        $published
 * @property int                         $access_counter
 * @property int                         $creator_id
 * @property int                         $updater_id
 * @property \App\Modules\Images\Image[] $images
 * @property \User $creator
 */
class Gallery extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'published'];

    protected $slugable = true;

    protected $rules = [
        'title'     => 'required|min:3',
        'published' => 'boolean',
    ];

    public static $relationsData = [
        'images'    => [self::HAS_MANY, 'App\Modules\Images\Image'], // Probably this is not a strong dependency.
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

    /**
     * Select only those that have been published
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePublished($query)
    {
        return $query->wherePublished(true);
    }

}
