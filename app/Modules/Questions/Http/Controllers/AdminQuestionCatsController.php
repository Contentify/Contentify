<?php 

namespace App\Modules\Questions\Http\Controllers;

use App\Modules\Questions\QuestionCat;
use BackController;
use Hover;
use ModelHandlerTrait;

class AdminQuestionCatsController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'tasks';

    public function __construct()
    {
        $this->modelClass = QuestionCat::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')     => 'id', 
                trans('app.title')  => 'title'
            ],
            'tableRow' => function(QuestionCat $questionCat)
            {
                Hover::modelAttributes($questionCat, ['creator', 'updated_at']);

                return [
                    $questionCat->id,
                    raw(Hover::pull(), $questionCat->title),
                ];
            }
        ]);
    }

}
