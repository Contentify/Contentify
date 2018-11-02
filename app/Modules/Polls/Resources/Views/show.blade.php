<h1 class="page-title"><a class="back" href="{!! url('polls') !!}" title="{{ trans('app.back') }}">{!! HTML::fontIcon('chevron-left') !!}</a> {{ $poll->title }}</h1>

tba

{!! Comments::show('polls', $poll->id) !!}