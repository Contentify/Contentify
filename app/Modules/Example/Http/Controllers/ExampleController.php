<?php namespace App\Modules\Example\Http\Controllers;

use FrontController;

class ExampleController extends FrontController {

    public function getIndex()
    {
        $this->pageView('example::show');
    }

}