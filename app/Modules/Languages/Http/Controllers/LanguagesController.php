<?php 

namespace App\Modules\Languages\Http\Controllers;

use App\Modules\Languages\Language;
use FrontController;
use Redirect;
use Session;

class LanguagesController extends FrontController
{

    /**
     * Change the language of the current user / client
     *
     * @param string $code The code of the language
     * @return \Illuminate\Http\RedirectResponse
     */
    public function set($code)
    {
        /** @var Language $language */
        $language = Language::whereCode($code)->firstOrFail();

        if (user()) {
            $user = user();
            $user->language_id = $language->id;
            $user->save(); // This will trigger an event listener in the user model
        } else {
            Session::put('app.locale', $code);
        }

        $this->alertFlash(trans('app.updated', [trans('app.object_language')]));
        return Redirect::route('home');
    }

}