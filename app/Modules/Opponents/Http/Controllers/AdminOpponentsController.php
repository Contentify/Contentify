<?php

namespace App\Modules\Opponents\Http\Controllers;

use ModelHandlerTrait;
use App\Modules\Opponents\Opponent;
use HTML, Hover, BackController;

class AdminOpponentsController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'shield-alt';

    public function __construct()
    {
        $this->modelName = 'Opponent';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')     => 'id', 
                trans('app.title')  => 'title'
            ],
            'tableRow' => function($opponent)
            {
                Hover::modelAttributes($opponent, ['image', 'creator']);

                $icon = $opponent->country->icon 
                    ? HTML::image($opponent->country->uploadPath().$opponent->country->icon, $opponent->country->title)
                    : null;

                return [
                    $opponent->id,
                    raw(Hover::pull().$icon, ' '.$opponent->title),
                ];            
            }
        ]);
    }

    /**
     * Returns the lineup of an opponent team
     * 
     * @param  int      $id ID of the opponent
     * @return string
     */
    public function lineup($id)
    {
        $opponent = Opponent::findOrFail($id);

        return $opponent->lineup;
    }

}