<h1 class="page-title model-index">{{ trans_object('matches') }}</h1>

<div class="content-filter-ui" data-url="{!! URL::current() !!}">
    {{ trans('matches::left_team') }}: {!! Form::selectForeign('team_id', null, true) !!}
</div>