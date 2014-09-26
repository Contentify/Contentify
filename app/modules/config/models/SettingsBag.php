<?php namespace App\Modules\Config\Models;

use BaseModel;

class SettingsBag extends BaseModel {

    protected $fillable = ['app_analytics'];

    protected $rules = [
        //'app_analytics' => 'required',
    ];

    public function getFillable()
    {
        return $this->fillable;
    }

}