<select size="10">
    @foreach($templates as $template)
        <option value="{!! $template->id !!}">{{ $template->title }}</option>
    @endforeach
</select>