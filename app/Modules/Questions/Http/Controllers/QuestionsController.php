<?php 

namespace App\Modules\Questions\Http\Controllers;

use App\Modules\Questions\Question;
use Config;
use Contentify\GlobalSearchInterface;
use FrontController;
use URL;

class QuestionsController extends FrontController implements GlobalSearchInterface
{

    public function index()
    {
        $perPage = Config::get('app.frontItemsPerPage');

        $questions = Question::wherePublished(true)->orderBy('question_cat_id', 'asc')->orderBy('position', 'asc')->orderBy('title', 'asc')
            ->paginate($perPage);

        $this->pageView('questions::index', compact('questions'));
    }
    
    /**
     * This method is called by the global search (SearchController->postCreate()).
     * Its purpose is to return an array with results for a specific search query.
     * 
     * @param  string $subject The search term
     * @return string[]
     */
    public function globalSearch($subject)
    {
        $questions = Question::wherePublished(true)->where('title', 'LIKE', '%'.$subject.'%')->get();

        $results = array();
        foreach ($questions as $question) {
            $results[$question->title] = URL::to('questions#question_'.$question->id);
        }

        return $results;
    }

}