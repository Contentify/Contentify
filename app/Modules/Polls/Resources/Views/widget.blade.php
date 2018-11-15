<div class="widget widget-polls">
    @if ($poll)
        @include('polls::poll', compact('poll', 'userVoted'))
    @else
        {{ trans('app.nothing_new') }}
    @endif
</div>