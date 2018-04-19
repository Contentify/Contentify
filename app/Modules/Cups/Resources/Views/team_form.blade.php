<h1 class="page-title">
    <a class="back" href="{!! url('cups/teams/overview/'.user()->id) !!}" title="{{ trans('app.back') }}">{!! HTML::fontIcon('chevron-left') !!}</a> {{ trans('cups::edit_team') }}
</h1>

{!! Form::errors($errors) !!}

@if (isset($team))
    {!! Form::model($team, ['url' => ['cups/teams/'.$team->id], 'files' => true, 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'cups/teams', 'files' => true]) !!}
@endif
    {!! Form::smartText('title', trans('app.name')) !!}

    {!! Form::smartText('password', trans('app.password'), rand(1000,9999)) !!}

    {!! Form::smartImageFile('image', trans('app.image')) !!}

    {!! Form::actions(['submit'], false) !!}
{!! Form::close() !!}