{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.forums.update', $model->id], 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/forums']) !!}
@endif
    {!! Form::smartText('title', trans('app.title')) !!}

    @include('forums::select_forum', ['model' => isset($model) ? $model : null, 'forums' => $forums, 'empty' => true])

    {!! Form::smartTextarea('description', trans('app.description'), false) !!}

    {!! Form::smartNumeric('position', trans('app.position'), 0) !!}

    <hr>

    <div class="well">
        {!! trans('forums::access_info') !!}
    </div>

    {!! Form::smartSelectRelation('team', trans('app.object_team'), $modelClass, null, true, true) !!}

    {!! Form::smartCheckbox('internal', trans('app.internal')) !!}

    {!! Form::actions() !!}
{!! Form::close() !!}