<?php namespace App\Modules\Matches;

use BaseModel;

class MatchScore extends BaseModel {

    protected $fillable = ['left_score', 'right_score', 'map_id', 'match_id'];

    protected $rules = [
        'left_score'    => 'required|integer|min:0',
        'right_score'   => 'required|integer|min:0',
        'match_id'      => 'required|integer',
        'map_id'        => 'required|integer',
    ];

    public static $relationsData = [
        'match' => [self::BELONGS_TO, 'App\Modules\Matches\Models\Match'],
        'map'   => [self::BELONGS_TO, 'App\Modules\Maps\Models\Map'],
    ];

    public static function boot()
    {
        self::saved(function($matchScore)
        {
            $matchScore->match->updateScore();
        });

        self::deleted(function($matchScore)
        {
            $matchScore->match->updateScore();
        });
    }

}