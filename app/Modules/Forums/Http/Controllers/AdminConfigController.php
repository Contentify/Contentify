<?php

namespace App\Modules\Forums\Http\Controllers;

use ConfigController;

class AdminConfigController extends ConfigController {

    public function __construct()
    {
        $this->modelName = 'ForumConfigBag';

        parent::__construct();
    }

}