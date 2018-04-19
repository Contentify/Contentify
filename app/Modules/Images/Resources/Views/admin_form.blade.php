{!! Form::errors($errors) !!}

@if (isset($model))
    {!! Form::model($model, ['route' => ['admin.images.update', $model->id], 'files' => true, 'method' => 'PUT']) !!}
@else
    {!! Form::open(['url' => 'admin/images', 'files' => true]) !!}
@endif
    {!! Form::smartTags('tags', 'Tags') !!}

    {!! Form::smartImageFile() !!}

    <hr>

    {!! Form::smartSelectRelation('gallery', 'Gallery', $modelClass, null, true, true) !!}

    {!! Form::smartText('title', trans('app.title')) !!}

    {!! Form::actions() !!}
{!! Form::close() !!}

<script>
    $(document).ready(function()
    {
        $('#image').change(function()
        {
            var files   = this.files;
            var file    = files[0];
            var name    = file.name;
            var pos     = name.indexOf('.');

            if (pos > 0) {
                name = name.substr(0, pos);
            }
   
            $('#tags').tagsinput('add', name);
        });
    });
</script>