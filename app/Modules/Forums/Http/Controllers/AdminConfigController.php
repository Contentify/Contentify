<?php

namespace App\Modules\Forums\Http\Controllers;

use App\Modules\Forums\ForumConfigBag;
use ConfigController;

class AdminConfigController extends ConfigController
{

    public function __construct()
    {
        $this->modelClass = ForumConfigBag::class;

        parent::__construct();
    }
}
