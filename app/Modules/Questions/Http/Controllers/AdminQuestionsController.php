<?php

namespace App\Modules\Questions\Http\Controllers;

use App\Modules\Questions\Question;
use BackController;
use Hover;
use HTML;
use ModelHandlerTrait;

class AdminQuestionsController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'tasks';

    public function __construct()
    {
        $this->modelClass = Question::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons'   => ['new', 'category'],
            'tableHead' => [
                trans('app.id')              => 'id',
                trans('app.published')       => 'published',
                trans('app.object_question') => 'title',
                trans('app.category')        => 'question_cat_id',
            ],
            'tableRow' => function(Question $question)
            {
                $link = HTML::link('questions#question_'.$question->id, $question->title);

                return [
                    $question->id,
                    raw($question->published ? HTML::fontIcon('check') : HTML::fontIcon('times')),
                    raw(Hover::modelAttributes($question, ['creator', 'updated_at'])->text($question->answer, true)->pull().$link),
                    $question->questionCat->title,
                ];
            }
        ]);
    }

}
