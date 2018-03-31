<?php

namespace App\Modules\Adverts;

use BaseModel;
use Illuminate\Database\Eloquent\Builder;
use SoftDeletingTrait;

/**
 * @property int $id
 * @property \Carbon $deleted_at
 * @property string $title
 * @property string $code
 * @property string $url
 * @property bool $published
 * @property int $advertcat_id
 * @property \App\Modules\Adverts\Advertcat $advertcat
 * @property \User $creator
 */
class Advert extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'code', 'url', 'published', 'advertcat_id'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    protected $rules = [
        'title'         => 'required|min:3',
        'url'           => 'sometimes|url',
        'published'     => 'boolean',
        'advertcat_id'  => 'required|integer'
    ];

    public static $relationsData = [
        'advertcat' => [self::BELONGS_TO, 'App\Modules\Adverts\Advertcat'],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

    /**
     * Usually the name would be "adverts" but ad blockers
     * recognize this part of the URl and would block the image
     *
     * @var string|null
     */
    protected $uploadDir = 'influence';

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