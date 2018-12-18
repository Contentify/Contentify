<?php 

namespace App\Modules\Pages\Http\Controllers;

use Config;
use Widget;

class ShortBiographyWidget extends Widget
{

    public function render(array $parameters = array())
    {
        $shortBiography = Config::get('app.short_biography');

        if (! $shortBiography) {
            $shortBiography = trans('app.nothing_here');
        }

        return $shortBiography;
    }

}
