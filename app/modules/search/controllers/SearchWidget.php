<?php namespace App\Modules\Search\Controllers;

use View, Widget;

class SearchWidget extends Widget {

    public function render($parameters = array())
    {
        return View::make('search::widget')->render();
    }

}