{!! Form::smartGroupOpen('forum_id', trans('forums::parent')) !!}
    <select name="forum_id">
        @if ($empty)
            <option value="">-</option>
        @endif
        
        @foreach ($forums as $forum)
            <?php 
                if (isset($model) and $forum->id == $model->forum_id) {
                    $selected = 'selected="selected"';
                } else {
                    $selected = '';
                }
            ?>

            <option value="{{ $forum->id }}" {!! $selected !!}>{{ $forum->title }}</option>
        @endforeach
    </select>
{!! Form::smartGroupClose() !!}