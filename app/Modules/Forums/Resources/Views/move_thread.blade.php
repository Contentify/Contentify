{{ Form::open(['url' => 'forums/threads/move/'.$model->id]) }}
    @include('forums::select_forum', ['model' => $model, 'forums' => $forums, 'empty' => false])

    {{ Form::actions(['submit'], false) }}    
{{ Form::close() }}