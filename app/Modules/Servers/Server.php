<?php 

namespace App\Modules\Servers;

use Illuminate\Database\Eloquent\Builder;
use SoftDeletingTrait, BaseModel;

class Server extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'ip', 'hoster', 'slots', 'description', 'published', 'game_id'];

    protected $rules = [
        'title'     => 'required|min:3',
        'ip'        => 'required|min:7', // If we enforce it to be an IP adding a port is invalid!
        'slots'     => 'integer|min:0',
        'published' => 'boolean',
        'game_id'   => 'nullable|integer',
    ];

    public static $relationsData = [
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
        'game'      => [self::BELONGS_TO, 'App\Modules\Games\Game'],
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