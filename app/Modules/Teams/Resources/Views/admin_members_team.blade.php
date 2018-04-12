<select class="form-control">
    @foreach ($teams as $team)
        <option value="{!! $team->id !!}">
            {!! $team->title !!}
        </option>
    @endforeach
</select>