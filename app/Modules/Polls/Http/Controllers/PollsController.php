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
                    return $users; // Do not restrict => do show all polls
                } else {
                    return $users->whereInternal(false); // Only show non-internal polls
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

        $userVoted = user() ? $poll->userVoted(user()) : false;

        $this->pageView('polls::show', compact('poll', 'userVoted'));
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

        // Do not allow to vote twice or to vote in closed polls
        if ($poll->userVoted(user()) or ! $poll->open) {
            $this->alertError(trans('app.access_denied'));
            return;
        }

        $votes = [];
        if ($poll->max_votes == 1) {
            $votes[] = Input::get('option');
        } else {
            for ($counter = 1; $counter <= Poll::MAX_OPTIONS; $counter++) {
                $value = Input::get('option'.$counter);

                // Note: If someone manipulates the sent form data and adds votes for
                // options that are not enabled, we simply ignore these votes.
                if ($value !== null and $poll['option'.$counter]) {
                    $votes[] = $counter;
                }
            }

            // Do not allow the user to send more votes than the maximum allowed votes
            if (count($votes) > $poll->max_votes) {
                $this->alertFlash(trans('polls::too_many_votes'));
                return Redirect::to('polls/'.$poll->id.'/'.$poll->slug)->withInput();
            }
        }

        $poll->vote(user(), $votes);

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