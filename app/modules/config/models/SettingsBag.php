<?php namespace App\Modules\Config\Models;

use Ardent;

class SettingsBag extends Ardent {

    protected $fillable = ['app_analytics'];

    public static $rules = [
        //'app_analytics' => 'required',
    ];

    public function getFillable()
    {
        return $this->fillable;
    }

}