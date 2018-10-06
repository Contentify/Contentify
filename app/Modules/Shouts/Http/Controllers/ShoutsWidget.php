<?php 

namespace App\Modules\Shouts\Http\Controllers;

use App\Modules\Shouts\Shout;
use View;
use Widget;

class ShoutsWidget extends Widget
{

    /**
     * Maximum number of shouts displayed
     */
    const SHOUT_LIMIT = 10;

    public function render(array $parameters = array())
    {
        $shouts = Shout::orderBy('created_at', 'desc')->with('creator')->take(self::SHOUT_LIMIT)->get();
        
        return View::make('shouts::widget', compact('shouts'))->render();
    }

}