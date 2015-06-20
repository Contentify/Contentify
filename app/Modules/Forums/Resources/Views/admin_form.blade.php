{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.forums.update', $model->id], 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/forums']) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}

    @include('forums::select_forum', ['model' => isset($model) ? $model : null, 'forums' => $forums, 'empty' => true])

    {!! Form::smartTextarea('description', trans('app.description'), true) !!}

    {!! Form::smartNumeric('position', trans('app.position')) !!}

    <hr>

    <p>
        {!! trans('forums::access_info') !!}
    </p>

    {!! Form::smartSelectRelation('team', 'Team', $modelClass, null, true, true) !!}

    {!! Form::smartCheckbox('internal', trans('app.internal')) !!}   

    {!! Form::actions() !!}
{!! Form::close() !!}