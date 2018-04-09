<?php

namespace App\Modules\Config;

use BaseModel;

class SettingsBag extends BaseModel
{

    // NOTE: "app." is replaced with "app::"!
    protected $fillable = [
        'app::name',
        'app::theme',
        'app::theme_christmas',
        'app::theme_snow_color',
        'app::facebook',
        'app::twitter',
        'app::youtube',
        'app::twitchKey',
        'app::analytics',
        'auth::registration',
        'app::https',
        'app::dbBackup',
    ];

    protected $rules = [
        'app::name'          => 'min:3',
        'auth::registration' => 'boolean',
        'app::https'         => 'boolean',
        'app::dbBackup'      => 'boolean',
    ];

}