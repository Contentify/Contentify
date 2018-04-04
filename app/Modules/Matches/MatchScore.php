<?php

namespace App\Modules\Matches;

use BaseModel;

/**
 * @property int $left_score
 * @property int $right_score
 * @property int $map_id
 * @property int $match_id
 * @property \App\Modules\Matches\Match $match,
 * @property \App\Modules\Maps\Map $map
 */
class MatchScore extends BaseModel
{

    protected $fillable = ['left_score', 'right_score', 'map_id', 'match_id'];

    protected $rules = [
        'left_score'    => 'required|integer|min:0',
        'right_score'   => 'required|integer|min:0',
        'match_id'      => 'required|integer',
        'map_id'        => 'required|integer',
    ];

    public static $relationsData = [
        'match' => [self::BELONGS_TO, 'App\Modules\Matches\Match'],
        'map'   => [self::BELONGS_TO, 'App\Modules\Maps\Map'],
    ];

    public static function boot()
    {
        self::saved(function(self $matchScore)
        {
            $matchScore->match->updateScore();
        });

        self::deleted(function(self $matchScore)
        {
            $matchScore->match->updateScore();
        });
    }

}