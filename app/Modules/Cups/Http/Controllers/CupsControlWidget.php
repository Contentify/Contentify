<?php

namespace App\Modules\Cups\Http\Controllers;

use App\Modules\Cups\Cup;
use View;
use Widget;

class CupsControlWidget extends Widget
{

    public function render(array $parameters = array())
    {
        $cup = new Cup;
        $cups = $cup->cupsByUser(user(), true);

        return View::make('cups::widget_cups_control', compact('cups'))->render();
    }

}