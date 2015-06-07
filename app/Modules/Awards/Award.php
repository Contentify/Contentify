<?php namespace App\Modules\Awards;

use HTML, SoftDeletingTrait, BaseModel;

class Award extends BaseModel {

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
        'tournament_id' => 'integer',
        'team_id'       => 'integer',
    ];

    public static $relationsData = [
        'game'          => [self::BELONGS_TO, 'App\Modules\Games\Models\Game'],
        'tournament'    => [self::BELONGS_TO, 'App\Modules\Tournaments\Models\Tournament'],
        'team'          => [self::BELONGS_TO, 'App\Modules\Teams\Models\Team'],
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