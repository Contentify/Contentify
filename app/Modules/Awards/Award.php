<?php

namespace App\Modules\Awards;

use BaseModel;
use HTML;
use SoftDeletingTrait;

/**
 * @property \Carbon                                $created_at
 * @property \Carbon                                $deleted_at
 * @property string                                 $title
 * @property string                                 $url
 * @property int                                    $position
 * @property \Carbon                                $achieved_at
 * @property int                                    $game_id
 * @property int|null                               $tournament_id
 * @property int|null                               $team_id
 * @property int                                    $creator_id
 * @property int                                    $updater_id
 * @property \App\Modules\Games\Game                $game
 * @property \App\Modules\Tournaments\Tournament    $tournament
 * @property \App\Modules\Teams\Team                $team
 * @property \User                                  $creator
 */
class Award extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at', 'achieved_at'];

    protected $fillable = [
        'title', 
        'url', 
        'position', 
        'achieved_at', 
        'game_id', 
        'tournament_id',
        'team_id',
    ];

    protected $rules = [
        'title'         => 'required|min:3',
        'url'           => 'nullable||url',
        'position'      => 'integer',
        'game_id'       => 'integer',
        'tournament_id' => 'nullable|integer',
        'team_id'       => 'nullable|integer',
    ];

    public static $relationsData = [
        'game'          => [self::BELONGS_TO, 'App\Modules\Games\Game'],
        'tournament'    => [self::BELONGS_TO, 'App\Modules\Tournaments\Tournament'],
        'team'          => [self::BELONGS_TO, 'App\Modules\Teams\Team'],
        'creator'       => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

    /**
     * Returns HTML code of an icon representing the position (rank) achieved in the tournament.
     * 
     * @return string HTML code of the icon
     */
    public function positionIcon()
    {
        $pos = $this->position;

        $icon = 'trophy';

        switch ($pos) {
            case 1:
                $color = 'gold';
                break;
            case 2:
                $color = 'silver';
                break;
            case 3:
                $color = '#CD7F32';
                break;
            default:
                $color = null;
        }

        $icon = HTML::fontIcon($icon, $color).'&nbsp;'.$pos.'.';

        return $icon;
    }

}
