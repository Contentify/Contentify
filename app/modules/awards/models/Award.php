<?php namespace App\Modules\Awards\Models;

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
    ];

    public static $rules = [
        'title'     => 'required',
    ];

    public static $relationsData = [
        'game'          => [self::BELONGS_TO, 'App\Modules\Games\Models\Game'],
        'tournament'    => [self::BELONGS_TO, 'App\Modules\Tournaments\Models\Tournament'],
    ];

    /**
     * Returns HTML code of an icon representing the position (rank) achieved in the tournament.
     * 
     * @return string HTML code of the icon
     */
    public function positionIcon()
    {
        $pos = $this->position;

        $icon = 'award_star_nothing';
        if ($pos == 1) $icon = 'award_star_gold_3';
        if ($pos == 2) $icon = 'award_star_silver_3';
        if ($pos == 3) $icon = 'award_star_bronze_3';

        $icon = HTML::image(get_image_url($icon)).'&nbsp;'.$pos.'.';

        return $icon;
    }

}