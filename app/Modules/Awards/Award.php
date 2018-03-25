<?php

namespace App\Modules\Awards;

use HTML, SoftDeletingTrait, BaseModel;

/**
 * @property int $id
 * @property \Carbon $deleted_at
 * @property string $title
 * @property string $url
 * @property int $position
 * @property \Carbon $achieved_at
 * @property int $game_id
 * @property int|null $tournament_id
 * @property int|null $team_id
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
        'url'           => 'sometimes|url',
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
        $color = null;
        if ($pos == 1) $color = 'gold';
        if ($pos == 2) $color = 'silver';
        if ($pos == 3) $color = '#CD7F32';

        $icon = HTML::fontIcon($icon, $color).'&nbsp;'.$pos.'.';

        return $icon;
    }

}