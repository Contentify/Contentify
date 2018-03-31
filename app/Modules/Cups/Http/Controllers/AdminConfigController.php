<?php

namespace App\Modules\Cups\Http\Controllers;

use App\Modules\Cups\CupConfigBag;
use ConfigController;

class AdminConfigController extends ConfigController
{

    public function __construct()
    {
        $this->modelClass = CupConfigBag::class;

        parent::__construct();
    }

}