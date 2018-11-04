<?php 

namespace App\Modules\Polls\Http\Controllers;

use App\Modules\Polls\Poll;
use Contentify\GlobalSearchInterface;
use DB;
use FrontController;
use HTML;
use Illuminate\Database\Eloquent\Builder;
use Input;
use Redirect;
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
            'permaFilter' => function(Builder $users)
            {
                $hasAccess = (user() and user()->hasAccess('internal'));

                if ($hasAccess) {
                    return $users; // Do not restrict
                } else {
                    return $users->whereInternal(false); // Only return non-internal polls
                }
            }
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

        $hasAccess = (user() and user()->hasAccess('internal'));
        if ($poll->internal and ! $hasAccess) {
            $this->alertError(trans('app.access_denied'));
            return;
        }

        $poll->access_counter++;
        $poll->save();

        $this->title($poll->title);

        $this->pageView('polls::show', compact('poll'));
    }

    /**
     * Store the vote for an option of a poll
     *
     * @param int $id The ID of the poll
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function vote($id)
    {
        /** @var Poll $poll */
        $poll = Poll::findOrFail($id);

        $hasAccess = (user() and user()->hasAccess('internal'));
        if ($poll->internal and ! $hasAccess) {
            $this->alertError(trans('app.access_denied'));
            return;
        }

        if ($poll->userVoted(user()) or ! $poll->open) {
            $this->alertError(trans('app.access_denied'));
            return;
        }

        $votes = [];
        if ($poll->max_votes == 1) {
            $votes[] = ['poll_id' => $poll->id, 'user_id' => user()->id, 'option_id' => Input::get('option')];
        } else {
            for ($counter = 1; $counter <= Poll::MAX_OPTIONS; $counter++) {
                $value = Input::get('option'.$counter);

                if ($value !== null) {
                    $votes[] = ['poll_id' => $poll->id, 'user_id' => user()->id, 'option_id' => $counter];
                }
            }
        }

        DB::table('polls_votes')->insert($votes);

        $this->alertFlash(trans('app.successful'));
        return Redirect::to('polls/'.$poll->id.'/'.$poll->slug);
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
        $hasAccess = (user() and user()->hasAccess('internal'));

        $query = Poll::where('title', 'LIKE', '%'.$subject.'%');
        if (! $hasAccess) {
            $query->whereInternal(false); // Only return non-internal polls
        }
        $polls = $query->get();

        $results = array();
        foreach ($polls as $poll) {
            $results[$poll->title] = URL::to('polls/'.$poll->id.'/'.$poll->slug);
        }

        return $results;
    }

}