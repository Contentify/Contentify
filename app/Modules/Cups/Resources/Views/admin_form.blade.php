{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['url' => 'admin/cups/'.$model->id, 'files' => true, 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/cups', 'files' => true]) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}

    {!! Form::smartSelectRelation('game', trans('app.object_game'), $modelClass) !!}

    {!! Form::smartSelect('players_per_team', trans('cups::players_per_team'), $modelClass::makeOptionArray('playersPerTeamValues')) !!}

    {!! Form::smartSelect('slots', trans('app.slots'), $modelClass::makeOptionArray('slotValues')) !!}

    {!! Form::smartText('prize', trans('app.prize')) !!}

    {!! Form::smartTextarea('description', trans('app.description'), true) !!}

    {!! Form::smartTextarea('rulebook', trans('app.rules'), true) !!}

    @if (isset($model))
        {!! Form::smartSelectRelation('referees', trans('cups::referees'), $modelClass) !!}
    @endif

    {!! Form::smartDateTime('join_at', trans('cups::join_at')) !!}

    {!! Form::smartDateTime('check_in_at', trans('cups::check_in_at')) !!}

    {!! Form::smartDateTime('start_at', trans('cups::start_at')) !!}

    {!! Form::smartCheckbox('featured', trans('app.featured'), true) !!}

    {!! Form::smartCheckbox('published', trans('app.published'), true) !!}

    {!! Form::smartCheckbox('closed', trans('app.closed'), false) !!}

    {!! Form::smartImageFile() !!}

    {!! Form::actions() !!}
{!! Form::close() !!}