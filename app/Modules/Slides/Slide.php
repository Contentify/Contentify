<?php 

namespace App\Modules\Slides;

use BaseModel;
use Illuminate\Database\Eloquent\Builder;
use SoftDeletingTrait;

/**
 * @property \Carbon                      $created_at
 * @property \Carbon                      $deleted_at
 * @property string                       $title
 * @property string                       $text
 * @property string                       $url
 * @property int                          $position
 * @property bool                         $published
 * @property int                          $slide_cat_id
 * @property string                       $image
 * @property int                          $creator_id
 * @property int                          $updater_id
 * @property \App\Modules\Slides\SlideCat $slideCat
 * @property \User                        $creator
 */
class Slide extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'text', 'url', 'position', 'published', 'slide_cat_id'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    protected $rules = [
        'title'         => 'required|min:3',
        'url'           => 'required|url',
        'position'      => 'nullable||integer',
        'published'     => 'boolean',
        'slide_cat_id'  => 'required|integer'
    ];

    public static $relationsData = [
        'slideCat'  => [self::BELONGS_TO, 'App\Modules\Slides\SlideCat'],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username']
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