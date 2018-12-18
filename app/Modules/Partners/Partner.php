<?php 

namespace App\Modules\Partners;

use BaseModel;
use Illuminate\Database\Eloquent\Builder;
use SoftDeletingTrait;

/**
 * @property \Carbon                          $created_at
 * @property \Carbon                          $deleted_at
 * @property string                           $title
 * @property string                           $slug
 * @property string                           $text
 * @property string                           $url
 * @property string                           $facebook
 * @property string                           $twitter
 * @property string                           $youtube
 * @property string                           $discord
 * @property int                              $position
 * @property bool                             $published
 * @property int                              $partner_cat_id
 * @property string                           $image
 * @property int                              $access_counter
 * @property int                              $creator_id
 * @property int                              $updater_id
 * @property \App\Modules\Partners\PartnerCat $partnerCat
 * @property \User                            $creator
 */
class Partner extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $slugable = true;

    protected $fillable = [
        'title',
        'text',
        'url',
        'facebook',
        'twitter',
        'youtube',
        'discord',
        'position',
        'published',
        'partner_cat_id'
    ];

    public static $fileHandling = ['image' => ['type' => 'image']];

    protected $rules = [
        'title'          => 'required|min:3',
        'url'            => 'required|url',
        'published'      => 'boolean',
        'position'       => 'required|integer',
        'partner_cat_id' => 'required|integer',
    ];

    public static $relationsData = [
        'partnerCat'    => [self::BELONGS_TO, 'App\Modules\Partners\PartnerCat'],
        'creator'       => [self::BELONGS_TO, 'User', 'title' => 'username'],
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
