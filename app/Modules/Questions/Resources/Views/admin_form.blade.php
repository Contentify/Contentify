{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.questions.update', $model->id], 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/questions']) !!}
@endif
    {!! Form::smartText('title', trans('app.object_question')) !!}

    {!! Form::smartSelectRelation('questionCat', trans('app.object_question').' '.trans('app.category'), $modelClass, null) !!}

    {!! Form::smartTextarea('answer', trans('app.answer'), true) !!}

    {!! Form::smartNumeric('position', trans('app.position'), 0) !!}

    {!! Form::smartCheckbox('published', trans('app.published'), true) !!}

    {!! Form::actions() !!}
{!! Form::close() !!}