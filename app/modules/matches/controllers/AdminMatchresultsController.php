<?php namespace App\Modules\Matches\Controllers;

use App\Modules\Matches\Models\Matchresult as Matchresult;
use Hover, BackController;

class AdminMatchresultsController extends BackController {

    protected $icon = 'joystick.png';

    public function __construct()
    {
        $this->model = 'Matchresult';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                t('ID')     => 'id', 
                t('Title')  => 'title'
            ],
            'tableRow' => function($game)
            {
                $hover = new Hover();
                if ($game->icon) $hover->image($game->uploadPath().$game->icon);

                return [
                    $game->id,
                    $hover.$game->title,
                ];            
            }
        ]);
    }

}