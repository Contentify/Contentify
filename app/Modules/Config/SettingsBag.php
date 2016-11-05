<?php namespace App\Modules\Config;

use BaseModel;

class SettingsBag extends BaseModel {

    // NOTE: "app." is replaced with "app::"!
    protected $fillable = ['app::analytics', 'auth::registration', 'app::https', 'app::dbBackup', 'app::twitchKey'];

    protected $rules = [
        'auth::registration'    => 'boolean',      
        'app::https'            => 'boolean',
        'app::dbBackup'         => 'boolean',
    ];

}