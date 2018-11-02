<?php 

namespace App\Modules\Polls\Http\Controllers;

use App\Modules\Polls\Poll;
use View;
use Widget;

class PollsWidget extends Widget
{

    public function render(array $parameters = array())
    {
        $poll = Poll::orderBy('open', 'desc')->orderBy('created_at', 'desc')->get();

        return View::make('polls::widget', compact('poll'))->render();
    }

}
