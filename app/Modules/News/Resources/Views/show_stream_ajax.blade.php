@foreach ($streamItems as $item)
    @include('news::stream_item', compact('item', 'more'))
@endforeach