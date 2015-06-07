<?php namespace App\Modules\Config;

use BaseModel;

class SettingsBag extends BaseModel {

    // NOTE: The "app." is replaced with "app::"!
    protected $fillable = ['app::analytics', 'auth::registration'];

    protected $rules = [
        'auth::registration' => 'boolean',        
    ];

}