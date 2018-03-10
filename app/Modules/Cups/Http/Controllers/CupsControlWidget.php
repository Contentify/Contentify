<?php

namespace App\Modules\Cups\Http\Controllers;

use App\Modules\Cups\Cup;
use View, Widget;

class CupsControlWidget extends Widget {

    public function render($parameters = array())
    {
        $cup = new Cup;
        $cups = $cup->cupsByUser(user(), true);

        return View::make('cups::widget_cups_control', compact('cups'))->render();
    }

}