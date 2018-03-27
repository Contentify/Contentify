<?php

namespace App\Modules\Cups\Http\Controllers;

use App\Modules\Cups\Cup;
use View;
use Widget;

class FeaturedCupWidget extends Widget
{

    public function render(array $parameters = array())
    {
        $cup = Cup::orderBy('start_at', 'DESC')->whereFeatured(true)->wherePublished(true)->first();

        if ($cup) {
            return View::make('cups::widget_featured', compact('cup'))->render();
        }
    }

}