<?php

namespace App\Modules\Cups\Http\Controllers;

use ConfigController;

class AdminConfigController extends ConfigController
{

    public function __construct()
    {
        $this->modelName = 'CupConfigBag';

        parent::__construct();
    }

}