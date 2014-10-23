<?php namespace App\Modules\Auth\Controllers;

use ConfigController;

class AdminConfigController extends ConfigController {

    public function __construct()
    {
        $this->modelName = 'AuthConfigBag';

        parent::__construct();
    }

}