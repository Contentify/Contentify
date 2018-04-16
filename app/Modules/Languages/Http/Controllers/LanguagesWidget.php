<?php 

namespace App\Modules\Languages\Http\Controllers;

use App\Modules\Countries\Country;
use Lang;
use View;
use Widget;

class LanguagesWidget extends Widget
{

    public function render(array $parameters = array())
    {
        $languageCodes = Lang::languageCodes();
        sort($languageCodes);

        $countryCodes = array_merge($languageCodes, ['uk']); // For the language 'en' we use the flag of United Kingdom
        $countries = Country::whereIn('code', $countryCodes)->get();

        return View::make('languages::widget', compact('languageCodes', 'countries'))->render();
    }

}