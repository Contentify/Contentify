<?php

namespace App\Modules\Config;

use BaseModel;

class SettingsBag extends BaseModel
{

    /*
     * ATTENTION: "app." is replaced with "app::"!
     */
    protected $fillable = [
        'app::name',
        'app::theme',
        'app::theme_christmas',
        'app::theme_snow_color',
        'app::facebook',
        'app::twitter',
        'app::youtube',
        'app::instagram',
        'app::twitch',
        'app::twitchKey',
        'app::discord',
        'app::analytics',
		'app::author',
		'app::keywords',
		'app::description',
        'auth::registration',
        'app::https',
        'app::dbBackup',
        'app::forbidden_email_domains',
        'app::frontend_css_code',
        'app::frontend_js_code',
        'app::backend_css_code',
        'app::backend_js_code',
    ];

    protected $rules = [
        'app::name'          => 'min:3',
        'auth::registration' => 'boolean',
        'app::https'         => 'boolean',
        'app::dbBackup'      => 'boolean',
    ];

}
