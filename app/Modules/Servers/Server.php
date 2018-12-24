<?php 

namespace App\Modules\Servers;

use BaseModel;
use Illuminate\Database\Eloquent\Builder;
use SoftDeletingTrait;

/**
 * @property \Carbon                 $created_at
 * @property \Carbon                 $deleted_at
 * @property string                  $title
 * @property string                  $ip
 * @property string                  $hoster
 * @property int                     $slots
 * @property string                  $description
 * @property bool                    $published
 * @property int                     $game_id
 * @property int                     $creator_id
 * @property int                     $updater_id
 * @property \App\Modules\Games\Game $game
 * @property \User                   $creator
 */
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
        'game'      => [self::BELONGS_TO, 'App\Modules\Games\Game'],
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
