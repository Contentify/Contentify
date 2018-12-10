<?php 

namespace App\Modules\Polls\Http\Controllers;

use App\Modules\Polls\Poll;
use View;
use Widget;

class PollsWidget extends Widget
{

    public function render(array $parameters = array())
    {
        $hasAccess = (user() and user()->hasAccess('internal'));

        $query = Poll::orderBy('open', 'desc')->orderBy('created_at', 'desc');
        if (! $hasAccess) {
            $query->whereInternal(false);
        }
        $poll = $query->first();

        $userVoted = (user() and $poll) ? $poll->userVoted(user()) : false;

        return View::make('polls::widget', compact('poll', 'userVoted'))->render();
    }

}
