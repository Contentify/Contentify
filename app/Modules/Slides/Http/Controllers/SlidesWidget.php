<?php namespace App\Modules\Slides\Http\Controllers;

use App\Modules\Slides\Models\Slide;
use App\Modules\Slides\Models\Slidecat;
use DB, View, Widget;

class SlidesWidget extends Widget {

    public function render($parameters = array())
    {
        if (isset($parameters['categoryId'])) {
            $categoryId = $parameters['categoryId'];
        } else {
            $slidecat = Slidecat::first();
            if ($slidecat) {
                $categoryId = $slidecat->id;
            } else {
                $categoryId = 0;
            }
        }

        $slides = Slide::whereSlidecatId($categoryId)->get();

        return View::make('slides::widget', compact('slides', 'categoryId'))->render();
    }

}