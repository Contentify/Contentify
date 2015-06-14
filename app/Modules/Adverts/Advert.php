<?php namespace App\Modules\Adverts;

use SoftDeletingTrait, BaseModel;

class Advert extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'code', 'url', 'published', 'advertcat_id'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    protected $rules = [
        'title'         => 'required|min:3',
        'url'           => 'required|url',
        'published'     => 'boolean',
        'advertcat_id'  => 'required|integer'
    ];

    public static $relationsData = [
        'advertcat' => [self::BELONGS_TO, 'App\Modules\Adverts\Advertcat'],
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