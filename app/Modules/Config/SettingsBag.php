<?php namespace App\Modules\Config;

use BaseModel;

class SettingsBag extends BaseModel {

    // NOTE: The "app." is replaced with "app::"!
    protected $fillable = [
        'app::theme',
    	'app::facebook',
    	'app::twitter',
    	'app::youtube',
    	'app::analytics', 
    	'auth::registration', 
    	'app::https', 
    	'app::dbBackup',
    ];

    protected $rules = [
        'auth::registration'    => 'boolean',      
        'app::https'            => 'boolean',
        'app::dbBackup'         => 'boolean',
    ];

}