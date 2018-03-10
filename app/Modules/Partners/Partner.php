<?php 

namespace App\Modules\Partners;

use Illuminate\Database\Eloquent\Builder;
use SoftDeletingTrait, BaseModel;

class Partner extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $slugable = true;

    protected $fillable = ['title', 'text', 'url', 'position', 'published', 'partnercat_id'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    protected $rules = [
        'title'         => 'required|min:3',
        'url'           => 'required|url',
        'published'     => 'boolean',
        'position'      => 'required|integer',
    ];

    public static $relationsData = [
        'partnercat'    => [self::BELONGS_TO, 'App\Modules\Partners\Partnercat'],
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