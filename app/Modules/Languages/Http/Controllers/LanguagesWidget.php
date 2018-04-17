<?php 

namespace App\Modules\Languages\Http\Controllers;

use App\Modules\Countries\Country;
use Lang;
use View;
use Widget;

class LanguagesWidget extends Widget
{

    /**
     * For the language 'en' we use the flag of United Kingdom
     */
    const REPLACEMENTS = ['uk' => 'en'];

    public function render(array $parameters = array())
    {
        $languageCodes = Lang::languageCodes();

        $countryCodes = array_merge($languageCodes, array_keys(self::REPLACEMENTS));
        $countries = Country::whereIn('code', $countryCodes)->get();

        // Replace the code of a country with a locale code
        $countries->map(function ($item, $key) {
            foreach (self::REPLACEMENTS as $from => $to) {
                if ($item->code === $from) {
                    $item->code = $to;
                }
            }

            return $item;
        });

        $countries = $countries->sortBy('code');

        return View::make('languages::widget', compact('languageCodes', 'countries'))->render();
    }

}