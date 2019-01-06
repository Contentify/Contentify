<?php 

namespace App\Modules\Search\Http\Controllers;

use View;
use Widget;

class SearchWidget extends Widget
{

    public function render(array $parameters = [])
    {
        return View::make('search::widget')->render();
    }

}
