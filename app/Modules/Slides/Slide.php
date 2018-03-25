<?php 

namespace App\Modules\Slides;

use Illuminate\Database\Eloquent\Builder;
use SoftDeletingTrait, BaseModel;

/**
 * @property int $id
 * @property \Carbon $deleted_at
 * @property string $title
 * @property string $text
 * @property string $url
 * @property int $position
 * @property bool $published
 * @property int $slidecat_id
 * @property string $image
 * @property \App\Modules\Slides\Slidecat $slidecat
 * @property \User $creator
 */
class Slide extends BaseModel
{

    use SoftDeletingTrait;    

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'text', 'url', 'position', 'published', 'slidecat_id'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    protected $rules = [
        'title'         => 'required|min:3',
        'url'           => 'required|url',
        'position'      => 'sometimes|integer',
        'published'     => 'boolean',
        'slidecat_id'   => 'required|integer'
    ];

    public static $relationsData = [
        'slidecat'  => [self::BELONGS_TO, 'App\Modules\Slides\Slidecat'],
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