<?php 

namespace App\Modules\Slides\Http\Controllers;

use App\Modules\Slides\Slide;
use App\Modules\Slides\Slidecat;
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

        $slides = Slide::whereSlidecatId($categoryId)->published()->orderBy('position', 'ASC')->get();

        return View::make('slides::widget', compact('slides', 'categoryId'))->render();
    }

}