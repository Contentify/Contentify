<?php 

namespace App\Modules\Pages\Http\Controllers;

use Config;
use View;
use Widget;

class ShortBiographyWidget extends Widget
{

    public function render(array $parameters = [])
    {
        $shortBiography = Config::get('app.short_biography');

        if (! $shortBiography) {
            $shortBiography = trans('app.nothing_here');
        }

        return View::make('news::widget', compact('shortBiography'))->render();
    }

}
