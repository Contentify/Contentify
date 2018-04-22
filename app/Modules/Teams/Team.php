<?php 

namespace App\Modules\Teams;

use BaseModel;
use Illuminate\Database\Eloquent\Builder;
use SoftDeletingTrait;

/**
 * @property \Carbon                        $created_at
 * @property \Carbon                        $deleted_at
 * @property string                         $title
 * @property string                         $slug
 * @property string                         $short
 * @property string                         $text
 * @property int                            $position
 * @property bool                           $published
 * @property int                            $team_cat_id
 * @property int                            $country_id
 * @property string                         $image
 * @property int                            $access_counter
 * @property int                            $creator_id
 * @property int                            $updater_id
 * @property \App\Modules\Matches\Match[]   $matches
 * @property \User[]                        $members
 * @property \App\Modules\Teams\TeamCat     $teamCat
 * @property \App\Modules\Countries\Country $country
 * @property \App\Modules\Awards\Award[]    $awards
 * @property \User                          $creator
 * @property \User[]                        $users
 */
class Team extends BaseModel 
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $slugable = true;

    protected $fillable = ['title', 'text', 'position', 'published', 'team_cat_id', 'country_id'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    protected $rules = [
        'title'         => 'required|min:3',
        'position'      => 'nullable||integer',
        'published'     => 'boolean',
        'team_cat_id'   => 'required|integer',
        'country_id'    => 'nullable|integer',
    ];

    public static $relationsData = [
        'matches'   => [
            self::HAS_MANY, 'App\Modules\Matches\Match', 'foreignKey' => 'left_team_id', 'dependency' => true
        ],
        'members'   => [self::BELONGS_TO_MANY, 'User'],
        'teamCat'   => [self::BELONGS_TO, 'App\Modules\Teams\TeamCat'],
        'country'   => [self::BELONGS_TO, 'App\Modules\Countries\Country'],
        'awards'    => [self::HAS_MANY, 'App\Modules\Awards\Award', 'dependency' => true],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

    /**
     * The BaseModel's handleRelationalArray() method does not support 
     * orderBy() for pivot attributes so we have to use old-school Eloquent instead.
     */
    public function users()
    {
        return $this->belongsToMany('User')->withPivot('task', 'description', 'position')
            ->orderBy('pivot_position', 'asc');
    }

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