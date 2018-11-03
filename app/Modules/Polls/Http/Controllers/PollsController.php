<?php 

namespace App\Modules\Polls\Http\Controllers;

use App\Modules\Polls\Poll;
use Contentify\GlobalSearchInterface;
use FrontController;
use HTML;
use URL;

class PollsController extends FrontController implements GlobalSearchInterface
{

    public function __construct()
    {
        $this->modelClass = Poll::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons'   => null,
            'tableHead' => [
                trans('app.id')            => 'id',
                trans('app.title')         => 'title',
                trans('app.open')          => 'open',
                trans('app.created_at')    => 'created_at',
            ],
            'tableRow' => function(Poll $poll)
            {
                return [
                    $poll->id,
                    raw(link_to('polls/'.$poll->id.'/'.$poll->slug, $poll->title)),
                    raw($poll->open ? HTML::fontIcon('check') : HTML::fontIcon('times')),
                    $poll->created_at,
                ];
            },
            'actions'   => null,
            # TODO filter internal?
#            'permaFilter' => function($users)
#            {
#                return $users->where('id', '!=', 1); // Do not keep the daemon user
#            }
        ], 'front');
    }

    /**
     * Show a poll
     * 
     * @param  int $id The ID of the poll
     * @return void
     */
    public function show($id)
    {
        /** @var Poll $poll */
        $poll = Poll::findOrFail($id);

        $poll->access_counter++;
        $poll->save();

        $this->title($poll->title);

        $this->pageView('polls::show', compact('poll'));
    }

    /**
     * Store the vote for an option of a poll
     *
     * @param int $id The ID of the poll
     */
    public function vote($id)
    {
        /** @var Poll $poll */
        $poll = Poll::findOrFail($id);

        dd('tba');
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
        $polls = Poll::where('title', 'LIKE', '%'.$subject.'%')->get();

        $results = array();
        foreach ($polls as $poll) {
            $results[$poll->title] = URL::to('polls/'.$poll->id.'/'.$poll->slug);
        }

        return $results;
    }

}