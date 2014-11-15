<?php namespace App\Modules\News\Controllers;

use ConfigController;

class AdminConfigController extends ConfigController {

    public function __construct()
    {
        $this->modelName = 'NewsConfigBag';

        parent::__construct();
    }

}